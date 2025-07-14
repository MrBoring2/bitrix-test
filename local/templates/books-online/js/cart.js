

class Cart {
    constructor(ajaxUrl = '/local/components/books-online/cart/ajax.php'){
        this.ajaxUrl = ajaxUrl;
    }
    async updateCartHeader() {
        const html = await $.get('/local/components/books-online/cart_mini/ajax.php');
        $('#header-cart-mini-container').html(html);
    }
    async add(itemId, quantity = 1, props = []){
        const response = await $.post(this.ajaxUrl, {
            action: 'add',
            id: itemId,
            quantity: quantity,
            props: props
        }, null, 'json')
        await this.updateCartHeader();
        return response;
    }

    async remove(positionId) {
        const response = await $.post(this.ajaxUrl, {
            action: 'remove',
            positionId: positionId,
        }, null, 'json')
        console.log(response)
        await this.updateCartHeader();
        return response;
    }

    async update(productId, quantity){
        const response = await $.post(this.ajaxUrl, {
            action: 'update',
            productId: productId,
            quantity: quantity
        }, null, 'json')
        await this.updateCartHeader();
        return response;
    }

    async get() {
        const response = await $.post(this.ajaxUrl, {
            action: 'get'
        }, null, 'json')
        await this.updateCartHeader();
        return response;
    }
    
}