<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverWait;
use OTPHP\TOTP;

// URL для подключения к Selenium
$host = 'http://selenium-firefox:4444/wd/hub'; // Используем имя контейнера

// Указываем браузер, например, Firefox
$capabilities = DesiredCapabilities::firefox();

// Открываем сессию WebDriver
$driver = RemoteWebDriver::create($host, $capabilities);

try {

    // Открываем страницу
    $driver->get('https://vk.com');
    $wait = new WebDriverWait($driver, 10); // Ожидание до 10 секунд

    $driver->findElement(WebDriverBy::id('index_email'))
        ->sendKeys('phone_number');
    $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('VkIdForm__form'))
    );
    $driver->findElement(WebDriverBy::className('VkIdForm__form'))
        ->submit();

    // Ожидаем появления поля для ввода кода 2FA
    $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('vkc__TextField__input'))
    );
    // Ваш секретный ключ (он должен быть в base32 формате)
    $secret = 'SECRET';  // Пример: замените на свой
    // Создаем объект TOTP с секретом
    $totp = TOTP::create($secret);
    // Получаем текущий одноразовый пароль
    $code = $totp->now();

    echo 'Ваш 2FA код: ' . $code . PHP_EOL;
    $driver->findElement(WebDriverBy::className('vkc__TextField__input'))->sendKeys($code);

    $driver->findElement(WebDriverBy::className('vkuiButton--mode-primary'))->submit();
    // Ожидаем появления поля для ввода пароля
    $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('password'))
    );
    $driver->findElement(WebDriverBy::name('password'))
        ->sendKeys('PASSWORD');

    $driver->findElement(WebDriverBy::className('vkuiButton'))->submit();

    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('l_aud')));
    $driver->findElement(WebDriverBy::id('l_aud'))->click();

    sleep(10);
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('audio_search')));
    $driver->findElement(WebDriverBy::id('audio_search'))->sendKeys('Capo When They See Us')->sendKeys(
        WebDriverKeys::ENTER
    );

    sleep(15);

    $elements = $driver->findElements(WebDriverBy::cssSelector('.audio_row__performer_title'));

    if (!empty($elements)) {
        // Прокручиваем к первому элементу
        $driver->executeScript('arguments[0].scrollIntoView();', [$elements[42]]);
        sleep(1); // Небольшая пауза

        // Кликаем с помощью JavaScript
        $driver->executeScript('arguments[0].click();', [$elements[42]]);
        \dump('Нажимаем на 49 в списке');
        // этот сработал
    }


    sleep(35);

    \var_dump('end');

    // Поиск элемента по классу
    $addMusicButton = $driver->findElement(
        WebDriverBy::cssSelector(
            '.vkuiIcon.vkuiIcon--24.vkuiIcon--w-24.vkuiIcon--h-24.vkuiIcon--add_24.vkitgetColorClass__colorIconSecondary--FEZTp'
        )
    );

    // Нажатие на элемент
    $addMusicButton->click();

    sleep(15);
    // Закрываем браузер
} catch (Throwable $e) {
    \dump($e, 'unlucky');
} finally {
    $driver->quit();
}
