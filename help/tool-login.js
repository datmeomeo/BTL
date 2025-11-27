// Toggle Password Visibility
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    const btn = input.nextElementSibling;

    if (input.type === "password") {
        input.type = "text";
        btn.textContent = "Ẩn";
    } else {
        input.type = "password";
        btn.textContent = "Hiện";
    }
}

// Switch Tabs
document.addEventListener('DOMContentLoaded', () => {
    const tabs = document.querySelectorAll('.auth-tab');
    const forms = document.querySelectorAll('.auth-form');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Remove active class
            tabs.forEach(t => t.classList.remove('active'));
            forms.forEach(f => f.classList.remove('active'));

            // Add active class
            tab.classList.add('active');
            const formId = tab.dataset.tab + '-form';
            document.getElementById(formId).classList.add('active');
        });
    });

    // Handle Login Form
    const loginForm = document.querySelector('#login-form form');
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    // Handle Register Form
    const registerForm = document.querySelector('#register-form form');
    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
});

// Toast Notification (Reused from book detail if available, or simple alert fallback)
function showToast(message, type = 'success') {
    // Check if showToast exists globally (from tool-bookhienthi.js or similar)
    // If not, create a simple one
    if (window.showToastGlobal) {
        window.showToastGlobal(message, type);
        return;
    }

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerText = message;
    Object.assign(toast.style, {
        position: 'fixed',
        top: '20px',
        right: '20px',
        zIndex: '9999',
        padding: '12px 24px',
        backgroundColor: type === 'success' ? '#4CAF50' : '#F44336',
        color: 'white',
        borderRadius: '4px',
        boxShadow: '0 2px 5px rgba(0,0,0,0.2)',
        opacity: '0',
        transition: 'opacity 0.3s ease-in-out'
    });
    document.body.appendChild(toast);
    requestAnimationFrame(() => toast.style.opacity = '1');
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

async function handleLogin(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = 'Đang xử lý...';
    btn.disabled = true;

    const email = e.target.querySelector('input[type="text"]').value; // Note: Input placeholder says Phone/Email but we use Email for now
    const password = document.getElementById('login-password').value;

    try {
        const res = await fetch('../backend/api.php?route=auth&action=login', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email, password })
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast('Đăng nhập thành công! Đang chuyển hướng...', 'success');
            setTimeout(() => {
                window.location.href = '../giaodien/trangchu.php';
            }, 1000);
        } else {
            showToast(data.message || 'Đăng nhập thất bại', 'error');
            btn.innerText = originalText;
            btn.disabled = false;
        }
    } catch (err) {
        console.error(err);
        showToast('Lỗi kết nối server', 'error');
        btn.innerText = originalText;
        btn.disabled = false;
    }
}

async function handleRegister(e) {
    e.preventDefault();
    const btn = e.target.querySelector('button[type="submit"]');
    const originalText = btn.innerText;
    btn.innerText = 'Đang xử lý...';
    btn.disabled = true;

    const fullName = e.target.querySelector('input[placeholder="Nhập tên đăng nhập"]').value; // Using username field as fullname for now or need to adjust form
    const email = e.target.querySelector('input[type="email"]').value;
    const password = document.getElementById('register-password').value;
    const confirmPassword = document.getElementById('confirm-password').value;

    if (password !== confirmPassword) {
        showToast('Mật khẩu nhập lại không khớp', 'error');
        btn.innerText = originalText;
        btn.disabled = false;
        return;
    }

    try {
        const res = await fetch('../backend/api.php?route=auth&action=register', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ fullName, email, password })
        });
        const data = await res.json();

        if (data.status === 'success') {
            showToast('Đăng ký thành công! Vui lòng đăng nhập.', 'success');
            // Switch to login tab
            document.querySelector('.auth-tab[data-tab="login"]').click();
            e.target.reset();
        } else {
            showToast(data.message || 'Đăng ký thất bại', 'error');
        }
    } catch (err) {
        console.error(err);
        showToast('Lỗi kết nối server', 'error');
    } finally {
        btn.innerText = originalText;
        btn.disabled = false;
    }
}