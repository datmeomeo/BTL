// frontend/pages/login/login.ui.js

// === UI FUNCTIONS ===
const LoginUI = {
    els: {
        loginForm: document.querySelector('#login-form form'),
        registerForm: document.querySelector('#register-form form'),
        authTabs: document.querySelectorAll('.auth-tab'),
        passwordLoginInput: document.getElementById('login-password'),
        passwordRegisterInput: document.getElementById('register-password'),
        passwordConfirmInput: document.getElementById('confirm-password')
    },

    /**
     * Chuyển đổi trạng thái hiển thị mật khẩu.
     * @param {HTMLInputElement} inputElement Phần tử input mật khẩu.
     */
    togglePasswordVisibility: (inputElement) => {
        const btn = inputElement.nextElementSibling;
        if (inputElement.type === "password") {
            inputElement.type = "text";
            if (btn) btn.textContent = "Ẩn";
        } else {
            inputElement.type = "password";
            if (btn) btn.textContent = "Hiện";
        }
    },

    /**
     * Cập nhật trạng thái nút submit (đang xử lý/ban đầu).
     * @param {HTMLButtonElement} button Nút submit.
     * @param {boolean} isLoading Trạng thái tải.
     * @param {string} originalText Văn bản gốc của nút.
     */
    setSubmitButtonState: (button, isLoading, originalText) => {
        if (button) {
            button.innerText = isLoading ? 'Đang xử lý...' : originalText;
            button.disabled = isLoading;
        }
    },

    /**
     * Gắn các event listener cho các tab chuyển đổi form.
     * @param {function} onTabChange Callback khi tab thay đổi.
     */
    attachTabListeners: () => {
        LoginUI.els.authTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                LoginUI.els.authTabs.forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));

                tab.classList.add('active');
                const formId = tab.dataset.tab + '-form';
                document.getElementById(formId)?.classList.add('active');
            });
        });
    },

    /**
     * Gắn event listener cho nút toggle password.
     * @param {string} inputId ID của input mật khẩu.
     */
    attachTogglePasswordListener: (inputId) => {
        const input = document.getElementById(inputId);
        const btn = input?.nextElementSibling;
        if (btn && input) {
            btn.addEventListener('click', () => LoginUI.togglePasswordVisibility(input));
        }
    },

    /**
     * Lấy dữ liệu từ form đăng nhập.
     * @returns {{email: string, password: string}} Dữ liệu form.
     */
    getLoginFormValues: () => {
        const emailInput = LoginUI.els.loginForm?.querySelector('input[type="text"]');
        const passwordInput = LoginUI.els.passwordLoginInput;
        return {
            email: emailInput ? emailInput.value : '',
            password: passwordInput ? passwordInput.value : ''
        };
    },

    /**
     * Lấy dữ liệu từ form đăng ký.
     * @returns {{fullName: string, email: string, password: string, confirmPassword: string}} Dữ liệu form.
     */
    getRegisterFormValues: () => {
        const fullNameInput = LoginUI.els.registerForm?.querySelector('input[placeholder="Nhập tên đăng nhập"]');
        const emailInput = LoginUI.els.registerForm?.querySelector('input[type="email"]');
        const passwordInput = LoginUI.els.passwordRegisterInput;
        const confirmPasswordInput = LoginUI.els.passwordConfirmInput;

        return {
            fullName: fullNameInput ? fullNameInput.value : '',
            email: emailInput ? emailInput.value : '',
            password: passwordInput ? passwordInput.value : '',
            confirmPassword: confirmPasswordInput ? confirmPasswordInput.value : ''
        };
    },

    /**
     * Reset form đăng ký.
     */
    resetRegisterForm: () => {
        LoginUI.els.registerForm?.reset();
    },

    /**
     * Chuyển sang tab đăng nhập.
     */
    switchToLoginTab: () => {
        document.querySelector('.auth-tab[data-tab="login"]')?.click();
    }
};

export default LoginUI;
