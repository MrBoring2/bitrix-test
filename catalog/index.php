<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title2", "Книги-Онлайн — Каталог — Книги");
$APPLICATION->SetTitle("Каталог");
?>
<div class="catalog-top-cont">
	<div class="catalog-top-container-index">
		<?
		$APPLICATION->IncludeComponent(
			"books-online:catalog", 
			".default", 
			array(
				"IBLOCK_ID" => "1",
				"COMPONENT_TEMPLATE" => ".default",
				"ELEMENTS_COUNT" => "8",
				"IBLOCK_TYPE" => "books_shop",
				"SEF_MODE" => "Y",
				"SEF_FOLDER" => "/catalog/",
				"VARIABLES" => "",
				"SEF_URL_TEMPLATES" => array(
					"element" => "#ELEMENT_CODE#/",
				)
			),
			false
		);?>
	</div>

</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>