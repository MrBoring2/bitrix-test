<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');
$APPLICATION->SetPageProperty("title2", "Книги-Онлайн — Оформление заказа");
$APPLICATION->SetTitle("");

?><div class="main-index-container">
	<div class="main-banner">
		 <?$APPLICATION->IncludeComponent(
	"books-online:block_element",
	".default",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENT_COUNT" => "0",
		"IBLOCK_ID" => "3"
	)
);?>
	</div>
	<div class="slider-container">

	 <?$APPLICATION->IncludeComponent(
	"books-online:slider", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"IBLOCK_ID" => "2",
		"SECTION_ID" => "16",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
</div>
	<div class="popular-books">
		<h2>Популярные новинки</h2>
		<div class="catalog-top">
			 <?$APPLICATION->IncludeComponent(
	"books-online:popular", 
	".default", 
	array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"ELEMENTS_COUNT" => "8",
		"IBLOCK_ID" => "1",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
		</div>
	</div>
</div>
<br><?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>