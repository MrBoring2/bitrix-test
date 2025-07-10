<?php

define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

if (!CModule::IncludeModule("iblock")) {
    die("no iblock");
}

$_REQUEST["AJAX"] = "Y";

$APPLICATION->IncludeComponent(
    'books-online:catalog.index',
    "",
    [
        'IBLOCK_ID' => isset($_REQUEST['IBLOCK_ID']) ? (int)$_REQUEST['IBLOCK_ID'] : 0,
        'ELEMENT_COUNT' => isset($_REQUEST['ELEMENT_COUNT']) ? (int)$_REQUEST['ELEMENT_COUNT'] : 8,
        'PAGE' => isset($_REQUEST['PAGE']) ? (int)$_REQUEST['PAGE'] : 1,
        'AJAX' => 'Y',
        'SECTION_CODE' => $_REQUEST['SECTION_CODE'] ?? '',
        'CACHE_TIME' => 3600
    ],
    false
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_after.php");