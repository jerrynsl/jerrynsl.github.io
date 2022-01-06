<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Create a Record - PHP CRUD Tutorial</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

</head>

<body>
    <!-- container -->
    <div class="container">
        <?php 
        include 'session.php';
        include 'navbar.php'; ?>

        <div class="page-header">
            <h1>Read Products</h1>
        </div>

        <?php
        // include database connection
        include 'config/database.php';
        $flag = 0;
        // delete message prompt will be here
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'deleted') {
            echo "<div class='alert alert-success'>Record was deleted.</div>";
        }



        $query = "SELECT products.id, products.name, products.description, products.product_img, products.price, categories.category_name FROM products INNER JOIN categories ON products.category_id=categories.category_id ORDER BY id DESC";
        $stmt = $con->prepare($query);

        // select all data  
        if (isset($_POST['filter'])) {
            if ($_POST['category'] != 'all') {
                $query = "SELECT products.id, products.name, products.description, products.product_img products.price, categories.category_name FROM products INNER JOIN categories ON products.category_id=categories.category_id WHERE categories.category_id=:category_id ORDER BY id DESC";
                $stmt = $con->prepare($query);
                $stmt->bindParam(":category_id", $_POST['category']);
            }
        } else if (isset($_POST['search'])) {
            if (empty($_POST['pname'])) {
                $flag = 1;
                echo "<div class='alert alert-danger'>Please insert value</div>";
            }
            $pname = "%" . $_POST['pname'] . "%";
            $query = "SELECT products.id, products.name, products.description, products.product_img, products.price, categories.category_name FROM products INNER JOIN categories ON products.category_id=categories.category_id WHERE products.name LIKE :name ORDER BY id DESC";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":name", $pname);
        }
        $stmt->execute();
        $num = $stmt->rowCount();

        $catQ = 'SELECT category_id, category_name FROM categories';
        $stmt2 = $con->prepare($catQ);
        $stmt2->execute();

        echo "<table>";
        echo "<tr>";
        echo "<td><a href='product_create.php' class='btn btn-primary m-b-1em'>Create New Product</a></td>";
        echo '<td><form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
        $selected = '';
        echo '<select class="fs-4 rounded" id="" name="category">';
        echo  '<option value="all" >All</option>';

        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

            if ($_POST) {
                $selected = $row['category_id'] == $_POST['category'] ? 'selected' : '';
            }

            echo "<option value='" . $row['category_id'] . "' " . $selected . ">" . $row['category_name'] . "</option>";
        }
        echo "</select>";

        $pname = isset($_POST['search']) ? $_POST['pname'] : '';
        echo " <input type='submit' name='filter' value='Filter' class='btn btn-primary' />";
        echo '</td></form>';
        echo '<form action="' . $_SERVER["PHP_SELF"] . '" method="POST">';
        echo "<td><input type='text' name='pname' value='$pname' /> <input type='submit' name='search' value='Search' class='btn btn-danger' /></td>";
        echo "</tr></form></table>";

        //check if more than 0 record found
        if ($num > 0) {

            echo "<table class='table table-hover table-responsive table-bordered'>"; //start table

            //creating our table heading
            echo "<tr>";
            echo "<th>ID</th>";
            echo "<th>Name</th>";
            echo "<th>Photo</th>";
            echo isset($_POST['filter']) && $_POST['category'] !== 'all' ? '' : "<th>Category</th>";
            echo "<th>Description</th>";
            echo "<th>Price</th>";
            echo "<th>Action</th>";
            echo "</tr>";

            // retrieve our table contents
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                extract($row);
                echo "<tr>";
                echo "<td>{$id}</td>";
                echo "<td>{$name}</td>";
                echo "<td><img src='imagesP/{$product_img}' width='200px'></td>";
                echo isset($_POST['filter']) && $_POST['category'] !== 'all' ? '' : "<td>{$category_name}</td>";
                echo "<td>{$description}</td>";
                echo "<td>" . number_format($price, 2) . "</td>";
                echo "<td>";
                // read one record
                echo "<a href='product_read_one.php?id={$id}' class='btn btn-info m-r-1em'>Read</a>";

                // we will use this links on next part of this post
                echo "<a href='product_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";

                // we will use this links on next part of this post
                echo "<a href='#' onclick='delete_product({$id});'  class='btn btn-danger'>Delete</a>";
                echo "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<div class='alert alert-danger'>No records found.</div>";
        }
        ?>
    </div> <!-- end .container -->

    <!-- confirm delete record will be here -->
    <script type='text/javascript'>
        // confirm record deletion
        function delete_product(id) {

            if (confirm('Are you sure?')) {
                // if user clicked ok,
                // pass the id to delete.php and execute the delete query
                window.location = 'product_delete.php?id=' + id;
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>