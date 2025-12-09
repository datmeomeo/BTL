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
     * Hiển thị Modal thông báo đăng nhập thành công (Dùng chung cho Admin và Khách)
     * @param {string} title Tiêu đề modal (Ví dụ: Xin chào Admin)
     * @param {string} message Nội dung thông báo
     * @param {string} buttonText Chữ hiển thị trên nút (Ví dụ: Về trang chủ)
     * @param {Function} callback Hàm sẽ chạy khi người dùng bấm nút
     */
    showSuccessModal: (title, message, buttonText, callback) => {
        // Xóa modal cũ nếu có để tránh trùng lặp
        const existingModal = document.getElementById('loginSuccessModal');
        if (existingModal) existingModal.remove();

        // Tạo HTML cho modal
        const modalHtml = `
            <div id="loginSuccessModal" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center; z-index: 9999;">
                <div style="background: white; padding: 30px; border-radius: 12px; text-align: center; width: 350px; box-shadow: 0 5px 15px rgba(0,0,0,0.3); animation: fadeIn 0.3s;">
                    <h3 style="margin-top: 0; color: #333; font-size: 20px;">${title}</h3>
                    
                    <div style="margin: 20px auto; width: 60px; height: 60px; background: #4CAF50; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                        <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                    
                    <p style="font-size: 16px; color: #555; margin-bottom: 25px;">${message}</p>
                    
                    <button id="btnBackHome" style="background: #C92127; color: white; border: none; padding: 10px 0; width: 100%; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 16px;">
                        ${buttonText}
                    </button>
                </div>
            </div>
        `;

        // Chèn vào body
        document.body.insertAdjacentHTML('beforeend', modalHtml);

        // Xử lý sự kiện click nút
        document.getElementById('btnBackHome').addEventListener('click', () => {
            document.getElementById('loginSuccessModal').remove(); // Xóa modal
            if (callback && typeof callback === 'function') {
                callback(); // Chuyển trang
            }
        });
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
