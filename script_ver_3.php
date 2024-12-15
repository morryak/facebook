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

// todo перенести все это в команду
try {
    // Открываем страницу
    $driver->get('https://vk.com');
    $wait = new WebDriverWait($driver, 10); // Ожидание до 10 секунд
    sleep(10);
    // Ждем, пока кнопка станет доступной
    $driver->wait()->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::xpath("//span[contains(text(), 'Other sign-in options')]")
        )
    );
    // Находим кнопку
    // Нажимаем на кнопку
    $button = $driver->findElement(
        WebDriverBy::xpath("//span[contains(text(), 'Other sign-in options')]")
    )->click();

    // Ждем, пока элемент станет доступным
    $driver->wait()->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(
            WebDriverBy::xpath("//span[text()='Email']")
        )
    );

    // Находим элемент с текстом "Email"
    $emailElement = $driver->findElement(
        WebDriverBy::xpath("//span[text()='Email']")
    );

    // Нажимаем на элемент
    $emailElement->click();
    // Ожидание загрузки элемента (если необходимо)
    $driver->wait()->until(
        WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::name('login'))
    );

    // Поиск элемента input по атрибуту name="login"
    $inputElement = $driver->findElement(WebDriverBy::name('login'));

    // Очистка (если нужно) и ввод текста в input
    $inputElement->clear(); // Удаляет текущее значение
    $inputElement->sendKeys('phone_number'); // Ввод текста

//    $driver->findElement(WebDriverBy::className('vkuiUnstyledTextField vkuiInput__el vkuiText vkuiText--sizeY-compact vkuiTypography vkuiRootComponent'))
//        ->sendKeys('phone_number');
    $wait->until(
        WebDriverExpectedCondition::elementToBeClickable(WebDriverBy::className('vkuiButton vkuiButton--size-l vkuiButton--mode-primary vkuiButton--appearance-accent vkuiButton--align-center vkuiButton--stretched vkuiTappable vkuiTappable--hasPointer-none vkuiClickable__resetButtonStyle vkuiClickable__host vkuiClickable__realClickable vkui-focus-visible vkuiRootComponent'))
    );
    // кнопка вход
    $driver->findElement(WebDriverBy::className('vkuiButton vkuiButton--size-l vkuiButton--mode-primary vkuiButton--appearance-accent vkuiButton--align-center vkuiButton--stretched vkuiTappable vkuiTappable--hasPointer-none vkuiClickable__resetButtonStyle vkuiClickable__host vkuiClickable__realClickable vkui-focus-visible vkuiRootComponent'))
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

    $trackList = [
        'Bite it, you scum GG Allin',
        'N.E.R.D. — Spaz',
    ];

    $wait->until(WebDriverExpectedCondition::visibilityOfElementLocated(WebDriverBy::id('audio_search')));

    foreach ($trackList as $track) {
        $driver->findElement(WebDriverBy::id('audio_search'))->clear()->sendKeys($track)->sendKeys(WebDriverKeys::ENTER);
        sleep(10);

        $elements = $driver->findElements(WebDriverBy::cssSelector('.audio_row__performer_title'));

        if (!empty($elements)) {
            // Прокручиваем к первому элементу
            $driver->executeScript('arguments[0].scrollIntoView();', [$elements[49]]);
            sleep(1); // Небольшая пауза

            // Кликаем с помощью JavaScript
            $driver->executeScript('arguments[0].click();', [$elements[49]]);
            \dump('Нажимаем на 49 в списке');
            // этот сработал
        }

        sleep(35);

        // Поиск элемента по классу
        $addMusicButton = $driver->findElement(
            WebDriverBy::cssSelector(
                '.vkuiIcon.vkuiIcon--24.vkuiIcon--w-24.vkuiIcon--h-24.vkuiIcon--add_24.vkitgetColorClass__colorIconSecondary--FEZTp'
            )
        );

        // Нажатие на элемент
        $addMusicButton->click();

        sleep(15);
    }

    // Закрываем браузер
} catch (Throwable $e) {
    \dump($e, 'unlucky');
} finally {
    $driver->quit();
}
