<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<? foreach ($arResult['ITEMS'] as $item): ?>
    <div class="catalog-top-item">
            <? if ($item['PREVIEW_PICTURE_SRC']):?>
                <img src="<?= $item['PREVIEW_PICTURE_SRC'] ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
            <?endif?>
            <p class="book-price">Цена: <?= $item['PROPERTY_PRICE_VALUE'] ?> руб.</p>
            <p class="book-title"><?= htmlspecialchars($item['NAME']) ?> </p>
            <button class="add-to-cart">Купить</button>
    </div>
<?endforeach?>