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
        const data = await AuthService.login(email, password);
        if (!data) return;
        showToast('Đăng nhập thành công! Đang chuyển hướng...', 'success');
        setTimeout(() => {
            window.location.href = 'index.php'; // Điều hướng đến trang chủ
        }, 1000);
    } catch (error) {
        console.error("Lỗi đăng nhập:", error);
        showToast(error.message || 'Lỗi kết nối server', 'error');
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
