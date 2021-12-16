<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap CSS → -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    <title>My First Bootstrap Page</title>
</head>

<body>
    <!-- Your Content Starts Here -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sm-light bg-light ">
        <div class="container-sm border ">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse " id="navbarSupportedContent">
                <ul class="navbar-nav justify-content-end ">
                    <li class="nav-item">
                        <a class="nav-link active text-primary" aria-current="page" href="home.php">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="product_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Products
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-primary" href="product_create.php">Create Product</a></li>
                            <li><a class="dropdown-item text-primary" href="product_read.php">Read Product</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="read_customer.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Customers
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-primary" href="create_customer.php">Create Customer</a></li>
                            <li><a class="dropdown-item text-primary" href="read_customer.php">Read Customer</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="order_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Orders
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-primary" href="create_new_order.php">Create Order</a></li>
                            <li><a class="dropdown-item text-primary" href="order_read.php">Read Order</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-primary" href="category_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Categories
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li><a class="dropdown-item text-primary" href="category_create.php">Create Categories</a></li>
                            <li><a class="dropdown-item text-primary" href="category_read.php">Read Categories</a></li>
                        </ul>
                    </li>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link text-primary" href="contact.php">Contact Us</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link text-primary" href="logout.php">Log Out</a>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>