<?php

namespace Page;

use \ExceptionWithPsycho;

class DocumentReception
{
    // include url of current page
    public static $URL = '';
    const ALL = 'all';
    const EMPTY = 'empty';

    public $header = 'div.header-section';
    private $documentHeader = 'div.document-header';
    public $document = ' > div.document-item__row';
    public $contextMenu = ' > div.document-item__row > div > div.multiselect > div.multiselect__content-wrapper > ul.multiselect__content';
    public $requiredField = ' > label > span > span.document-item__title-required';
    public $requiredForCheckboxes = ' > label > span > span > span.document-item__title-required';
    public $bodyCheckbox = ' > label > span.document-checkbox__icon';
    private $scroll = 'div.ps__rail-y > div.ps__thumb-y';
    private $previousScroll;

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

    public $teacherPsy = 'div.document-section:nth-child(2) > div.document-section__row:nth-child(2) > div.document-item:nth-child(1)';
    public $teacherLogoped = 'div.document-section:nth-child(2) > div.document-section__row:nth-child(3) > div.document-item:nth-child(1)';
    public $teacherDefect = 'div.document-section:nth-child(2) > div.document-section__row:nth-child(4) > div.document-item:nth-child(1)';
    public $teacherSocial = 'div.document-section:nth-child(2) > div.document-section__row:nth-child(5) > div.document-item:nth-child(1)';

    public $timeRepeatInspection = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(1) > div.document-item:nth-child(1)';
    public $recommendedInspection = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(1) > div.document-item:nth-child(2) > div.document-checkbox-wrapper:nth-child(1)';
    public $incompleteDocumentation = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(1) > div.document-item:nth-child(2) > div.document-checkbox-wrapper:nth-child(2)';
    public $kindCommission = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(2) > div.document-item:nth-child(1)';
    public $resultCommission = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(2) > div.document-item:nth-child(2)';
    public $personalPlan = 'div.document-section:nth-child(3) > div.document-section__row:nth-child(3) > div.document-item:nth-child(1)';

    public $memberCommissionPsy = 'div.document-section:nth-child(4) > div:nth-child(2) > div.document-section__row:nth-child(1) > div.document-item:nth-child(1)';
    public $memberCommissionSocial = 'div.document-section:nth-child(4) > div:nth-child(2) > div.document-section__row:nth-child(2) > div.document-item:nth-child(1)';
    public $memberCommissionDefect = 'div.document-section:nth-child(4) > div:nth-child(2) > div.document-section__row:nth-child(3) > div.document-item:nth-child(1)';
    public $memberCommissionLogoped = 'div.document-section:nth-child(4) > div:nth-child(2) > div.document-section__row:nth-child(4) > div.document-item:nth-child(1)';
    public $buttons = 'div.document-buttons';
    private $footer = 'footer.footer';

    public $ovgItems = ['Ранняя помощь', 'Дошкольники', 'ФГОС НОО ОВЗ, ФГОС УО', 'СОШ', 'СПО'];
    public $programItems = ['Глухие', 'Слабослышащие', 'Слепые', 'Слабовидящие', 'Тяжелые нарушения речи', 'Нарушения опорно двигательного аппарата',
        'Задержка психического развития', 'Расстройство аутистического спектра', 'Умственная отсталость', 'Сложные дефекты', 'Основная образовательная программа(норма)'];
    public $levelEducationItems = ['Дошкольный возраст', 'Начальный общий', 'Основной общий', 'Средний общий'];
    public $assistantItems = ['(НОДА, см. ИПРА) оказание помощи в использовании технических средств реабилитации',
        '(СД и НОДА без навыков самообслуживания) оказание помощи в соблюдении санитарно-гигиенических требований на группу/класс',
        '(НОДА-колясочники) обеспечение доступа в здание образовательной организации и предоставляемым в нем услугам',
        '(НОДА-колясочники) оказание технической помощи по преодолению препятствий',
        '(слепые) оказание индивидуальной технической помощи по преодолению препятствий в условиях инклюзивного образования'];
    public $nameOOItems = ['Школа № 1', 'Школа № 2', 'Школа № 3', 'Школа № 4'];
    public $tutorEscortItems = ['(обучающимся с РАС от полугода до 1 года) индивидуальное сопровождение на период адаптации в условиях инклюзивного образования',
        '(обязательно для выбора) осуществление общего тьюторского сопровождения реализации АООП',
        '(обязательно для выбора) педагогическое сопровождение обучающихся в реализации АООП',
        '(по согласованию) подбор и адаптация педагогических средств, индивидуализация образовательного процесса',
        '(по согласованию) разработка и подбор методических средств (визуальной поддержки, альтернативной коммуникации)'];
    public $districtItems = ['Не посещал', 'Центральный', 'Северный', 'Северо-Восточный', 'Восточный', 'Юго-Восточный', 'Южный', 'Юго-Западный', 'Западный', 'Северо-Западный', 'Зеленоградский', 'Троицкий', 'Новомосковский', 'Нет данных'];
    public $teacherSocialItems = ['(для обучающихся под опекой и ЦССВ) мониторинг социальной ситуации развития',
        '(1-5 классы – для обучающихся «группы риска») профилактика и коррекция асоциального (девиантного) поведения обучающегося',
        '(6-11 классы – для обучающихся «группы риска») профилактика и коррекция асоциального (девиантного) поведения обучающегося, повышение уровня правовой грамотности обучающегося и его семьи',
        '(для обучающегося «группы риска») профилактика и коррекция асоциального (девиантного) поведения обучающегося, повышение уровня правовой грамотности обучающегося',
        '(обязательно для выбора) координация взаимодействия субъектов образовательного процесса'];
    public $kindCommissionItems = ['Не требуется', 'Углубленная комиссия', 'Расширенная комиссия', 'Межведомственная комиссия'];
    public $resultCommissionItems = ['Не требуется', 'Подтверждение образовательного маршрута', 'Изменение образовательного маршрута'];

