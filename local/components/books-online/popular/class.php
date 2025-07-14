<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// проверяем установку модуля «Информационные блоки»
if (!CModule::IncludeModule('iblock')) {
    return;
}

// пространство имен для 404 ошибки
use Bitrix\Iblock\Component\Tools;
// пространство имен для загрузки необходимых файлов, классов, модулей
use Bitrix\Main\Loader;
use Bitrix\Sale\Fuser;
// пространство имен для обращения к глобальным сущностям ядра
use \Bitrix\Main\Application;
class BooksOnlinePopularComponent extends CBitrixComponent
{
    public function executeComponent(){
        $this->LoadPopular();

        $this->includeComponentTemplate();
    }

    protected function LoadPopular(){
        $fuserId = Fuser::getId();
        $basket = \CSaleBasket::GetList([], [
            'FUSER_ID' => $fuserId,
            'LID' => SITE_ID,
            'ORDER_ID' => 'NULL'
        ]);

        $inCartIds = [];
        while ($item = $basket->Fetch()) {
            $inCartIds[] = (int)$item['PRODUCT_ID'];
        }
        $res = CIBlockElement::GetList(
                ['RAND' => 'ASC'],
                ['IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
                false,
                ['nTopCount' => $this->arParams['ELEMENTS_COUNT']],
                ['ID', 'CODE', 'NAME', 'DETAIL_PICTURE', 'CATALOG_PRICE_1']
        );
        
        while($item = $res->GetNext()) {  
            if ($item['DETAIL_PICTURE']) {
                    $item['DETAIL_PICTURE_SRC'] = CFile::GetPath($item['DETAIL_PICTURE']);
            }
            $res2 = CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $item['ID'], "sort", "asc", array("CODE" => "ATT_AUTHOR"));
            $VALUES = [];
            while ($ob = $res2->GetNext())
            {
	            $VALUES[] = $ob['VALUE'];
            }
            $item['AUTHORS'] = $VALUES;
            $item['AUTHORS_STRING'] = implode(', ', $VALUES);
            $item['IS_IN_CART'] = in_array((int)$item['ID'], $inCartIds);
            $items[] = $item;
        }
        $this->arResult['ITEMS'] = $items;
    }
}