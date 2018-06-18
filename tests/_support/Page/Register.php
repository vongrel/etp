<?php

namespace Page;

class Register
{
    public static $URL = '/#front/resident';

    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }


    public function fillFirstForm($typeOfRegistration)
    {
        $json = Register::getJson('selectors.json');
        Register::parseJson($json, $typeOfRegistration);
    }

    public function getJson($name)
    {
        $json = file_get_contents($name);
        $json_data = json_decode($json, true);
        return $json_data;
    }

    //to enable access to Admin's verify code
    public function putJson($name, $jsonName)
    {
        $json = json_encode($name);
        file_put_contents($jsonName, $json);
    }

    public function parseJson($json_data, $typeOfRegistration)
    {
        $mock = 'autotestUser' . date('m-d-h-i');
        $mockEmail = $mock . '@' . $mock;
        Register::saveParamTmpStorage('email', $mockEmail);
        $json_data = $json_data['User Data'];
        $json_data = $json_data[$typeOfRegistration];
        foreach ($json_data as $key1 => $value1) {
            print_r($json_data[$key1]);
            $selector = $json_data[$key1]['selector'];
            $content = $json_data[$key1]['content'];
            if ($content != NULL) {
                if ($content == 'MOCK') {
                    if ($json_data[$key1]['field name'] == 'email') {
                        Register::fillInput($selector, $mockEmail);
                    } else {
                        Register::fillInput($selector, $mock);
                    }
                } else {
                    Register::fillInput($selector, $content);
                }
            } else {
                echo 'Check content in ' . $json_data[$key1]['field name'];
            }
        }
    }

    public function fillInput($where, $what)
    {
        $I = $this->tester;
        $I->fillField($where, $what);
        return $this;
    }

    public function fillProduction()
    {
        $json_data = Register::getJson('selectors.json');
        $json_data = $json_data['Production field'];
        $I = $this->tester;
        $I->click('Добавить');
        $I->wait(4);
        foreach ($json_data as $key1 => $value1) {
            $I->doubleClick($json_data[$key1]);
            $I->wait(1);
        }
        return $this;
    }

    public function fillCorporateData()
    {
        $json_data = Register::getJson('selectors.json');
        $json_data = $json_data['Corporate Data'];
        $json_data = $json_data['Юр лицо из РФ'];
        $I = $this->tester;
        foreach ($json_data as $key1 => $value1) {
            $selector = $json_data[$key1]['selector'];
            $content = $json_data[$key1]['content'];
            if ($content != NULL) {
                Register::fillInput($selector, $content);
            } else {
                echo 'Check content in ' . $json_data[$key1]['field name'];

            }

        }
        return $this;


    }

    public function getEmailAndSaveVerifyCode()
    {
        $I = $this->tester;
        $userEmail = Register::getParamFromTmpStorage('email');
        $I->wait(1);

        $I->fillField('.x-form-text.x-form-field.search_field_cls', $userEmail);
        $I->click('Искать');
/*Insert try catch here to handle cases when email isn't send yet
        $I->click('Расширенный поиск');
        $I->fillField('input[name=email]', $userEmail);
        $I->click('неотправленных');
        $I->clickWithLeftButton('.x-panel-fbar.x-small-editor.x-toolbar-layout-ct button');
*/
        $I->wait(2);
        $I->click('Просмотр');
        $I->wait(3);
        $text = $I->grabTextFrom('form > label');
        if(preg_match("/активации: (.*?) /", $text,$matches))
            $text1 = $matches[1];
        Register::saveParamTmpStorage('confirmCode',$text1);
    }

    public function getParamFromTmpStorage($emailOrPwd)
    {
        $json = Register::getJson('tmpStorage.json');
        return $json['emailAndCode'][$emailOrPwd];
    }


    public function saveParamTmpStorage($type, $emailOrPwd)
    {
        $json = Register::getJson('tmpStorage.json');
        $json['emailAndCode'][$type] = $emailOrPwd;
        Register::putJson($json, 'tmpStorage.json');
    }


}
