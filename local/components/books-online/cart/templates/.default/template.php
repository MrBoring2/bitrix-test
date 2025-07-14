<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="cart-container">
    <div class="cart-header" style="<?= count($arResult['ITEMS']) == 0 ? 'display:none' : '' ?>">
        <div class="total-price-container">
            <p>Итого:&nbsp;</p>
            <p id="total-price-text"><?=$arResult['TOTAL_PRICE']?></p>
            <p class="price-currency">&nbsp;₽</p>
        </div>
        <a href="/order">
            <button id="order-button">Оформить заказ</button>
        </a> 
    </div>
    <div class="cart-inner">
        <div class="cart-inner-header">
            <div id="cart-quantity" style="<?= count($arResult['ITEMS']) == 0 ? 'display:none' : '' ?>">
                <h3>В корзине <?=$arResult['QUANTITY']?>
                    <?if($arResult['QUANTITY'] > 1 && $arResult['QUANTITY'] < 5):?>
                        позиции
                    <?elseif($arResult['QUANTITY'] % 10 == 1):?>
                        позиция
                    <?elseif($arResult['QUANTITY'] % 100 > 10 && $arResult['QUANTITY'] % 100 < 20) :?>
                        позиций
                    <?else:?>
                        позиций
                    <?endif?>
                    </h3>
            </div>
        </div>
        <div class="empty-container" style="<?= count($arResult['ITEMS']) > 0 ? 'display:none' : '' ?>">
            <h1>В корзине пусто</h1>
            <img src="/local/templates/books-online/images/shopping_cart.svg"/>
            <a href="/catalog">
                <button id="to-catalog-button">За покупками</button>
            </a>
            
        </div>
        <table class="cart-items-list" style="<?= count($arResult['ITEMS']) == 0 ? 'display:none' : '' ?>">
            <tbody>
            <? foreach ($arResult['ITEMS'] as $item): ?>
                <tr class="cart-item">
                    <td class="cart-item-content">
                        <div class="cart-item-content-container">
                            <div class="cart-item-image">
                            <? if ($item['PROPS']['DETAIL_PICTURE']):?>
                            <img  src="<?= $item['PROPS']['DETAIL_PICTURE'] ?? null; ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
                            <?endif?>
                            </div>
                            <di class="cart-item-content-props">
                                <p class="cart-item-name"><?= htmlspecialchars($item['NAME']) ?> </p>
                                <p class="cart-item-title"><?= htmlspecialchars($item['PROPS']['NAME']) ?> </p>
                            </div>
                        </div>  
                    </td>
                        <td class="cart-item-price">
                            <p class="field-value-text item-price"><?= intval($item['PRICE']) ?> ₽</p>
                            <p class="field-title-text">цена за 1 шт.</p>
                        </td>
                        <td class="cart-item-quantity">
                            <div class="cart-item-quantity-container">
                                <button data-id="<?= $item['ID']?>" class="quantity-down">–</button>
                                <div class="cart-item-quantity-inner">
                                    <input data-id="<?= $item['ID']?>" class="cart-item-quantity-input" type="number" value="<?=intval($item['QUANTITY'])?>"/>
                                    <label class="field-title-text">шт</label>
                                </div>
                                <button data-id="<?= $item['ID']?>" class="quantity-up">+</button>
                            </div>
                        </td>
                        <td class="cart-item-total-price">
                            <div class="cart-item-total-price-container">
                                <p class="field-value-text item-total-price" class=""><?= intval($item['TOTAL_PRICE']) ?></p>
                                <p>&nbsp;₽</p>
                            </div>
                        </td>
                        <td class="cart-item-remove">
                            <button class="cart-item-remove-button" data-id="<?= $item['ID']?>">
                            <i class="fa fa-close"></i>
                            </button>
                        </td>   
                </tr>
            <?endforeach?>
            </tbody>
        </table>
    </div>
</div>
