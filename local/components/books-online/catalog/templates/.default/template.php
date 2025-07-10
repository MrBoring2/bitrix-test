<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<div class="catalog-wrapper">
    <div class="catalog-sidebar">
         <div class="section-list">
            <?$APPLICATION->IncludeComponent(
            "books-online:catalog.sections",
            "",
            [
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "CURRENT_SECTION" => $_GET["section"] ?? null,
                "ELEMENT_COUNT" => $arParams["ELEMENTS_COUNT"],
            ],
            $component
            );?>
        </div>
    </div>

    <div class="catalog-content">
        <?$APPLICATION->IncludeComponent(
            "books-online:catalog.filter",
            "",
            [
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                'SECTION_CODE' => $arResult['BACK_SECTION_CODE'] ?? ($_REQUEST['section'] ?? null),
                
            ]
        );?>

        <?$APPLICATION->IncludeComponent(
            "books-online:catalog.index",
            "",
            [
                "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                "ELEMENT_COUNT" => $arParams["ELEMENTS_COUNT"],
                "PAGE" => $_GET['PAGE'] ?? 1,
                'SECTION_CODE' => $_GET['section'] ?? null,
            ]
        );?>
    </div>
</div>