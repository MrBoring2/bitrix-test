<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<diV class="catalog-element-container-index">
    <?$APPLICATION->IncludeComponent(
            "books-online:catalog.element",
                "",
                [
            "CACHE_TIME" => "3600",
            "CACHE_TYPE" => "A",
                        "SEF_MODE" => $arParams["SEF_MODE"],
                        "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                        "ELEMENT_ID" => $arResult["VARIABLES"]["ELEMENT_ID"],
                        "ELEMENT_CODE" => $arResult["VARIABLES"]["ELEMENT_CODE"],
                        "SECTION_CODE" => $arResult["VARIABLES"]["SECTION_CODE"],
                        "CURRENT_SECTION" => $_GET["section"] ?? null,
                        "SEF_FOLDER" => $arParams["SEF_FOLDER"],
                        ],
    );?>
</diV>

