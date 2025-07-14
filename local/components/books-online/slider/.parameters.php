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

$sections = [];
if (!empty($arCurrentValues['IBLOCK_ID'])) {
    $res = CIBlockSection::GetList(
        ['SORT' => 'ASC'],
        ['IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'], 'ACTIVE' => 'Y'],
        false,
        ['ID', 'NAME', 'DEPTH_LEVEL']
    );
    while ($section = $res->Fetch()) {
        $prefix = str_repeat(" . ", (int)$section['DEPTH_LEVEL'] - 1);
        $sections[$section['ID']] = $prefix . $section['NAME'];
    }
}

$arComponentParameters = [
    "GROUPS" => array(),
    "PARAMETERS" => [
        'IBLOCK_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Инфоблок',
            'TYPE' => 'LIST',
            'VALUES' => $iblocks,
            'REFRESH' => 'Y'
        ],
        'SECTION_ID' => [
            'PARENT' => 'BASE',
            'NAME' => 'Секция',
            'TYPE' => 'LIST',
            'VALUES' => $sections
        ],
        'CACHE_TIME' => ['DEFAULT' => 3600]
    ]
];