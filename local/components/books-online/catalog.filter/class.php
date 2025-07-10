<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;

class BooksOnlineFilterComponent extends CBitrixComponent
{
    public function executeComponent()
    {
        if (!Loader::includeModule('iblock')) {
            ShowError("Модуль iblock не подключен");
            return;
        }

        $this->includeComponentTemplate();
    }
}