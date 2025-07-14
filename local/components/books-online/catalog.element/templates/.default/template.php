<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="element-container">
    <?$APPLICATION->SetTitle("Карточка товара");?>
        <div id="images-full-size-wrapper">
            <div class="images-full-size-inner">
                <button id="close-full-size-button">
                    <i class="fa fa-close"></i>
                </button>
                <div id="displayed-image-full-size" class="active">
                    <img src="<?= htmlspecialchars($arResult['PHOTOS'][0]) ?>" alt="<?= htmlspecialchars($arResult['NAME']) ?>">
                </div>
                <?php if (!empty($arResult['PHOTOS'])): ?>
                    <div class="image-list-full-size">
                        <?php foreach ($arResult['PHOTOS'] as $src): ?>
                            <div class="image-item-full-size">
                                <div class="image-item-backgorund"></div>
                                <img src="<?= htmlspecialchars($src) ?>" alt="Фото товара" >
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <button id="button-back">
            <a href="/catalog">
                <i class="fa fa-chevron-left"></i>
                <p>Назад в каталог</p>
            </a>
        </button>
        <h2 class="book-name"><?= htmlspecialchars($arResult['NAME']) ?></h2>
        <p class="book-authors"><?= htmlspecialchars($arResult['AUTHORS_STRING']) ?></p>
        <div class="element-details-inner">
            <div class="element-details-left">
                <div class="element-details-preview-content">
                    <div class="details-images">
                        <?php if (!empty($arResult['PHOTOS'])): ?>
                            <div id="displayed-image" >
                                <img src="<?= htmlspecialchars($arResult['PHOTOS'][0]) ?>" alt="<?= htmlspecialchars($arResult['NAME']) ?>">
                            </div>      
                        <?php endif; ?>
                        <?php if (!empty($arResult['PHOTOS'])): ?>
                            <div class="details-images-list">
                            <?php foreach ($arResult['PHOTOS'] as $src): ?>
                            <div class="image-item">
                                <div class="image-item-backgorund"></div>
                                <img src="<?= htmlspecialchars($src) ?>" alt="Фото товара" >
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                <div class="element-details-preview">
                    <p class="element-descrtiption-preview">
                    <?= $arResult['~PROPERTY_ATT_DESCRIPTION_VALUE']['TEXT'] ?>
                    </p>
                <a href="#element-description" class="detail-button">Перейти к описанию</a>
                <ul class="preview-details">
                    <li class="detail-item">
                        <p class="detail-title">Количество страниц</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_PAGES_VALUE']?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Вес, г</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_WEIGHT_VALUE'] * 1000 ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Размер</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_SIZE_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Издательство</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_PUBLISHING_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Серия</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_SERIA_VALUE'] ?></p>
                    </li>
                </ul>
                <a href="#element-characteristics" class="detail-button">Перейти к характеристикам</a>
            </div>    
            </div>
            <div class="element-details-description">
                <h1>ОПИСАНИЕ И ХАРАКТЕРИСТИКИ</h1>
                <p id="element-description" class="details-description"><?= $arResult['~PROPERTY_ATT_DESCRIPTION_VALUE']['TEXT'] ?></p>
                <ul class="preview-details" id="element-characteristics">
                    <li class="detail-item">
                        <p class="detail-title">Тип обложки</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_BINDING_VALUE']?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Количество страниц</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_PAGES_VALUE']?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Вес, г</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_WEIGHT_VALUE'] * 1000 ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Размер</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_SIZE_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Издательство</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_PUBLISHING_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Серия</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_SERIA_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">Возрастное ограничение</p>
                        <p class="detail-value"><?= $arResult['PROPERTY_ATT_AGE_LIMIT_VALUE'] ?></p>
                    </li>
                    <li class="detail-item">
                        <p class="detail-title">ISBN</p>
                        <p class="detail-value"><?= $arResult['ISBN_STRING'] ?></p>
                    </li>
                </ul>
                <br/>
                <br/>
                <br/>
            </div> 
        </div>
        <div class="element-details-right">
            <p class="detail-price">
                <?= intval($arResult['CATALOG_PRICE_1']) ?> ₽
            </p>
                <button style="<?= $arResult['IS_IN_CART'] ? 'display:none' : '' ?>" id="buy-button" data-id="<?= htmlspecialchars($arResult["ID"]) ?>">Купить</button>
                <a style="<?= !$arResult['IS_IN_CART'] ? 'display:none' : '' ?>" id="to-cart-href" href="/cart">
                <button id="to-cart">В корзине</button>
                </a>
            </a>   
           
            <? if($arResult['CATALOG_QUANTITY'] == 1): ?>
                <div class="details-quantity-stock">
                    <i class="fa fa-check"></i>
                    <p>В наличии</p>
                </div>   
            <? else: ?>
                <div class="details-quantity-not-stock">
                    <i class="fa fa-close"></i>
                    <p>Нет в наличии</p>
                </div>
            <?endif?>
        </div>
    </div>
</div>