<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<form id="catalog-filter-form">
    <div>
        <label>Автор:</label>
        <input  id="filter-author" type="text" name="AUTHOR" value="<?= htmlspecialchars($_REQUEST['AUTHOR'] ?? '') ?>">
    </div>
    <div>
        <label>Цена от:</label>
        <input id="filter-price-min"  type="number" name="PRICE_MIN" value="<?= htmlspecialchars($_REQUEST['PRICE_MIN'] ?? '') ?>">
    </div>
    <div>
        <label>Цена до:</label>
        <input id="filter-price-max"type="number" name="PRICE_MAX" value="<?= htmlspecialchars($_REQUEST['PRICE_MAX'] ?? '') ?>">
    </div>
    <input type="hidden" name="section" value="<?= htmlspecialchars($_REQUEST['section'] ?? '') ?>">
    <button type="submit" id="post_filter_button" data-iblock-id="<?= htmlspecialchars($arParams["IBLOCK_ID"]) ?>">Применить фильтр</button>
        <?echo $arParams["IBLOCK_ID"]?>
</form>

<script>

$(document).ready(function() {

     window.currentFilter = {
        PRICE_MIN: $('#filter-price-min').val(),
        PRICE_MAX: $('#filter-price-max').val(),
        AUTHOR: $('#filter-author').val(),
    };

    $('#catalog-filter-form').on('submit', function(e) {
    e.preventDefault();
    window.currentFilter = {
        PRICE_MIN: $('#filter-price-min').val(),
        PRICE_MAX: $('#filter-price-max').val(),
        AUTHOR: $('#filter-author').val(),
    };
    console.log(window.currentFilter)
    const form = $(this);
    const iblockId = $('#post_filter_button').data('iblock-id');
    const sectionCode = window.currentSectionCode || '';
     console.log(sectionCode)
    const formData = new FormData(form[0]);
    formData.append('AJAX', 'Y');
    formData.append('IBLOCK_ID', iblockId)
    formData.append('SECTION_CODE', sectionCode)
    formData.append('PAGE', 1);
    // сохраняем фильтр в адресной строке (опционально)
    const params = new URLSearchParams(form.serialize());
    params.set('section', sectionCode)
    const currentUrl = window.location.pathname + '?' + params.toString();
    history.pushState({}, '', currentUrl);

    $.ajax({
        url: '/local/components/books-online/catalog.index/ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function(html) {
            const $temp = $('<div>').html(html);
            const $newItems = $temp.find('.catalog-top-item');
            $('#catalog-top-inner').empty();
            $('#catalog-top-inner').append($newItems);
            $('#load-more').data('page', 1).prop('disabled', false).show(); // сбрасываем пагинацию
            
        },
        error: function(xhr, status, error) {
            console.error('Фильтр не применился:', status, error);
        }
    });
});
});


</script>