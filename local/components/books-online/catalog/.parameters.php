<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

Loader::includeModule('iblock');

$arIBlockType = CIBlockParameters::GetIBlockTypes();

$arInfoBlocks = array();

$arFilterInfoBlocks = array('ACTIVE' => 'Y');

$arOrderInfoBlocks = array('SORT' => 'ASC');

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
    $arFilterInfoBlocks['TYPE'] = $arCurrentValues['IBLOCK_TYPE'];
}

$rsIBlock = CIBlock::GetList($arOrderInfoBlocks, $arFilterInfoBlocks);

while ($obIBlock = $rsIBlock->Fetch()) {
    $arInfoBlocks[$obIBlock['ID']] = '[' . $obIBlock['ID'] . '] ' . $obIBlock['NAME'];
}

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        'IBLOCK_TYPE' => array(                  // ключ массива $arParams в component.php
            'PARENT' => 'BASE',                  // название группы
            'NAME' => 'Выберите тип инфоблока',  // название параметра
            'TYPE' => 'LIST',                    // тип элемента управления, в котором будет устанавливаться параметр
            'VALUES' => $arIBlockType,           // входные значения
            'REFRESH' => 'Y',                    // перегружать настройки или нет после выбора (N/Y)
            'DEFAULT' => 'news',                 // значение по умолчанию
            'MULTIPLE' => 'N',                   // одиночное/множественное значение (N/Y)
        ),
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => "Выберите инфоблок",
            "TYPE" => "LIST",
            'VALUES' => $arInfoBlocks,
            'REFRESH' => 'Y', 
            "DEFAULT" => "",
            'MULTIPLE' => 'N',
            "ADDITIONAL_VALUES" => "Y",
        ],
        "ELEMENTS_COUNT" => [
            "PARENT" => "BASE",
            "NAME" => "Количество элементов",
            "TYPE" => "STRING",
            "DEFAULT" => "8",
        ],
        "VARIABLES" => [
            // элемент
            "ELEMENT_ID" => [
                "NAME" => 'GET параметр для ID элемента без ЧПУ',
                "DEFAULT" => "ELEMENT_ID",
            ],
            // базовый URL
            "CATALOG_URL" => [
                "NAME" => 'Базовый URL каталога без ЧПУ',
                "DEFAULT" => "/test/",
            ]
        ],
        "SEF_MODE" => [
            // настройки для элемента
            "element" => [
                "NAME" => 'Детальная страница',
                "DEFAULT" => "#ELEMENT_CODE#/",
            ]
        ],
    ],
];