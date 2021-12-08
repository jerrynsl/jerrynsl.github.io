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
            $qod = "SELECT order_details.orderDetail_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name FROM order_details INNER JOIN products ON order_details.product_id = products.id WHERE order_id = ? LIMIT 0,1";
            $stmt = $con->prepare($qod);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $product_name = $row['name'];
            $product_id = $row['product_id'];
            $selected_quantity = $row['quantity'];

            $qp = "SELECT id, name, price FROM products";
             $stmt2 = $con->prepare($qp);
            $stmt2->execute();

            $pArrayID = array();
            $pArrayName = array();

            while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                array_push($pArrayID, $row2['id']);
                array_push($pArrayName, $row2['name']);
            }
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
                  SET name=:name, description=:description, price=:price, promotion_price=:promotion_price, manufacture_date=:manufacture_date, expired_date=:expired_date, modified=:modified WHERE id = :id";
                // prepare query for excecution
                $stmt = $con->prepare($query);

                // posted values
                $name = htmlspecialchars(strip_tags($_POST['name']));
                $description = htmlspecialchars(strip_tags($_POST['description']));
                $price = htmlspecialchars(strip_tags($_POST['price']));
                $promo_price = $_POST['promo_price'];
                $manu_date = $_POST['manu_date'];
                $exp_date = $_POST['exp_date'];


                // bind the parameters
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':description', $description);
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
                    <td>Customer Name</td>
                    <td><input type='text' name='name' value="<?php echo htmlspecialchars($name, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <th>Products</th>
                    <th>Quantity</th>

                </tr>

                <?php


                // $product_count = $_POST ? count($_POST['product']) : 1;
                $arrayP = array('');

                if ($_POST) {
                    for ($y = 0; $y <= count($_POST['product']); $y++) {
                        if (empty($_POST['product'][$y])  && empty($_POST['quantity'][$y])) {

                            unset($_POST['product'][$y]);
                            unset($_POST['quantity'][$y]);
                        }

                        if (count($_POST['product']) == 0) {
                            $arrayP = array('');
                        } else {
                            $arrayP = $_POST['product'];
                        }
                    }
                }
                foreach ($arrayP as $pRow => $pID) {

                    echo "<tr class='pRow'>";

                    echo '<td><select class="fs-4 rounded" id="" name="product[]">';
                    echo  '<option class="bg-white"></option>';

                    $pList = $_POST ? $_POST['product'] : '[]';

                    for ($pCount = 0; $pCount < count($pArrayName); $pCount++) {

                        $product_selected = $pArrayID[$pCount] == $pList[$pRow] ? 'selected' : '';



                        echo "<option value='" . $pArrayID[$pCount] . "' $product_selected>" . $pArrayName[$pCount] . "</option>";
                    }

                    echo "</select>";

                    echo "<td>";
                    echo '<select class="w-25 fs-4 rounded" name="quantity[]" >';
                    echo "<option></option>";
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $selected_quantity = $quantity == $selected_quantity ? 'selected' : '';
                        echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                    }
                    echo '</td>';
                }





                ?>
            </table>
        </form>


    </div>
    <!-- end .container -->
</body>

</html>