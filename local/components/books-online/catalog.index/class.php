<?
if(!defined("B_PROLOG_INCLUDED") ||  B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\SystemException;

use Bitrix\Sale\Fuser;
class BooksOnlineTopCatalogComponent extends CBitrixComponent{
    
    protected $isAjax = false;
    protected $page = 1;

    public function onPrepareComponentParams($arParams) {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        }
         else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
        $arParams['SECTION_CODE'] = $arParams['SECTION_CODE'] 
        ?? ($_REQUEST['SECTION_CODE'] ?? null);
        $arParams['IBLOCK_ID'] = (int)$arParams['IBLOCK_ID'];
        $arParams['ELEMENTS_COUNT'] = (int)($arParams['ELEMENT_COUNT'] ?? 8);
        $this->page = isset($_REQUEST['PAGE']) ? (int)$_REQUEST['PAGE'] : 1;
        $this->isAjax = ($_REQUEST['AJAX'] ?? '') == 'Y';
        return $arParams;
    }


    public function executeComponent(){
        try{
            $this->checkModules();
            $this->GetItems();
        }
        catch(SystemException $e) {
             ShowError($e);
        } 
    }
    protected function checkModules()
    {
        if (!Loader::includeModule('iblock') || !Loader::includeModule('sale'))
            throw new SystemException("Не удалось подключить модуль iblock");

    }
    public function GetItems() {

       // if ($this->startResultCache()) {
            $sectionCode = $this->arParams['SECTION_CODE'] ?? null;
            $sectionId = null;
            if ($sectionCode) {
                $section = CIBlockSection::GetList([], [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'CODE' => $sectionCode
                ])->GetNext();
                if ($section) {
                    $sectionId = $section['ID'];
                }
            }
            


            $filter = [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'ACTIVE' => 'Y'
            ];
            
            $authorFilter = [];
            if (!empty($_REQUEST['AUTHOR'])) {
                $authorFilter = [
                    'LOGIC' => 'OR',
                    ['%PROPERTY_ATT_AUTHOR' => $_REQUEST['AUTHOR']]
                ];
            }
            if ($sectionId) {
                $filter['SECTION_ID'] = $sectionId;
                $filter['INCLUDE_SUBSECTIONS'] = 'Y';
            }
            if (!empty($authorFilter)) {
            $filter[] = $authorFilter;
            }
            if ($_REQUEST['PRICE_MIN']) {
                $filter['>=CATALOG_PRICE_1'] = (float)$_REQUEST['PRICE_MIN'];
            }
            if ($_REQUEST['PRICE_MAX']) {
                $filter['<=CATALOG_PRICE_1'] = (float)$_REQUEST['PRICE_MAX'];
            }

            if ($_REQUEST['YEAR_MIN']) {
                $filter['>=PROPERTY_ATT_PUBLISHING_YEAR'] = (int)$_REQUEST['YEAR_MIN'];
            }
             if ($_REQUEST['YEAR_MAX']) {
                $filter['<=PROPERTY_ATT_PUBLISHING_YEAR'] = (int)$_REQUEST['YEAR_MAX'];
            }

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
            ['NAME' => 'ASC'],
            $filter,
            false,
            [
            'iNumPage' => $this->page,
            'nPageSize' => $this->arParams['ELEMENTS_COUNT'],
            'bShowAll' => false
            ],
            ['ID', 'CODE', 'NAME', 'PREVIEW_PICTURE', 'CATALOG_PRICE_1']
            );
            while($item = $res->fetch()) {  
                if($item['PREVIEW_PICTURE']) {
                    $item['PREVIEW_PICTURE_SRC'] = CFile::GetPath($item['PREVIEW_PICTURE']);
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
            if (isset($this->arResult)){
                if ($this->page > $res->NavPageCount) {
                    $this->arResult['ITEMS'] = [];
                    $this->arResult['HAS_MORE'] = false;
                }
                else {       
                
                    $this->arResult['CURRENT_PAGE'] = $this->page;
                    $el_count =  $this->arParams['ELEMENTS_COUNT'];
                    if($res->NavPageNomer < $res->NavPageCount){
                        $this->arResult['HAS_MORE'] = 1;
                    }
                    else $this->arResult['HAS_MORE'] = 0;
                   // var_dump
                   //  = count($this->arResult['ITEMS']) === $this->arParams['ELEMENTS_COUNT'];

                    $this->SetResultCacheKeys(
                        array()
                    );

                    $template = $this->isAjax ? "ajax_template" : "template";
                    $this->includeComponentTemplate($template);
                }
            }
          //  else{
          //      $this->AbortResultCache();
          //  }
       // }
    }
}