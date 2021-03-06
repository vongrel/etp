<?php

use Page\Register as Register;
use Page\Login as LoginPage;

/**
 * @group register
 */


class RegisterULRFCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    public function RegisterULRF(AcceptanceTester $I, $scenario)
    {

        $I = new AcceptanceTester($scenario);
        $I->wantTo('register to the site as UL from RF');
        $I->amOnPage(Register::$URL);
        $I->waitForText('Укажите резидентский статус', 10);
        $I->click('Далее -->');
        //В идеале все "$I->wait(2);" дожны быть заменены на похожие. Выигрыш во времени
        //прохождения тестов, но писать дольше
        $I->waitForText('Регистрация на торговых площадках', 10);
        $Register = new Register($I);
        //Предполагается, что данный метод будет являться основным в регистрациях
        //При более детальном понимании площадки можно выделить общее и вынести в один метод, а не общее
        //оставить более специфичным для конкретных регистраций
        $Register->fillFirstForm('Юр лицо из РФ');
        $I->wait(2);
        $I->click('Далее -->');
        //Переход на заполнение данных об организации
        $I->waitForText('Основные сведения', 10);
        $I->click('.x-panel-body.x-panel-body-noheader.x-box-layout-ct > div > table');
        $I->wait(1);
        $I->doubleClick('//span[contains(text(), "91 Индивидуальные предприниматели")]');
        $I->wait(1);
        //Выбран ИНН, который заполняет часть полей через контрагентов. Можно или удалять всех созданных
        //пользователей после выполнения тестов (можно выполнять данное действие, даже если тесты упали,
        //заодно проверяя реально ли они созданы. Более предпочтительный вариант). Или можно генерировать
        //ИНН через сайты или скрипом (есть на в открытых репах)
        $I->fillField('input[name=inn]', '7842401454');
        $I->clickWithLeftButton('input[name=kpp]');
        $I->wait(2);
        $I->doubleClick('.x-grid3-col > div');
        $I->wait(2);
        $I->click('Да');
        //Ожидание такое большое, потому что запрос к контрагентам или быстрый или ооочень медленный
        $I->wait(20);
        $I->click('OK');
        $I->wait(2);
        $I->fillField('//input[contains(@name, \'legal\') and contains(@name, \'city\')]', 'Омск');
        $I->fillField('input[name=registration_date]', '04.04.18');
        $Register->fillProduction();
        $Register->fillCorporateData();
        $I->fillField('textarea[name=offered_products]', 'some text');
        //Бик подтягивает часть полей
        $I->fillField('input[name=bik]','045209673');
        $I->fillField('input[name=account]', '12345678912345678900');
        $I->clickWithLeftButton('input[name=accept_processing]');
        $I->wait(1);
        $I->click('Зарегистрироваться');
        $I->wait(5);
        $I->click('Подтвердить');
        $I->waitForText('Активация адреса электронной почты', 25);
        //Самое интересное, в новом окне, не закрывая основное админ логинится и достает ключ для
        //верификации. Возможно также доставать и ссылку
        $admin = $I->haveFriend('admin');
        $admin->does(function(AcceptanceTester $I) {
            $I->amOnPage(LoginPage::$URL);
            $I->wait(1);
            $I->fillField(LoginPage::$loginField, LoginPage::getLogin('admin'));
            $I->wait(1);
            $I->fillField(LoginPage::$passwordField, LoginPage::getPassword('admin'));
            $I->click(LoginPage::$loginButton);
            $I->waitForText('Администратор ЭТП Системы');
            $I->amOnPage('/#log/maillog');
            $I->wait(1);
            $Register = new Register($I);
            $Register->getEmailAndSaveVerifyCode();
            $I->wait(2);
        });
        //Закрываем окно админа и получаем фокус на окне с активацией
        $admin->leave();
        $I->wait(2);
        $I->fillField('input[name=key]',$Register::getParamFromTmpStorage('confirmCode'));
        $I->click('Активировать');
        $I->wait(1);
        $I->clickWithLeftButton('.x-tool.x-tool-close');
        $I->wait(5);
        //закомментировано из-за ошибки отправки в ЭДО
        /*$I->click('Подключить ЭДО');
        $I->fillField('input[name=ifns]', '5501');
        $emailForPwd = $Register::getParamFromTmpStorage('confirmCode');
        $I->fillField('input[name=edo_password]', $emailForPwd);
        $I->fillField('input[name=confirm_password]', $emailForPwd);
        $I->clickWithLeftButton('img[src="/css/images/default/s.gif"]');
        $I->click('Мужской');
        $I->fillField('//input[contains(@name, \'user_phone\') and contains(@name, \'cntr_code\')]', '7');
        $I->fillField('//input[contains(@name, \'user_phone\') and contains(@name, \'city_code\')]', '111');
        $I->fillField('//input[contains(@name, \'user_phone\') and contains(@name, \'number\')]', '2222222');
        $I->click('Подписать');
        $I->clickWithLeftButton('.x-btn.signature_submit_button button');*/
        $I->clickWithLeftButton('.x-window.x-window-plain.x-window-dlg .x-tool.x-tool-close');
        $I->wait(1);
        $I->clickWithLeftButton('input[name=agree]');
        $I->wait(1);
        $I->click('Подтвердить');
        $I->click('Отказаться от опроса');
        $I->wait(7);


    }
}
