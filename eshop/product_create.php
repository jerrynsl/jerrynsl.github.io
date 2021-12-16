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
        include 'navbar.php';

        ?>
        <div class="page-header">
            <h1>Create Product</h1>
        </div>


        <?php
        include 'config/database.php';

        $catQ = 'SELECT category_id, category_name FROM categories';
        $stmt2 = $con->prepare($catQ);
        $stmt2->execute();


        if ($_POST) {
            // include database connection

            try {
                // insert query
                $query = "INSERT INTO products SET name=:name, category_id=:category_id, description=:description, price=:price, created=:created, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $name = $_POST['name'];
                $category = $_POST['category'];
                $description = $_POST['description'];
                $price = $_POST['price'];
                $promo_price = $_POST['promo_price'];
                $manu_date = $_POST['manu_date'];
                $exp_date = $_POST['exp_date'];
                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':category_id', $category);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':price', $price);
                $created = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':created', $created);
                $stmt->bindParam(':promotion_price', $promo_price);
                $stmt->bindParam(':manufacture_date', $manu_date);
                $stmt->bindParam(':expired_date', $exp_date);
                // Execute the query

                $flag = 0;
                $message = '';

                if (empty($name)) {

                    $flag = 1;
                    $message = 'Please enter item name.';
                }

                if (empty($price)) {

                    $flag = 1;
                    $message = 'Please enter price.';
                }

                if (empty($promo_price)) {

                    $flag = 1;
                    $message = 'Please enter promotion price.';
                }
                if (empty($manu_date)) {

                    $flag = 1;
                    $message = 'Please select manufactor date.';
                }

                if (empty($exp_date)) {

                    $flag = 1;
                    $message = 'Please select expired date.';
                }
                if (!is_numeric($price) || !is_numeric($promo_price)) {
                    $flag = 1;
                    $message = "Price must be numerical.";
                }

                if ($price < 0 || $promo_price < 0) {
                    $flag = 1;
                    $message = "Price cannot be negative.";
                }
                if ($promo_price > $price) {
                    $flag = 1;
                    $message = "Promo Price cannot bigger than Normal Price";
                }
                if ($manu_date > $exp_date) {
                    $flag = 1;
                    $message = "Expired date must be after Manufacture date";
                }

                if ($flag == 0) {

                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was saved.</div>";
                    } else {
                        $message = 'Unable to save record.';
                    }
                } else {

                    echo "<div class='alert alert-danger'>" . $message . "</div>";
                }
            }
            // show error
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        }

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Category</td>
                    <td>
                        <?php

                        $selected = '';
                        echo '<select class="fs-4 rounded" id="" name="category">';
                        echo  '<option selected></option>';

                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                            if ($_POST) {
                                $selected = $row['category_id'] == $_POST['category'] ? 'selected' : '';
                            }

                            echo "<option value='" . $row['category_id'] . "' " . $selected . ">" . $row['category_name'] . "</option>";
                        }

                        echo "</select>";

                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea type='text' name='description' class='form-control'></textarea></td>
                </tr>
                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promo_price' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manu_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='exp_date' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>





    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>