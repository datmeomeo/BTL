// frontend/pages/cart/cart.ui.js
import { escapeHTML, formatCurrency } from '../../utils/utils.js';

const CartUI = {
    els: {
        cartItemsBody: document.getElementById('cart-items-body'),
        cartTotalElement: document.getElementById('cart-total'),
        cartCountElement: document.getElementById('cart-count')
    },
    /**
     * Hiển thị thông báo lỗi.
     * @param {string} message Thông báo lỗi.
     */
    showError: (message) => {
        if (CartUI.els.cartItemsBody) {
            CartUI.els.cartItemsBody.innerHTML = `<tr><td colspan="5" class="text-center py-5 text-danger">${escapeHTML(message)}</td></tr>`;
        }
    },

    /**
     * Render toàn bộ giỏ hàng lên giao diện.
     * @param {object} cartData Dữ liệu giỏ hàng.
     */
    renderCart: (cartData) => {
        const { cartItemsBody, cartTotalElement, cartCountElement } = CartUI.els;

        if (!cartItemsBody || !cartTotalElement || !cartCountElement) {
            console.error('Không tìm thấy các phần tử DOM cần thiết cho giỏ hàng.');
            return;
        }

        cartCountElement.textContent = cartData.totalQuantity;
        cartTotalElement.textContent = formatCurrency(cartData.totalPrice);

        if (cartData.items.length === 0) {
            cartItemsBody.innerHTML = '<tr><td colspan="5" class="text-center py-5">Giỏ hàng trống</td></tr>';
            return;
        }

        console.log(cartData.items);
        cartItemsBody.innerHTML = cartData.items.map(item => `
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <img src="${escapeHTML(item.image || '')}" class="cart-item-img me-3" alt="${escapeHTML(item.name)}">
                        <div>
                            <h6 class="mb-0"><a href="index.php?page=book&id=${item.productId}" class="text-decoration-none text-dark">${escapeHTML(item.name)}</a></h6>
                        </div>
                    </div>
                </td>
                <td style="text-align:center;">${formatCurrency(item.price)}</td>
                <td style="text-align:center;">
                    <div class="quantity-control">
                        <button class="btn-qty-minus" data-product-id="${item.productId}" data-current-qty="${item.quantity}">-</button>
                        <span class="qty-value">${item.quantity}</span>
                        <button class="btn-qty-plus" data-product-id="${item.productId}" data-current-qty="${item.quantity}">+</button>
                    </div>
                </td>
                <td style="text-align:center;" class="fw-bold text-danger">
                    ${formatCurrency(item.total)}
                </td>
                <td>
                    <div class="div-of-btn-remove-cart">
                        <button class="btn btn-sm text-danger btn-remove-item" data-product-id="${item.productId}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    },

};

export default CartUI;
