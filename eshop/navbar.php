
<nav class="navbar navbar-expand-lg navbar-dark navbar-sm-primary" style="background-color: #B8E6E6;">
    <div class="container-sm d-flex">
    <a class="navbar-brand" href="home.php">Jerry Shop</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon "></span>
        </button>
        <div class="collapse navbar-collapse row" id="navbarSupportedContent">
            <ul class="navbar-nav col justify-content-end ">
                <li class="nav-item">
                    <a class="nav-link active text-primary" aria-current="page" href="home.php">Home</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="product_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Products
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="product_create.php">Create Product</a></li>
                        <li><a class="dropdown-item" href="product_read.php">Read Product</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="order_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Orders
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="create_new_order.php">Create Order</a></li>
                        <li><a class="dropdown-item" href="order_read.php">Read Order</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="category_read.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Categories
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="category_create.php">Create Categories</a></li>
                        <li><a class="dropdown-item" href="category_read.php">Read Categories</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-primary" href="read_customer.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Customers
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="create_customer.php">Create Customer</a></li>
                        <li><a class="dropdown-item" href="read_customer.php">Read Customer</a></li>
                    </ul>
                </li>
                </ul>
                <ul class="navbar-nav col justify-content-end ">
                <?php 
                include 'config/database.php';
        
                if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
                    $query = 'SELECT username, customer_img from customers WHERE email= ?';
                } else {
                    $query = 'SELECT username, customer_img FROM customers WHERE username=?';
                }
        
                $userstmt = $con->prepare($query);
                $userstmt->bindParam(1, $_SESSION['username']);
                $userstmt->execute();
                $num=$userstmt->rowCount();
                if($num>0){
                $row=$userstmt->fetch(PDO::FETCH_ASSOC);
                $customer_img=$row['customer_img'];
                $username=$row['username'];
                echo ' <li class="nav-item ">';
                echo '<img src="imagesC/'.$customer_img.'"width="40px">';
                echo '<li>';
                echo '<li class="nav-item dropdown">';
                echo '<a class="nav-link dropdown-toggle text-primary"  id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                '.$username.'</a>';
                echo '<ul class="dropdown-menu" aria-labelledby="navbarDropdown">';
                echo '<li><a class="dropdown-item" href="update_customer.php?id='.$username.'">Edit Profile</a></li>';
                echo '<li><a class="nav-link text-danger" href="logout.php">Log Out</a></li>';
                echo '</ul>';
                echo '</li>';
                }
                ?>
            </ul>
        </div>
    </div>
</nav>