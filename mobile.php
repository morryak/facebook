<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Facebook\WebDriver\Firefox\FirefoxOptions;
use Facebook\WebDriver\Remote\RemoteWebDriver;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverDimension;
use Facebook\WebDriver\WebDriverExpectedCondition;
use Facebook\WebDriver\WebDriverKeys;
use Facebook\WebDriver\WebDriverWait;
use OTPHP\TOTP;

try {
//    $host = 'http://selenium-firefox:4444/wd/hub';
    $host = 'http://172.17.0.1:4444/wd/hub';
    // Настраиваем мобильный user-agent
    $options = new FirefoxOptions();
    $options->addArguments([
        '--user-agent=Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1',
        '--lang=ru-RU', // Установка русского языка
    ]);

    // Устанавливаем возможности для Firefox с опциями
    $capabilities = DesiredCapabilities::firefox();
    $capabilities->setCapability(FirefoxOptions::CAPABILITY, $options);

    // Создаем сессию с удаленным WebDriver
    $driver = RemoteWebDriver::create($host, $capabilities);

    // Устанавливаем разрешение окна браузера на мобильное (например, для iPhone 7)

    $driver->manage()->window()->setSize(new WebDriverDimension(390, 844));

    // Открываем страницу
    $driver->get('https://m.vk.com/');
    $wait = new WebDriverWait($driver, 10);

    \var_dump(1);
    sleep(5);
    /* нажимаем Войти по телефону или почте */
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('vkidform__signin')));
    $elements = $driver->findElement(WebDriverBy::id('vkidform__signin'))->click();

    \var_dump(2);
    /* вводим номер телефона */

    sleep(10);

    $driver->wait(10)->until(
        WebDriverExpectedCondition::presenceOfAllElementsLocatedBy(
            WebDriverBy::name('login')
        )
    );

    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::name('login')));
    $driver->findElement(WebDriverBy::name('login'))->sendKeys('phone_number');

    \var_dump(3);
    /* нажимаем продолжить */
    $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('vkc__DefaultSkin__button'))
    );
    $driver->findElement(WebDriverBy::className('vkc__DefaultSkin__button'))
        ->click();
    \var_dump(4);
    /* вводим 2фа */
    // Ожидаем появления поля для ввода кода 2FA
    $wait->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('otp'))
    );
    // Ваш секретный ключ (он должен быть в base32 формате)
    $secret = 'SECRET';  // Пример: замените на свой
    // Создаем объект TOTP с секретом
    $totp = TOTP::create($secret);
    // Получаем текущий одноразовый пароль
    $code = $totp->now();

    echo 'Ваш 2FA код: ' . $code . PHP_EOL;
    $driver->findElement(WebDriverBy::id('otp'))
        ->sendKeys($code);

    $driver->findElement(WebDriverBy::className('vkuiButton__in'))->click();
    \var_dump(5);
    /* вводим пароль */

    // Ожидаем появления поля для ввода пароля
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('password')));
    $driver->findElement(WebDriverBy::name('password'))->sendKeys('PASSWORD');

    $driver->findElement(WebDriverBy::className('vkuiButton'))->submit();

    \var_dump(6);
    // нажимаем меню
    sleep(4);
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('menu')));
    $driver->findElement(WebDriverBy::id('menu'))->click();
    \var_dump(7);
    // нажимаем музыка
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath("//a[@href='/audio']")));
    $driver->findElement(WebDriverBy::xpath("//a[@href='/audio']"))->click();

    \var_dump(8);
    // выбираем трек
    //    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('search')));
    //    $driver->findElement(WebDriverBy::name('search'))->sendKeys('rufus du sol - paris collides');
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::xpath('//input[@data-testid="search_input"]')));
    $searchField = $driver->findElement(WebDriverBy::xpath('//input[@data-testid="search_input"]'));
    $searchField->sendKeys('rufus du sol - paris collides')->sendKeys(WebDriverKeys::ENTER);
    \var_dump(9);
    // ищем кнопку чтобы добавить
    sleep(10);
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.MusicAudios-module__padding--gQDGb')));
    $elements = $driver->findElements(WebDriverBy::cssSelector('.MusicAudios-module__padding--gQDGb'));

    if (!empty($elements)) {
        // Нажимаем на первый элемент в списке
        $elements[0]->click();
    } else {
        echo 'Элемент не найден';
    }

    sleep(31);
    \var_dump(10);

    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.AudioPlayerBottomCollapsed')));
    $element = $driver->findElement(WebDriverBy::cssSelector('.AudioPlayerBottomCollapsed'))->click();
    sleep(5);
    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.AudioPlayerBottomExpanded__controls')));
    $element = $driver->findElement(WebDriverBy::cssSelector('.AudioPlayerBottomExpanded__controls'))->click();

    // Ожидание появления кнопки в DOM
    //    $wait = new WebDriverWait($driver, 10); // Указываем время ожидания в секундах
    //    $moreButton = $wait->until(
    //        WebDriverExpectedCondition::presenceOfElementLocated(
    //            WebDriverBy::cssSelector('.AudioPlayerBottomExpanded__more')
    //        )
    //    );
    //
    // // Нажатие на кнопку
    //    $moreButton->click();

    // Ожидание появления кнопки "Добавить в мою музыку"
    sleep(10);
    $addToMusicButton = $wait->until(
        WebDriverExpectedCondition::presenceOfElementLocated(
            WebDriverBy::cssSelector('.AudioMoreMenuItem')
        )
    );
    $addToMusicButton->click();
    // Предполагаем, что у вас уже есть объект $driver

    $driver->wait()->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('AudioMoreMenuItem'))
    );

    $button = $driver->findElement(WebDriverBy::className('AudioMoreMenuItem'));
    $driver->executeScript('arguments[0].click();', [$button]);

    // Нажатие на кнопку "Добавить в мою музыку"
    //    $addToMusicButton->click();
    //    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::cssSelector('.AudioMoreMenuItem__label')));
    //    $wait->until(WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('AudioMoreMenuItem__label')));
    //    $element = $driver->findElement(WebDriverBy::cssSelector('.AudioMoreMenuItem__label'))->click();
    //    $addMusicButton = $wait->until(
    //        WebDriverExpectedCondition::visibilityOfElementLocated(
    //            WebDriverBy::xpath("//span[contains(text(), 'Добавить в мою музыку')]")
    //        )
    //    );
    // Кликаем по кнопке
    //    $addMusicButton->click();
    sleep(15);
    //    $driver->executeScript("arguments[0].click();", [$element]);
    //    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('AudioPlayerBottomCollapsed')));
    //    $driver->findElement(WebDriverBy::className('AudioPlayerBottomCollapsed'))->click();
    \var_dump(11);

    //    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('AudioPlayerBottomExpanded__controls')));
    //    $driver->findElement(WebDriverBy::className('AudioPlayerBottomExpanded__controls'))->click();
    //    \var_dump(12);
    //    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::className('AudioMoreMenuItem')));
    //    $driver->findElement(WebDriverBy::className('AudioMoreMenuItem'))->click();
    //    \var_dump(13);
    //    sleep(15);
    //    // Закрываем браузер
    $driver->quit();
} catch (Throwable $e) {
    $driver->quit();
    \dd($e, 'unlucky');
}
