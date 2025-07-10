<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Каталог");
?>
<?
$APPLICATION->IncludeComponent(
	"books-online:catalog", 
	".default", 
	array(
		"IBLOCK_ID" => "1",
		"COMPONENT_TEMPLATE" => ".default",
		"ELEMENTS_COUNT" => "8"
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>