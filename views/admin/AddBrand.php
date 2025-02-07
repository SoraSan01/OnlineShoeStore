<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Brand</title>

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
                <h1 class="mt-4">Add Brand</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="/Projects/OnlineShoeStore/views/admin/Brands.php">Brands</a></li>
                    <li class="breadcrumb-item active">Add Brand</li>
                </ol>
            </div>

            <div class="container">
                <!-- Brand Form -->
                <form id="addBrandForm" class="row g-3 needs-validation" novalidate>
                    <div class="col-md-6">
                        <label for="brandName" class="form-label">Brand Name</label>
                        <input type="text" class="form-control" id="brandName" name="name" required>
                        <div class="invalid-feedback">Please enter a brand name.</div>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">Add Brand</button>
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
    $('#addBrandForm').submit(function (e) {
        e.preventDefault();
        $('#submitBtn').prop('disabled', true).text('Adding...');

        var formData = $(this).serialize(); // Serialize form data

        $.ajax({
            url: '/Projects/OnlineShoeStore/views/admin/brands/create_brand.php', // Adjust the URL to your API endpoint
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (data.Result === 'OK') {
                    $('#addBrandForm')[0].reset();
                    showMessage('success', 'Brand added successfully!');
                } else {
                    showMessage('danger', data.Message || 'Failed to add brand.');
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText); // Logs detailed error to the console
                showMessage('danger', `Error: ${xhr.responseText}`);
            },
            complete: function () {
                $('#submitBtn').prop('disabled', false).text('Add Brand');
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
</body>
</html>