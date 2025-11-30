import BookService from '../../services/book-service.js';
import BookUI from './book-detail.ui.js';
import CartService from '../../services/cart-service.js';
import { showToast } from '../../utils/utils.js';

document.addEventListener('DOMContentLoaded', async () => {
    try {
        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');

        if (!bookId) {
            showToast('Không tìm thấy ID sách trong đường dẫn.', 'error');
            return;
        }

        BookService.increaseView(bookId);
        BookUI.showLoading();

        const bookData = await BookService.getDetail(bookId);
        BookUI.render(bookData);
    } catch (error) {
        showToast('Không thể tải dữ liệu. Vui lòng thử lại sau.', 'error');
    } finally {
        BookUI.hideLoading();
    }

    const addToCartButton = document.getElementById('btn-add-cart');

    addToCartButton.addEventListener('click', async () => {
        const quantityInput = document.getElementById('qty-carts');
        const quantity = parseInt(quantityInput.value, 10) || 1;

        const urlParams = new URLSearchParams(window.location.search);
        const bookId = urlParams.get('id');
        await CartService.addToCart(bookId, quantity);
    });

    const toggleDescription = document.getElementById('toggleDescription');
    toggleDescription.addEventListener('click', () => BookUI.toggleDescription());
    const thumbnails = document.getElementsByClassName('thumbnail');
    Array.from(thumbnails).forEach(thumb => {
        thumb.addEventListener('click', function() {
            BookUI.changeImage(this.querySelector('.thumbnail-image').src, this);
        });
    });
});