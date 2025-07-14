const cart = new Cart();

$(document).on('click', '.add-to-cart', async function(e) {
    console.log('dasdasd')
    const id = $(this).data('id'); 
    $(this).prop('disabled', true);
    e.preventDefault();
    const response = await cart.add(id, 1, ['NAME', 'DETAIL_PICTURE'])
       
    if(response.success){
        console.log(response)
        $(this).prop('disabled', false);
        const $card = $(this).closest('.catalog-top-item');
        $card.find('.add-to-cart').hide();
        $card.find('.to-cart-href').show();
    } else {
        console.error('Ошибка добавления в корзину:', response.error);
        $(this).prop('disabled', false);
    }

})

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
        $btn.prop("disabled",true);
        $('#post_filter_button').prop('disabled', true)
        $('#drop_filter_button').prop('disabled', true)
        const formData = new FormData();
        formData.append('AJAX', 'Y');
        formData.append('PAGE', page);
        Object.entries(window.currentFilter).forEach(([key, value]) => {
            formData.append(key, value);
        });
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
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
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
                $('#post_filter_button').prop('disabled', false)
                $('#drop_filter_button').prop('disabled', false)
            }
        });
    });

    
});