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

        <div class="page-header">
            <h1>Create New Order</h1>
        </div>


        <?php

        // include database connection
        include 'config/database.php';

        $q = "SELECT id, name, price FROM products";

        $stmt = $con->prepare($q);
        $stmt->execute();

        $stmt2 = $con->prepare($q);
        $stmt2->execute();

        $stmt3 = $con->prepare($q);
        $stmt3->execute();

        ?>

        <!-- html form here where the product information will be entered -->
        <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">




            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Customer Name:</td>
                    <td><input type='text' name='customer_name' class='form-control' /></td>
                </tr>
                <tr>
                    <th>Products</th>
                    <th>Quantity</th>

                </tr>

                <?php


                echo "<tr>";
                echo '<td><select class="fs-4 rounded" id="" name="product_name1">';
                echo  '<option class="bg-white" selected></option>';



                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


                    extract($row);

                    echo "<option class='bg-white' value='" . $row['name'] . "'>" . $row['name'] . "</option>";
                }

                echo "</select>
                          
                          </td>
                         <td><input type='number' name='quantity1' class='form-control' /></td> 
                         </tr>";


                echo "<tr>";
                echo '<td><select class="fs-4 rounded" id="" name="product_name2">';
                echo  '<option class="bg-white" selected></option>';




                while ($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {


                    extract($row2);

                    echo "<option class='bg-white' value='" . $row2['name'] . "'>" . $row2['name'] . "</option>";
                }

                echo "</select>
                                   
                 </td>
                 <td>
                 <input type='number' name='quantity2' class='form-control' /></td> 
                </tr>";

                echo "<tr>";
                echo '<td><select class="fs-4 rounded" id="" name="product_name3">';
                echo  '<option class="bg-white" selected></option>';

                while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {


                    extract($row3);

                    echo "<option class='bg-white' value='" . $row3['name'] . "'>" . $row3['name'] . "</option>";
                }

                echo "</select>
                                                   
                 </td>
                 <td>
                 <input type='number' name='quantity3' class='form-control' />
                 </td> 
                 </tr>";


                ?>




                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='index.php' class='btn btn-danger'>Back to read products</a>
                    </td>
                </tr>
            </table>
        </form>





    </div>


    <?php




    if ($_POST) {

        $q1 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name1'] . "'";
        $stmt = $con->prepare($q1);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $p1 = $row['price'];
        }

        $q2 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name2'] . "'";
        $stmt = $con->prepare($q2);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $p2 = $row['price'];
        }

        $q3 = "SELECT id, name, price FROM products WHERE name='" . $_POST['product_name3'] . "'";
        $stmt = $con->prepare($q3);
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $p3 = $row['price'];
        }


        try {
            $product_name1 = $_POST['product_name1'];
            $quantity1 = $_POST['quantity1'];
            $price1 = $p1 * $_POST['quantity1'];
            $product_name2 = $_POST['product_name2'];
            $quantity2 = $_POST['quantity2'];
            $price2 = $p2 * $_POST['quantity2'];
            $product_name3 = $_POST['product_name3'];
            $quantity3 = $_POST['quantity3'];
            $price3 = $p3 * $_POST['quantity3'];
            // insert query
            $qd = "INSERT INTO order_details SET product_name1=:product_name1, quantity1=:quantity1, price1=:price1, product_name2=:product_name2, quantity2=:quantity2, price2=:price2,product_name3=:product_name3, quantity3=:quantity3, price3=:price3";
            // prepare query for execution
            $stmt = $con->prepare($qd);

            // bind the parameters
            $stmt->bindParam(':product_name1', $product_name1);
            $stmt->bindParam(':quantity1', $quantity1);
            $stmt->bindParam(':price1', $price1);
            $stmt->bindParam(':product_name2', $product_name2);
            $stmt->bindParam(':quantity2', $quantity2);
            $stmt->bindParam(':price2', $price2);
            $stmt->bindParam(':product_name3', $product_name3);
            $stmt->bindParam(':quantity3', $quantity3);
            $stmt->bindParam(':price3', $price3);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Record was saved.</div>";
            } else {
                echo "<div class='alert alert-danger'>Unable to save record.</div>";
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

        try {
            $customer_name = $_POST['customer_name'];
            $total_amount = $price1 + $price2 + $price3;
            $order_create = date('Y-m-d');

            // insert query
            $qs = "INSERT INTO order_summary SET customer_name=:customer_name, total_amount=:total_amount, order_create=:order_create";
            // prepare query for execution
            $stmt = $con->prepare($qs);

            // bind the parameters
            $stmt->bindParam(':customer_name', $customer_name);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':order_create', $order_create);

            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Record was saved.</div>";
            } else {
                echo "<div class='alert alert-danger'>Unable to save record.</div>";
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }

    }



    ?>

    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>