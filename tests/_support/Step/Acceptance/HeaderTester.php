<?php
namespace Step\Acceptance;
use \ExceptionWithPsycho;

class HeaderTester extends \AcceptanceTester
{
    const CARDS_CHILDREN = 'Карты детей';
    const RECORDS = 'Записи';
    const TABLE_RECEPTIONS = 'Табло приёмов';
    const ORDERS = 'Отчёты';
    const ADMIN = 'Администрирование';

    public function openSection($section, $tab = NULL)
    {
        switch ($section)
        {
            case 'Карты детей':
                $i = 1;
                $element = 'div.table.ps > div';
                break;
            case 'Записи':
                $i = 2;
                $element = '';
                break;
            case 'Табло приёмов':
                $i = 3;
                $element = 'div.visit-content';
                break;
            case 'Отчёты':
                $i = 4;
                $element = '';
                break;
            case 'Администрирование':
                $i = 5;
                $element = 'div.admin';
                break;
            default:
                throw new ExceptionWithPsycho('Передан неверный параметр.');
        }
        $this -> waitForElement('div.header-menu', 60);
        $this -> wait(1);
        $this -> click('a[class="header-menu__button button-icon"]', 'div.header-menu');
        $this -> waitForElementVIsible('div.nav-sidebar', 10);
        $this -> wait(1);
        $this -> click('li.nav__item:nth-child(' . $i . ')', 'div.nav-sidebar > ul.nav');
        $this -> waitForElement($element, 60);
        $this -> wait(1);
        if($tab) {
            $this -> click('li.tabs__tab:nth-child(2)', 'ul.tabs');
            $this -> waitForElement('div.visit-content', 60);
            $this -> wait(1);
        }
    }

    public function selectAddress($address)
    {
        $this -> waitForElement('div.control-select.control__select', 60);
        $this -> wait(1);
        $this -> click('div.control-select.control__select', 'div.control__left');
        $this -> waitForElement('ul.dropdown-menu', 10);
        $this -> wait(1);
        for ($i = 1; $i < 5; $i++) {
            $current_address = $this->grabTextFrom('ul.dropdown-menu > li[role="option"]:nth-child(' . $i . ') > a');
            if ($current_address == $address) break;
        }
        $this -> click('li[role="option"]:nth-child(' . $i . ')', 'ul.dropdown-menu');
        $this -> waitForElementNotVisible('ul.dropdown-menu', 10);
        $this -> wait(1);
        $this -> see($address, 'div.control-select.control__select');
    }
}