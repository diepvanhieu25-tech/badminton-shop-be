<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Khai báo các Element
        const tbody = document.getElementById('variants-body');
        const btnAdd = document.getElementById('btn-add-variant');
        const productSkuInput = document.getElementById('product-sku');
        const template = document.getElementById('variant-row-template');

        // --- CÁC HÀM XỬ LÝ (HELPER FUNCTIONS) ---

        // A. Cập nhật SKU tự động
        function syncVariantSku() {
            if (!productSkuInput) return;
            const baseSku = productSkuInput.value.trim() || 'SKU';
            tbody.querySelectorAll('.variant-row').forEach((row, index) => {
                const skuInput = row.querySelector('.variant-sku');
                if (skuInput) skuInput.value = `${baseSku}-V${index + 1}`;
            });
        }

        // B. Build JSON từ Size/Color
        function buildAttributes(row) {
            const size = row.querySelector('.variant-size')?.value;
            const color = row.querySelector('.variant-color')?.value;
            const jsonInput = row.querySelector('.variant-attributes-json');

            const obj = {};
            if (size) obj.size = size;
            if (color) obj.color = color;

            // Xóa rỗng nếu không có dữ liệu
            if (!size && !color) {
                jsonInput.value = '';
            } else {
                jsonInput.value = JSON.stringify(obj);
            }
        }

        // C. Xử lý Preview ảnh (Hàm mới thêm)
        function setupVariantImagePreview(row) {
            const imgInput = row.querySelector('.variant-img-input');
            const imgPreview = row.querySelector('.variant-img-preview');
            const imgPlaceholder = row.querySelector('.variant-img-placeholder');

            if (!imgInput || !imgPreview) return;

            imgInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        // Hiển thị ảnh
                        imgPreview.src = e.target.result;
                        imgPreview.classList.remove('hidden');
                        
                        // Ẩn placeholder
                        if (imgPlaceholder) imgPlaceholder.classList.add('hidden');
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- HÀM GẮN SỰ KIỆN CHO 1 DÒNG (CORE) ---
        function attachRowEvents(row) {
            // 1. Sự kiện nút Xóa
            row.querySelector('.btn-remove')?.addEventListener('click', () => {
                row.remove();
                syncVariantSku(); // Cập nhật lại số thứ tự SKU sau khi xóa
            });

            // 2. Sự kiện nhập Size/Color -> update JSON
            row.querySelectorAll('.variant-size, .variant-color').forEach(input => {
                input.addEventListener('input', () => buildAttributes(row));
            });

            // 3. Sự kiện Preview ảnh (GỌI HÀM C ĐÃ VIẾT Ở TRÊN)
            setupVariantImagePreview(row);
        }

        // --- MAIN LOGIC (CHẠY KHI LOAD TRANG) ---

        // 1. Khởi tạo sự kiện cho các dòng có sẵn (khi Edit)
        if (tbody) {
            tbody.querySelectorAll('.variant-row').forEach(row => {
                attachRowEvents(row);
            });
        }

        // 2. Sự kiện click nút "Thêm biến thể"
        if (btnAdd && template) {
            btnAdd.addEventListener('click', () => {
                // Tính index dựa trên số dòng hiện tại
                const index = tbody.querySelectorAll('.variant-row').length;

                // Clone template
                const clone = template.content.cloneNode(true);
                const row = clone.querySelector('tr');

                // Thay thế placeholder INDEX bằng số thứ tự thực
                row.innerHTML = row.innerHTML.replace(/INDEX/g, index);

                // Gắn sự kiện cho dòng mới
                attachRowEvents(row);

                // Thêm vào bảng
                tbody.appendChild(row);

                // Cập nhật lại SKU
                syncVariantSku();
            });
        }

        // 3. Lắng nghe thay đổi SKU cha để update SKU con
        if (productSkuInput) {
            productSkuInput.addEventListener('input', syncVariantSku);
        }
    });
</script>