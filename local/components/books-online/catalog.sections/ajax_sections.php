<?php
define('STOP_STATISTICS', true);
define('NO_KEEP_STATISTIC', 'Y');
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

$_REQUEST["AJAX"] = "Y";

$APPLICATION->IncludeComponent(
    "books-online:catalog.sections",
    "", 
    [
        'IBLOCK_ID' => (int)$_REQUEST['IBLOCK_ID'],
        "ELEMENT_COUNT" => (int)$_REQUEST["ELEMENT_COUNT"],
        //'SECTION_CODE' => $_REQUEST['section'] ?? '',
    ],
    false
);