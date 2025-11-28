document.addEventListener('DOMContentLoaded', function () {
    loadCart();
});

function loadCart() {
    fetch('../backend/api.php?route=cart&action=get')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart(data.data);
            } else {
                console.error('Failed to load cart:', data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function renderCart(cartData) {
    const tbody = document.getElementById('cart-items-body');
    const totalEl = document.getElementById('cart-total');
    const countEl = document.getElementById('cart-count');

    countEl.textContent = cartData.totalQuantity;
    totalEl.textContent = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(cartData.totalPrice);

    if (cartData.items.length === 0) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center py-5">Giỏ hàng trống</td></tr>';
        return;
    }

    tbody.innerHTML = cartData.items.map(item => `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <img src="${item.image || '../img/no-image.jpg'}" class="cart-item-img me-3" alt="${item.name}">
                    <div>
                        <h6 class="mb-0"><a href="sach.php?id=${item.productId}" class="text-decoration-none text-dark">${item.name}</a></h6>
                    </div>
                </div>
            </td>
            <td style="text-align:center;">${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.price)}</td>
            <td style="text-align:center;">
                <div class="quantity-control">
                    <button onclick="updateQuantity(${item.productId}, ${item.quantity - 1})">-</button>
                    <span class="qty-value">${item.quantity}</span>
                    <button onclick="updateQuantity(${item.productId}, ${item.quantity + 1})">+</button>
                </div>
            </td>
            <td style="text-align:center;" class="fw-bold text-danger">
                ${new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.total)}
            </td>
            <td>
                <div class="div-of-btn-remove-cart">
                    <a onclick="cart.deleteItem('424379', event);" title="Remove Item" class="btn-remove-desktop-cart">
                        <i class="fa fa-trash-o" style="font-size:22px">
                        </i>
                    </a>
                </div>
                <button class="btn btn-sm text-danger" onclick="removeItem(${item.productId})">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"></path><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"></path><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"></path><line x1="10" x2="10" y1="11" y2="17"></line><line x1="14" x2="14" y1="11" y2="17"></line></svg>
                </button>
            </td>
        </tr>
    `).join('');
}

function updateQuantity(productId, quantity) {
    quantity = parseInt(quantity);
    if (quantity < 1) return; // Or confirm remove

    fetch('../backend/api.php?route=cart&action=update', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId: productId, quantity: quantity })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart(data.data);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}

function removeItem(productId) {
    if (!confirm('Bạn có chắc muốn xóa sản phẩm này?')) return;

    fetch('../backend/api.php?route=cart&action=remove', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ productId: productId })
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderCart(data.data);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error('Error:', error));
}
