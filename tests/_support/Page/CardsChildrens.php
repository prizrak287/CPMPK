<?php
namespace Page;

use ExceptionWithPsycho;

class CardsChildrens
{
    // include url of current page
    public static $URL = '';
    const NONE_FILL = 'Документ не заполнен';
    const FILL = 'Документ заполнен';

    const EXAMINATION = 'Обследование';
    const GIA = 'ГИА';
    const CONSULTATION = 'Консультация';

    private $page = 'div.table-content';
    private $numberCard = 'div.row > div.cell.cell--card';
    private $name = 'div.row > div.cell.cell--name';
    private $dateOfBorn = 'div.row > div.cell.cell--date-birth';
    private $date = 'div.row > div.cell.cell--last-date-visit';
    private $numberReception = 'div.row > div.cell.cell--number-visit';
    private $type = 'div.row > div.cell.cell--type-visit';
    private $education = 'div.row > div.cell.cell--education';
    private $status = ' > div.row > div.cell.cell--status > div';

    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
    }

    public function openDocumentReception($status, $type)
    {
        $I = $this->tester;
        $I->waitForElement($this->page, 30);
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ')' . $this->status);
            $I->wait(1);
            $statusCurrent = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ')' . $this->status . ' > div.cards-status > div.tooltip.tooltip--left > div');
            if ($statusCurrent == $status) {
                break;
            }
        }
        $I->wait(1);
        $I->click('div.table.ps > div:nth-child(' . $i . ')' . $this->status);
        switch ($type) {
            case CardsChildrens::EXAMINATION:
                $item = 1;
                break;
            case CardsChildrens::GIA:
                $item = 2;
                break;
            case CardsChildrens::CONSULTATION:
                $item = 3;
                break;
            default:
                throw new ExceptionWithPsycho('Передан некорректный парамет type: ' . $type);
        }
        $I->waitForElement('div.popup', 10);
        $I->wait(1);
        $I->click('div.popup > form > label:nth-child(' . $item . ')');
        $I->wait(1);
        $I->click('a.button-orange', 'div.popup');
        $I->waitForElement('div.document', 60);
        $I->waitForElement('div.content', 60);
        $I->waitForElement('div.document-section', 60);
        $I->wait(1);
    }

    public function getRowOfPatient($name_of_patient)
    {
        $I = $this->tester;
        do {
            $count_rows = $I -> getQuantityElement('div[class="row"]');
            for ($i = 1; $i <= $count_rows; $i++) {
                $patient_current = $I -> grabTextFrom('div[class="row"]:nth-child(' . $i . ') > div[class="cell cell--name"]');
                if ($patient_current == $name_of_patient) {
                    $I->wait(1);
                    return $i;
                }
            }
            if ($I -> boolExistsElement('li[class="pagination__item pagination__item--next"]')) {
                $I -> click('li[class="pagination__item pagination__item--next"]', 'ul.pagination');
                $I -> waitForElement('div.table', 60);
                $repeat = true;
            }
            else {
                $repeat = false;
            }
        } while ($repeat);
        throw new ExceptionWithPsycho('Строки с именем пациента "' . $name_of_patient . '" не найдено.');
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
        return static::$URL.$param;
    }


}
