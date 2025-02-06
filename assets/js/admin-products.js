$(document).ready(() => {
    const table = $('#product-table').DataTable({
        responsive: true,
        ajax: {
            url: '/Projects/OnlineShoeStore/views/admin/products/product_list.php',
            dataSrc: (json) => {
                if (json.Result === 'OK') {
                    showToast('success', 'Products loaded successfully!');
                    return json.Records;
                } else {
                    showToast('warning', 'No products found!');
                    return [];
                }
            }
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'price' },
            { data: 'stock' },
            { data: 'description' },
            {
                data: 'image',
                render: (data, type, row) => `
                    <div class="d-flex justify-content-center">
                        ${data ? `<img src="/Projects/OnlineShoeStore/uploads/${data}" alt="${row.name} image" width="50" loading="lazy">` : 'No Image'}
                    </div>
                `
            },            
            {
                data: null,
                render: (data) => `
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm edit-btn" data-id="${data.id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-btn" data-id="${data.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `
            }            
        ]
    });

    // Event delegation for edit and delete buttons
    $('#product-table').on('click', '.edit-btn', function () {
        const productId = $(this).data('id');
        fetchProductDetails(productId);
    });

    $('#product-table').on('click', '.delete-btn', function () {
        const productId = $(this).data('id');
        $('#deleteConfirmationModal').modal('show');
        $('#confirmDelete').data('id', productId);
    });

    $('#confirmDelete').on('click', function () {
        const productId = $(this).data('id');
        deleteProduct(productId);
    });

    $('#editProductForm').on('submit', function (e) {
        e.preventDefault();
        updateProduct(new FormData(this));
    });

    const fetchProductDetails = (productId) => {
        $.ajax({
            url: "/Projects/OnlineShoeStore/views/admin/products/get_product.php",
            type: "GET",
            data: { id: productId },
            dataType: "json",
            success: (response) => {
                if (response.Result === "OK") {
                    const product = response.Product;
                    $('#editProductId').val(product.id);
                    $('#editProductName').val(product.name);
                    $('#editProductPrice').val(product.price);
                    $('#editProductStock').val(product.stock);
                    $('#editProductDescription').val(product.description);
                    $('#editProductModal').modal('show');
                } else {
                    showToast('error', response.Message);
                }
            },
            error: (xhr, status, error) => {
                console.error("Error:", error);
                showToast('error', 'Failed to fetch product details.');
            }
        });
    };

    const deleteProduct = (productId) => {
        $.ajax({
            url: "/Projects/OnlineShoeStore/views/admin/products/delete_product.php",
            type: "POST",
            data: { id: productId },
            dataType: "json",
            success: (response) => {
                if (response.Result === "OK") {
                    showToast('success', 'Product deleted successfully!');
                    table.ajax.reload();
                } else {
                    showToast('error', response.Message);
                }
            },
            error: (xhr, status, error) => {
                console.error("Error:", error);
                showToast('error', 'Failed to delete product.');
            }
        });
    };

    const updateProduct = (formData) => {
        $.ajax({
            url: '/Projects/OnlineShoeStore/views/admin/products/update_product.php',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: (response) => {
                if (response.Result === 'OK') {
                    showToast('success', 'Product updated successfully!');
                    $('#editProductModal').modal('hide');
                    table.ajax.reload();
                } else {
                    showToast('error', response.Message);
                }
            },
            error: (xhr, status, error) => {
                console.error("Error:", error);
                showToast('error', 'Failed to update product.');
            }
        });
    };

    const showToast = (type, message) => {
        const toastId = `#${type}-toast`;
        const messageId = `#${type}-toast-message`;
        $(messageId).text(message);
        $(toastId).removeClass('d-none').toast('show');
    };
});