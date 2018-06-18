<?php
use Page\Login as LoginPage;
/**
 * @group admin
 */

class SinginAsAdminCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    public function _after(AcceptanceTester $I)
    {
    }

    // tests
    public function loginAsAdmin(AcceptanceTester $I, $scenario)
    {

        $I = new AcceptanceTester($scenario);
        $I->wantTo('login to site as Admin');
        $I->amOnPage(LoginPage::$URL);
        $I->wait(3);
        $I->fillField(LoginPage::$loginField, LoginPage::getLogin('admin'));
        $I->fillField(LoginPage::$passwordField, LoginPage::getPassword('admin'));
        $I->click(LoginPage::$loginButton);
        $I->see('Welcome, bill');
    }
}
