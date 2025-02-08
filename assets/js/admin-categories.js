$(document).ready(function() {
    // Initialize DataTable
    const categoryTable = $('#category-table').DataTable({
        responsive: true,
        ajax: {
            url: '/Projects/OnlineShoeStore/views/admin/categories/get_categories.php',
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
            { data: 'description' },
            { data: 'updated_at' },
            { data: 'created_at' },
            {
                data: null,
                render: (data, type, row) => `
                    <div class="d-flex justify-content-center">
                        <button class="btn btn-primary btn-sm edit-category" data-id="${row.id}">
                            <i class="fa-solid fa-pen-to-square"></i>
                        </button>
                        <button class="btn btn-danger btn-sm delete-category" data-id="${row.id}">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </div>
                `
            }
        ]
    });

    // Handle category edit
    $('#category-table').on('click', '.edit-category', async function() {
        const categoryId = $(this).data('id');
        
        try {
            const response = await fetch(`/Projects/OnlineShoeStore/views/admin/categories/get_categories.php?id=${categoryId}`);
            const data = await response.json();

            if (data.error) {
                showToast('error', data.error);
            } else {
                $('#editCategoryId').val(data.id);
                $('#editCategoryName').val(data.name);
                $('#editCategoryDescription').val(data.description);
                $('#editCategoryModal').modal('show');
            }
        } catch (error) {
            showToast('error', 'Error fetching category data.');
        }
    });

    // Handle category deletion
    $('#category-table').on('click', '.delete-category', function() {
        $('#confirmDelete').data('id', $(this).data('id'));
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm deletion
    $('#confirmDelete').on('click', async function() {
        const categoryId = $(this).data('id');

        try {
            const response = await fetch(`/Projects/OnlineShoeStore/views/admin/categories/delete_categories.php?id=${categoryId}`);
            if (!response.ok) throw new Error();

            $('#deleteConfirmationModal').modal('hide');
            showToast('success', 'Category deleted successfully!');
            categoryTable.ajax.reload();
        } catch (error) {
            showToast('error', 'Error deleting category.');
        }
    });

    // Submit edit form
    $('#editCategoryForm').on('submit', async function(e) {
        e.preventDefault();

        try {
            const response = await fetch('/Projects/OnlineShoeStore/views/admin/categories/update_categories.php', {
                method: 'POST',
                body: new URLSearchParams(new FormData(this))
            });

            if (!response.ok) throw new Error();

            $('#editCategoryModal').modal('hide');
            showToast('success', 'Category updated successfully!');
            categoryTable.ajax.reload();
        } catch (error) {
            showToast('error', 'Error updating category.');
        }
    });

    // Toast Notification Function
    const showToast = (type, message) => {
        const toastId = `#${type}-toast`;
        const messageId = `#${type}-toast-message`;
        $(messageId).text(message);
        $(toastId).removeClass('d-none').toast('show');
    };
});
