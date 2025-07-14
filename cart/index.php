<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title2", "Книги-Онлайн — Корзина");
$APPLICATION->SetTitle("Корзина");
?>
<div class="cart-container-index">
<?$APPLICATION->IncludeComponent(
	"books-online:cart", 
	"", 
	array(
		
	),
	false
);?>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>