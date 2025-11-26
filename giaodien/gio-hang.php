<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Hàng - Fahasa</title>
    <link rel="stylesheet" href="../bootstrap-5.0.2-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/main.css">
    <style>
        .cart-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .cart-header {
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .cart-item-img {
            width: 80px;
            height: 120px;
            object-fit: cover;
        }
        .quantity-control {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            padding: 6px 10px;
            border: 1px solid #d1d5db;       
            border-radius: 999px;             
            background: #fff;
        }
        .quantity-control button {
            border: none;
            background: none;
            font-size: 20px;
            line-height: 1;
            color: #9ca3af;                   
            cursor: pointer;
            padding: 0 4px;
        }
        .quantity-control input {
            width: 40px;
            height: 30px;
            text-align: center;
            border: 1px solid #ddd;
        }
        .qty-value {
            min-width: 16px;
            text-align: center;
            font-size: 16px;
            color: #4b4d51ff;
        }
        .cart-summary {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .btn-checkout {
            background: #C92127;
            color: white;
            width: 100%;
            padding: 10px;
            border: none;
            font-weight: bold;
            text-transform: uppercase;
        }
        .btn-checkout:hover {
            background: #a01a1f;
            color: white;
        }
    </style>
    <script src="../bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js"></script>
    <script src="../help/tool-menu.js" defer></script>
    <script src="../help/tool-giohang.js" defer></script>
</head>

<body>
    <?php include '../com/header.php'; ?>

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

    <?php include '../com/footer.php'; ?>
</body>

</html>
