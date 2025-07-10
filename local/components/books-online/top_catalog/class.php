<?
if(!defined("B_PROLOG_INCLUDED") ||  B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\SystemException;

class BooksOnlineTopCatalogComponent extends CBitrixComponent{
    
    protected $isAjax = false;
    protected $page = 1;

    public function onPrepareComponentParams($arParams) {
        if (!isset($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        } else {
            $arParams['CACHE_TIME'] = intval($arParams['CACHE_TIME']);
        }
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
        if (!Loader::includeModule('iblock'))
            throw new SystemException("Не удалось подключить модуль iblock");

    }
    public function GetItems() {

       // if ($this->startResultCache()) {
            $res = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            ['IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
            false,
        [
            'iNumPage' => $this->page,
            'nPageSize' => $this->arParams['ELEMENTS_COUNT'],
            'bShowAll' => false
             ],
            ['ID', 'NAME', 'PREVIEW_PICTURE', 'CATALOG_PRICE_1']
            );
            while($item = $res->fetch()) {  
                if($item['PREVIEW_PICTURE']) {
                    $item['PREVIEW_PICTURE_SRC'] = CFile::GetPath($item['PREVIEW_PICTURE']);
                }
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
                    $this->arResult['HAS_MORE'] = count($this->arResult['ITEMS']) === $this->arParams['ELEMENTS_COUNT'];;

                    $this->SetResultCacheKeys(
                        array()
                    );

                    //$template = $this->isAjax ? "ajax_template" : "template";
                    $this->includeComponentTemplate();
                }
            }
          //  else{
          //      $this->AbortResultCache();
          //  }
       // }
    }
}