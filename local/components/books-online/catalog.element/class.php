<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;

class CatalogElementComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) return;

        $elementCode = $this->arParams['ELEMENT_CODE'];
        $iblockId = (int) $this->arParams['IBLOCK_ID'];

        $res = CIBlockElement::GetList([], [
            "IBLOCK_ID" => $iblockId,
            "CODE" => $elementCode,
            "ACTIVE" => "Y"
        ], false, false, ["ID", "NAME", "DETAIL_TEXT", "DETAIL_PICTURE"]);

        if ($item = $res->GetNext()) {
            if ($item['DETAIL_PICTURE']) {
                $file = CFile::GetFileArray($item['DETAIL_PICTURE']);
                $item['DETAIL_PICTURE_SRC'] = $file['SRC'];
            }
            $this->arResult = $item;
        } else {
            ShowError("Элемент не найден");
            return;
        }

        $this->includeComponentTemplate();
    }
}