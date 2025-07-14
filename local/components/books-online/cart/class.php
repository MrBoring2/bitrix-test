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
Loader::includeModule('sale');
use Bitrix\Sale\Fuser;
// пространство имен для обращения к глобальным сущностям ядра
use \Bitrix\Main\Application;
class BooksOnlineCartComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError("Модуль iblock не подключен");
            return;
        }
        $this->LoadCart();

        $this->includeComponentTemplate();
    }

    protected function LoadCart(){
        $fuserId = Fuser::getId();
        $arFilter = ['FUSER_ID' => $fuserId, 'LID' => SITE_ID, 'ORDER_ID' => NULL];
        $busket = CSaleBasket::GetList([], $arFilter);
        
        $items = [];
        $totalPrice = 0;
        $totalQuantity = 0;
        while($item =$busket->Fetch()){
            $item['PROPS'] = [];
            $props = CSaleBasket::GetPropsList(
                [],
                ["BASKET_ID" => $item["ID"]]
            );

            while ($arProp = $props->Fetch()) {
                $item['PROPS'][$arProp['CODE']] = $arProp['VALUE'];
            }
            $item['TOTAL_PRICE'] = $item['PRICE'] * $item['QUANTITY'];

            $items[] = $item;
            $totalPrice += $item['PRICE'] * $item['QUANTITY'];
            $totalQuantity += $item['QUANTITY'];
        }
        $this->arResult['ITEMS'] = $items;
        $this->arResult['QUANTITY'] = count($items);
        $this->arResult['TOTAL_QUANTITY'] = $totalQuantity;
        $this->arResult['TOTAL_PRICE'] = $totalPrice;
    }
}