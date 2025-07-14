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
use Bitrix\Main\SystemException;
class BooksOnlineSliderElementComponent extends CBitrixComponent
{
    public function executeComponent(){
        try{
            $this->checkModules();
            $this->LoadSlider();
        }
        catch (SystemException $e) {
            ShowError($e->getMessage());
        }

        $this->includeComponentTemplate();
    }

    protected function LoadSlider(){
        $items = [];
        $res = CIBlockElement::GetList(
            ['NAME' => 'ASC'],
            ['SECTION_ID' => $this->arParams['SECTION_ID'], 'IBLOCK_ID' => $this->arParams['IBLOCK_ID'], 'ACTIVE' => 'Y'],
            false,
            false,
            ['ID', 'CODE', 'NAME', 'DETAIL_PICTURE']
            );
            while ($item = $res->fetch()) {
            if ($item['DETAIL_PICTURE']) {
                    $item['DETAIL_PICTURE_SRC'] = CFile::GetPath($item['DETAIL_PICTURE']);
            }
            $items[] = $item;
            
            }
        $this->arResult['ITEMS'] = $items;
    }
    protected function checkModules()
    {
        // если модуль не подключен
        if (!Loader::includeModule('iblock') || !Loader::includeModule('sale'))
            // выводим сообщение в catch
            throw new SystemException(Loc::getMessage('IBLOCK_MODULE_NOT_INSTALLED'));
    }

}