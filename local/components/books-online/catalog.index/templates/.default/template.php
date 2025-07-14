<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="catalog-top-container">
    <div id="catalog-top-inner">
    <? foreach ($arResult['ITEMS'] as $item): ?>
        <div class="catalog-top-item">
            <a href="/catalog/<?= htmlspecialchars($item['CODE']) ?>/" class="catalog-link">
            <div class="catalog-top-item-content">
             <? if ($item['PREVIEW_PICTURE_SRC']):?>
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
            <?endif?>
            <p class="book-price">Цена: <?= intval($item['CATALOG_PRICE_1']) ?> руб.</p>
            <p class="book-title"><?= htmlspecialchars($item['NAME']) ?> </p>
            <p class="book-authors"><?= htmlspecialchars($item['AUTHORS_STRING']) ?> </p>
            </div>
                <button style="<?= $item['IS_IN_CART'] ? 'display:none' : '' ?>" class="add-to-cart" data-id="<?= $item["ID"]?>">Купить</button>
                <a style="<?= !$item['IS_IN_CART'] ? 'display:none' : '' ?>" class="to-cart-href" href="/cart">
                    <p>В корзине</p>
                </a>
            </a>
        </div>
    <?endforeach?>
    </div>
        <button id="load-more" data-page="<?= $arResult["CURRENT_PAGE"]?>"
        data-iblock-id="<?= htmlspecialchars($arParams["IBLOCK_ID"]) ?>"
        data-count="<?= htmlspecialchars($arParams["ELEMENTS_COUNT"]) ?>"
        data-section="<?= htmlspecialchars($arParams["SECTION_CODE"]) ?>"
        data-has-more="<?= htmlspecialchars($arResult["HAS_MORE"]) ?>"
        >Показать ещё</button>
</div>
