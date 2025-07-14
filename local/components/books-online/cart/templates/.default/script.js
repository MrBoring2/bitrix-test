
const cart = new Cart();
let inputHandled = false;
async function updateInput(context, value, isInput = false){
    const id = parseInt(context.data('id'))
    context.prop('disabled', true);
    const response = await cart.update(id, value)
    if(response.success) {
        setInputValue(context, response.quantity, isInput)
    }
    inputHandled = false;
    context.prop('disabled', false);
    var price = parseInt(context.closest('.cart-item').find('.item-price').text());
    var totlaItemPriceText = context.closest('.cart-item').find('.item-total-price')
    animatePrice(totlaItemPriceText, price * value)
}

async function removeItem(context, id) {
    const response = await cart.remove(id)
    if(response.success) {
        removeCartItemDOM(context)
    }
}

function updateTotalPrice() {
    var totalPricetext = $('#total-price-text');
    let total = 0;
    $('.cart-item').each(function() {
        const price = parseInt($(this).find('.item-total-price').text());
        if (!isNaN(price)) {
            total += price;
        }
    });
    animatePrice(totalPricetext, total)
}

function removeCartItemDOM(context) {
    const item = context.closest('.cart-item');
    console.log(item)
    if(item) {
        item.fadeOut(200, function() {
            $(this).remove();
            checkEmpty();
        });
    }
}

function setInputValue(context, value, isInput = false){
    let input = null;
    if(!isInput)
        input = context.closest('.cart-item-quantity-container').find('.cart-item-quantity-input');
    else input = context;
    input.val(value)
}

function getInputValue(context, isInput = false){
    let input
    if(!isInput)
        input = context.closest('.cart-item-quantity-container').find('.cart-item-quantity-input');
    else input = context;
    return value = parseInt(input.val())
}

function checkEmpty(){
    console.log($('.cart-item').length)
    if($('.cart-item').length == 0){
        $('#cart-quantity').hide();
        $('.cart-items-list').hide();
        $('.cart-header').hide();
        $('.empty-container').show();
    }
    else {
        $('#cart-quantity').show();
        $('.cart-header').show();
        $('.cart-items-list').show();
        $('.empty-container').hide();
    }
}


function animatePrice(context, value){
    context.val(value)

    $({countNum: context.text()})
    .animate({countNum: value}, {
        duration: 500,
        easing: 'linear',
        step: function() {
            var nn = this.countNum;
            context.text(Math.floor(nn))
        },
        complete: function() {
            var nn = this.countNum;
            context.text(Math.floor(nn))
            updateTotalPrice()
        }
    });
}

$(document).ready(function() {
    $('.quantity-up').on('click', async function(){
        let value = getInputValue($(this)); 
        await updateInput($(this), ++value)
    });

    $('.quantity-down').on('click', async function(){
        let value = getInputValue($(this)); 
        if(value > 1){
            await updateInput($(this), --value)
        }
        else {
            await removeItem($(this), $(this).data('id'))
        }
    });

    $('.cart-item-quantity-input').on('keydown blur', function(e){
        if(e.type === 'keydown' && e.key !== 'Enter') return;
        if(inputHandled) return;
        inputHandled = true;
        let value = getInputValue($(this), true)
        if(value > 0 && value < 100){
            updateInput($(this), value, true)
        }
        else {
            value = 1;
            updateInput($(this), value, true)
        }
    });

    $('.cart-item-remove-button').on('click', function(){
        const id = parseInt($(this).data('id'))
        removeItem($(this), id)
       
    });
})