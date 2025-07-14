
$(document).ready(function () {
const urlParams = new URLSearchParams(window.location.search);
const sectionCode = urlParams.get('section');
let newTitle = $('#section-meta').data('title') || 'Каталог';
document.title = 'Книги-Онлайн — Каталог — ' + newTitle;
if (sectionCode) {
    window.currentSectionCode = sectionCode;
}


function loadSection(iblockId, sectionCode, count) {

    $('.catalog-top-container').fadeOut(200);
    $('.catalog-sections').fadeOut(200);
    $('#post_filter_button').prop('disabled', true)
    $('#drop_filter_button').prop('disabled', true)
    window.currentSectionCode = sectionCode;
    sessionStorage.setItem('catalog_section_back', sectionCode);
    const url = sectionCode ? '?section=' + sectionCode : '?';
    history.pushState({ section: sectionCode, iblockId: iblockId, count: count }, '', url);
    const formData = new FormData();
    formData.append('SECTION_CODE', sectionCode);
    formData.append('IBLOCK_ID', iblockId)
    
    formData.append('ELEMENT_COUNT', count)
    formData.append('AJAX', 'Y');
    $('#load-more')
                .data('page', 1)
                .prop('disabled', false)
                .show(); 

    $.ajax({
        url: '/local/components/books-online/catalog.index/ajax.php',
        type: 'POST',
        data: formData,
        contentType: false,
        processData: false,
        success: function (html) {
           
            $('#catalog-top-inner').empty();
            const $temp = $('<div>').html(html);
            const $newItems = $temp.find('.catalog-top-item');
            $('#catalog-top-inner').append($newItems);
            $('.catalog-top-container').fadeIn(200)
            $('#post_filter_button').prop('disabled', false)
            $('#drop_filter_button').prop('disabled', false)
        },
        error: function (xhr, status, error) {
            console.error('Ошибка загрузки товаров:', status, error);
            $('#post_filter_button').prop('disabled', false)
            $('#drop_filter_button').prop('disabled', false)
        }
    });

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
            
            $('.catalog-sections').html(html).fadeIn(200);
            const $form = $('#catalog-filter-form');
            if ($form.length) {
            $form.find('input').each(function () {
                if ($(this).is(':checkbox') || $(this).is(':radio')) {
                    $(this).prop('checked', false);
                } else {
                    $(this).val('');
                }
            });
            newTitle = $('#section-meta').data('title') || 'Каталог';
            document.title = 'Книги-Онлайн — Каталог — ' + newTitle
        }
        },
        error: function (xhr, status, error) {
            console.error('Ошибка загрузки секций:', status, error);
        }
    });


}

$(document).on('click', '.ajax-section-link', function(e) {

    e.preventDefault();
    const sectionCode = $(this).data('section');
    const iblockId = $(this).data('iblock-id');
    const count = $(this).data('count');
    console.log(sectionCode)
    $('.ajax-section-link').addClass('disabled-link');
    loadSection(iblockId, sectionCode, count);
});

window.addEventListener('popstate', function(event) {
    const iblockId = (event.state && event.state.iblockId) || '';
    const sectionCode = (event.state && event.state.section) || '';
    const count = (event.state && event.state.count) || '';
    loadSection(iblockId, sectionCode, count);
});
});
