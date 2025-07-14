<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<a href="/cart" class="cart-mini-inner">
    <div class="craft-mini-left">
        <i class="fa fa-shopping-cart"></i>
    </div>
    <div class="cart-mini-right">
        <div class="cart-mini-quatity">
            <p>Позиций в корзине:&nbsp;</p>
            <p> <?= $arResult['QUANTITY'] ?></p>
        </div>
        <div class="cart-mini-price">
            <p>На:&nbsp;</p>
            <p><?= $arResult['TOTAL_PRICE'] ?> руб.</p>
        </div>
        <div class="cart-mini-total-quantity">
            <p>Всего в корзине:&nbsp;</p>
            <p><?= $arResult['TOTAL_QUANTITY'] ?>
            <?if(($arResult['TOTAL_QUANTITY'] > 1 && $arResult['TOTAL_QUANTITY'] < 5) || ($arResult['TOTAL_QUANTITY'] % 10 > 1 && $arResult['TOTAL_QUANTITY'] % 10 < 5)):?>
                товара
            <?elseif($arResult['TOTAL_QUANTITY'] % 10 == 1):?>
                товар
            <?elseif($arResult['TOTAL_QUANTITY'] % 100 > 10 && $arResult['TOTAL_QUANTITY'] % 100 < 20) :?>
                товаров
            <?else:?>
                товаров
            <?endif?>
            </p>
        </div>
    </div>
</a>