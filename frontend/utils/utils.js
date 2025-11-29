/**
 * Chuyển đổi các ký tự đặc biệt thành HTML entities để ngăn chặn XSS.
 * Sử dụng Regex để tối ưu hiệu năng render danh sách lớn.
 * @param {string} str Chuỗi cần xử lý.
 * @returns {string} Chuỗi đã được escape.
 */
export const escapeHTML = (str) => {
    // Kiểm tra nếu không có dữ liệu thì trả về chuỗi rỗng ngay
    if (!str && str !== 0) return ''; 
    
    return String(str).replace(/[&<>"']/g, function(m) {
        return { 
            '&': '&amp;', 
            '<': '&lt;', 
            '>': '&gt;', 
            '"': '&quot;', 
            "'": '&#039;' 
        }[m];
    });
};

/**
 * Định dạng số thành tiền tệ Việt Nam (VND).
 * Có kiểm tra dữ liệu đầu vào an toàn.
 * @param {number|string} amount Số tiền.
 * @returns {string} Chuỗi tiền tệ (vd: "100.000đ").
 */
export const formatCurrency = (amount) => {
    // Chuyển đổi sang số (phòng trường hợp API trả về string "100000")
    const num = Number(amount);

    // Validate: Nếu không phải số hợp lệ thì trả về 0đ
    if (isNaN(num)) return '0đ';

    // Format và thay thế biểu tượng cho đẹp
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(num).replace('₫', 'đ');
};

export const createStarIconHTML = () => {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="#ffc107" stroke="none"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>';
};

/**
 * Hiển thị thông báo Toast.
 * @param {string} message Thông báo.
 * @param {'success'|'error'} type Loại thông báo.
 */
export const showToast = (message, type = 'success') => {
    // Ưu tiên sử dụng showToast toàn cục nếu có, nếu không thì dùng fallback
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
};