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
                
                <!-- Products Section -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#productCollapse" aria-expanded="false" aria-controls="productCollapse">
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
                
                <!-- Users Section -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#userCollapse" aria-expanded="false" aria-controls="userCollapse">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                    Users
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="userCollapse" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/user_list.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            User List
                        </a>
                    </nav>
                </div>
                
                <!-- Brands Section -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#brandCollapse" aria-expanded="false" aria-controls="brandCollapse">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                    Brands
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="brandCollapse" aria-labelledby="headingThree" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/brands.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Brand List
                        </a>
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/AddBrand.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-plus"></i></div>
                            Add Brand
                        </a>
                    </nav>
                </div>

                <!-- Category Section -->
                <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#categoryCollapse" aria-expanded="false" aria-controls="categoryCollapse">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-tags"></i></div>
                    Categories
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="categoryCollapse" aria-labelledby="headingFour" data-bs-parent="#sidenavAccordion">
                    <nav class="sb-sidenav-menu-nested nav">
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/categories.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>
                            Category List
                        </a>
                        <a class="nav-link" href="/Projects/OnlineShoeStore/views/admin/AddCategory.php">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-square-plus"></i></div>
                            Add Category
                        </a>
                    </nav>
                </div>
            </div>
        </div>
        
        <div class="sb-sidenav-footer">
            <div class="small">Logged in as:</div>
            <?php
            if (isset($_SESSION['username'])) {
                echo htmlspecialchars($_SESSION['username']); // Display the username safely
            } else {
                echo "Guest"; // Display a fallback if no user is logged in
            }
            ?>
        </div>
    </nav>
</div>