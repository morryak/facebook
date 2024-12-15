<?php

declare(strict_types=1);

namespace App\UseCase\ProcessMusic\Vk;

use App\Manager\VkServiceManager;
use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverWait;
use OTPHP\TOTP;
use Throwable;

use function dump;

class VkService
{
    public function __construct(private readonly VkServiceManager $manager)
    {
    }

    public function processTrackList(array $trackList)
    {
        // $host = 'http://selenium-firefox:4444/wd/hub';
        $host = 'http://172.17.0.2:4444/wd/hub/';
        $capabilities = DesiredCapabilities::firefox();
        $firefoxOptions = new FirefoxOptions();
        $firefoxOptions->addArguments(['-headless']);
        $capabilities->setCapability(FirefoxOptions::CAPABILITY, $firefoxOptions);

        // Открываем сессию WebDriver
        dump('Открываем сессию WebDriver');
        $driver = RemoteWebDriver::create($host, $capabilities);
        $currentTrack = '';

        try {
            dump('enter');
            $driver->get('https://vk.com');
            $wait = new WebDriverWait($driver, 10);
            sleep(10);

            $driver->findElement(WebDriverBy::id('index_email'))->sendKeys('phone_number');
            $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('VkIdForm__form')));
            $driver->findElement(WebDriverBy::className('VkIdForm__form'))
                ->submit();

            // Ввод 2fa
            dump('Ввод 2fa');
            $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('vkc__TextField__input')));
            $driver->findElement(WebDriverBy::className('vkc__TextField__input'))->sendKeys($this->getCode());
            $driver->findElement(WebDriverBy::className('vkuiButton--mode-primary'))->submit();

            $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('password')));
            $driver->findElement(WebDriverBy::name('password'))->sendKeys('PASSWORD');
            $driver->findElement(WebDriverBy::className('vkuiButton'))->submit();

            // переходим в аудио
            dump('переходим в аудио');
            sleep(15);
            $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('l_aud')));
            $driver->findElement(WebDriverBy::id('l_aud'))->click();

            sleep(10);

            foreach ($trackList as $track) {
                $currentTrack = $track;
                dump($track);
                sleep(10);
                $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('audio_search')));
                $driver->findElement(WebDriverBy::id('audio_search'))->clear()->sendKeys($track)->sendKeys(WebDriverKeys::ENTER);
                sleep(10);
                $elements = $driver->findElements(WebDriverBy::cssSelector('.audio_row__performer_title'));

                if (!empty($elements)) {
                    //                     Прокручиваем к первому элементу
                    $driver->executeScript('arguments[0].scrollIntoView();', [$elements[42]]);
                    sleep(1); // Небольшая пауза

                    //                     Кликаем с помощью JavaScript
                    $driver->executeScript('arguments[0].click();', [$elements[42]]);
                } else {
                    $this->manager->insertLog($currentTrack, 'пропущенный трек не нашел WebDriverBy::cssSelector(audio_row__performer_title)');
                    continue;
                }

                sleep(35);

                $addMusicButton = $driver->findElement(
                    WebDriverBy::className(
                        'vkuiIcon vkuiIcon--24 vkuiIcon--w-24 vkuiIcon--h-24 vkuiIcon--add_24 vkitgetColorClass__colorIconSecondary--FEZTp'
                    )
                );
                $addMusicButton->click();
                dump('$addMusicButton->click()');
                $this->manager->insertLog($track);
                sleep(15);
            }
        } catch (Throwable $e) {
            dump($e);
            $this->manager->insertLog($currentTrack, $e->getMessage());
        } finally {
            $driver->quit();
        }
    }

    private function getCode(): string
    {
        return TOTP::create('SECRET')->now();
    }
}
