<?php

namespace Page;

use ExceptionWithPsycho;

class Admin
{
    // include url of current page
    public static $URL = '';
    public $users = 'div.admin-menu-wrapper > div.admin-menu > div.admin-menu__item:nth-child(1)';
    public $archive = 'div.admin-menu-wrapper > div.admin-menu > div.admin-menu__item:nth-child(2)';

    private $contextMenu = ' > div > div.multiselect > div.multiselect__content-wrapper > ul.multiselect__content';
    private $page = 'div.admin';
    private $usersTable = 'div.table-wrapper > div.admin-table-wrapper';
    private $addUserButton = 'a[class="button-action button-action--invert control__button-action control__button-action--invert"]';
    private $formNewUser = 'div[class="admin-form admin-form--top"]';
    private $FIO = 'div[class="admin-form admin-form--top"] > div.admin-form__row:nth-child(1) > div > label > div.field-wrapper > input#admin-form-1';
    private $mail = 'div[class="admin-form admin-form--top"] > div.admin-form__row:nth-child(2) > div > label > div.field-wrapper > input#admin-form-2';
    private $password = 'div[class="admin-form admin-form--top"] > div.admin-form__row:nth-child(3) > div > label > div.field-wrapper > input#admin-form-3';
    private $role = 'div[class="admin-form admin-form--top"] > div.field-wrapper > div > div > label > div.admin-form__select-row';
    private $position = 'div[class="admin-form admin-form--top"] > div:nth-child(5) > div.admin-form__row:nth-child(1) > div > label > div.admin-form__select-row:nth-child(2) > div.admin-form__select-row';
    private $addPositionButton = 'div[class="admin-form admin-form--top"] > div:nth-child(5) > div.admin-form__row:nth-child(2) > a[class="button-add button-add--active"]';
    private $saveButton = 'div[class="admin-form__row admin-form__row--end"] > a[class="button-orange button-orange--save"]';
    private $deleteButton = 'div.popup.popup-visit-start > div.popup-visit-start__button > a.button-orange';

    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function createUser($FIO, $mail, $password, $role, $position)
    {
        $I = $this->tester;
        $I->waitForElement($this->page, 30);
        $I->wait(1);
        $I->click($this->users);
        $I->waitForElement($this->usersTable, 30);
        $I->waitForElement($this->addUserButton, 30);
        $I->wait(1);
        //Проверка на наличие пользователя с таким же именем
        $count = $I->getQuantityElement('div.admin-table > div[class="row"]', 4);
        for ($i = 4; $i <= $count; $i++) {
            $nameCurrent = $I->grabTextFrom('div.admin-table > div[class="row"]:nth-child(' . $i . ')
             > a > div.admin-cell.cell:nth-child(3) > span');
            if ($nameCurrent == $FIO) {
                throw new \ExceptionWithPsycho('Пользовтаель с именем ' . $FIO . ' уже существует.');
            }
        }
        $I->wait(1);
        $I->click($this->addUserButton);
        $I->waitForElement($this->formNewUser);
        $I->wait(1);
        //Заполняем ФИО
        $I->fillField($this->FIO, $FIO);
        $I->wait(1);
        $I->seeInField($this->FIO, $FIO);
        //Заполняем mail
        $I->fillField($this->mail, $mail);
        $I->wait(1);
        $I->seeInField($this->mail, $mail);
        //Заполняем mail
        $I->fillField($this->password, $password);
        $I->wait(1);
        $I->seeInField($this->password, $password);
        //Выбираем роль
        $this->setValueFieldInDocumentReception($this->role, $role);
        //Добавляем должномть
        $I->click($this->addPositionButton);
        $I->waitForElement($this->position, 10);
        $I->wait(1);
        $this->setValueFieldInDocumentReception($this->position, $position);
        $I->click($this->saveButton);
        $I->waitForText('Пользователь создан', 30);
        $I->wait(1);
    }

    public function deleteUser($name)
    {
        $I = $this->tester;
        $I->waitForElement($this->page, 30);
        $I->wait(1);
        $I->click($this->users);
        $I->waitForElement($this->usersTable);
        $I->wait(1);
        $userIsFound = false;
        $count = $I->getQuantityElement('div.admin-table > div[class="row"]', 4);
        //Поиск пользователя по имени
        for ($i = 4; $i <= $count; $i++) {
            $nameCurrent = $I->grabTextFrom('div.admin-table > div[class="row"]:nth-child(' . $i . ')
             > a > div.admin-cell.cell:nth-child(3) > span');
            if ($nameCurrent == $name) {
                $userIsFound = true;
                break;
            }
        }
        if ($userIsFound) {
            //Удаление пользователя, если пользователь найден
            $I->click('div.admin-table > div[class="row"]:nth-child(' . $i . ') > div.admin-cell > span > a.admin-cell__delete');
            $I->waitForText('Удалить пользователя?', 10, 'div.popup.popup-visit-start');
            $I->wait(1);
            $I->click($this->deleteButton);
            $I->wait(3);
            $I->dontSee($name, $this->usersTable);
        } else {
            throw new ExceptionWithPsycho('Пользователь ' . $name . ' не найден!');
        }
    }

    private function setValueFieldInDocumentReception($field, $value)
    {
        $I = $this->tester;
        $valueFound = false;
        $I->click($field);
        $I->waitForElement($field . $this->contextMenu);
        $I->wait(1);
        $countItem = $I->getQuantityElement($field . $this->contextMenu . ' > li.multiselect__element');
        for ($count = 1; $count <= $countItem; $count++) {
            $valueCurrent = $I->grabTextFrom($field . $this->contextMenu . ' > li.multiselect__element:nth-child(' . $count . ')');
            if ($valueCurrent == $value) {
                $valueFound = true;
                break;
            }
        }
        if ($valueFound) {
            $I->click($field . $this->contextMenu . ' > li.multiselect__element:nth-child(' . $count . ')');
            $I->wait(1);
            $I->see($value, $field);
        }
        else {
            throw new ExceptionWithPsycho('Значение "' . $value . '" не найдено в поле "' . $field . '".');
        }
        $I->wait(1);
    }



    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$URL . $param;
    }


}
