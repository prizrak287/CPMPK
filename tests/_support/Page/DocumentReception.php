<?php
namespace Page;
use \ExceptionWithPsycho;
class DocumentReception
{
    // include url of current page
    public static $URL = '';
    public $document = ' > div.document-item__row';
    public $contextMenu = ' > div.document-item__row > div > div.multiselect > div.multiselect__content-wrapper > ul.multiselect__content';
    public $required = ' > label > span > span.document-item__title-required';
    public $requiredForCheckboxes = ' > label > span > span > span.document-item__title-required';
    public $type = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(3) > div.document-item:nth-child(1)';
    public $ovg = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(4) > div.document-item:nth-child(1)';
    public $program = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(4) > div.document-item:nth-child(2)';
    public $FIO = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(5) > div.document-item:nth-child(1)';
    public $dateOfBorn = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(5) > div.document-item:nth-child(2) > div.document-item';
    public $bilingual = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(5) > div.document-item:nth-child(2) > div.document-checkbox-wrapper';
    public $hasInvalid = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(6) > div.document-item:nth-child(1) > div.document-checkbox-wrapper:nth-child(1)';
    public $invalid = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(6) > div.document-item:nth-child(1) > div.document-item:nth-child(2)';
    public $deviant = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(6) > div.document-item:nth-child(1) > div.document-checkbox-wrapper:nth-child(3)';
    public $nameOO = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(7) > div.document-item:nth-child(1)';
    public $districtOO = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(7) > div.document-item:nth-child(2)';
    public $chosenProgram = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(8) > div.document-item:nth-child(1)';
    public $withPsychoSpecial = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(9) > div.document-item:nth-child(1)';
    public $levelEducation = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(9) > div.document-item:nth-child(2)';
    public $variantProgram = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(10) > div.document-item:nth-child(1)';
    public $assistant = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(10) > div.document-item:nth-child(2)';
    public $freeEnvironment = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(11) > div.document-item:nth-child(1)';
    public $specialFacilities = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(11) > div.document-item:nth-child(2)';
    public $tutorEscort = 'div.document-section:nth-child(1) > div.document-section__row:nth-child(12) > div.document-item:nth-child(1)';

    public $ovgItems = ['Ранняя помощь', 'Дошкольники', 'ФГОС НОО ОВЗ, ФГОС УО', 'СОШ', 'СПО'];
    public $programItems = ['Глухие', 'Слабослышащие', 'Слепые', 'Слабовидящие', 'Тяжелые нарушения речи', 'Нарушения опорно двигательного аппарата',
        'Задержка психического развития', 'Расстройство аутистического спектра', 'Умственная отсталость'];
    public $levelEducationItems = ['Дошкольный возраст', 'Начальный общий', 'Основной общий', 'Средний общий'];
    public $assistantItems = ['(НОДА, см. ИПРА) оказание помощи в использовании технических средств реабилитации',
        '(СД и НОДА без навыков самообслуживания) оказание помощи в соблюдении санитарно-гигиенических требований на группу/класс',
        '(НОДА-колясочники) обеспечение доступа в здание образовательной организации и предоставляемым в нем услугам',
        '(НОДА-колясочники) оказание технической помощи по преодолению препятствий',
        '(слепые) оказание индивидуальной технической помощи по преодолению препятствий в условиях инклюзивного образования'];
    public $nameOOItems = ['Школа № 1', 'Школа № 2', 'Школа № 3', 'Школа № 4'];
    public $tutorEscortItems=['(обучающимся с РАС от полугода до 1 года) индивидуальное сопровождение на период адаптации в условиях инклюзивного образования',
        '(обязательно для выбора) осуществление общего тьюторского сопровождения реализации АООП',
        '(обязательно для выбора) педагогическое сопровождение обучающихся в реализации АООП',
        '(по согласованию) подбор и адаптация педагогических средств, индивидуализация образовательного процесса',
        '(по согласованию) разработка и подбор методических средств (визуальной поддержки, альтернативной коммуникации)'];
    public $districtItems = ['Не посещал', 'Центральный', 'Северный', 'Северо-Восточный', 'Восточный', 'Юго-Восточный', 'Южный', 'Юго-Западный', 'Западный', 'Северо-Западный', 'Зеленоградский', 'Троицкий', 'Новомосковский', 'Нет данных'];

