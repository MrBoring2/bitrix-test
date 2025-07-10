$(document).ready(function () {
const urlParams = new URLSearchParams(window.location.search);
const sectionCode = urlParams.get('section');

if (sectionCode) {
    window.currentSectionCode = sectionCode; // Восстанавливаем глобально

}


function loadSection(iblockId, sectionCode, count) {
    // Обновляем URL
    
    window.currentSectionCode = sectionCode;
    const url = sectionCode ? '?section=' + sectionCode : '?';
    history.pushState({ section: sectionCode, iblockId: iblockId, count: count }, '', url);
    console.log(iblockId)
    const formData = new FormData();
    console.log('count')
    console.log(count)
    formData.append('SECTION_CODE', sectionCode);
    formData.append('IBLOCK_ID', iblockId)
    
    formData.append('ELEMENT_COUNT', count)
    formData.append('AJAX', 'Y');
    $('#load-more')
                .data('page', 1)
                .prop('disabled', false)
                .show(); // показываем кнопку обратно
    // Загрузка товаров (catalog.index/ajax.php)
    $.ajax({
        url: '/local/components/books-online/catalog.index/ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (html) {
            console.log('Товары загружены3124124124232132114124');
           // console.log(html)
            $('#catalog-top-inner').empty();
            const $temp = $('<div>').html(html);
            const $newItems = $temp.find('.catalog-top-item');
            $('#catalog-top-inner').append($newItems);
           
        },
        error: function (xhr, status, error) {
            console.error('Ошибка загрузки товаров:', status, error);
        }
    });

    // Загрузка секций
    const sectionData = new FormData();
    
    sectionData.append('IBLOCK_ID', iblockId)
    sectionData.append('ELEMENT_COUNT', count)
    sectionData.append('section', sectionCode);

    $.ajax({
        url: '/local/components/books-online/catalog.sections/ajax_sections.php',
        type: 'POST',
        data: sectionData,
        contentType: false,
        processData: false,
        success: function (html) {
            console.log('✅ Секции загружены');
            $('.section-list').html(html);
            const $form = $('#catalog-filter-form');
            if ($form.length) {
            $form.find('input').each(function () {
                if ($(this).is(':checkbox') || $(this).is(':radio')) {
                    $(this).prop('checked', false);
                } else {
                    $(this).val('');
                }
            });
        }
        },
        error: function (xhr, status, error) {
            console.error('❌ Ошибка загрузки секций:', status, error);
        }
    });


}

// Обработка кликов по секциям
$(document).on('click', '.ajax-section-link', function(e) {

    e.preventDefault();
    const sectionCode = $(this).data('section');
    const iblockId = $(this).data('iblock-id');
    const count = $(this).data('count');
    console.log(sectionCode)
    $('.ajax-section-link').addClass('disabled-link');
    loadSection(iblockId, sectionCode, count);
});

// Обработка кнопок "Назад/Вперёд"
window.addEventListener('popstate', function(event) {
    console.log(event.state)
    const iblockId = (event.state && event.state.iblockId) || '';
    const sectionCode = (event.state && event.state.section) || '';
    const count = (event.state && event.state.count) || '';
    loadSection(iblockId, sectionCode, count);
});
});
