<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>
<div class="slider-wrapper">
    <button id="slider-control-button-left">←</button>

    <div class="slider">
        <? foreach ($arResult['ITEMS'] as $item): ?>
            <div class="slider-item">
                <a class="slider-item-link">
                    <div class="slideritem--image">
                        <? if ($item['DETAIL_PICTURE_SRC']): ?>
                            <img src="<?= $item['DETAIL_PICTURE_SRC'] ?>" alt="<?= htmlspecialchars($item['NAME']) ?>">
                        <? endif ?>
                    </div>
                </a>
            </div>
        <? endforeach ?>
    </div>

    <button id="slider-control-button-right">→</button>
</div>