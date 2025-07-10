<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = [
    "GROUPS" => [],
    "PARAMETERS" => [
        "IBLOCK_ID" => [
            "PARENT" => "BASE",
            "NAME" => "ID инфоблока",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        "SECTION_CODE" => [
            "PARENT" => "BASE",
            "NAME" => "Символьный код раздела",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
        'ELEMENT_COUNT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Элементов на страницу',
            'TYPE' => 'STRING',
            'DEFAULT' => '8'
        ],
    ],
];