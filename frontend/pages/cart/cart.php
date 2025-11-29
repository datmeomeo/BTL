<div class="cart-container">
    <div class="cart-header">
        <h2>Giỏ Hàng (<span id="cart-count">0</span> sản phẩm)</h2>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th style="width: 50%">Sản phẩm</th>
                            <th style="width: 15%; text-align: center;">Đơn giá</th>
                            <th style="width: 15%; text-align: center;">Số lượng</th>
                            <th style="width: 15%; text-align: center;">Thành tiền</th>
                            <th style="width: 5%"></th>
                        </tr>
                    </thead>
                    <tbody id="cart-items-body">
                        <!-- Items will be loaded here via JS -->
                        <tr>
                            <td colspan="5" class="text-center py-5">Đang tải giỏ hàng...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="cart-summary">
                <h4>Thành tiền</h4>
                <div class="d-flex justify-content-between mb-3">
                    <span>Tổng số tiền:</span>
                    <span class="fw-bold text-danger fs-4" id="cart-total">0đ</span>
                </div>
                <button class="btn btn-checkout" onclick="alert('Chức năng thanh toán đang phát triển')">Thanh toán</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts for Cart Page -->
<script type="module" src="./cart.js"></script>