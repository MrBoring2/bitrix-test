<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
if (!Loader::includeModule('iblock'))
	return;

$iblocks = [];
$res = CIBlock::GetList([], ["ACTIVE" => "Y"]);
while ($ar = $res->Fetch()) {
    $iblocks[$ar["ID"]] = "[" . $ar["ID"] . "] " . $ar["NAME"];
}

$arComponentParameters = [
    "GROUPS" => array(),
    "PARAMETERS" => [
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $iblocks,
            'REFRESH' => 'N'
        ],
        'ELEMENTS_COUNT' => [
            'PARENT' => 'BASE',
            'NAME' => 'Топ популярных',
            'TYPE' => 'STRING',
            'DEFAULT' => '8'
        ],
        'CACHE_TIME' => ['DEFAULT' => 3600]
    ]
];