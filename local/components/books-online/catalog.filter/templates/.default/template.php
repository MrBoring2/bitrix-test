<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<p class="filter-title">Фильтр</p>
<form id="catalog-filter-form">
    <div class="filter-item">
        <label class="filter-item-title">Автор</label>
        <div class="search-filter">
            <i class="fa fa-search"></i>
            <input id="filter-author" class="filter-input" placeholder="Поиск по автору" type="text" name="AUTHOR" value="<?= htmlspecialchars($_REQUEST['AUTHOR'] ?? '') ?>">
        </div>
    </div>
    <div class="filter-item">
        <label class="filter-item-title">Стоимость, руб.</label>
        <div class="range-filter">
            <div class="range-filter-inner">
                <p class="range-filter-sub-text">От</p>
                <input id="filter-price-min" class="filter-range-input" type="number" name="PRICE_MIN" value="<?= htmlspecialchars($_REQUEST['PRICE_MIN'] ?? '') ?>">
            </div>
            <div class="range-filter-inner">
                <p class="range-filter-sub-text">До</p>
                <input id="filter-price-max" class="filter-range-input" type="number" name="PRICE_MAX" value="<?= htmlspecialchars($_REQUEST['PRICE_MAX'] ?? '') ?>">
            </div>
        </div>    
    </div>
    <div class="filter-item">
        <label class="filter-item-title">Год издания</label>
        <div class="range-filter">
            <div class="range-filter-inner">
                <p class="range-filter-sub-text">От</p>
                <input id="filter-year-min" class="filter-range-input" type="number" name="YEAR_MIN" value="<?= htmlspecialchars($_REQUEST['YEAR_MIN'] ?? '') ?>">
            </div>
            <div class="range-filter-inner">
                <p class="range-filter-sub-text">До</p>
                <input id="filter-year-max" class="filter-range-input" type="number" name="YEAR_MAX" value="<?= htmlspecialchars($_REQUEST['YEAR_MAX'] ?? '') ?>">
            </div>
            
        </div>    
    </div>
    <input type="hidden" name="section" value="<?= htmlspecialchars($_REQUEST['section'] ?? '') ?>">
    <button type="submit" id="post_filter_button" data-iblock-id="<?= htmlspecialchars($arParams["IBLOCK_ID"]) ?>">Применить фильтр</button>
    <button id="drop_filter_button" data-iblock-id="<?= htmlspecialchars($arParams["IBLOCK_ID"]) ?>">Сбросить фильтр</button>
</form>
