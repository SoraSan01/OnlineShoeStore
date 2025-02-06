<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
  <link href="\Projects\OnlineShoeStore\assets\css\styles.css" rel="stylesheet" />
  <title>Dashboard</title>
</head>
<body>

<?php
    include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/navbar.php";
?>

<div id="layoutSidenav">

    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/Projects/OnlineShoeStore/views/admin/includes/sidebar.php";
    ?>

    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Dashboard</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item"><a href="/Projects/OnlineShoeStore/views/admin/dashboard.php">Dashboard</a></li>
                </ol>
            </div>
        </main>
        
    <?php
    include $_SERVER['DOCUMENT_ROOT'] . "/Projects/LibraryMS/views/admin/includes/footer.php";
    ?>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="\Projects\LibraryMS\assets\js\scripts.js"></script>
</body>
</html>
