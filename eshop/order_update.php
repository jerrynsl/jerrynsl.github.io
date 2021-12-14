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
        <?php
        include 'session.php';
        include 'navbar.php';
        ?>
        <div class="page-header">
            <h1>Update Order</h1>
    </div>


            <?php
            // get passed parameter value, in this case, the record ID
            // isset() is a PHP function used to verify if a value is there or not
            $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record ID not found.');

            //include database connection
            include 'config/database.php';

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

                // store retrieved row to a variable
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

            <?php
            // check if form was submitted
            if ($_POST) {



                try {

                    $flag = 0; //0-success
                    $pflag = 0; //0-fail
                    $fail_flag = 0;
                    $message = '';

                    for ($a = 0; $a < count($_POST['product']); $a++) {
                        if (!empty($_POST['product'][$a]) && !empty($_POST['quantity'][$a])) {
                            $pflag++; //$pflag=1

                        }
                        if (empty($_POST['product'][$a]) || empty($_POST['quantity'][$a])) {
                            $fail_flag++; //$pflag=1
                        }
                    }

                    // count(array) !== count(array_unique());

                    if (count($_POST['product']) !== count(array_unique($_POST['product']))) {

                        $flag = 1;
                        $message .= 'Items are duplicate.';
                    }

                    if ($pflag == 0 || $fail_flag > 0) {
                        $flag = 1;
                        $message.= 'Please select item and quantity.';
                    }

                    $qdelete = "DELETE FROM order_details WHERE order_id = ?";
                    $stmt = $con->prepare($qdelete);
                    $stmt->bindParam(1, $id);

                    if ($flag == 0) {
                        if ($stmt->execute()) {
                            for ($c = 0; $c < count($_POST['product']); $c++) {
                                $qud = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                                $stmt = $con->prepare($qud);
                                $stmt->bindParam(':order_id', $id);
                                $stmt->bindParam(':product_id', $_POST['product'][$c]);
                                $stmt->bindParam(':quantity', $_POST['quantity'][$c]);

                                if (!empty($_POST['product'][$c]) && !empty($_POST['quantity'][$c])) {
                                    $stmt->execute();
                                }
                            }
                            echo "<div class='alert alert-success'>Record was saved.</div>";
                        }else{
                            $message.='Unable to save record';
                        }
                    }else{
                        echo "<div class='alert alert-danger'>" . $message . "</div>";
                    }
                }

                // show errors
                catch (PDOException $exception) {
                    die('ERROR: ' . $exception->getMessage());
                }
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
                echo "<tr>";
                echo "</table>";

                echo "<table class='table table-hover table-responsive table-bordered'>"; //start table
                echo "<th>Product Name</th>";
                echo "<th>Quantity</th>";
                echo "</tr>";

                $arrayP = array('');

                if ($_POST) {
                    for ($y = 0; $y <= count($_POST['product']); $y++) {
                        if (empty($_POST['product'][$y])  && empty($_POST['quantity'][$y])) {

                            unset($_POST['product'][$y]);
                            unset($_POST['quantity'][$y]);
                        }
                       
                    if(count($_POST['product'])==0){
                        $arrayP = array('');
                    }else{
                        $arrayP = $_POST['product']; 
                    }   
                    }
                }

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $pList = $_POST ? $_POST['product'] : '[]';
                    echo "<tr class='pRow'>";
                    echo "<td><select class='form-control' name='product[]'>";
                    echo "<option selected></option>";
                    for ($pCount = 0; $pCount < count($pArrayName); $pCount++) {
                        $product_selected = $pArrayID[$pCount] == $row['product_id'] || $pArrayID[$pCount] == $pList[$pCount] ? 'selected' : '';
                        echo "<option value='" . $pArrayID[$pCount] . "'$product_selected>" . $pArrayName[$pCount] . "</option>";
                    }
                    echo "</select>";

                    echo "</td>";
                    echo "<td><select class='form-select' name='quantity[]'>";
                    echo  '<option selected></option>';
                    for ($quantity = 1; $quantity <= 5; $quantity++) {
                        $quantity_selected = $row['quantity'] == $quantity || $quantity == $_POST['quantity'][$quantity]? 'selected' : '';
                        echo "<option value='$quantity'$quantity_selected>$quantity</option>";
                    }
                    echo "</select></td>";
                    echo "</tr>";
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
            </tr>
            </table>
        </form>

    </div>
    <!-- end .container -->

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
    </script>
</body>

</html>