<?php
/**
 * Created by PhpStorm.
 * User: sbori
 * Date: 08.02.2019
 * Time: 13:52
 */

class TableReceptionTester extends HeaderTester
{
    public function selectBlockOfPatient($name_of_patient)
    {
        $this -> waitForElement('li.visit-list__item.visit-card');
        $count_time_blocks = $this -> getQuantityElement('div.visit-section', 3);
        for ($i = 3; $i <= $count_time_blocks; $i++) {
            $count_patient = $this->getQuantityElement('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card');
            for ($j = 1; $j <= $count_patient; $j++) {
                if (!$this -> boolSeeElement('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')')) {
                    if ($i !== $count_patient)
                        $this -> dragAndDrop('div.ps__rail-y > div.ps__thumb-y', 'div.visit-section:nth-child(' . ($i+1) . ')');
                    else
                        $this -> dragAndDrop('div.ps__rail-y > div.ps__thumb-y', 'footer.footer');
                }
                $patient_current = $this->grabTextFrom('div.visit-section:nth-child(' . ($i) . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')
                 > div[class="visit-card__content"] > div > div');
                if ($patient_current == $name_of_patient) {
                    $this -> click('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')');
                    $this -> wait(1);
                    $class = $this -> grabAttributeFrom('div.visit-section:nth-child(' . $i . ') > ul > li.visit-list__item.visit-card:nth-child(' . $j . ')', 'class');
                    if ($class !== 'visit-list__item visit-card visit-card--active')
                        $this -> comment('Выбранный блок не активен');
                    break 2;
                }
            }
        }
        if ($i > $count_time_blocks)
            throw new ExceptionWithPsycho('Блока с именем пациента "' . $name_of_patient . '" не найдено.');
    }
}