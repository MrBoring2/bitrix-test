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
            'NAME' => 'Контентный блок',
            'TYPE' => 'LIST',
            'VALUES' => $iblocks,
            'REFRESH' => 'N'
        ],
        'BLOCK_TYPE' => [
            'PARENT' => 'BASE',
            'NAME' => 'Тип блока',
            'TYPE' => 'LIST',
            'VALUES' => ['welcome'],
            'DEFAULT' => 'welcome'
        ],
        'CACHE_TIME' => ['DEFAULT' => 3600]
    ]
];