// frontend/assets/js/utils.js

/**
 * Escapes HTML special characters in a string to prevent XSS attacks.
 * @param {string} str The string to escape.
 * @returns {string} The escaped string.
 */
const escapeHTML = (str) => {
    if (typeof str !== 'string') return '';
    const div = document.createElement('div');
    div.textContent = str;
    return div.innerHTML;
};

/**
 * Formats a number as Vietnamese currency (VND).
 * @param {number} amount The number to format.
 * @returns {string} The formatted currency string (e.g., "100.000đ").
 */
const formatCurrency = (amount) => {
    if (typeof amount !== 'number' || isNaN(amount)) return '0đ';
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount).replace('₫', 'đ');
};
