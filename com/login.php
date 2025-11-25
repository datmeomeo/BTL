<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main class="auth-main">
        <div class="container">
            <div class="auth-container">
                <!-- Tabs -->
                <div class="auth-tabs">
                    <button class="auth-tab active" data-tab="login">Đăng nhập</button>
                    <button class="auth-tab" data-tab="register">Đăng ký</button>
                </div>

                <!-- Form Đăng nhập -->
                <div class="auth-form active" id="login-form">
                    <form>
                        <div class="form-group">
                            <label>Số điện thoại/Email</label>
                            <input type="text" class="form-control" placeholder="Nhập số điện thoại hoặc email">
                        </div>

                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="password-input">
                                <input type="password" class="form-control" placeholder="Nhập mật khẩu" id="login-password">
                                <button type="button" class="toggle-password" onclick="togglePassword('login-password')">Hiện</button>
                            </div>
                        </div>

                        <div class="form-options">
                            <a href="#" class="forgot-password">Quên mật khẩu?</a>
                        </div>

                        <button type="submit" class="btn-submit">Đăng nhập</button>
                    </form>
                </div>

                <!-- Form Đăng ký -->
                <div class="auth-form" id="register-form">
                    <form>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <input type="tel" class="form-control" placeholder="Nhập số điện thoại">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholder="Nhập Email">
                        </div>

                        <div class="form-group">
                            <label>Tên đăng nhập</label>
                            <input type="text" class="form-control" placeholder="Nhập tên đăng nhập">
                        </div>

                        <!-- FIXED: Tách thành 2 form-group riêng biệt -->
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="password-input">
                                <input type="password" class="form-control" placeholder="Nhập mật khẩu" id="register-password">
                                <button type="button" class="toggle-password" onclick="togglePassword('register-password')">Hiện</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Nhập lại Mật khẩu</label>
                            <div class="password-input">
                                <input type="password" class="form-control" placeholder="Nhập lại mật khẩu" id="confirm-password">
                                <button type="button" class="toggle-password" onclick="togglePassword('confirm-password')">Hiện</button>
                            </div>
                        </div>

                        <button type="submit" class="btn-submit">Đăng ký</button>

                        <div class="terms-text">
                            Bằng việc đăng ký, bạn đã đồng ý với Fahasa.com về 
                            <a href="#">Điều khoản dịch vụ</a> & 
                            <a href="#">Chính sách bảo mật</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</body>
</html>