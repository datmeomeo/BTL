        // Change main image
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            
            // Remove active class from all thumbnails
            document.querySelectorAll('.thumbnail').forEach(thumb => {
                thumb.classList.remove('active');
            });
            
            // Add active class to clicked thumbnail
            element.classList.add('active');
        }

        // Quantity controls
        function increaseQty() {
            const input = document.getElementById('quantity');
            const max = parseInt(input.max);
            if (parseInt(input.value) < max) {
                input.value = parseInt(input.value) + 1;
            }
        }

        function decreaseQty() {
            const input = document.getElementById('quantity');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
            }
        }
        // Toggle description
        function toggleDescription() {
            const content = document.getElementById('description-content');
            const button = document.getElementById('toggleDescription');
            
            if (content.classList.contains('collapsed')) {
                content.classList.remove('collapsed');
                button.textContent = 'Thu gọn ▲';
            } else {
                content.classList.add('collapsed');
                button.textContent = 'Xem thêm ▼';
                // Scroll to top of description
                content.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }