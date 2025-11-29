// frontend/services/auth-service.js

const AuthService = {
    BASE_API: 'http://localhost/BTL/backend/api.php?route=auth',

    login: async (email, password) => {
        const url = `${AuthService.BASE_API}&action=login`;
        const response = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(data.message || 'Đăng nhập thất bại');
        }
        return data;
    },

    register: async (fullName, email, password) => {
        const response = await fetch(`${AuthService.BASE_API}&action=register`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fullName, email, password })
        });
        const data = await response.json();
        if (data.status !== 'success') {
            throw new Error(data.message || 'Đăng ký thất bại');
        }
        return data;
    }
};

export default AuthService;
