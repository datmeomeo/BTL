import { showToast } from "../utils/utils.js";

// frontend/services/cart-service.js
const CartService = {
    BASE_API: 'http://localhost/BTL/backend/api.php?route=cart',
    /**
     * Lấy dữ liệu giỏ hàng từ API.
     * @returns {Promise<object>} Dữ liệu giỏ hàng.
     */
    getCart: async () => {
        const response = await fetch(`${CartService.BASE_API}&action=get`);
        if (!response.ok) {
            throw new Error(`Lỗi mạng! Trạng thái: ${response.status}`);
        }
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(`API trả về lỗi: ${data.message || 'Không thể tải giỏ hàng'}`);
        }
        return data.data;
    },
    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     * @param {string} productId ID của sản phẩm sách.
     * @param {number} quantity Số lượng mới.
     * @returns {Promise<object>} Đã thêm vào giỏ hàng thành công.
     */
    addToCart: async (productId, quantity) => {
        if (quantity < 1) throw new Error('Số lượng không hợp lệ.');

        const response = await fetch(`${CartService.BASE_API}&action=add`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId: productId, quantity: quantity })
        });

        if (!response.ok) {
            throw new Error(`Lỗi mạng! Trạng thái: ${response.status}`);
        }
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(`API trả về lỗi: ${data.message || 'Không thể thêm vào giỏ hàng'}`);
        }
        else{
            showToast('Thêm vào giỏ hàng thành công!');
        }
    },

    /**
     * Cập nhật số lượng sản phẩm trong giỏ hàng.
     * @param {string} productId ID của sản phẩm sách.
     * @param {number} quantity Số lượng mới.
     * @returns {Promise<object>} Dữ liệu giỏ hàng đã cập nhật.
     */
    updateQuantity: async (productId, quantity) => {
        if (quantity < 1) throw new Error('Số lượng không hợp lệ.');

        const response = await fetch(`${CartService.BASE_API}&action=update`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId: productId, quantity: quantity })
        });
        if (!response.ok) {
            throw new Error(`Lỗi mạng! Trạng thái: ${response.status}`);
        }
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(`API trả về lỗi: ${data.message || 'Không thể cập nhật giỏ hàng'}`);
        }
        return data.data;
    },

    /**
     * Xóa sản phẩm khỏi giỏ hàng.
     * @param {string} productId ID của sản phẩm cần xóa.
     * @returns {Promise<object>} Dữ liệu giỏ hàng đã cập nhật.
     */
    removeItem: async (productId) => {
        const response = await fetch(`${CartService.BASE_API}&action=remove`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ productId: productId })
        });
        if (!response.ok) {
            throw new Error(`Lỗi mạng! Trạng thái: ${response.status}`);
        }
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(`API trả về lỗi: ${data.message || 'Không thể xóa sản phẩm khỏi giỏ hàng'}`);
        }
        return data.data;
    }
};

export default CartService;
