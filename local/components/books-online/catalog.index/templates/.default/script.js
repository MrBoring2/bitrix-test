$(document).ready(function() {
    const $loadMoreBtn = $('#load-more');
    $loadMoreBtn.show();
    if (!$loadMoreBtn.length) return;

    $loadMoreBtn.on('click', function() {
        const $btn = $(this);
        const page = parseInt($btn.data('page')) + 1;
        const iblockId = $btn.data('iblock-id');
        const count = $btn.data('count');
        const sectionCode = window.currentSectionCode || '';
         console.log('секция')
              console.log(sectionCode)
         $btn.prop("disabled",true);
        const formData = new FormData();
        formData.append('AJAX', 'Y');
        formData.append('PAGE', page);
        console.log('dsadasdasdas')
     console.log(page)
    //if(window.currentFilter.PRICE_MIN)
        formData.append('PRICE_MIN', window.currentFilter.PRICE_MIN);
    //if(window.currentFilter.PRICE_MAX)
        formData.append('PRICE_MAX', window.currentFilter.PRICE_MAX);   
    //if(window.currentFilter.AUTHOR)
        formData.append('AUTHOR', window.currentFilter.AUTHOR || '');
        formData.append('SECTION_CODE', sectionCode)
        formData.append('IBLOCK_ID', iblockId);
        formData.append('ELEMENT_COUNT', count);
        
        $.ajax({
           // url: window.location.href,
            url: '/local/components/books-online/catalog.index/ajax.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
          
            success: function(html) {
                const $temp = $('<div>').html(html);
                const $newItems = $temp.find('.catalog-top-item');
                console.log($newItems)
                if ($newItems.length === 0) {
                    $btn.hide();
                    return;
                }
                else{
                    $btn.show();
                }
                $btn.prop("disabled",false);
                $('#catalog-top-inner').append($newItems);
                $btn.data('page', page);
            },
    error: function(xhr, status, error) {
        console.log('AJAX error:', status, error);
        $btn.prop("disabled",false);
    }
        });
    });
});