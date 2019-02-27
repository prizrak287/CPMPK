<?php

use Page\CardsChildrens;
use \Page\DocumentReception as DocumentReception;
use \Step\Acceptance\HeaderTester as HeaderTester;
use \Page\Admin as Admin;

class PsychoCest
{
    public function Test1InputInSystem(AcceptanceTester $I)
    {
        //не готов, не отображается поле "Заполните поле" в консоле
        $I->moveToServer('Тест 1 - Вход в учётную запись');
        $I->fillField('input[id="password"]', '123');
        $I->wait(1);
        $I->click('button[class="button-action enter-button"]');
        $I->wait(1);
        $I->clearField('input[id="password"]');
        $I->fillField('input[id="name"]', '123');
        $I->wait(1);
        $I->clearField('input[id="name"]');
        $I->fillField('input[id="name"]', 's.boriskin287@gmail.com');
        $I->wait(1);
        $I->fillField('input[id="password"]', 'abcd');
        $I->wait(1);
        $I->waitForText('Неверный логин или пароль', 5);
        $I->wait(1);
        $I->fillField('input[id="name"]', 'fsffg');
        $I->wait(1);
        $I->fillField('input[id="password"]', '12345678');
        $I->wait(1);
        $I->waitForText('Неверный логин или пароль', 5);
        $I->wait(1);
        $I->inputInSystem('s.boriskin287@gmail.com', '12345678');
        $I->exitOfSystem();
    }

    public function Test2ExitOfSystem(AcceptanceTester $I)
    {
        $I->moveToServer('Тест 2 - Выход из учётной записи');
        $I->inputInSystem('s.boriskin287@gmail.com', '12345678');
        $I->exitOfSystem();
    }

    public function Test3SearchByKidsCard(AcceptanceTester $I)
    {
        //не готов, не работает поиск на картах детей из-за пробела
        $name_of_patient = 'Марков Алексей Петрович';
        $I->moveToServer('Тест 3 - Поиск по картам детей');
        $I->inputInSystem('s.boriskin287@gmail.com', '12345678');
        $I->openSection('Карты детей');
        $I->fillField('div.control-search > #input1', 'Мар');
        $I->wait(1);
        $I->see($name_of_patient, 'div.table.ps');
        $I->fillField('div.control-search > #input1', 'Але');
        $I->wait(1);
        $I->see($name_of_patient, 'div.table.ps');
        $I->fillField('div.control-search > #input1', 'Пет');
        $I->wait(1);
        $I->see($name_of_patient, 'div.table.ps');

    }

