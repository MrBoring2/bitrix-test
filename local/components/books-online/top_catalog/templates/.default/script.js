$(document).ready(function() {
    const $loadMoreBtn = $('#load-more');
    if (!$loadMoreBtn.length) return;

    $loadMoreBtn.on('click', function() {
        const $btn = $(this);
        const page = parseInt($btn.data('page')) + 1;
        const iblockId = $btn.data('iblock-id');
        const count = $btn.data('count');
         $btn.prop("disabled",true);
        const formData = new FormData();
        formData.append('AJAX', 'Y');
        formData.append('PAGE', page);
        formData.append('IBLOCK_ID', iblockId);
        formData.append('ELEMENT_COUNT', count);

        $.ajax({
           // url: window.location.href,
            url: '/local/components/books-online/top_catalog/ajax.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
          
            success: function(html) {
                const $temp = $('<div>').html(html);
                const $newItems = $temp.find('.catalog-top-item');

                if ($newItems.length === 0) {
                    $btn.remove();
                    return;
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