// frontend/pages/cart/cart.js

import CartService from '../../services/cart-service.js';
import CartUI from './cart.ui.js';
// Assuming utils.js is loaded globally for escapeHTML and formatCurrency

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const cartData = await CartService.getCart();
        CartUI.renderCart(cartData);
    } catch (error) {
        console.error("Lỗi khi tải giỏ hàng:", error);
        CartUI.showError(error.message || 'Không thể tải giỏ hàng. Vui lòng thử lại.');
    } 

    // Gắn các sự kiện UI
    attachCartEventListeners();
});

/**
 * Xử lý cập nhật số lượng sản phẩm.
 * @param {string} productId ID sản phẩm.
 * @param {number} newQuantity Số lượng mới.
 */
async function handleUpdateQuantity(productId, newQuantity) {
    try {
        const updatedCart = await CartService.updateQuantity(productId, newQuantity);
        CartUI.renderCart(updatedCart);
    } catch (error) {
        console.error("Lỗi khi cập nhật số lượng:", error);
        if (error.message !== 'Hủy thao tác xóa.') { // Avoid showing error if user cancelled delete
            alert(error.message || 'Không thể cập nhật số lượng sản phẩm.');
        }
        await loadAndRenderCart(); // Tải lại giỏ hàng để đảm bảo trạng thái đúng
    } 
}

/**
 * Xử lý xóa sản phẩm khỏi giỏ hàng.
 * @param {string} productId ID sản phẩm.
 */
async function handleRemoveItem(productId) {
    try {
        const updatedCart = await CartService.removeItem(productId);
        CartUI.renderCart(updatedCart);
    } catch (error) {
        console.error("Lỗi khi xóa sản phẩm:", error);
        if (error.message !== 'Hủy thao tác xóa.') { // Avoid showing error if user cancelled delete
            alert(error.message || 'Không thể xóa sản phẩm khỏi giỏ hàng.');
        }
        await loadAndRenderCart(); // Tải lại giỏ hàng để đảm bảo trạng thái đúng
    } finally {
        CartUI.hideLoading();
    }
}

/**
 * Gắn các event listener cho các nút điều khiển số lượng và xóa sản phẩm.
 */
function attachCartEventListeners() {
    if (CartUI.els.cartItemsBody) {
        CartUI.els.cartItemsBody.addEventListener('click', async (event) => {
            const target = event.target;
            const productId = target.closest('[data-product-id]')?.dataset.productId; // Tìm productId từ nút hoặc thẻ cha

            if (!productId) return;

            const currentQtyElement = target.closest('.quantity-control')?.querySelector('.qty-value');
            let currentQty = parseInt(currentQtyElement?.textContent || '1');

            if (target.classList.contains('btn-qty-minus')) {
                await handleUpdateQuantity(productId, currentQty - 1);
            } else if (target.classList.contains('btn-qty-plus')) {
                await handleUpdateQuantity(productId, currentQty + 1);
            } else if (target.classList.contains('btn-remove-item')) {
                await handleRemoveItem(productId);
            }
        });
    }
}
