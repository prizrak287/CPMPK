<?php

use \Page\DocumentReception as DocumentReception;

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

    public function Test3SearchByKidsCrad(AcceptanceTester $I)
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

    public function Test8fillDocumentReceptionPart0(AcceptanceTester $I)
    {
        $documentReception = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 0');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        //$I -> openSection('Карты детей');
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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
        $I->seeElement($documentReception->type . ' > label[for="typeText"]');
        $I->seeElement($documentReception->type . $documentReception->required);
        $I->seeElement($documentReception->ovg . ' > label[for="ageGroupId"]');
        $I->seeElement($documentReception->ovg . $documentReception->required);
        $I->seeElement($documentReception->program . ' > label[for="programId"]');
        $I->seeElement($documentReception->program . $documentReception->required);
        $I->seeElement($documentReception->FIO . ' > label[for="patientFullName"]');
        $I->seeElement($documentReception->FIO . $documentReception->required);
        $I->seeElement($documentReception->dateOfBorn . ' > label[for="dob"]');
        $I->seeElement($documentReception->dateOfBorn . $documentReception->required);
        $I->see('Ребенок с билингвизмом (двуязычием)', $documentReception->bilingual);
        $I->dontSeeCheckboxIsChecked($documentReception->bilingual . ' > label > span:nth-child(3)');
        $I->seeElement($documentReception->invalid . ' > label[for="disabilityText"]');
        $I->seeElement($documentReception->invalid . $documentReception->required);
        $I->see('Наличие девиантного поведения', $documentReception->deviant);
        $I->dontSeeCheckboxIsChecked($documentReception->deviant . ' > label > span:nth-child(3)');
        $I->see('Наличие инвалидности', $documentReception->hasInvalid);
        $I->dontSeeCheckboxIsChecked($documentReception->hasInvalid . ' > label > span:nth-child(3)');
        $I->seeElement($documentReception->hasInvalid . $documentReception->requiredForCheckboxes);
        $I->seeElement($documentReception->nameOO . ' > label[for="educationOrgId"]');
        $I->seeElement($documentReception->districtOO . ' > label[for="districtId"]');
        $documentReception->scrollTo($documentReception->withPsychoSpecial);
        $I->seeElement($documentReception->chosenProgram . ' > label[for="programSelectedText"]');
        $I->seeElement($documentReception->chosenProgram . $documentReception->required);
        $documentReception->scrollTo('div.document-header');
        $documentReception->checkingFiledInDocumentReception($documentReception->ovg, 'all');
    }

    public function Test8fillDocumentReceptionPart1(AcceptanceTester $I)
    {
        $dr = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 1(Ранняя помощь)');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        //$I -> openSection('Карты детей');
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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
        //$I -> see('Обследование', 'input#receptionType');
        $dr->setValueFieldInDocumentReception($dr->ovg, 'Ранняя помощь');

        //Глухие
        $I->comment('Проверка "Ранняя помощь" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Глухие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для глухих обучающихся', 'div.document-section__row:nth-child(8)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.header-section');

        //Слабослышащие
        $I->comment('Проверка "Ранняя помощь" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабослышащие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Слепые
        $I->comment('Проверка "Ранняя помощь" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слепые');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Слабовидящие
        $I->comment('Проверка "Ранняя помощь" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабовидящие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Тяжелые нарушения речи
        $I->comment('Проверка "Ранняя помощь" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Тяжелые нарушения речи');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "Ранняя помощь" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Нарушения опорно двигательного аппарата');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Задержка психического развития
        $I->comment('Проверка "Ранняя помощь" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Задержка психического развития');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Расстройство аутистического спектра
        $I->comment('Проверка "Ранняя помощь" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Расстройство аутистического спектра');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Умственная отсталость
        $I->comment('Проверка "Ранняя помощь" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Умственная отсталость');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Сложные дефекты
        $I->comment('Проверка "Ранняя помощь" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Сложные дефекты');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $I->click('div.document-item:nth-child(1)', 'div.document-section__row:nth-child(9)');
        $I->waitForElementVisible('div.document-section__row:nth-child(9) > div.document-item:nth-child(1) > div.document-item__row > div > div > div.multiselect__content-wrapper > ul.multiselect__content', 10);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $dr->scrollTo('div.document-header');

        //Основная образовательная программа(норма)
        $I->comment('Проверка "Ранняя помощь" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Основная образовательная программа(норма)');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        for ($i = 9; $i <= 11; $i++) {
            $I->dontSeeElement('document-section__row:nth-child(' . $i . ')');
        }
    }

    public function Test8fillDocumentReceptionPart2(AcceptanceTester $I)
    {
        $dr = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 2(Дошкольники)');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        //$I -> openSection('Карты детей');
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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

        //Выбираем "Дошкольники"
        $dr->setValueFieldInDocumentReception($dr->ovg, 'Дошкольники');

        //Глухие
        $I->comment('Проверка "Дошкольники" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Глухие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для глухих обучающихся', 'div.document-section__row:nth-child(8)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr -> tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабослышащие
        $I->comment('Проверка "Дошкольники" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабослышащие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слепые
        $I->comment('Проверка "Дошкольники" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слепые');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабовидящие
        $I->comment('Проверка "Дошкольники" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабовидящие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Тяжелые нарушения речи
        $I->comment('Проверка "Дошкольники" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Тяжелые нарушения речи');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "Дошкольники" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Нарушения опорно двигательного аппарата');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Задержка психического развития
        $I->comment('Проверка "Дошкольники" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Задержка психического развития');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Расстройство аутистического спектра
        $I->comment('Проверка "Дошкольники" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Расстройство аутистического спектра');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Умственная отсталость
        $I->comment('Проверка "Дошкольники" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Умственная отсталость');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Сложные дефекты
        $I->comment('Проверка "Дошкольники" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Сложные дефекты');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Основная образовательная программа(норма)
        $I->comment('Проверка "Дошкольники" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Основная образовательная программа(норма)');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        for ($i = 9; $i <= 12; $i++) {
            $I->dontSeeElement('document-section__row:nth-child(' . $i . ')');
        }
    }

    public function Test8fillDocumentReceptionPart3(AcceptanceTester $I)
    {
        $dr = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 3(СОШ)');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        //$I -> openSection('Карты детей');
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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

        //Выбираем "СОШ"
        $dr->setValueFieldInDocumentReception($dr->ovg, 'СОШ');

        //Глухие
        $I->comment('Проверка "СОШ" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Глухие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'all');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабослышащие
        $I->comment('Проверка "СОШ" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабослышащие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабослышащих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'all');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слепые
        $I->comment('Проверка "СОШ" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слепые');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слепых обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'all');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабовидящие
        $I->comment('Проверка "СОШ" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабовидящие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для слабовидящих обучающихся', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'all');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Тяжелые нарушения речи
        $I->comment('Проверка "СОШ" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Тяжелые нарушения речи');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с тяжелыми нарушениями речи', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $arr = [$dr->levelEducationItems[0], $dr->levelEducationItems[1], $dr->levelEducationItems[3]];
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "СОШ" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Нарушения опорно двигательного аппарата');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с нарушением опорно-двигательного аппарата', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[4], $dr->programItems[6], $dr->programItems[7], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'empty');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Задержка психического развития
        $I->comment('Проверка "СОШ" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Задержка психического развития');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с задержкой психического развития', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $arr = [$dr->levelEducationItems[0], $dr->levelEducationItems[1], $dr->levelEducationItems[3]];
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, $arr);
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Расстройство аутистического спектра
        $I->comment('Проверка "СОШ" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Расстройство аутистического спектра');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с расстройствами аутистического спектра', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[6], $dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'all');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[0], $dr->tutorEscortItems[1]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Умственная отсталость
        $I->comment('Проверка "СОШ" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Умственная отсталость');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся с умственной отсталостью', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'empty');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Сложные дефекты
        $I->comment('Проверка "СОШ" - "Сложные дефекты"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Сложные дефекты');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception($dr -> levelEducation, 'empty');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $I->seeElement($dr->specialFacilities . $dr->required);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Основная образовательная программа(норма)
        $I->comment('Проверка "СОШ" - "Основная образовательная программа(норма)"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Основная образовательная программа(норма)');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Основная образовательная программа', 'div.document-section__row:nth-child(6)');
        for ($i = 9; $i <= 12; $i++) {
            $I->dontSeeElement('document-section__row:nth-child(' . $i . ')');
        }
    }

    public function Test8fillDocumentReceptionPart4(AcceptanceTester $I)
    {
        $dr = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 4(ФГОС НОО ОВЗ, ФГОС УО)');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        //$I -> openSection('Карты детей');
        $countItem = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $countItem; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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

        //Выбираем "ФГОС НОО ОВЗ, ФГОС УО"
        $dr->setValueFieldInDocumentReception($dr->ovg, 'ФГОС НОО ОВЗ, ФГОС УО');

        //Глухие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Глухие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Глухие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 1.1', 'Вариант 1.2', 'Вариант 1.3', 'Вариант 1.4'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Выбираем "Вариант 1.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 1.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 1.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Выбираем "Вариант 1.4"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабослышащие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слабослышащие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабослышащие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 2.1', 'Вариант 2.2 (I отделение)', 'Вариант 2.2 (II Отделение)', 'Вариант 2.3'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Выбираем "Вариант 2.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 2.2 (I Отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.2 (I отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 2.2 (II Отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.2 (II отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 2.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слепые
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слепые"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слепые');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 3.1', 'Вариант 3.2', 'Вариант 3.3', 'Вариант 3.4'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Выбираем "Вариант 3.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 3.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 3.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Выбираем "Вариант 3.4"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 3.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Слабовидящие
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Слабовидящие"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабовидящие');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 4.1', 'Вариант 4.2', 'Вариант 4.3'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Выбираем "Вариант 4.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 4.2"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 4.3"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 4.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //ТНР
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Тяжелые нарушения речи"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Тяжелые нарушения речи');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[8]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 5.1', 'Вариант 5.2 (I отделение)', 'Вариант 5.2 (II отделение)'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Выбираем "Вариант 5.1"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 5.2 (I отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.2 (I отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Выбираем "Вариант 5.2 (II отделение)"
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 5.2 (II отделение)');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');
        $dr->scrollTo('div.document-header');

        //Нарушения опорно двигательного аппарата
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Нарушения опорно двигательного аппарата"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Нарушения опорно двигательного аппарата');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 6.1', 'Вариант 6.2', 'Вариант 6.3', 'Вариант 6.4'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Вариант 6.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Вариант 6.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Вариант 6.3
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 6.4
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 6.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Задержка психического развития
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Задержка психического развития"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Задержка психического развития');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 7.1', 'Вариант 7.2'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Вариант 7.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 7.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Вариант 7.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 7.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');
        $dr->scrollTo('div.document-header');

        //Расстройство аутистического спектра
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Расстройство аутистического спектра"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Расстройство аутистического спектра');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 8.1', 'Вариант 8.2', 'Вариант 8.3', 'Вариант 8.4'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Вариант 8.1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Вариант 8.2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, 'all');

        //Вариант 8.3
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.3');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 8.4
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 8.4');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');

        //Умственная отсталость
        $I->comment('Проверка "ФГОС НОО ОВЗ, ФГОС УО" - "Умственная отсталость"');
        $dr->scrollTo($dr->program);
        $dr->setValueFieldInDocumentReception($dr->program, 'Умственная отсталость');
        $dr->scrollTo($dr->withPsychoSpecial);
        //$I -> see('Адаптированная основная образовательная программа для обучающихся со сложными дефектами', 'div.document-section__row:nth-child(6)');
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $I->seeElement('div.document-section__row:nth-child(10) > div.document-item:nth-child(1) > label > span > span.document-item__title-required');
        $arr = ['Вариант 1', 'Вариант 2'];
        $dr->checkingFiledInDocumentReception($dr -> variantProgram, $arr);

        //Вариант 1
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 1');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);

        //Вариант 2
        $dr->setValueFieldInDocumentReception($dr->variantProgram, 'Вариант 2');
        $dr->checkingFiledInDocumentReception($dr->assistant, 'all');
        $arr = [$dr->tutorEscortItems[1], $dr->tutorEscortItems[2], $dr->tutorEscortItems[3], $dr->tutorEscortItems[4]];
        $dr->checkingFiledInDocumentReception($dr->tutorEscort, $arr);
        $dr->scrollTo('div.document-header');
    }

    public function Test8fillDocumentReceptionPart5(AcceptanceTester $I)
    {
        $dr = new DocumentReception($I);
        $I->moveToServer('Заполнение документа приёма (Обследование, Тип приёма - Ранняя помощь, ОВГ, Программа) - Часть 5(СПО)');
        $I->inputInSystem('admin', 'admin');
        $I->waitForElement('div.table.ps > div', 60);
        $I->wait(1);
        $arrNeeded = ['Требуется', 'Не требуется'];
        //$I -> openSection('Карты детей');
        $count = $I->getQuantityElement('div.table.ps > div', 4);
        for ($i = 4; $i <= $count; $i++) {
            $I->moveMouseOver('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div');
            $I->wait(1);
            $status = $I->grabTextFrom('div.table.ps > div:nth-child(' . $i . ') > div.row > div.cell.cell--status > div > div.cards-status > div.tooltip.tooltip--left > div');
            if ($status == 'Документ не заполнен') break;
        }
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

        //Выбираем "СПО"
        $dr->setValueFieldInDocumentReception($dr->ovg, 'СПО');

        //Глухие
        $dr->setValueFieldInDocumentReception($dr->program, 'Глухие');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Слабослышащие
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабослышащие');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Слепые
        $dr->setValueFieldInDocumentReception($dr->program, 'Слепые');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Слабовидящие
        $dr->setValueFieldInDocumentReception($dr->program, 'Слабовидящие');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[1], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Нарушения опорно двигательного аппарата
        $dr->setValueFieldInDocumentReception($dr->program, 'Нарушения опорно двигательного аппарата');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Расстройство аутистического спектра
        $dr->setValueFieldInDocumentReception($dr->program, 'Расстройство аутистического спектра');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[1], $dr->programItems[3], $dr->programItems[5]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Умственная отсталость
        $dr->setValueFieldInDocumentReception($dr->program, 'Умственная отсталость');
        $dr->scrollTo($dr->withPsychoSpecial);
        $arr = [$dr->programItems[0], $dr->programItems[1], $dr->programItems[2], $dr->programItems[3], $dr->programItems[5], $dr->programItems[7]];
        $dr->checkingFiledInDocumentReception($dr->withPsychoSpecial, $arr);
        $dr->checkingFiledInDocumentReception('Безбарьерная архитектурная среда', $arrNeeded);
        $dr->checkingFiledInDocumentReception($dr->specialFacilities, $arrNeeded);
        $dr->scrollTo('div.document-header');

        //Основная образовательная программа(норма)
        $dr->setValueFieldInDocumentReception($dr->program, 'Основная образовательная программа(норма)');
        $dr->scrollTo($dr->withPsychoSpecial);
        for ($i = 9; $i <= 11; $i++) {
            $I->dontSeeElement('document-section__row:nth-child(' . $i . ')');
        }
        $dr->scrollTo('div.document-header');
    }

    public function Test9fillDocumentReception(DocumentReceptionTester $I)
    {
        $dr = new DocumentReception($I);
        $monthsOfYears = ['янв', 'февр', 'мар', 'апр', 'мая', 'июн', 'июл', 'авг', 'сент', 'окт', 'нояб', 'дек'];
        $I->moveToServer('Тест 9 - Заполнение документа приёма (Обследование, ФИО, Дата рождния, Наименование ОО, Округ ОО))');
        $I->inputInSystem('admin', 'admin');
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
        $I->seeElement($dr -> bilingual . ' > label[for="bilingual"]');
        $I->seeElement($dr -> deviant . ' > label[for="deviant"]');
        $dr -> scrollTo($dr -> nameOO);
        $dr->checkingFiledInDocumentReception('Наименование ОО', 'all');
        $I->click('div.document-section__row:nth-child(7) > div.document-item:nth-child(1) > div.document-item__row');
        $I->wait(1);
        foreach ($I->nameOO as $item) {
            $I->fillField('#educationOrgId', $item);
            $I->wait(1);
            $I->see($item, $dr -> nameOO);
            $I->wait(1);
        }
        unset($item);
        $dr->checkingFiledInDocumentReception('Округ ОО', 'all');
    }
}