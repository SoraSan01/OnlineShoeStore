<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add Product</title>

  <!-- Font Awesome -->
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- jQuery -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                <h1 class="mt-4">Add Product</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="/Projects/OnlineShoeStore/views/admin/Products.php">Products</a></li>
                    <li class="breadcrumb-item active">Add Product</li>
                </ol>
            </div>

            <div class="container">
                <!-- Product Form -->
                <form id="addProductForm" enctype="multipart/form-data" class="row g-3 needs-validation" novalidate>
                    <div class="col-md-6">
                        <label for="productName" class="form-label">Product Name</label>
                        <input type="text" class="form-control" id="productName" name="name" required>
                        <div class="invalid-feedback">Please enter a product name.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="productPrice" class="form-label">Price</label>
                        <input type="number" class="form-control" id="productPrice" name="price" min="0" step="0.01" required>
                        <div class="invalid-feedback">Please enter a valid price.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="productStock" class="form-label">Stock</label>
                        <input type="number" class="form-control" id="productStock" name="stock" min="0" required>
                        <div class="invalid-feedback">Please enter the stock quantity.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="productDescription" class="form-label">Description</label>
                        <textarea class="form-control" id="productDescription" name="description" rows="3" required></textarea>
                        <div class="invalid-feedback">Please provide a product description.</div>
                    </div>
                    <div class="col-md-6">
                        <label for="productImage" class="form-label">Product Image</label>
                        <input type="file" class="form-control" id="productImage" name="image" accept="image/*" required>
                        <div class="invalid-feedback">Please upload a product image.</div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">Add Product</button>
                    </div>
                </form>
            </div>

            <!-- Alert Messages -->
            <div id="alertMessage" class="alert mt-3 d-none"></div>

        </main>
        
        <?php include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/footer.php"; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function () {
    function showMessage(type, message) {
        let alertBox = $('#alertMessage');
        alertBox.removeClass('d-none alert-success alert-danger').addClass(`alert-${type}`).text(message);
        setTimeout(() => alertBox.addClass('d-none'), 5000);
    }

    // Bootstrap form validation
    $('#addProductForm').submit(function (e) {
        e.preventDefault();
        $('#submitBtn').prop('disabled', true).text('Adding...');

        var formData = new FormData(this); // Create FormData object to handle file upload

        $.ajax({
            url: '/Projects/OnlineShoeStore/views/admin/products/create.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            processData: false, // Required for file upload
            contentType: false, // Required for file upload
            success: function (data) {
                if (data.Result === 'OK') {
                    $('#addProductForm')[0].reset();
                    showMessage('success', 'Product added successfully!');
                } else {
                    showMessage('danger', data.Message || 'Failed to add product.');
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Logs detailed error to the console
                showMessage('danger', `Error: ${xhr.responseText}`);
            },
            complete: function () {
                $('#submitBtn').prop('disabled', false).text('Add Product');
            }
        });
    });

    // Enable Bootstrap validation feedback
    (function () {
        'use strict'
        var forms = document.querySelectorAll('.needs-validation')

        Array.prototype.slice.call(forms)
        .forEach(function (form) {
            form.addEventListener('submit', function (event) {
                if (!form.checkValidity()) {
                    event.preventDefault()
                    event.stopPropagation()
                }
                form.classList.add('was-validated')
            }, false)
        })
    })();
});
</script>
<script src="\Projects\LibraryMS\assets\js\scripts.js"></script>

</body>
</html>
