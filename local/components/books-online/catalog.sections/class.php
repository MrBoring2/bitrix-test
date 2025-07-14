<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
use Bitrix\Main\Loader;
use Bitrix\Main\SystemException;

class BooksOnlineSectionsComponent extends CBitrixComponent
{
    protected function checkModules() {
    if (!Loader::includeModule('iblock'))
        throw new SystemException("Не удалось подключить модуль iblock");

    if (empty($this->arParams['IBLOCK_ID'])) {
        throw new SystemException("Не передан IBLOCK_ID");
    }
    }
    public function executeComponent()
    {
        try{
            $this->checkModules();
            if (!Loader::includeModule('iblock')) {
            throw new SystemException('Модуль iblock не подключен');
        }
        $this->arResult['IBLOCK_ID'] = $this->arParams['IBLOCK_ID'];
        $this->arResult['ELEMENT_COUNT'] = $this->arParams['ELEMENT_COUNT'];
        $this->arResult['SECTIONS'] = [];
        $this->arResult['SECTION_CHAIN'] = [];
        $this->arResult['SECTION_CHAIN'][] = [
            'NAME' => 'Книги',
            'CODE' => '',
        ];

        $sectionCode = $_REQUEST['section'] ?? null;
        $this->arResult['PARENT_SECTION'] = null;

        $filter = [
            'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
            'ACTIVE' => 'Y',
            'GLOBAL_ACTIVE' => 'Y'
        ];

        if ($sectionCode) {
            $parent = CIBlockSection::GetList([], [
                'IBLOCK_ID' => $this->arParams['IBLOCK_ID'],
                'CODE' => $sectionCode
            ])->GetNext();

             if ($parent) {
                $filter['SECTION_ID'] = $parent['ID'];
                $this->arResult['PARENT_SECTION'] = $parent;

                $nav = CIBlockSection::GetNavChain(
            $this->arParams['IBLOCK_ID'],
            $parent['ID'],
            ['ID', 'NAME', 'CODE']
            );
            $chain = [];
            while ($chainSection = $nav->GetNext()) {
                $chain[] = $chainSection;
            }
            array_pop($chain);
            $this->arResult['SECTION_CHAIN'] = array_merge($this->arResult['SECTION_CHAIN'], $chain);
                if ($parent['IBLOCK_SECTION_ID']) {
                    $parentSection = CIBlockSection::GetByID($parent['IBLOCK_SECTION_ID'])->GetNext();
                    if ($parentSection) {
                        $this->arResult['BACK_SECTION_CODE'] = $parentSection['CODE'];
                    }
                } else {
                    $this->arResult['BACK_SECTION_CODE'] = '';
                }
                $GLOBALS['CURRENT_SECTION_CODE'] = $sectionCode;
            }
        } else {
            $filter['DEPTH_LEVEL'] = 1;
        }

        $res = CIBlockSection::GetList(['SORT' => 'ASC'], $filter);
        while ($section = $res->GetNext()) {
            $this->arResult['SECTIONS'][] = $section;
        }

        $this->includeComponentTemplate();
        }
        catch(Exception $e){
            ShowError($e->getMessage());
        }
        
    }
}