    protected $tester;
    public function __construct(\AcceptanceTester $I)
    {
        $this -> tester = $I;
    }

    public function checkingFiledInDocumentReception($field, $arr = null)
    {
        $I = $this -> tester;
        $array = null;
        switch($field) {
            case $this -> ovg:
                $array = $this -> ovgItems;
                break;
            case $this -> program:
            case $this -> withPsychoSpecial:
                $array = $this -> programItems;
                break;
            case $this -> nameOO:
                $array = $this ->nameOOItems;
                break;
            case $this -> districtOO:
                $array = $this -> districtItems;
                break;
            case $this -> levelEducation:
                $array = $this -> levelEducationItems;
                break;
            case $this -> chosenProgram:
            case $this -> freeEnvironment:
            case $this -> specialFacilities:
                $array = ['Требуется', 'Не требуется'];
                break;
            case $this -> variantProgram:
                break;
            case $this -> assistant:
                $array = $this -> assistantItems;
                break;
            case $this -> tutorEscort:
                $array = $this -> tutorEscortItems;
                break;
            default:
                throw new ExceptionWithPsycho('Некорректный параметр "field"');
        }
        $I -> click($field . $this -> document);
        $I -> waitForElement($field . $this ->contextMenu);
        $I -> wait(1);
        $countItem = $I->getQuantityElement($field . $this ->contextMenu . '> li.multiselect__element');
        if ($arr == 'all')  {
            $arr = $array;
        }
        elseif ($arr == 'empty') {
            $arr = ['Нет данных'];
            $countItem = 1;
        }
        elseif (!is_array($arr)) {
            throw new ExceptionWithPsycho('Передан некорректный параметр "$arr = "' . $arr . '.');
        }
        if ($array != null) {
            if ($countItem != count($arr)) {
                throw new ExceptionWithPsycho('Список элементов "' . $field . '" не соответствует требованиям.');
            }
            foreach($array as $item) {
                if (in_array($item, $arr)){
                    $I -> see($item, $field . $this -> contextMenu);
                }
                else {
                    $I -> dontSee($item, $field . $this -> contextMenu);
                }
            }
        }
        else {
            foreach($arr as $item) {
                $I -> see($item, $field . $this -> contextMenu);
            }
        }
        unset($item);
        $I -> click($field . ' > label');
        $I -> wait(1);
    }

    public function setValueFieldInDocumentReception($field, $value) {
        $I = $this -> tester;
        $valueFound = false;
        $I -> waitForElement('div.document', 60);
        $I -> waitForElement('div.content', 60);
        $I -> waitForElement('div.document-section', 60);
        $I -> click($field . $this -> document);
        $I -> waitForElement($field . $this -> contextMenu);
        $I -> wait(1);
        $countItem = $I -> getQuantityElement($field . $this -> contextMenu . ' > li.multiselect__element');
        for ($count = 1; $count <= $countItem; $count++) {
            $valueCurrent = $I -> grabTextFrom($field . $this -> contextMenu . ' > li.multiselect__element:nth-child(' . $count . ')');
            if ($valueCurrent == $value) {
                $I -> click($field . $this -> contextMenu . ' > li.multiselect__element:nth-child(' . $count . ')');
                $I -> wait(1);
                $I -> see($value, $field);
                $valueFound = true;
                break;
            }
        }
        if (!$valueFound) {
            throw new ExceptionWithPsycho('Значение "' . $value . '" не найдено в поле "' . $field . '".');
        }
        $I -> wait(1);
    }

    public function scrollTo($element)
    {
        $I = $this -> tester;
        $I -> dragAndDrop('div.ps__rail-y', $element);
        $I -> wait(1);
        $I -> click('div.header-section');
        $I -> wait(1);
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
