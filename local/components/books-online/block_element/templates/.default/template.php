<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<div class="block-emelent-container">
    
    <div class="block-emelent-content">
        <p class="block-element-main-text"><?= $arResult['~PROPERTY_ATT_MAIN_TEXT_VALUE']['TEXT'] ?></p>
        <p class="block-element-detail-text"><?= $arResult['~PROPERTY_ATT_DETAIL_TEXT_VALUE']['TEXT'] ?></p>
    </div>
    <img class="background-image" src="<?=$arResult['DETAIL_PICTURE_SRC']?>"/>
    
</div>