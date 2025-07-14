
$(document).ready(function() {
    function getCssVarInPx(varName) {
        const remValue = getComputedStyle(document.documentElement)
            .getPropertyValue(varName)
            .trim()
        return parseFloat(remValue) * parseFloat(getComputedStyle(document.documentElement).fontSize)
    }
    
    const cart = new Cart();

    const title = $(this).find('.book-name').text();
    document.title = 'Книги-Онлайн — Карточка товара — ' + title;



    $('#button-back').on('click', function(event) {
        const referrer = document.referrer;
        if (referrer && referrer.includes(window.location.hostname)) {
            event.preventDefault();
            history.back()
        }
    })

    $(document).on('keydown', function(e) {
        if (e.key === "Esc" || e.keyCode === 27) {
            $('#images-full-size-wrapper').removeClass('active');
            //$('#images-full-size-wrapper').addClass('hide');
        }
    });
    
    $('.image-item').on('click', function(event){
        const img = $(this).find('img');
        $('#displayed-image').find('img').attr('src', img.attr('src'))
    }) 
    $('.image-item-full-size').on('click', function(event){
        const img = $(this).find('img');
        $('#displayed-image-full-size').find('img').attr('src', img.attr('src'))
    }) 

    $('#displayed-image').on('click', function(event){
        //$('#images-full-size-wrapper').removeClass('hide');
        $('#images-full-size-wrapper').addClass('active');
    })

    $('#close-full-size-button').on('click', function(event){
        $('#images-full-size-wrapper').removeClass('active');
       // $('#images-full-size-wrapper').addClass('hide');
    })

    $('a[href^="#"').on('click', function(e) {
      
        e.preventDefault();
        const target = $(this.getAttribute('href'));
        
        if(target.length) {
            const headerHeight = getCssVarInPx('--header-heigth');
              console.log(headerHeight)
            $('html, body').animate({
                scrollTop: target.offset().top - headerHeight
            }, 200);
        }   
    })


    $('#buy-button').on('click', async function(e) {
        const id = $(this).data('id');
        $(this).prop('disabled', true);
        e.preventDefault();
        const response = await cart.add(id, 1, ['NAME', 'DETAIL_PICTURE'])
       
        if(response.success){
            console.log(response)
            $(this).prop('disabled', false);
            $('#to-cart-href').show();
            $('#buy-button').hide();
        } else {
            console.error('Ошибка добавления в корзину:', response.error);
            $(this).prop('disabled', false);
        }
        
        //const a = cart.get()
        //console.log(a)
    })
})