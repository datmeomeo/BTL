// frontend/pages/login/login.js

import AuthService from '../../services/auth-service.js';
import LoginUI from './login.ui.js';
import { showToast } from '../../utils/utils.js';

document.addEventListener('DOMContentLoaded', () => {
    // Gắn các sự kiện UI
    LoginUI.attachTabListeners();
    LoginUI.attachTogglePasswordListener('login-password');
    LoginUI.attachTogglePasswordListener('register-password');
    LoginUI.attachTogglePasswordListener('confirm-password');

    // Gắn xử lý submit form đăng nhập
    if (LoginUI.els.loginForm) {
        LoginUI.els.loginForm.addEventListener('submit', handleLogin);
    }

    // Gắn xử lý submit form đăng ký
    if (LoginUI.els.registerForm) {
        LoginUI.els.registerForm.addEventListener('submit', handleRegister);
    }
});

/**
 * Xử lý sự kiện đăng nhập.
 * @param {Event} e Sự kiện submit form.
 */
async function handleLogin(e) {
    e.preventDefault();
    const submitButton = e.submitter;
    const originalText = submitButton ? submitButton.innerText : 'Đăng nhập';

    if (submitButton) LoginUI.setSubmitButtonState(submitButton, true, originalText);

    const { email, password } = LoginUI.getLoginFormValues();

    try {
        const response = await AuthService.login(email, password);
        
        // Lấy dữ liệu user từ response (cấu trúc backend trả về data -> user)
        // Dựa vào AuthController.php của bạn: data trả về nằm trong response.data
        const user = response.data; 
        const role = user.role;

        // Lưu thông tin user vào bộ nhớ trình duyệt để Header đọc được
        const userInfo = {
            name: user.fullName || "Khách hàng",
            role: user.role
        };
        localStorage.setItem('user_info', JSON.stringify(userInfo));

        // === XỬ LÝ HIỂN THỊ MODAL THEO VAI TRÒ ===
        
        if (role === 'admin' || role === 1) {
            // TRƯỜNG HỢP: ADMIN
            LoginUI.showSuccessModal(
                'Xin chào Admin!',              // Tiêu đề
                'Đăng nhập quyền quản trị thành công.', // Nội dung
                'Vào trang quản trị',           // Nút bấm
                () => {                         // Callback chuyển trang
                    window.location.href = '/BTL/frontend/pages/Admin_MVC/index.php'; // Đường dẫn admin của bạn
                }
            );
        } else {
            // TRƯỜNG HỢP: KHÁCH HÀNG
            LoginUI.showSuccessModal(
                'Thông báo',                    // Tiêu đề
                `Xin chào ${user.fullName}!`,   // Nội dung
                'Về trang chủ',                 // Nút bấm
                () => {                         // Callback chuyển trang
                    window.location.href = 'index.php';
                }
            );
        }

    } catch (error) {
        console.error("Lỗi đăng nhập:", error);
        showToast(error.message || 'Sai email hoặc mật khẩu', 'error');
    } finally {
        if (submitButton) LoginUI.setSubmitButtonState(submitButton, false, originalText);
    }
}
/**
 * Xử lý sự kiện đăng ký.
 * @param {Event} e Sự kiện submit form.
 */
async function handleRegister(e) {
    e.preventDefault();
    const submitButton = e.submitter;
    const originalText = submitButton ? submitButton.innerText : 'Đăng ký';

    if (submitButton) LoginUI.setSubmitButtonState(submitButton, true, originalText);

    const { fullName, email, password, confirmPassword } = LoginUI.getRegisterFormValues();

    if (password !== confirmPassword) {
        showToast('Mật khẩu nhập lại không khớp', 'error');
        if (submitButton) LoginUI.setSubmitButtonState(submitButton, false, originalText);
        return;
    }

    try {
        const data = await AuthService.register(fullName, email, password);
        if (!data) return;
        showToast('Đăng ký thành công! Vui lòng đăng nhập.', 'success');
        LoginUI.resetRegisterForm();
        LoginUI.switchToLoginTab();
    } catch (error) {
        console.error("Lỗi đăng ký:", error);
        showToast(error.message || 'Lỗi kết nối server', 'error');
    } finally {
        if (submitButton) LoginUI.setSubmitButtonState(submitButton, false, originalText);
    }
}
