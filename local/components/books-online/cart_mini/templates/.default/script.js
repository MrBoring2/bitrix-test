$(document).ready(function() {
    const cart = new Cart();
    async function LoadCart() {
        const response = await cart.get();
        console.log(response)
    }

    //LoadCart();
});