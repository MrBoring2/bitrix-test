
$(document).ready(function() {
    function saveFilter(){
            const filterForm = $('#catalog-filter-form');
            window.currentFilter = {};

            filterForm.find('input[name]').each(function () {
            const $input = $(this);
            const name = $input.attr('name');
            const value = $input.val();

            if (value !== '') {
                window.currentFilter[name] = value;
            }
        });
    }

    function dropFilter(){
        window.currentFilter = {}
    }

    saveFilter();

    $('#catalog-filter-form').on('submit', function(e) {
        e.preventDefault();
        $('.catalog-top-container').fadeOut(200);
        $('#post_filter_button').prop('disabled', true)
        $('#drop_filter_button').prop('disabled', true)
        saveFilter();
        console.log(window.currentFilter)
        const form = $(this);
        const iblockId = $('#post_filter_button').data('iblock-id');
        const sectionCode = window.currentSectionCode || '';
        const formData = new FormData();
        Object.entries(window.currentFilter).forEach(([key, value]) => {
            formData.append(key, value);
        });
        formData.append('AJAX', 'Y');
        formData.append('IBLOCK_ID', iblockId)
        formData.append('SECTION_CODE', sectionCode)
        formData.append('PAGE', 1);

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
                $('#load-more').data('page', 1).prop('disabled', false).show();
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
                $('.catalog-top-container').fadeIn(200);
            },
            error: function(xhr, status, error) {
                console.error('Фильтр не применился:', status, error);
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
            }
        });  
    });

    $('#drop_filter_button').on('click', function(e){
        e.preventDefault(); 
        console.log('drop')
        $('.catalog-top-container').fadeOut(200);
        $('#post_filter_button').prop('disabled', true)
        $('#drop_filter_button').prop('disabled', true)
        dropFilter()
        const iblockId = $(this).data('iblock-id');
        const sectionCode = window.currentSectionCode || '';
        const formData = new FormData();
        formData.append('SECTION_CODE', sectionCode)
        formData.append('IBLOCK_ID', iblockId)
        const params = new URLSearchParams();
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
                $('#load-more').data('page', 1).prop('disabled', false).show();
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
                $('.catalog-top-container').fadeIn(200);
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
            error: function(xhr, status, error) {
                console.error('Фильтр не применился:', status, error);
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
            }
        });  
    })
});