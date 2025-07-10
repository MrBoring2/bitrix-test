<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="catalog-top-container">
    <div id="catalog-top-inner">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="catalog-top-item">
             <? if ($item['PREVIEW_PICTURE_SRC']):?>
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
            <?endif?>
            <p class="book-price">Цена: <?= intval($item['CATALOG_PRICE_1']) ?> руб.</p>
            <p class="book-title"><?= htmlspecialchars($item['NAME']) ?> </p>
            <button class="add-to-cart">Купить</button>
        </div>
    <?endforeach?>
    </div>
    <? if ($arResult['HAS_MORE']): ?>
        <button id="load-more" data-page="<?= $arResult["CURRENT_PAGE"]?>"
        data-iblock-id="<?= htmlspecialchars($arParams["IBLOCK_ID"]) ?>"
        data-count="<?= htmlspecialchars($arParams["ELEMENTS_COUNT"]) ?>"
        >Показать ещё</button>
    <?endif?>
</div>
