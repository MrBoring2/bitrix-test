<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Sale\Fuser;
// пространства имен для всех исключений в системе
use Bitrix\Main\SystemException;
class CatalogElementComponent extends CBitrixComponent
{
    public function executeComponent()
    {   
        try{
            $this->checkModules();
            $this->getResult();
        }
        catch (SystemException $e) {
            ShowError($e->getMessage());
        }
    }

     public function onPrepareComponentParams($arParams)
    {
        

        // время кеширования
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        if (empty($arParams['ELEMENT_CODE'])) {
            $arParams['ELEMENT_CODE'] = '-';
        }

        // возвращаем в метод новый массив $arParams     
        return $arParams;
    }

    protected function checkModules()
    {
        // если модуль не подключен
        if (!Loader::includeModule('iblock') || !Loader::includeModule('sale'))
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }

    public function getResult() {
        //if($this->startResultCache()){

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
            [],
            ['CODE' => $this->arParams['ELEMENT_CODE'], 'IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
            false,
            false,
            ['ID', 'NAME', 'PROPERTY_ATT_DESCRIPTION', 'DETAIL_PICTURE', 'PROPERTY_ATT_PAGES', 'PROPERTY_ATT_WEIGHT',
             'PROPERTY_ATT_SIZE', 'PROPERTY_ATT_PUBLISHING', 'PROPERTY_ATT_SERIA', 'CATALOG_PRICE_1', 'CATALOG_QUANTITY', 'PROPERTY_ATT_BINDING',
             'PROPERTY_ATT_AGE_LIMIT', 'PROPERTY_ATT_PUBLISHING_YEAR']
            );
           
            if ($item = $res->GetNext()) {
                //Основное фото
                $photos = [];
                if ($item['DETAIL_PICTURE']) {
                    $item['DETAIL_PICTURE_SRC'] = CFile::GetPath($item['DETAIL_PICTURE']);
                    $photos[] = $item['DETAIL_PICTURE_SRC'];
                }
                //Товар в наличии
                $item['CATALOG_QUANTITY'] = $item['CATALOG_QUANTITY'] > 0 ? 1 : 0;

                //Авторы
                $res2 = CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $item['ID'], "sort", "asc", array("CODE" => "ATT_AUTHOR"));
                $authors = [];
                while ($ob = $res2->GetNext())
                {
	                $authors[] = $ob['VALUE'];
                }
                $item['AUTHORS'] = $authors;
                $item['AUTHORS_STRING'] = implode(', ', $authors);

                //Дополнительные фото
                $res2 = CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $item['ID'], [], ['CODE' => 'ATT_PHOTOS']);
                while ($ob = $res2->GetNext()) {
                    if (!empty($ob['VALUE'])) {
                        $photos[] = CFile::GetPath($ob['VALUE']);
                    }
                }
                $item['PHOTOS'] = $photos;

                //ISBN
                $res2 = CIBlockElement::GetProperty($this->arParams['IBLOCK_ID'], $item['ID'], [], ['CODE' => 'ATT_ISBN']);
                while ($ob = $res2->GetNext()) {
                    if (!empty($ob['VALUE'])) {
                        $isbn[] = $ob['VALUE'];
                    }
                }
                $item['ISBN'] = $isbn;
                $item['ISBN_STRING'] = implode(', ', $isbn);
                $item['IS_IN_CART'] = in_array((int)$item['ID'], $inCartIds);
                $this->arResult = $item;
            } 
            else {
                ShowError("Элемент не найден");
                return;
            }
            if (!empty($this->arResult)) {
                // ключи $arResult перечисленные при вызове этого метода, будут доступны в component_epilog.php и ниже по коду, обратите внимание там будет другой $arResult
                $this->SetResultCacheKeys(
                    array()
                );
                // подключаем шаблон и сохраняем кеш
                $this->IncludeComponentTemplate();
            //} else { // если выяснилось что кешировать данные не требуется, прерываем кеширование и выдаем сообщение «Страница не найдена»
            //    $this->AbortResultCache();
            //    \Bitrix\Iblock\Component\Tools::process404(
            //        "Элемент не найден",
            //        true,
            //        true
            //   );
            //}
        }
    }
}