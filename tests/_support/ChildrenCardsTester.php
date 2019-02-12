<?php
/**
 * Created by PhpStorm.
 * User: sbori
 * Date: 08.02.2019
 * Time: 13:56
 */

class ChildrenCardsTester extends HeaderTester
{
    public function getRowOfPatient($name_of_patient)
    {
        do {
            $count_rows = $this -> getQuantityElement('div[class="row"]');
            for ($i = 1; $i <= $count_rows; $i++) {
                $patient_current = $this -> grabTextFrom('div[class="row"]:nth-child(' . $i . ') > div[class="cell cell--name"]');
                if ($patient_current == $name_of_patient) {
                    $this->wait(1);
                    return $i;
                }
            }
            if ($this -> boolExistsElement('li[class="pagination__item pagination__item--next"]')) {
                $this -> click('li[class="pagination__item pagination__item--next"]', 'ul.pagination');
                $this -> waitForElement('div.table', 60);
                $repeat = true;
            }
            else {
                $repeat = false;
            }
        } while ($repeat);
        throw new ExceptionWithPsycho('Строки с именем пациента "' . $name_of_patient . '" не найдено.');
    }
}