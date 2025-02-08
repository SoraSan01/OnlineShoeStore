$(document).ready(function() {
    // Initialize DataTable
    const brandTable = $('#brand-table').DataTable({
        responsive: true,
        ajax: {
            url: '/Projects/OnlineShoeStore/views/admin/brands/get_brands.php', // Ensure this returns data
            dataSrc: function(json) {
                if (json.error) {
                    showToast('error', 'Failed to load brands.');
                    alert(json.error);
                    return [];
                }
                showToast('success', 'Brands loaded successfully!');
                return json;
            },
        },
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'updated_at' },
            { data: 'created_at' },
            {
                data: null,
                render: function(data, type, row) {
                    return `
                        <div class="d-flex justify-content-center gap-2">
                            <button class="btn btn-primary btn-sm edit-brand" data-id="${row.id}" data-name="${row.name}">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="btn btn-danger btn-sm delete-brand" data-id="${row.id}">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    `;
                }
            }
        ]
    });

    // Handle edit button click using event delegation
    $('#brand-table tbody').on('click', '.edit-brand', function() {
        const brandId = $(this).data('id');
        const brandName = $(this).data('name');

        $('#editBrandId').val(brandId);  // Populate the hidden input
        $('#editBrandName').val(brandName);  // Populate the text input
        $('#editBrandModal').modal('show');  // Show the modal
    });

    // Handle delete button click using event delegation
    $('#brand-table tbody').on('click', '.delete-brand', function() {
        const brandId = $(this).data('id');
        $('#confirmDelete').data('id', brandId);
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete action
    $('#confirmDelete').on('click', function() {
        const brandId = $(this).data('id');
        $.ajax({
            url: `/Projects/OnlineShoeStore/views/admin/brands/delete_brand.php`,
            type: 'POST',
            data: { id: brandId },
            success: function(response) {
                $('#deleteConfirmationModal').modal('hide');
                showToast('success', 'Brand deleted successfully!');
                brandTable.ajax.reload();
            },
            error: function() {
                alert('Error deleting brand. Please try again.');
            }
        });
    });

    // Handle edit form submission
    $('#editBrandForm').on('submit', function(e) {
        e.preventDefault();
        $.ajax({
            url: '/Projects/OnlineShoeStore/views/admin/brands/update_brand.php',
            type: 'POST',
            data: $(this).serialize(),
            // After updating a brand
success: function(response) {
    $('#editBrandModal').modal('hide');
    showToast('success', 'Brand updated successfully!');
    brandTable.ajax.reload();
},
            error: function() {
                alert('Error updating brand. Please try again.');
            }
        });
    });

    const showToast = (type, message) => {
        const toastId = `#${type}-toast`;
        const messageId = `#${type}-toast-message`;
        $(messageId).text(message);
        $(toastId).removeClass('d-none').toast('show');
    };
});
