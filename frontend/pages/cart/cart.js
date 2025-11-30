// frontend/pages/cart/cart.js

import CartService from '../../services/cart-service.js';
import { showToast } from '../../utils/utils.js';
import CartUI from './cart.ui.js';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const cartData = await CartService.getCart();
        CartUI.renderCart(cartData);
        await addEventListener();
    } catch (error) {
        console.error("Lỗi khi tải giỏ hàng:", error);
        showToast(error.message || 'Không thể tải giỏ hàng. Vui lòng thử lại.', 'error');
    } 
});

async function addEventListener(){
    if (CartUI.els.cartItemsBody) {
        const dataProduct = CartUI.els.cartItemsBody.querySelectorAll('[data-product-id]');
        for (const element of dataProduct) {
            const productId = element.dataset.productId;
            if (element.classList.contains('btn-qty-minus')) {
                const currentQty = parseInt(element.dataset.currentQty || '1');
                const newQuantity = currentQty - 1;
                if (newQuantity < 1) {
                    element.addEventListener('click', async () => await handleRemoveItem(productId));
                    continue;
                }
                element.addEventListener('click', async () => await handleUpdateQuantity(productId, newQuantity));
            } else if (element.classList.contains('btn-qty-plus')) {
                const currentQty = parseInt(element.dataset.currentQty || '1');
                element.addEventListener('click', async () => await handleUpdateQuantity(productId, currentQty + 1));
            } else if (element.classList.contains('btn-remove-item')) {
                element.addEventListener('click', async () => await handleRemoveItem(productId));
            }
        }
    }
}

async function handleUpdateQuantity(productId, newQuantity) {
    try {
        const updatedCart = await CartService.updateQuantity(productId, newQuantity);
        CartUI.renderCart(updatedCart);
        await addEventListener();
        showToast('Cập nhật số lượng sản phẩm thành công!');
    } catch (error) {
        showToast(error.message || 'Lỗi khi cập nhật số lượng sản phẩm.', 'error');
        console.error("Lỗi khi cập nhật số lượng:", error);
    } 
}

async function handleRemoveItem(productId) {
    try {
        const updatedCart = await CartService.removeItem(productId);
        CartUI.renderCart(updatedCart);
        await addEventListener();
        showToast('Xóa sản phẩm khỏi giỏ hàng thành công!');
    } catch (error) {
        console.error("Lỗi khi xóa sản phẩm:", error);
    } 
}
