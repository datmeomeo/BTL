// FIXED: Wrap trong DOMContentLoaded để đảm bảo HTML đã load xong
document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.auth-tab');
    const forms = document.querySelectorAll('.auth-form');

    // FIXED: Kiểm tra xem có tìm thấy elements không
    if (tabs.length === 0) {
        console.error('Không tìm thấy .auth-tab elements!');
        return;
    }
    if (forms.length === 0) {
        console.error('Không tìm thấy .auth-form elements!');
        return;
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            const targetTab = tab.getAttribute('data-tab');
            
            // Remove active class from all tabs and forms
            tabs.forEach(t => t.classList.remove('active'));
            forms.forEach(f => f.classList.remove('active'));
            
            // Add active class to clicked tab
            tab.classList.add('active');
            
            // Show corresponding form
            if (targetTab === 'login') {
                document.getElementById('login-form').classList.add('active');
            } else {
                document.getElementById('register-form').classList.add('active');
            }
        });
    });
});

// Toggle password visibility - FIXED: Định nghĩa ngoài để có thể gọi từ onclick
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (!input) {
        console.error('Không tìm thấy input với id:', inputId);
        return;
    }
    
    const button = input.nextElementSibling;
    
    if (input.type === 'password') {
        input.type = 'text';
        button.textContent = 'Ẩn';
    } else {
        input.type = 'password';
        button.textContent = 'Hiện';
    }
}