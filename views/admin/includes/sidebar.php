<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <div class="sb-sidenav-menu-heading">Core</div>
                <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/dashboard.php">
                    <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                    Dashboard
                </a>
                <div class="sb-sidenav-menu-heading">Interface</div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#productCollapse" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-cart-shopping"></i></div>
                    Products
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="productCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/product.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Product List
                        </a>
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/AddProduct.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-plus"></i></div>
                            Add Product
                        </a>
                    </nav>
                </div>
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#userCollapse" aria-expanded="false" aria-controls="collapseLayouts">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Users
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="userCollapse" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/Projects/LibraryMS/views/admin/add_user.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            User List
                        </a>
                        <a class="nav-link" href="/Projects/LibraryMS/views/admin/add_user.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-plus"></i></div>
                            Add User
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php
            if (isset($_SESSION['email'])) {
                echo $_SESSION['user_email']; // Display the email if it's set in the session
            } else {
                echo "Guest"; // Display a fallback if no user is logged in
            }
            ?>
        </div>
    </nav>
</div>