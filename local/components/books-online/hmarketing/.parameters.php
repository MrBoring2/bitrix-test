<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// пространства имен для загрузки необходимых файлов, классов, модулей
use Bitrix\Main\Loader;

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

// подключаем модуль «Информационные блоки»
Loader::includeModule('iblock');

// получаем массив всех типов инфоблоков для возможности выбора
$arIBlockType = CIBlockParameters::GetIBlockTypes();

// пустой массив для вывода 
$arInfoBlocks = array();

// выбираем активные инфоблоки
$arFilterInfoBlocks = array('ACTIVE' => 'Y');

// сортируем по озрастанию поля сортировка
$arOrderInfoBlocks = array('SORT' => 'ASC');

// если уже выбран тип инфоблока, выбираем инфоблоки только этого типа
if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

// метод выборки информационных блоков
$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);

// перебираем и выводим в адмику доступные информационные блоки
while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}

// настройки компонента, формируем массив $arParams
$arComponentParameters = [
    // основной массив с параметрами
    "PARAMETERS" => [
        // выбор типа инфоблока
        'IBLOCK_TYPE' => array(                  // ключ массива $arParams в component.php
            'PARENT' => 'BASE',                  // название группы
            'NAME' => 'Выберите тип инфоблока',  // название параметра
            'TYPE' => 'LIST',                    // тип элемента управления, в котором будет устанавливаться параметр
            'VALUES' => $arIBlockType,           // входные значения
            'REFRESH' => 'Y',                    // перегружать настройки или нет после выбора (N/Y)
            'DEFAULT' => 'news',                 // значение по умолчанию
            'MULTIPLE' => 'N',                   // одиночное/множественное значение (N/Y)
        ),
        // выбор самого инфоблока
        'IBLOCK_ID' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Выберите родительский инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y',
            "DEFAULT" => '',
            "ADDITIONAL_VALUES" => "Y",
        ),
        // настройки режима без ЧПУ, доступно в админке до активации чекбокса
        "VARIABLE_ALIASES" => [
            // элемент
            "ELEMENT_ID" => [
                "NAME" => 'GET параметр для ID элемента без ЧПУ',
                "DEFAULT" => "ELEMENT_ID",
            ],
            // секция
            "SECTION_ID" => [
                "NAME" => 'GET параметр для ID раздела без ЧПУ',
                "DEFAULT" => "SECTION_ID",
            ],
            // базовый URL
            "CATALOG_URL" => [
                "NAME" => 'Базовый URL каталога без ЧПУ',
                "DEFAULT" => "/test/",
            ]
        ],
        // настройки режима ЧПУ, доступно в админке после активации чекбокса
        "SEF_MODE" => [
            // настройки для секции
            "section" => [
                "NAME" => 'Страница раздела',
                "DEFAULT" => "#SECTION_CODE#/",
            ],
            // настройки для элемента
            "element" => [
                "NAME" => 'Детальная страница',
                "DEFAULT" => "#SECTION_CODE#/#ELEMENT_CODE#/",
            ]
        ],
    ]
];
