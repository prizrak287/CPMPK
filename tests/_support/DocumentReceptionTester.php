<?php
/**
 * Created by PhpStorm.
 * User: sbori
 * Date: 08.02.2019
 * Time: 13:50
 */

class DocumentReceptionTester extends HeaderTester
{
    public $ovg = ['Ранняя помощь', 'Дошкольники', 'ФГОС НОО ОВЗ, ФГОС УО', 'СОШ', 'СПО'];
    public $programFull = ['Глухие', 'Слабослышащие', 'Слепые', 'Слабовидящие', 'Тяжелые нарушения речи', 'Нарушения опорно двигательного аппарата', 'Задержка психического развития', 'Расстройство аутистического спектра', 'Умственная отсталость'];
    //public $programSokr = ['Глухие', 'Слабослышащие', 'Слепые', 'Слабовидящие', 'ТНР', 'НОДА', 'ЗПР', 'РАС', 'УО', 'СД', 'ООП'];
    public $levelEducation = ['Дошкольный возраст', 'Начальный общий', 'Основной общий', 'Средний общий'];
    public $assistant = ['(НОДА, см. ИПРА) оказание помощи в использовании технических средств реабилитации',
        '(СД и НОДА без навыков самообслуживания) оказание помощи в соблюдении санитарно-гигиенических требований на группу/класс',
        '(НОДА-колясочники) обеспечение доступа в здание образовательной организации и предоставляемым в нем услугам',
        '(НОДА-колясочники) оказание технической помощи по преодолению препятствий',
        '(слепые) оказание индивидуальной технической помощи по преодолению препятствий в условиях инклюзивного образования'];
    public $nameOO = ['Школа № 1', 'Школа № 2', 'Школа № 3', 'Школа № 4'];

    public $tutorEscort=['(обучающимся с РАС от полугода до 1 года) индивидуальное сопровождение на период адаптации в условиях инклюзивного образования',
        '(обязательно для выбора) осуществление общего тьюторского сопровождения реализации АООП',
        '(обязательно для выбора) педагогическое сопровождение обучающихся в реализации АООП',
        '(по согласованию) подбор и адаптация педагогических средств, индивидуализация образовательного процесса',
        '(по согласованию) разработка и подбор методических средств (визуальной поддержки, альтернативной коммуникации)'];
    public $districts = ['Не посещал', 'Центральный', 'Северный', 'Северо-Восточный', 'Восточный', 'Юго-Восточный', 'Южный', 'Юго-Западный', 'Западный', 'Северо-Западный', 'Зеленоградский', 'Троицкий', 'Новомосковский', 'Нет данных'];

}