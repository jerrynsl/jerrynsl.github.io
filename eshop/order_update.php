<!DOCTYPE HTML>
<html>

<head>
    <title>Update Order</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
    <?php
    include 'session.php';
    include 'navbar.php';
    ?>
    <div class="container">

        <div class="page-header">
            <h1>Update Order</h1>
        </div>
        <?php

        include 'config/database.php';
        $arrayP = array('');
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');
        if ($_POST) {

            $flag = 0; //0-success
            $pflag = 0; //0-fail
            $fail_flag = 0;
            $message = '';

            //remove the empty rows (product and quantity)
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

            //make sure either one of the product and quantity is not empty
            for ($a = 0; $a < count($_POST['product']); $a++) {
                if (!empty($_POST['product'][$a]) && !empty($_POST['quantity'][$a])) {
                    $pflag++; //$pflag=1

                }
                if (empty($_POST['product'][$a]) || empty($_POST['quantity'][$a])) {
                    $fail_flag++; //$pflag=1
                }
            }
            //products duplicate
            if (count($_POST['product']) !== count(array_unique($_POST['product']))) {

                $flag = 1;
                $message .= 'Items are duplicate.';
            }
            if ($pflag == 0 || $fail_flag > 0) {
                $flag = 1;
                $message .= 'Please select item and quantity.';
            }
            if ($flag == 0) {


                try {
                    $qdelete = "DELETE FROM order_details WHERE order_id = ?";
                    $stmt = $con->prepare($qdelete);
                    $stmt->bindParam(1, $id);


                    if ($stmt->execute()) {
                        for ($c = 0; $c < count($_POST['product']); $c++) {
                            $qud = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                            $stmt = $con->prepare($qud);
                            $stmt->bindParam(':order_id', $id);
                            $stmt->bindParam(':product_id', $_POST['product'][$c]);
                            $stmt->bindParam(':quantity', $_POST['quantity'][$c]);
                            if (!empty($_POST['product'][$c]) && !empty($_POST['quantity'][$c])) {
                                $stmt->execute();
                                echo "<script>location.replace('order_read_one.php?id=" . $id . "&action=updsuccess')</script>";
                            }
                        }
                    } else {
                        $message .= 'Unable to save record';
                    }
                } catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
            } else {
                echo "<div class='alert alert-danger'>" . $message . "</div>";
            }
        }

        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not

        //include database connection

        // read current record's data
        try {
            $query = "SELECT order_details.orderDetail_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name FROM order_details INNER JOIN products ON order_details.product_id = products.id WHERE order_id = :order_id ";
            $stmt = $con->prepare($query);
            $stmt->bindParam(":order_id", $id);
            $stmt->execute();
            $num = $stmt->rowCount();


            $qc = "SELECT order_summary.order_id, customers.fname, customers.lname, order_summary.order_create FROM order_summary INNER JOIN customers ON order_summary.username = customers.username WHERE order_id=$id";
            $stmt2 = $con->prepare($qc);
            $stmt2->execute();
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $fname = $row2['fname'];
            $lname = $row2['lname'];

            $query3 = "SELECT * FROM products";
            $stmt3 = $con->prepare($query3);
            $stmt3->execute();

            $pArrayID = array();
            $pArrayName = array();
            while ($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)) {
                array_push($pArrayID, $row3['id']);
                array_push($pArrayName, $row3['name']);
            }
        }
        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$id}"); ?>" method="post">
            <?php
            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Order ID</th><td>" . $id . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Customer Name</th><td>" . $fname . " " . $lname . "</td>";
                echo "</tr>";
                echo "</table>";

                echo "<table id='order_table' class='table table-hover table-responsive table-bordered'>";
                //start table
                echo "<th>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "<th></th>";
                echo "</tr>";
                if (!$_POST) {
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        foreach ($arrayP as $pRow => $pID) {
                            $pList = '[]';
                            echo "<tr class='pRow'>";
                            echo "<td><select class='form-select' name='product[]'>";
                            echo "<option selected></option>";
                            for ($pCount = 0; $pCount < count($pArrayName); $pCount++) {
                                $product_selected = $pArrayID[$pCount] == $row['product_id'] ? 'selected' : '';
                                echo "<option value='" . $pArrayID[$pCount] . "'$product_selected>" . $pArrayName[$pCount] . "</option>";
                            }
                            echo "</select>";
                            echo "</td>";
                            echo "<td><select class='form-select' name='quantity[]'>";
                            echo  '<option selected></option>';
                            for ($quantity = 1; $quantity <= 5; $quantity++) {
                                $quantity_selected =  $row['quantity'] == $quantity ? 'selected' : '';
                                echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                            }
                            echo "</select></td>";
                            echo "<td><button type='button' class='btn mb-3 mx-2' onclick='deleteMe(this)'>X</button></td>";
                            echo "</tr>";
                        }
                    }
                } else {
                    foreach ($arrayP as $pRow => $pID) {
                        $pList = $_POST['product'];
                        echo "<tr class='pRow'>";
                        echo "<td><select class='form-control' name='product[]'>";
                        echo "<option selected></option>";
                        for ($pCount = 0; $pCount < count($pArrayName); $pCount++) {
                            $product_selected = $pArrayID[$pCount] == $pList[$pRow] ? 'selected' : '';
                            echo "<option value='" . $pArrayID[$pCount] . "'$product_selected>" . $pArrayName[$pCount] . "</option>";
                        }
                        echo "</select>";
                        echo "</td>";
                        echo "<td><select class='form-select' name='quantity[]'>";
                        echo  '<option selected></option>';
                        for ($quantity = 1; $quantity <= 5; $quantity++) {
                            $quantity_selected = $quantity == $_POST['quantity'][$pRow] ? 'selected' : '';
                            echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                        }
                        echo "</select></td>";
                        echo "<td><button type='button' class='btn mb-3 mx-2' onclick='deleteMe(this)'>X</button></td>";
                        echo "</tr>";
                    }
                }
            }
            ?>
            <tr>
                <td>
                    <input type='button' value='Add More' class='add_one btn btn-primary' />
                    <input type='button' value='Delete' class='delete_one btn btn-danger' />
                </td>
                <td>
                    <input type='submit' value='Save Changes' class='btn btn-primary' />
                    <a href='order_read.php' class='btn btn-danger'>Back to read Order</a>
                </td>
                <td></td>
            </tr>
            </table>
        </form>
    </div>
    <!-- end .container -->


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('click', function(event) {
            if (event.target.matches('.add_one')) {
                var element = document.querySelector('.pRow');
                var clone = element.cloneNode(true);
                element.after(clone);
            }
            if (event.target.matches('.delete_one')) {
                var total = document.querySelectorAll('.pRow').length;
                if (total > 1) {
                    var element = document.querySelector('.pRow');
                    element.remove(element);
                }
            }
        }, false);

        function deleteMe(row) {
            var table = document.getElementById('order_table')
            var allrows = table.getElementsByTagName('tr');
            if (allrows.length == 1) {
                alert("You are not allowed to delete.");
            } else {
                if (confirm("Confirm to delete?")) {
                    row.parentNode.parentNode.remove();
                }
            }
        }
    </script>

    <?php

    ?>
</body>

</html>