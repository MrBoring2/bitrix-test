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
        "ELEMENTS_COUNT" => [
            "PARENT" => "BASE",
            "NAME" => "Количество элементов",
            "TYPE" => "STRING",
            "DEFAULT" => "8",
        ],
    ],
];