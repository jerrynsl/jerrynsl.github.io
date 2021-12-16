<!DOCTYPE HTML>
<html>

<head>
    <title>PDO - Read Records - PHP CRUD Tutorial</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <style>
        .m-r-1em {
            margin-right: 1em;
        }

        .m-b-1em {
            margin-bottom: 1em;
        }

        .m-l-1em {
            margin-left: 1em;
        }

        .mt0 {
            margin-top: 0;
        }
    </style>
</head>

<body>

    <div class="container">
        <?php include 'navbar.php';?>
        <div class="page-header">
            <h1>Update Product</h1>
        </div>
        <!-- PHP read record by ID will be here →
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

        //include database connection
        include 'config/database.php';

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT products.id, products.name, products.description, products.price, products.promotion_price, products.manufacture_date, products.expired_date, categories.category_id FROM products INNER JOIN categories ON products.category_id=categories.category_id WHERE id = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $name = $row['name'];
            $product_category_id = $row['category_id'];
            $description = $row['description'];
            $price = $row['price'];
            $promo_price = $row['promotion_price'];
            $manu_date = $row['manufacture_date'];
            $exp_date = $row['expired_date'];

            $catQ = 'SELECT category_id, category_name FROM categories';
            $stmt2 = $con->prepare($catQ);
             $stmt2->execute();
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>


         HTML form to update record will be here -->
        <!-- PHP post to update record will be here -->
        <?php
        // check if form was submitted
        if ($_POST) {
            try {
                // write update query
                // in this case, it seemed like we have so many fields to pass and
                // it is better to label them and not use question marks
                $query = "UPDATE products
                  SET name=:name, description=:description, category_id=:category_id, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, modified=:modified WHERE id = :id";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $category = $_POST['category'];
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promo_price = $_POST['promo_price'];
                $manu_date = $_POST['manu_date'];
                $exp_date = $_POST['exp_date'];


                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':category_id', $category);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':promotion_price', $promo_price);
                $stmt->bindParam(':manufacture_date', $manu_date);
                $stmt->bindParam(':expired_date', $exp_date);
                $modified = date('Y-m-d H:i:s'); // get the current date and time
                $stmt->bindParam(':modified', $modified);
                $stmt->bindParam(':id', $id);
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
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } ?>



        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
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
                            }else{

                                $selected = $row['category_id'] == $product_category_id ? 'selected' : '';
                            }

                            echo "<option value='" . $row['category_id'] . "' " . $selected . ">" . $row['category_name'] . "</option>";
                        }

                        echo "</select>";

                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name='description' class='form-control'><?php echo htmlspecialchars($description, ENT_QUOTES);  ?></textarea></td>
                </tr>

                <tr>
                    <td>Price</td>
                    <td><input type='text' name='price' value="<?php echo htmlspecialchars($price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Promotion Price</td>
                    <td><input type='text' name='promo_price' value="<?php echo htmlspecialchars($promo_price, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Manufacture Date</td>
                    <td><input type='date' name='manu_date' value="<?php echo htmlspecialchars($manu_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Expired Date</td>
                    <td><input type='date' name='exp_date' value="<?php echo htmlspecialchars($exp_date, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='product_read.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
</body>

</html>