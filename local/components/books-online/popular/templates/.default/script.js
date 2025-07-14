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
