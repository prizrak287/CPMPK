<?php

namespace Page;

use ExceptionWithPsycho;

class TableReception
{
    // include url of current page
    public static $URL = '';

    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function selectBlockOfPatient($name_of_patient)
    {
        $I = $this->tester;
        $I->waitForElement('li.visit-list__item.visit-card');
        $count_time_blocks = $I->getQuantityElement('div.visit-section', 3);
        for ($i = 3; $i <= $count_time_blocks; $i++) {
            $count_patient = $I->getQuantityElement('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card');
            for ($j = 1; $j <= $count_patient; $j++) {
                if (!$I->boolSeeElement('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')')) {
                    if ($i !== $count_patient)
                        $I->dragAndDrop('div.ps__rail-y > div.ps__thumb-y', 'div.visit-section:nth-child(' . ($i + 1) . ')');
                    else
                        $I->dragAndDrop('div.ps__rail-y > div.ps__thumb-y', 'footer.footer');
                }
                $patient_current = $I->grabTextFrom('div.visit-section:nth-child(' . ($i) . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')
                 > div[class="visit-card__content"] > div > div');
                if ($patient_current == $name_of_patient) {
                    $I->click('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')');
                    $I->wait(1);
                    $class = $I->grabAttributeFrom('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')', 'class');
                    if ($class !== 'visit-list__item visit-card visit-card--active')
                        $I->comment('Выбранный блок не активен');
                    break 2;
                }
            }
        }
        if ($i > $count_time_blocks)
            throw new ExceptionWithPsycho('Блока с именем пациента "' . $name_of_patient . '" не найдено.');
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