    protected $tester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->tester = $I;
        $this->previousScroll = $this->header;
    }

    public function checkingFiledInDocumentReception($field, $arr)
    {
        $I = $this->tester;
        switch ($field) {
            case $this->ovg:
                $array = $this->ovgItems;
                break;
            case $this->program:
            case $this->withPsychoSpecial:
                $array = $this->programItems;
                break;
            case $this->nameOO:
                $array = $this->nameOOItems;
                break;
            case $this->districtOO:
                $array = $this->districtItems;
                break;
            case $this->levelEducation:
                $array = $this->levelEducationItems;
                break;
            case $this->chosenProgram:
            case $this->freeEnvironment:
            case $this->specialFacilities:
                $array = ['Требуется', 'Не требуется'];
                break;
            case $this->assistant:
                $array = $this->assistantItems;
                break;
            case $this->tutorEscort:
                $array = $this->tutorEscortItems;
                break;
            case $this->teacherSocial:
                $array = $this->teacherSocialItems;
                break;
            case $this->kindCommission:
                $array = $this->kindCommissionItems;
                break;
            case $this->resultCommission:
                $array = $this->resultCommissionItems;
                break;
            case $this->variantProgram:
            default:
                $array = null;
        }
        $I->wait(1);
        $I->click($field . $this->document);
        $I->waitForElement($field . $this->contextMenu);
        $I->wait(1);
        $countItem = $I->getQuantityElement($field . $this->contextMenu . '> li.multiselect__element');
        if ($arr == DocumentReception::ALL) {
            $arr = $array;
        } elseif ($arr == DocumentReception::EMPTY) {
            $arr = ['Нет данных'];
            $countItem = 1;
        } elseif (!is_array($arr)) {
            throw new ExceptionWithPsycho('Передан некорректный параметр "$arr = "' . $arr . '.');
        }
        if ($array != null) {
            if ($countItem != count($arr)) {
                throw new ExceptionWithPsycho('Список элементов "' . $field . '" не соответствует требованиям.');
            }
            foreach ($array as $item) {
                if (in_array($item, $arr)) {
                    $I->see($item, $field . $this->contextMenu);
                } else {
                    $I->dontSee($item, $field . $this->contextMenu);
                }
            }
        } else {
            foreach ($arr as $item) {
                $I->see($item, $field . $this->contextMenu);
            }
        }
        unset($item);
        $I->click($this->header);
        $I->wait(1);
    }

    public function setValueFieldInDocumentReception($field, $value)
    {
        $I = $this->tester;
        $valueFound = false;
        $I->click($field . $this->document);
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
        } else {
            throw new ExceptionWithPsycho('Значение "' . $value . '" не найдено в поле "' . $field . '".');
        }
        $I->wait(1);
    }

    public function scrollTo($element)
    {
        $I = $this->tester;
        $elements = get_class_vars(get_class($this)); //Получаем ассоциативный массив полей класса
        if ($element == $this->header) {
            $I->dragAndDrop($this->scroll, $this->header);
            $I->wait(1);
        } elseif ($element == $this->memberCommissionLogoped) {
            $I->dragAndDrop($this->scroll, $this->footer);
            $I->wait(1);
        } else {
            //
            $arrayKeys = array_keys($elements); //Получаем индексированный массив ключей ассоциативного массива
            $key = array_search($element, $elements); //Получаем ключ элемента, переданного через параметр в ассоциативном массиве
            $index = array_search($key, $arrayKeys); //Получеам индекс (последовательный номер расположения на странице) ключа элемента в параметреиз массива ключей
            $keyPrevious = array_search($this->previousScroll, $elements); //Получаем ключ предыдущего проскроленного элемента из ассоциативного массива
            $previousIndex = array_search($keyPrevious, $arrayKeys); //Получаем индекс ключа предыдущего элемента из массива ключей
            if ($index === false || $previousIndex === false) {
                throw new ExceptionWithPsycho('Элемент ' . $element . ' не найден в массиве');
            }
            if ($index > $previousIndex) {
                $i = 0;
                while (!$I->boolSeeElement($element)) {
                    $I->pressKey($this->scroll, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN);
                    if ($I->boolSeeElement($this->buttons)) {
                        $i++;
                        if ($i >= 10) {
                            throw new ExceptionWithPsycho('Не удалось проскроллить до элемента ' . $element);
                        }
                    }
                }
                $i = 0;
                while ($I->boolSeeElement($element) && !$I->boolSeeElement($this->buttons)) {
                    $I->pressKey($this->scroll, \Facebook\WebDriver\WebDriverKeys::ARROW_DOWN);
                    if ($I->boolSeeElement($this->buttons)) {
                        $i++;
                        if ($i >= 10) {
                            throw new ExceptionWithPsycho('Не удалось проскроллить до элемента ' . $element);
                        }
                    }
                }
                $count = 3;
            } else {
                $i = 0;
                while (!$I->boolSeeElement($element)) {
                    $I->pressKey($this->scroll, \Facebook\WebDriver\WebDriverKeys::ARROW_UP);
                    $i++;
                    if ($I->boolSeeElement($this->documentHeader)) {
                        $i++;
                        if ($i >= 10) {
                            throw new ExceptionWithPsycho('Не удалось проскроллить до элемента ' . $element);
                        }
                    }
                }
                $count = 2;
            }
            for ($i = 0; $i < $count; $i++) {
                $I->pressKey($this->scroll, \Facebook\WebDriver\WebDriverKeys::ARROW_UP);
            }
        }
        $this->previousScroll = $element;
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
