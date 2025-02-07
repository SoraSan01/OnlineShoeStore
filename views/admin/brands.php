<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Brands</title>

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS & JS -->
    <link href="/Projects/OnlineShoeStore/assets/css/styles.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.4.0/css/responsive.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.0/js/dataTables.responsive.min.js"></script>

    <!-- Custom CSS -->
    <link href="/Projects/OnlineShoeStore/assets/css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/navbar.php"; ?>

    <div id="layoutSidenav">
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/sidebar.php"; ?>

        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Brands</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="/Projects/OnlineShoeStore/views/admin/Products.php">Products</a></li>
                        <li class="breadcrumb-item active">Brands</li>
                    </ol>

                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/toastNotification.php"; ?>

                    <!-- Brand Table -->
                    <div id="BrandTable">
                        <table class="table table-bordered table-responsive" id="brand-table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Updated At</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="brand-table-body">
                                <!-- Brand data will be loaded here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/footer.php"; ?>
        </div>
    </div>

    <!-- Edit Brand Modal -->
    <div class="modal fade" id="editBrandModal" tabindex="-1" aria-labelledby="editBrandModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editBrandModalLabel">Edit Brand</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editBrandForm">
                        <input type="hidden" id="editBrandId" name="id">
                        <div class="mb-3">
                            <label for="editBrandName" class="form-label">Name</label>
                            <input type="text" class="form-control" id="editBrandName" name="name" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this brand?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <script src="/Projects/OnlineShoeStore/assets/js/admin-brands.js"></script>
    <script src="/Projects/OnlineShoeStore/assets/js/scripts.js"></script>
<script>
    $(document).ready(function() {
    // Initialize DataTable
    const brandTable = $('#brand-table').DataTable({
        responsive: true,
        ajax: {
            url: '/Projects/OnlineShoeStore/views/admin/brands/get_brands.php', // Ensure this returns data
            dataSrc: function(json) {
                // Check if the response contains an error
                if (json.error) {
                    alert(json.error);  // Show error message
                    return [];  // Return empty array if there's an error
                }
                return json;  // Return the data if it's valid
            }
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
                        <button class="btn btn-warning btn-sm edit-brand" data-id="${row.id}">Edit</button>
                        <button class="btn btn-danger btn-sm delete-brand" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ]
    });

    // Handle edit button click
    $('#brand-table-body').on('click', '.edit-brand', function() {
        const brandId = $(this).data('id');
        $.get(`/Projects/OnlineShoeStore/views/admin/brands/get_brands.php?id=${brandId}`, function(data) {
            console.log(data);
            if (data.error) {
                alert(data.error);
            } else {
                $('#editBrandId').val(data.id); // Populate hidden input with brand id
                $('#editBrandName').val(data.name); // Populate input with brand name
                $('#editBrandModal').modal('show'); // Show the modal
            }
        }).fail(function() {
            alert('Error fetching brand data. Please try again.');
        });
    });

    // Handle delete button click
    $('#brand-table-body').on('click', '.delete-brand', function() {
        const brandId = $(this).data('id');
        $('#confirmDelete').data('id', brandId);
        $('#deleteConfirmationModal').modal('show');
    });

    // Confirm delete action
    $('#confirmDelete').on('click', function() {
        const brandId = $(this).data('id');
        $.ajax({
            url: `/Projects/OnlineShoeStore/views/admin/brands/delete_brand.php?id=${brandId}`,
            type: 'GET',
            success: function() {
                $('#deleteConfirmationModal').modal('hide');
                brandTable.ajax.reload(); // Reload the table data
            },
            error: function() {
                alert('Error deleting brand. Please try again.');
            }
        });
    });

    // Handle edit form submission
    $('#editBrandForm').on('submit', function(e) {
        e.preventDefault();
        const formData = $(this).serialize();
        $.ajax({
            url: '/Projects/OnlineShoeStore/views/admin/brands/update_brand.php',
            type: 'POST',
            data: formData,
            success: function() {
                $('#editBrandModal').modal('hide');
                brandTable.ajax.reload(); // Reload the table data
            },
            error: function() {
                alert('Error updating brand. Please try again.');
            }
        });
    });
});

</script>
</body>
</html>