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
		$APPLICATION->ShowPanel();
		
	
		$APPLICATION->ShowHead();
		
		?>
	</head>
