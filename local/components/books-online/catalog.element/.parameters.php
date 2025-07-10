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
        "ELEMENT_CODE" => [
            "PARENT" => "BASE",
            "NAME" => "Символьный код элемента",
            "TYPE" => "STRING",
            "DEFAULT" => "",
        ],
    ],
];