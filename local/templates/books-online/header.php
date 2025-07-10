<?
$bIsMainPage = $APPLICATION->GetCurPage(false) == SITE_DIR;
?>


<!doctype html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title><?$APPLICATION->ShowTitle();?></title>
		<link rel="icon" href="<?=SITE_TEMPLATE_PATH?>/images/favicon.ico">
		<?
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/font-awesome.min.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/header.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/footer.css");
		$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH."/css/about-shop.css");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/jquery-3.7.1.min.js");
		$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH."/js/books_online.js");
		
		
	
		$APPLICATION->ShowHead();
		
		?>
	</head>
	<body>
		<?$APPLICATION->ShowPanel();?>
		<header>
		<div class="header-containter">
			<a href="/">
				<img class="header-logo" src="<?= SITE_TEMPLATE_PATH ?>/images/logo.png"
				/>
			</a>
			
			<div class="header-menu-desktop-container">	
				<?$APPLICATION->IncludeComponent("bitrix:menu", "menu", Array(
					"ALLOW_MULTI_SELECT" => "N",	// Разрешить несколько активных пунктов одновременно
					"CHILD_MENU_TYPE" => "left",	// Тип меню для остальных уровней
					"DELAY" => "N",	// Откладывать выполнение шаблона меню
					"MAX_LEVEL" => "1",	// Уровень вложенности меню
					"MENU_CACHE_GET_VARS" => array(	// Значимые переменные запроса
						0 => "",
					),
					"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
					"MENU_CACHE_TYPE" => "N",	// Тип кеширования
					"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
					"ROOT_MENU_TYPE" => "top",	// Тип меню для первого уровня
					"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
				),
				false
			);?>
			</div>
			<div class="header-right">
				<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	"main_search", 
	array(
		"CATEGORY_0" => array(
			0 => "iblock_books_shop",
		),
		"CATEGORY_0_TITLE" => "",
		"CHECK_DATES" => "N",
		"CONTAINER_ID" => "title-search",
		"INPUT_ID" => "title-search-input",
		"NUM_CATEGORIES" => "1",
		"ORDER" => "date",
		"PAGE" => "#SITE_DIR#search/index.php",
		"SHOW_INPUT" => "Y",
		"SHOW_OTHERS" => "N",
		"TOP_COUNT" => "5",
		"USE_LANGUAGE_GUESS" => "Y",
		"COMPONENT_TEMPLATE" => "main_search",
		"CATEGORY_0_iblock_books_shop" => array(
			0 => "1",
		)
	),
	false
);?>
                <?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket.line", "small_cart", Array(
	"HIDE_ON_BASKET_PAGES" => "N",	// Не показывать на страницах корзины и оформления заказа
		"PATH_TO_AUTHORIZE" => "",	// Страница авторизации
		"PATH_TO_BASKET" => SITE_DIR."cart/",	// Страница корзины
		"PATH_TO_ORDER" => SITE_DIR."order/",	// Страница оформления заказа
		"PATH_TO_PERSONAL" => SITE_DIR."personal/",	// Страница персонального раздела
		"PATH_TO_PROFILE" => SITE_DIR."personal/",	// Страница профиля
		"PATH_TO_REGISTER" => SITE_DIR."login/",	// Страница регистрации
		"POSITION_FIXED" => "N",	// Отображать корзину поверх шаблона
		"SHOW_AUTHOR" => "N",	// Добавить возможность авторизации
		"SHOW_EMPTY_VALUES" => "Y",	// Выводить нулевые значения в пустой корзине
		"SHOW_NUM_PRODUCTS" => "Y",	// Показывать количество товаров
		"SHOW_PERSONAL_LINK" => "N",	// Отображать персональный раздел
		"SHOW_PRODUCTS" => "N",	// Показывать список товаров
		"SHOW_REGISTRATION" => "N",	// Добавить возможность регистрации
		"SHOW_TOTAL_PRICE" => "Y",	// Показывать общую сумму по товарам
	),
	false
);?>
            </div>
		</div>
	</header>
		<div class="body-main">		
			<h1 class="page-title"><?$APPLICATION->ShowTitle();?></h1>
			<div class="main-container">