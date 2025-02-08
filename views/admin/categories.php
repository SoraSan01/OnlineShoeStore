<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>

    <!-- Font Awesome -->
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS & JS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables CSS & JS -->
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
                    <h1 class="mt-4">Categories</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="/Projects/OnlineShoeStore/views/admin/Products.php">Products</a></li>
                        <li class="breadcrumb-item active">Categories</li>
                    </ol>

                    <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/toastNotification.php"; ?>

                    <!-- Category Table -->
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-bordered table-striped dt-responsive nowrap" id="category-table">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>Updated At</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>

            <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/footer.php"; ?>
        </div>
    </div>

<!-- Edit Category Modal -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="editCategoryModalLabel">Edit Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm">
                    <input type="hidden" id="editCategoryId" name="id">
                    <div class="mb-3">
                        <label for="editCategoryName" class="form-label">Name</label>
                        <input type="text" class="form-control" id="editCategoryName" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="editCategoryDescription" class="form-label">Description</label>
                        <input type="text" class="form-control" id="editCategoryDescription" name="description" required>
                    </div>
                    <div class="text-end">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this category?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


    <script src="/Projects/OnlineShoeStore/assets/js/admin-categories.js"></script>
    <script src="/Projects/OnlineShoeStore/assets/js/scripts.js"></script>

</body>
</html>