    public function Test4ChangeStatusOfPatientApply(AcceptanceTester $I)
    {
        $name_of_patient = 'Борисова Елена Ивановна';
        $I->moveToServer('Тест 4 - Изменение статуса приёма пациента (приём)');
        $I->inputInSystem('s.boriskin287@gmail.com', '12345678');
        $I->selectAddress('Верхний Михайловский проезд');
        $I->openSection('Табло приёмов');
        $block = $I->selectBlockOfPatient($name_of_patient);
        $I->click('div.visit-section:nth-child(' . $block[0] . ') > ul > li.visit-list__item.visit-card:nth-child(' . $block[1] . ')');
        $I->wait(1);
        $class = $I->grabAttributeFrom('div.visit-section:nth-child(' . $block[0] . ') > ul > li.visit-list__item.visit-card:nth-child(' . $block[1] . ')', 'class');
        if ($class !== 'visit-list__item visit-card visit-card--active')
            $I->comment('Выбранный блок не активен');
        $I->click('a[class="visit-sidebar__button button-orange"]', 'div.visit-sidebar');
        $I->waitForElement('div.popup.popup-visit-start', 30);
        $I->wait(1);
        //
        //Заполнение формы
        //
        $I->click('a.button-orange', 'div.popup.popup-visit-start');
        $I->waitForElementNotVisible('div.popup.popup-visit-start', 10);
        $I->wait(1);
        $I->click('li.tabs__tab:nth-child(2)', 'ul.tabs');
        $I->waitForElement('div.visit-content.ps', 30);
        $I->wait(1);
        $I->see($name_of_patient, 'div.visit-section:nth-child(3) > ul > li.visit-list__item.visit-card:nth-child(1)');
        $I->openSection('Карты детей');
        $count_row = $I->getQuantityElement('div.table > div:nth-child(2) > div.row');
        for ($i = 1; $i <= $count_row; $i++) {
            $name_of_patient_current = $I->grabTextFrom('div.table > div:nth-child(2) >
             div.row:nth-child(' . $i . ') > div.cell.cell--name');
            if ($name_of_patient_current == $name_of_patient) break;
        }
        $I->moveMouseOver('div.table > div:nth-child(2) > div.row:nth-child(' . $i . ') > div.cell.cell--status > div.cards-status');
        $I->wait(1);
        $I->waitForText('Прием идет', 5, 'div.table > div:nth-child(2) > div.row:nth-child(' . $i . ')');


        $I->openSection('Табло приёмов');
        $I->click('li.tabs__tab:nth-child(2)', 'ul.tabs');
        $I->waitForElement('div.visit-content.ps', 30);
        $I->wait(1);
        $block = $I->selectBlockOfPatient($name_of_patient);
        $I->click('div.visit-section:nth-child(' . $block[0] . ') > ul > li.visit-list__item.visit-card:nth-child(' . $block[1] . ')');
        $I->wait(1);
        $class = $I->grabAttributeFrom('div.visit-section:nth-child(' . $block[0] . ') > ul > li.visit-list__item.visit-card:nth-child(' . $block[1] . ')', 'class');
        if ($class !== 'visit-list__item visit-card visit-card--active')
            $I->comment('Выбранный блок не активен');
        $I->click('a[class="visit-sidebar__button button-orange"]', 'div.visit-sidebar');
        $I->waitForElement('div.popup.popup-visit-completed', 30);
        $I->see('Прием завершен', 'div.popup.popup-visit-completed');
        $I->click('a.popup__close', 'div.popup.popup-visit-completed');
        $I->waitForElementNotVisible('div.popup.popup-visit-completed', 30);
        $I->wait(1);
        $I->dontSee($name_of_patient, 'div.visit-content.ps');

        $I->openSection('Карты детей');
        $count_row = $I->getQuantityElement('div.table > div:nth-child(2) > div.row');
        for ($i = 1; $i <= $count_row; $i++) {
            $name_of_patient_current = $I->grabTextFrom('div.table > div:nth-child(2) >
             div.row:nth-child(' . $i . ') > div.cell.cell--name');
            if ($name_of_patient_current == $name_of_patient) break;
        }
        $I->moveMouseOver('div.table > div:nth-child(2) > div.row:nth-child(' . $i . ') > div.cell.cell--status > div.cards-status');
        $I->wait(1);
        $I->see('Документ не заполнен', 'div.table > div:nth-child(2) > div.row:nth-child(' . $i . ')');
    }

    public function Test5ChangeStatusOfPatientRefuse(AcceptanceTester $I)
    {
        $name_of_patient = 'Борисова Елена Ивановна';
        $I->moveToServer('Тест 5 - Изменение статуса приёма пациента (отказ)');
        $I->inputInSystem('s.boriskin287@gmail.com', '12345678');
        $I->selectAddress('Верхний Михайловский проезд');
        $I->openSection('Табло приёмов');
        $I->selectBlockOfPatient($name_of_patient);
        $I->click('a[class="visit-sidebar__button button-border"]', 'div.visit-sidebar');
        $I->waitForElement('div.popup.popup-visit-denied', 30);
        $I->wait(1);
        $I->click('a[class="button-orange popup-visit-denied__button"]', 'div.popup.popup-visit-denied');
        $I->waitForElementNotVisible('div.popup.popup-visit-denied', 10);
        $I->openSection('Карты детей');
        $row = $I->getRowOfPatient($name_of_patient);
        $I->moveMouseOver('div.table > div:nth-child(2) > div.row:nth-child(' . $row . ') > div.cell.cell--status > div.cards-status');
        $I->wait(1);
        $I->see('Отказано', 'div[class="row"]:nth-child(' . $row . ')');
        $name_of_patient = 'Марков Алексей Петрович';
        $I->openSection('Табло приёмов');
        $I->selectBlockOfPatient($name_of_patient);
        $I->click('a[class="visit-sidebar__button button-orange"]', 'div.visit-sidebar');
        $I->waitForElement('div.popup.popup-visit-start', 60);
        $I->wait(1);
        //
        //fill form
        //
        $I->click('a.button-orange', 'div.popup.popup-visit-start');
        $I->waitForElementNotVisible('div.popup.popup-visit-start', 10);
        $I->wait(1);
        $I->openSection('Карты детей');
        $row = $I->getRowOfPatient($name_of_patient);
        $I->moveMouseOver('div.table > div:nth-child(2) > div.row:nth-child(' . $row . ') > div.cell.cell--status > div.cards-status');
        $I->wait(1);
        $I->see('Прием идет', 'div[class="row"]:nth-child(' . $row . ')');
        $I->openSection('Табло приёмов', 'Прием идет');
        $I->selectBlockOfPatient($name_of_patient);
        $I->click('a[class="visit-sidebar__button button-border"]', 'div.visit-sidebar');
        $I->waitForElement('div.popup.popup-visit-denied', 60);
        $I->wait(1);
        $I->click('a[class="button-orange popup-visit-denied__button"]', 'div.popup.popup-visit-denied');
        $I->waitForElementNotVisible('div.popup.popup-visit-denied', 10);
        $I->openSection('Карты детей');
        $row = $I->getRowOfPatient($name_of_patient);
        $I->moveMouseOver('div.table > div:nth-child(2) > div.row:nth-child(' . $row . ') > div.cell.cell--status > div.cards-status');
        $I->wait(1);
        $I->see('Отказано', 'div[class="row"]:nth-child(' . $row . ')');
    }

    //1. Формы для заполнения членов комиссии, проверка их в меню "Идёт приём"
    //2. Поиск по табло приёмов

    public function Test8fillDocumentReceptionPart0(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 8 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 0');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);
        $I->wait(1);
        $I->seeElement($dr->type . ' > label[for="typeText"]');
        $I->seeElement($dr->type . $dr->requiredField);
        $I->seeElement($dr->ovg . ' > label[for="ageGroupId"]');
        $I->seeElement($dr->ovg . $dr->requiredField);
        $I->seeElement($dr->program . ' > label[for="programId"]');
        $I->seeElement($dr->program . $dr->requiredField);
        $I->seeElement($dr->FIO . ' > label[for="patientFullName"]');
        $I->seeElement($dr->FIO . $dr->requiredField);
        $I->seeElement($dr->dateOfBorn . ' > label[for="dob"]');
        $I->seeElement($dr->dateOfBorn . $dr->requiredField);
        $I->see('Ребенок с билингвизмом (двуязычием)', $dr->bilingual);
        $I->dontSeeCheckboxIsChecked($dr->bilingual . ' > label > span:nth-child(3)');
        $I->seeElement($dr->invalid . ' > label[for="disabilityText"]');
        $I->seeElement($dr->invalid . $dr->requiredField);
        $I->see('Наличие девиантного поведения', $dr->deviant);
        $I->dontSeeCheckboxIsChecked($dr->deviant . ' > label > span:nth-child(3)');
        $I->see('Наличие инвалидности', $dr->hasInvalid);
        $I->dontSeeCheckboxIsChecked($dr->hasInvalid . ' > label > span:nth-child(3)');
        $I->seeElement($dr->hasInvalid . $dr->requiredForCheckboxes);
        $I->seeElement($dr->nameOO . ' > label[for="educationOrgId"]');
        $I->seeElement($dr->districtOO . ' > label[for="districtId"]');
        $dr->scrollTo($dr->chosenProgram);
        $I->seeElement($dr->chosenProgram . ' > label[for="programSelectedText"]');
        $I->seeElement($dr->chosenProgram . $dr->requiredField);
        $dr->scrollTo($dr->header);
        $dr->checkingFiledInDocumentReception($dr->ovg, DocumentReception::ALL);
    }

    public function Test8fillDocumentReceptionPart1(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 8 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 1(Ранняя помощь)');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //$I -> see('Обследование', 'input#receptionType');
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[0]);

        //Глухие
        $I->comment('Проверка "Ранняя помощь" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для глухих обучающихся', 'div.document-section__row:nth-child(8)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Слабослышащие
        $I->comment('Проверка "Ранняя помощь" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Слепые
        $I->comment('Проверка "Ранняя помощь" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Слабовидящие
        $I->comment('Проверка "Ранняя помощь" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Тяжелые нарушения речи
        $I->comment('Проверка "Ранняя помощь" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "Ранняя помощь" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $I->comment('Проверка "Ранняя помощь" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Расстройство аутистического спектра
        $I->comment('Проверка "Ранняя помощь" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Умственная отсталость
        $I->comment('Проверка "Ранняя помощь" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Сложные дефекты
        $I->comment('Проверка "Ранняя помощь" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[9]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Основная образовательная программа(норма)
        $I->comment('Проверка "Ранняя помощь" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->chosenProgram);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        $hiddenFields = [$dr->withPsychoSpecial, $dr->levelEducation, $dr->variantProgram,
            $dr->assistant, $dr->freeEnvironment, $dr->specialFacilities, $dr->tutorEscort];
        foreach ($hiddenFields as $field) {
            $I->dontSeeElement($field);
        }
        unset($field);
    }

    public function Test9fillDocumentReceptionPart2(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 9 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 2(Дошкольники)');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "Дошкольники"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[1]);

        //Глухие
        $I->comment('Проверка "Дошкольники" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для глухих обучающихся', 'div.document-section__row:nth-child(8)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабослышащие
        $I->comment('Проверка "Дошкольники" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слепые
        $I->comment('Проверка "Дошкольники" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабовидящие
        $I->comment('Проверка "Дошкольники" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Тяжелые нарушения речи
        $I->comment('Проверка "Дошкольники" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "Дошкольники" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $I->comment('Проверка "Дошкольники" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Расстройство аутистического спектра
        $I->comment('Проверка "Дошкольники" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Умственная отсталость
        $I->comment('Проверка "Дошкольники" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Сложные дефекты
        $I->comment('Проверка "Дошкольники" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[9]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Основная образовательная программа(норма)
        $I->comment('Проверка "Дошкольники" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->chosenProgram);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        $hiddenFields = [$dr->withPsychoSpecial, $dr->levelEducation, $dr->variantProgram,
            $dr->assistant, $dr->freeEnvironment, $dr->specialFacilities, $dr->tutorEscort];
        foreach ($hiddenFields as $field) {
            $I->dontSeeElement($field);
        }
        unset($field);
    }

    public function Test10fillDocumentReceptionPart3(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 10 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 3(СОШ)');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "СОШ"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[3]);

        //Глухие
        $I->comment('Проверка "СОШ" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабослышащие
        $I->comment('Проверка "СОШ" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слепые
        $I->comment('Проверка "СОШ" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабовидящие
        $I->comment('Проверка "СОШ" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Тяжелые нарушения речи
        $I->comment('Проверка "СОШ" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $arr = [$dr->levelEducationItems[0], $dr->levelEducationItems[1], $dr->levelEducationItems[3]];
        $dr->checkingFiledInDocumentReception($dr->levelEducation, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "СОШ" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::EMPTY);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $I->comment('Проверка "СОШ" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $arr = [$dr->levelEducationItems[0], $dr->levelEducationItems[1], $dr->levelEducationItems[3]];
        $dr->checkingFiledInDocumentReception($dr->levelEducation, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Расстройство аутистического спектра
        $I->comment('Проверка "СОШ" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Умственная отсталость
        $I->comment('Проверка "СОШ" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::EMPTY);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Сложные дефекты
        $I->comment('Проверка "СОШ" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[9]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->levelEducation, DocumentReception::EMPTY);
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $I->seeElement($dr->specialFacilities . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Основная образовательная программа(норма)
        $I->comment('Проверка "СОШ" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->chosenProgram);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        $hiddenFields = [$dr->withPsychoSpecial, $dr->variantProgram,
            $dr->assistant, $dr->freeEnvironment, $dr->specialFacilities, $dr->tutorEscort];
        foreach ($hiddenFields as $field) {
            $I->dontSeeElement($field);
        }
        unset($field);
    }

    public function Test11fillDocumentReceptionPart4(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 11 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 4(ФГОС НОО ОВЗ, ФГОС УО)');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "ФГОС НОО ОВЗ, ФГОС УО"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[2]);

        //Глухие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 1.1', 'Вариант 1.2', 'Вариант 1.3', 'Вариант 1.4'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Выбираем "Вариант 1.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 1.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 1.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Выбираем "Вариант 1.4"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабослышащие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 2.1', 'Вариант 2.2 (I отделение)', 'Вариант 2.2 (II Отделение)', 'Вариант 2.3'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Выбираем "Вариант 2.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 2.2 (I Отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.2 (I отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 2.2 (II Отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.2 (II отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 2.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слепые
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 3.1', 'Вариант 3.2', 'Вариант 3.3', 'Вариант 3.4'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Выбираем "Вариант 3.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 3.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 3.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Выбираем "Вариант 3.4"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Слабовидящие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 4.1', 'Вариант 4.2', 'Вариант 4.3'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Выбираем "Вариант 4.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 4.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 4.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //ТНР
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 5.1', 'Вариант 5.2 (I отделение)', 'Вариант 5.2 (II отделение)'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Выбираем "Вариант 5.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 5.2 (I отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.2 (I отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Выбираем "Вариант 5.2 (II отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.2 (II отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 6.1', 'Вариант 6.2', 'Вариант 6.3', 'Вариант 6.4'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Вариант 6.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Вариант 6.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Вариант 6.3
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 6.4
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 7.1', 'Вариант 7.2'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Вариант 7.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 7.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Вариант 7.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 7.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);
        $dr->scrollTo($dr->header);

        //Расстройство аутистического спектра
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 8.1', 'Вариант 8.2', 'Вариант 8.3', 'Вариант 8.4'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Вариант 8.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Вариант 8.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, DocumentReception::ALL);

        //Вариант 8.3
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 8.4
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);

        //Умственная отсталость
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement($dr->variantProgram . $dr->requiredField);
        $arr = ['Вариант 1', 'Вариант 2'];
        $dr->checkingFiledInDocumentReception($dr->variantProgram, $arr);

        //Вариант 1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2');
        $dr->checkingFiledInDocumentReception($dr->assistant, DocumentReception::ALL);
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo($dr->header);
    }

    public function Test12fillDocumentReceptionPart5(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 12 - Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 5(СПО)');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "СПО"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[4]);

        //Глухие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Слабослышащие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);;

        //Слепые
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Слабовидящие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Нарушения опорно двигательного аппарата
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Расстройство аутистического спектра
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Умственная отсталость
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->freeEnvironment, DocumentReception::ALL);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, DocumentReception::ALL);
        $dr->scrollTo($dr->program);

        //Основная образовательная программа(норма)
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->chosenProgram);
        $hiddenFields = [$dr->withPsychoSpecial, $dr->levelEducation, $dr->variantProgram,
            $dr->assistant, $dr->freeEnvironment, $dr->specialFacilities, $dr->tutorEscort];
        foreach ($hiddenFields as $field) {
            $I->dontSeeElement($field);
        }
        unset($fields);
        $dr->scrollTo($dr->header);
    }

    public function Test13fillDocumentReceptionPart6(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $monthsOfYears = ['янв', 'февр', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сент', 'окт', 'нояб', 'дек'];
        $I->moveToServer('Тест 13 - Заполнение документа приёма (Обследование, ФИО, Дата рождния, Наименование ОО, Округ ОО))');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $I->waitForElement('div.table.ps > div', 30);
        $I->wait(1);
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
        $namePatient = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--name > span');
        $dateOfBornPatient = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--date-birth');
        $dateOfBornPatient = DateTime::createFromFormat('d.m.Y', $dateOfBornPatient);
        $dateOfBornPatient = $dateOfBornPatient->format('j.m.Y');
        $dateOfBornPatient = explode('.', $dateOfBornPatient);
        $dateOfBornPatient = $dateOfBornPatient[0] . ' ' . $monthsOfYears[$dateOfBornPatient[1] - 1] . '. ' . $dateOfBornPatient[2] . ' г.';
        $I->wait(1);
        $I->click('div.cell.cell--status > div', 'div.table.ps > div:nth-child(' . $i . ') > div.row');
        $I->waitForElement('div.popup', 10);
        $I->wait(1);
        $I->click('div.popup > form > label:nth-child(1)');
        $I->wait(1);
        $I->click('a.button-orange', 'div.popup');
        $I->waitForElement('div.document', 60);
        $I->waitForElement('div.content', 60);
        $I->waitForElement('div.document-section', 60);
        $I->wait(1);
        //$I -> see($namePatient, 'div.document-section__row:nth-child(5) > div.document-item:nth-child(1)');
        //$I -> see($dateOfBornPatient, 'div.document-section__row:nth-child(5) > div.document-item:nth-child(2)');
        $I->seeElement($dr->bilingual . ' > label[for="bilingual"]');
        $I->seeElement($dr->deviant . ' > label[for="deviant"]');
        $dr->scrollTo($dr->nameOO);
        $dr->checkingFiledInDocumentReception($dr->nameOO, DocumentReception::ALL);
        $I->click('div.document-section__row:nth-child(7) > div.document-item:nth-child(1) > div.document-item__row');
        $I->wait(1);
        foreach ($dr->nameOOItems as $item) {
            $I->fillField('#educationOrgId', $item);
            $I->wait(1);
            $I->see($item, $dr->nameOO);
            $I->wait(1);
        }
        unset($item);
        $dr->checkingFiledInDocumentReception($dr->districtOO, DocumentReception::ALL);
    }

    public function Test14DirectionCorrectorWorkPart1(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 14 - Заполнение документа приёма (Направление коррекционной работы) - Ранняя помощь');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Глухие
        $dr->scrollTo($dr->program);
        //Выбираем "Ранняя помощь"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[0]);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации', 'формирование и развитие адаптивных форм поведения'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(0-1 год) вызывание элементарных реакций на слуховые, зрительные, тактильные стимулы',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок', 'пространственных ориентировок', 'развитие крупной и мелкой моторики'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Слабослышащие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(0-1 год) вызывание элементарных реакций на слуховые, зрительные, тактильные стимулы',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок', 'пространственных ориентировок', 'крупной и мелкой моторики'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Слепые
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(0-1 год) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'развитие крупной и мелкой моторики', 'развитие познавательной активности', 'формирование сенсорных эталонов'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Слабовидящие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(0-1 год) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'коррекция и развитие дефицитарных функций, развитие остаточного зрения, слухового восприятия',
            'обогащение сенсорного опыта и стимуляция сенсорной активности', 'развитие крупной и мелкой моторики',
            'развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Тяжелые нарушения речи
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ ПО РЕЧИ) гармонизация эмоциональных состояний и реакций',
            'помощь в адаптации',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ ПО РЕЧИ) развитие познавательной активности',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ ПО РЕЧИ) стимуляция речевого развития (голосовых реакций, звуковой и собственной речевой активности)',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ ПО РЕЧИ) формирование и развитие адаптивных форм поведения, продуктивного взаимодействия, 
            элементарных коммуникаций, доступных продуктивных предметных и игровых действий'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Нарушения опорно двигательного аппарата
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['крупной и мелкой моторики',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных ориентировок', 'развитие дефицитарных функций, зрительно-двигательной координации',
            'развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['обогащение сенсорного опыта и стимуляция сенсорной активности',
            'развитие крупной и мелкой моторики',
            'стимуляция речевого развития (голосовых реакций, звуковой и собственной речевой активности)',
            'формирование и развитие познавательной активности',
            'формирование и развитие продуктивных предметных действий'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Расстройство аутистического спектра
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['крупной и мелкой моторики',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных ориентировок',
            'развитие зрительного, слухового, тактильного восприятия',
            'развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Умственная отсталость
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных ориентировок',
            'развитие крупной и мелкой моторики',
            'стимуляция речевого развития (голосовых реакций, звуковой и собственной речевой активности)',
            'формирование и развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Сложные дефекты
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[9]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения',
            '(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'помощь в адаптации',
            'формирование элементарного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'обогащение сенсорного опыта (пассивно)',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'развитие крупной и мелкой моторики',
            'развитие крупной и мелкой моторики (пассивно)'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Основная образовательная программа(норма)
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ) гармонизация эмоциональных состояний и реакций',
            'помощь в адаптации',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ) развитие познавательной активности',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ) формирование и развитие адаптивных форм поведения и деятельности, продуктивного взаимодействия, 
            элементарных коммуникаций, элементарных доступных продуктивных предметных и игровых действий',
            '(! ТОЛЬКО ДЛЯ ИНВАЛИДОВ - 2-3 года) формирование и развитие коммуникативных и социальных навыков, развитие эмоциональной 
            сферы, продуктивной предметной и игровой деятельности'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);
    }

    public function Test15DirectionCorrectorWorkPart2(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 15 - Заполнение документа приёма (Направление коррекционной работы) - Дошкольники');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Глухие
        $dr->scrollTo($dr->program);
        //Выбираем "Дошкольники"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[1]);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', '(5 лет) коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы',
            'помощь в адаптации', 'развитие адаптивных форм поведения', 'развитие пространственно-временных представлений'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(5-6 лет) коррекция и развитие мыслительных операций, познавательных процессов',
            '(4 года) развитие компетенций познавательной сферы',
            '(3 года) развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слабослышащие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', '(5 лет) коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы',
            'помощь в адаптации', 'развитие адаптивных форм поведения', 'формирование продуктивного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(5-6 лет) коррекция и развитие мыслительных операций, познавательных процессов',
            '(4 года) развитие компетенций познавательной сферы',
            '(3 года) развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слепые
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', '(5 лет) коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы',
            'помощь в адаптации', 'развитие адаптивных форм поведения', 'формирование продуктивного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $dr->scrollTo($dr->teacherDefect);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'конструктивной деятельности', '(5-6 лет) коррекция и развитие мыслительных операций, познавательных процессов',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слабовидящие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', '(5 лет) коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы',
            'помощь в адаптации', 'развитие адаптивных форм поведения', 'формирование продуктивного взаимодействия со взрослым'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'конструктивной деятельности', '(5-6 лет) коррекция и развитие мыслительных операций, познавательных процессов',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'осязания и мелкой моторики рук'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Тяжелые нарушения речи
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', 'активизация познавательной деятельности',
            'игровой деятельности', 'коммуникативных и социальных навыков', 'коммуникативных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(с 4-х лет) коррекция и развитие всех компонентов речи',
            'коррекция и развитие просодических компонентов речи', 'коррекция и развитие темпо-ритмической организации речи',
            'накопление и активизация словаря',
            '(с 3-х лет) развитие понимания обращенной речи'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Нарушения опорно двигательного аппарата
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', 'игровой деятельности',
            'игровых действий', 'коммуникативных и социальных навыков', 'коммуникативных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['коррекция звукопроизношения (с 4 лет 6 месяцев)',
            '(с 5-ти лет) коррекция звукопроизношения, развитие фонематических процессов',
            '(с 4-х лет) коррекция и развитие всех компонентов речи',
            'коррекция и развитие просодических компонентов речи',
            'коррекция и развитие темпо-ритмической организации речи'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'конструктивной деятельности', 'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок', 'пространственных ориентировок'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Задержка психического развития
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', 'игровой деятельности',
            'игровых действий', 'коммуникативных и социальных навыков', 'коммуникативных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['накопление и активизация словаря',
            '(с 3-х лет) развитие понимания обращенной речи',
            '(с 4-х лет) коррекция и развитие всех компонентов речи',
            'коррекция и развитие просодических компонентов речи',
            'коррекция и развитие темпо-ритмической организации речи'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'конструктивной деятельности', 'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок', 'пространственных ориентировок'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Расстройство аутистического спектра
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', 'игровой деятельности',
            'игровых действий', 'коммуникативных и социальных навыков', 'коммуникативных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['коррекция звукопроизношения (с 4 лет 6 месяцев)',
            '(с 5-ти лет) коррекция звукопроизношения, развитие фонематических процессов',
            'коррекция и развитие всех компонентов речи',
            'коррекция и развитие темпо-ритмической организации речи',
            'накопление и активизация словаря'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'конструктивной деятельности', 'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'пространственных и социально-бытовых ориентировок', 'пространственных ориентировок'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Умственная отсталость
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['адаптивных форм поведения', 'гармонизация эмоциональных состояний и реакций',
            'доступных игровых действий', 'игровой деятельности', 'игровых действий'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(с 4-х лет) коррекция и развитие всех компонентов речи',
            'коррекция и развитие темпо-ритмической организации речи',
            'накопление и активизация словаря',
            '(с 3-х лет) развитие понимания обращенной речи',
            'формирование активной подражательной речевой деятельности'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            '(5 лет) коррекция и развитие познавательных процессов, мыслительных операций',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'развитие крупной и мелкой моторики'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Сложные дефекты
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[9]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['вызывание элементарного эмоционального отклика', 'гармонизация эмоциональных состояний и реакций',
            'доступных игровых действий', 'игровой деятельности', 'игровых действий'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['вызывание звукокомплексов, звукоподражаний',
            'коррекция и развитие всех компонентов речи',
            'накопление и активизация словарного запаса',
            'развитие понимания обращенной речи',
            'формирование доступных форм альтернативной коммуникации'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = ['вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'обогащение сенсорного опыта и стимуляция сенсорной активности (пассивно)',
            'развитие крупной и мелкой моторики'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Основная образовательная программа(норма)
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['игровой деятельности', 'коммуникативных и социальных навыков',
            'коммуникативных навыков', 'компетенций эмоционально-волевой сферы', 'коррекция и развитие коммуникативных и социальных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['коррекция звукопроизношения (c 4 лет 6 месяцев)',
            '(с 5 лет) коррекция звукопроизношения, развитие фонематических процессов',
            'коррекция и развитие просодических компонентов речи',
            'коррекция и развитие темпо-ритмической организации речи'];
        $dr->checkingFiledInDocumentReception($dr->teacherLogoped, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);
    }

    public function Test16DirectionCorrectorWorkPart3(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 16 - Заполнение документа приёма (Направление коррекционной работы) - ФГОС');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Глухие
        $dr->scrollTo($dr->program);
        //Выбираем "ФГОС НОО ОВЗ, ФГОС УО"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[2]);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);

        //Вариант 1.1
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.1');
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы', 'помощь в адаптации к условиям школьной среды',
            'развитие продуктивного взаимодействия', 'развитие познавательного интереса и познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['коррекция и развитие познавательной деятельности',
            'развитие слухозрительного, слухового восприятия, речевого слуха, произносительной стороны речи, 
            коммуникативных функций речи, правил коммуникации и применение их в социальной жизни'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Вариант 1.2
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.2');
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы',
            'помощь в адаптации к условиям школьной среды', 'развитие продуктивного взаимодействия',
            'развитие познавательного интереса и познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['коррекция и развитие познавательной деятельности',
            'развитие слухозрительного, слухового восприятия, речевого слуха, произносительной стороны речи, 
            коммуникативных функций речи, правил коммуникации и применение их в социальной жизни'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Вариант 1.3
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.3');
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['коррекция и развитие компетенций коммуникативной и эмоционально-волевой сферы', 'помощь в адаптации к условиям школьной и социальной среды',
            'развитие продуктивного взаимодействия', 'развитие познавательной активности'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['активизация элементарных навыков устной коммуникации',
            'коррекция, развитие и активизация познавательной деятельности',
            'формирование базовых учебных действий', 'формирование и развитие речевого слуха'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);

        //Вариант 1.4
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.4');
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(другие возможные направления работы) вызывание элементарного эмоционального отклика',
            'гармонизация эмоциональных состояний и реакций', 'развитие элементарных навыков социального взаимодействия',
            'социальных компетенций'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(другие возможные направления работы) вызывание элементарных реакций на зрительные, слуховые, тактильные стимулы',
            'обогащение сенсорного опыта и стимуляция сенсорной активности',
            'обогащение сенсорного опыта и стимуляция сенсорной активности (пассивно)', 'развитие крупной и мелкой моторики'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);
    }

    public function Test17DirectionCorrectorWorkPart4(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $card = new CardsChildrens($I);
        $I->moveToServer('Тест 17 - Заполнение документа приёма (Направление коррекционной работы) - СОШ');
        $I->inputInSystem('admin', 'admin');
        $I->openSection('Карты детей');
        $card->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Глухие
        $dr->scrollTo($dr->program);
        //Выбираем "СОШ"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[3]);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->levelEducation);
        $dr->setValueFieldInDocumentReception($dr->levelEducation, $dr->levelEducationItems[1]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(НОО) коррекция и профилактика нежелательного поведения', '(НОО) развитие продуктивного взаимодействия',
            '(НОО) развитие учебно-познавательной мотивации', '(НОО)развитие эмоциональной сферы, коммуникативных и социальных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(НОО) коррекция и развитие познавательной деятельности, развитие мыслительных операций на основе изучения программного материала',
            'развитие слухового и слухозрительного восприятия, понимания обращенной речи, формирование доступных способов коммуникации'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слабослышпщие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(НОО) коррекция и профилактика нежелательного поведения', '(НОО) развитие продуктивного взаимодействия',
            '(НОО) развитие учебно-познавательной мотивации', '(НОО) развитие эмоциональной сферы, коммуникативных и социальных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(НОО) коррекция и развитие познавательной деятельности, развитие мыслительных операций на основе изучения программного материала',
            '(НОО) развитие и коррекция слухового восприятия'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слепые
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(НОО) коррекция и профилактика нежелательного поведения', '(НОО) развитие продуктивного взаимодействия',
            '(НОО) развитие учебно-познавательной мотивации', '(НОО) развитие эмоциональной сферы, коммуникативных и социальных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(НОО) коррекция и развитие познавательной деятельности, развитие мыслительных операций на основе изучения программного материала',
            'развитие остаточного зрения, слухового восприятия'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->program);

        //Слабовидящие
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->teacherPsy);
        $arr = ['(НОО) коррекция и профилактика нежелательного поведения', '(НОО) развитие продуктивного взаимодействия',
            '(НОО) развитие учебно-познавательной мотивации', '(НОО) развитие эмоциональной сферы, коммуникативных и социальных навыков'];
        $dr->checkingFiledInDocumentReception($dr->teacherPsy, $arr);
        $arr = ['(НОО) коррекция и развитие познавательной деятельности, развитие мыслительных операций на основе изучения программного материала',
            '(НОО) Развитие зрительного восприятия, зрительно-моторной координации'];
        $dr->checkingFiledInDocumentReception($dr->teacherDefect, $arr);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[1], $dr->teacherSocialItems[2], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
        $dr->scrollTo($dr->header);
    }

    public function Test18DirectionCorrectorWorkPart5(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 18 - Заполнение документа приёма (Направление коррекционной работы) - СПО');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Глухие
        $dr->scrollTo($dr->program);
        //Выбираем "СПО"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[4]);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Слабослышащие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Слепые
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Слабовидящие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Нарушения опорно двигательного аппарата
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Расстройство аутистического спектра
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Умственная отсталость
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);

        //Основная образовательная программа(норма)
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[10]);
        $dr->scrollTo($dr->teacherSocial);
        $arr = [$dr->teacherSocialItems[0], $dr->teacherSocialItems[3], $dr->teacherSocialItems[4]];
        $dr->checkingFiledInDocumentReception($dr->teacherSocial, $arr);
    }

    public function Test19DirectionCorrectorWorkPart6(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 19 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - Ранняя помощь');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "Ранняя помощь"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[0]);
        //Проверяем все программы
        foreach ($dr->programItems as $program) {
            $dr->scrollTo($dr->program);
            $dr->setValueFieldInDocumentReception($dr->program, $program);
            $dr->scrollTo($dr->timeRepeatInspection);
            $I->seeElement($dr->recommendedInspection);
            $I->dontSeeCheckboxIsChecked($dr->recommendedInspection . $dr->bodyCheckbox);
            $I->dontSeeCheckboxIsChecked($dr->incompleteDocumentation . $dr->bodyCheckbox);
            $arr = ['По достижении трёхлетнего возраста'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($program);
    }

    public function Test20DirectionCorrectorWorkPart7(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 20 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - Дошкольники');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем "Дошкольники"
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[1]);
        //Проверяем все программы
        foreach ($dr->programItems as $program) {
            $dr->scrollTo($dr->program);
            $dr->setValueFieldInDocumentReception($dr->program, $program);
            $dr->scrollTo($dr->timeRepeatInspection);
            $I->seeElement($dr->recommendedInspection);
            $I->dontSeeCheckboxIsChecked($dr->recommendedInspection . $dr->bodyCheckbox);
            $I->dontSeeCheckboxIsChecked($dr->incompleteDocumentation . $dr->bodyCheckbox);
            if ($program == $dr->programItems[10]) {
                $arr = ['При переходе с одного уровня образования на другой'];
            } else {
                $arr = ['Изменение ранее данных комиссией рекомендаций при устойчивых трудностях овладения АООП',
                    'При переходе с одного уровня образования на другой'];
            }
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($program);
    }

    public function Test21DirectionCorrectorWorkPart8(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 21 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - ФГОС');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Устанавливаем ФГОС
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[2]);

        //Глухие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[0]);
        //Вариант 1.1 - 1.2
        $variants = ['Вариант 1.1', 'Вариант 1.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 1.3 - 1.4
        $variants = ['Вариант 1.3', 'Вариант 1.4'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['На весь период обучения',
                'При переходе с одного уровня образования на другой'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Слабослышащие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[1]);

        //Вариант 2.1
        $variants = ['Вариант 2.1', 'Вариант 2.2 (I отделение)', 'Вариант 2.2 (II отделение)'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 2.3
        $I->comment('Проверка Вариант 2.3');
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.3');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['На весь период обучения',
            'При переходе с одного уровня образования на другой'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);

        //Слепые
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[2]);

        //Вариант 3.1 - 3.2
        $variants = ['Вариант 3.1', 'Вариант 3.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 3.3 - 3.4
        $variants = ['Вариант 3.3', 'Вариант 3.4'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['На весь период обучения',
                'При переходе с одного уровня образования на другой'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Слабовидящие
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[3]);

        //Вариант 4.1 - 4.2
        $variants = ['Вариант 4.1', 'Вариант 4.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 4.3
        $I->comment('Проверка Вариант 4.3');
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.3');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['На весь период обучения',
            'При переходе с одного уровня образования на другой'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);

        //Тяжелые нарушения речи
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[4]);

        //Вариант 5.1
        $I->comment('Проверка Вариант 5.1');
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.1');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['Изменение программы при компенсации речевых нарушений – через год после начала освоения АООП',
            'Изменение программы при устойчивой неуспеваемости по нескольким предметам',
            'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
            'При переходе с одного уровня образования на другой',
            'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);

        //Варианты 5.2(I отделение) - 5.2(II отделение)
        $variants = ['Вариант 5.2 (I отделение)', 'Вариант 5.2 (II отделение)'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Нарушения опорно-двигательного аппарата
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[5]);

        //Вариант 6.1 - 6.2
        $variants = ['Вариант 6.1', 'Вариант 6.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 6.3 - 6.4
        $variants = ['Вариант 6.3', 'Вариант 6.4'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['На весь период обучения',
                'При переходе с одного уровня образования на другой'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Задержка психического развития
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[6]);

        //Вариант 7.1 - 7.2
        $variants = ['Вариант 7.1', 'Вариант 7.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Расстройство аутистического спектра
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[7]);

        //Вариант 8.1 - 8.2
        $variants = ['Вариант 8.1', 'Вариант 8.2'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                'Изменение программы при устойчивой неуспеваемости по нескольким предметам – не позже, чем через год после начала освоения АООП',
                'При переходе с одного уровня образования на другой',
                'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП – в течение следующего учебного года'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Вариант 8.3 - 8.4
        $variants = ['Вариант 8.3', 'Вариант 8.4'];
        foreach ($variants as $variant) {
            $I->comment('Проверка ' . $variant);
            $dr->scrollTo($dr->variantProgram);
            $dr->setValueFieldInDocumentReception($dr->variantProgram, $variant);
            $dr->scrollTo($dr->timeRepeatInspection);
            $arr = ['На весь период обучения',
                'При переходе с одного уровня образования на другой'];
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($variant);

        //Умственная отсталость
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, $dr->programItems[8]);

        //Вариант 1
        $I->comment('Проверка Вариант 1');
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['При переходе с одного уровня образования на другой',
            'Уточнение ранее данных комиссией рекомендаций при устойчивых трудностях овладения рекомендованным вариантом АООП'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);

        //Вариант 2
        $I->comment('Проверка Вариант 2');
        $dr->scrollTo($dr->variantProgram);
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['При переходе с одного уровня образования на другой'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
    }

    public function Test22DirectionCorrectorWorkPart9(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 22 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - СОШ');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем СОШ
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[3]);

        //Программы Глухие - Сложные дефекты
        $programs = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3],
            $dr->programItems[4], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7],
            $dr->programItems[8], $dr->programItems[9]];
        foreach ($programs as $program) {
            $I->comment('Проверка ' . $program);
            $dr->scrollTo($dr->program);
            $dr->setValueFieldInDocumentReception($dr->program, $program);
            $dr->scrollTo($dr->timeRepeatInspection);
            if ($program == $dr->programItems[8]) {
                $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                    'На весь период обучения', 'При переходе с одного уровня образования на другой',
                    'Рекомендовано повторное прохождение ЦПМПК с целью создания специальных условий
                при сдаче ГИА за курс основного общего образования',
                    'Рекомендовано повторное прохождение ЦПМПК с целью создания специальных условий
                при сдаче ГИА за курс среднего общего образования'];
            } else {
                $arr = ['Изменение программы при устойчивой неуспеваемости по нескольким предметам',
                    'При переходе с одного уровня образования на другой',
                    'Рекомендовано повторное прохождение ЦПМПК с целью создания специальных условий
                при сдаче ГИА за курс основного общего образования',
                    'Рекомендовано повторное прохождение ЦПМПК с целью создания специальных условий
                при сдаче ГИА за курс среднего общего образования'];
            }
            $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($program);

        //Основная образовательная программа(норма)
        $I->comment('Проверка Основная образовательная программа(норма)');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Основная образовательная программа(норма)');
        $dr->scrollTo($dr->timeRepeatInspection);
        $arr = ['При переходе с одного уровня образования на другой',
            '(строго по согласованию!) Изменение ранее данных комиссией рекомендаций при 
            устойчивых трудностях овладения ООП'];
        $I->seeElement($dr->timeRepeatInspection . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->timeRepeatInspection, $arr);
        $I->seeElement($dr->kindCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
        $I->seeElement($dr->resultCommission . $dr->requiredField);
        $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        $I->seeElement($dr->personalPlan);
        $arr = ['Не требуется', '(строго по согласованию!) Обучение по индивидуальному
        учебному плану с учетом обучающегося, содержащему меры'];
        $dr->checkingFiledInDocumentReception($dr->personalPlan, $arr);
    }

    public function Test23DirectionCorrectorWorkPart10(HeaderTester $I)
    {
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        $I->moveToServer('Тест 23 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - СОШ');
        $I->inputInSystem('admin', 'admin');
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Выбираем СПО
        $dr->setValueFieldInDocumentReception($dr->ovg, $dr->ovgItems[4]);

        //Программы Глухие, Слабослышищие, Слепые, Слабовидящие, НОДА, РАС, УО, ООП
        $programs = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3],
            $dr->programItems[5], $dr->programItems[7], $dr->programItems[8], $dr->programItems[10]];
        foreach($programs as $program) {
            $dr->scrollTo($dr->program);
            $dr->setValueFieldInDocumentReception($dr->program, $program);
            $dr->scrollTo($dr->kindCommission);
            $I->dontSeeElement($dr->timeRepeatInspection);
            $I->seeElement($dr->kindCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->kindCommission, DocumentReception::ALL);
            $I->seeElement($dr->resultCommission . $dr->requiredField);
            $dr->checkingFiledInDocumentReception($dr->resultCommission, DocumentReception::ALL);
        }
        unset($program);
    }

    public function Test24MemberCommission(HeaderTester $I)
    {
        $admin = new Admin($I);
        $dr = new DocumentReception($I);
        $cards = new CardsChildrens($I);
        //Педагог-психолог
        $namePsy  = 'Абдулаев Абдула Абдулаевич';
        $mailPsy = 'abdulaev@mail.ru';
        $passwordPsy = 'Abdulaev1234*';
        $positionPsy = 'Педагог-психолог';

        //Социальный педагог
        $nameSocial  = 'Бесарабов Бесараб Бесарабович';
        $mailSocial = 'besarabov@mail.ru';
        $passwordSocial = 'Besarabov1234*';
        $positionSocial = 'Социальный педагог';

        //Учитель-дефектолог
        $nameDefect  = 'Лихтенштейн Наум Наумович';
        $mailDefect = 'lihtenshtein@mail.ru';
        $passwordDefect = 'Lihtenstein1234*';
        $positionDefect = 'Учитель-дефектолог';

        //Учитель-логопед
        $nameLogoped  = 'Вахтангов Вахтанг Вахтангович';
        $mailLogoped = 'vahtangov@mail.ru';
        $passwordLogoped = 'Vahtangov1234*';
        $positionLogoped = 'Учитель-логопед';

        //Должность
        $role = 'Член комиссии';
        $I->moveToServer('Тест 23 - Заполнение документа приёма (Направление коррекционной работы, срок, вид и результат комиссии) - СОШ');
        $I->inputInSystem('admin', 'admin');
        //Создание педагога-психолога
        $admin->createUser($namePsy, $mailPsy, $passwordPsy, $role, $positionPsy);
        $I->openSection(HeaderTester::ADMIN);
        //Создание социального педагога
        $admin->createUser($nameSocial, $mailSocial, $passwordSocial, $role, $positionSocial);
        $I->openSection(HeaderTester::ADMIN);
        //Создание учителя-дефектолога
        $admin->createUser($nameDefect, $mailDefect, $passwordDefect, $role, $positionDefect);
        $I->openSection(HeaderTester::ADMIN);
        //Создание учителя-логопеда
        $admin->createUser($nameLogoped, $mailLogoped, $passwordLogoped, $role, $positionLogoped);

        //Открываем Документ приёма - обследование
        $I->openSection(HeaderTester::CARDS_CHILDREN);
        $cards->openDocumentReception(CardsChildrens::NONE_FILL, CardsChildrens::EXAMINATION);

        //Проверка членов комиссии
        $dr->scrollTo($dr->memberCommissionLogoped);
        $arr = [$namePsy];
        $dr->checkingFiledInDocumentReception($dr->memberCommissionPsy, $arr);
        $arr = [$nameSocial];
        $dr->checkingFiledInDocumentReception($dr->memberCommissionSocial, $arr);
        $arr = [$nameDefect];
        $dr->checkingFiledInDocumentReception($dr->memberCommissionDefect, $arr);
        $arr = [$nameLogoped];
        $dr->checkingFiledInDocumentReception($dr->memberCommissionLogoped, $arr);

        //Удаление созданных ранее пользователей
        $I->openSection(HeaderTester::ADMIN);
        $admin->deleteUser($namePsy);
        $I->openSection(HeaderTester::ADMIN);
        $admin->deleteUser($nameSocial);
        $I->openSection(HeaderTester::ADMIN);
        $admin->deleteUser($nameDefect);
        $I->openSection(HeaderTester::ADMIN);
        $admin->deleteUser($nameLogoped);
    }
}