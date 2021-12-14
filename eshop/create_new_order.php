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
            <h1>Create New Order</h1>
        </div>


        <?php

        // include database connection
        include 'config/database.php';

        $qp = "SELECT id, name, price FROM products";
        $stmt = $con->prepare($qp);
        $stmt->execute();

        $pArrayID = array();
        $pArrayName = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($pArrayID, $row['id']);
            array_push($pArrayName, $row['name']);
        }

        $qc = "SELECT username, email, fname, lname FROM customers";

        $stmt2 = $con->prepare($qc);
        $stmt2->execute();

        if ($_POST) {


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
                $message = 'Items are duplicate.';
                
            }

            if (empty($_POST['customer'])) {
                $flag = 1;
                $message = 'Please select customer.';
            } else if ($pflag == 0 || $fail_flag > 0) {
                $flag = 1;
                $message = 'Please select item and quantity.';
            }

            try {

                $username = $_POST['customer'];

                $order_create = date('Y-m-d');

                // insert query
                $qs = "INSERT INTO order_summary SET username=:username, order_create=:order_create";
                // prepare query for execution
                $stmt = $con->prepare($qs);

                // bind the parameters
                $stmt->bindParam(':username', $username);
                $stmt->bindParam(':order_create', $order_create);

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        $last_id = $con->lastInsertId();
                        for ($c = 0; $c < count($_POST['product']); $c++) {
                            $qd = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                            $stmt = $con->prepare($qd);
                            $stmt->bindParam(':order_id', $last_id);
                            $stmt->bindParam(':product_id', $_POST['product'][$c]);
                            $stmt->bindParam(':quantity', $_POST['quantity'][$c]);

                            if (!empty($_POST['product'][$c]) && !empty($_POST['quantity'][$c])) {

                                $stmt->execute();
                            }
                        }
                        echo "<div class='alert alert-success'>Record was saved. Last Insert ID is $last_id</div>";
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
                    <td>Customer Name:</td>
                    <td>
                        <?php

                        $selected = '';
                        echo '<select class="fs-4 rounded" id="" name="customer">';
                        echo  '<option selected></option>';

                        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

                            if ($_POST) {
                                $selected = $row['username'] == $_POST['customer'] ? 'selected' : '';
                            }

                            echo "<option value='" . $row['username'] . "' " . $selected . ">" . $row['fname'] . " " . $row['lname'] . "(" . $row['email'] . ")</option>";
                        }

                        echo "</select>";

                        ?>


                    </td>
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
                       
                    if(count($_POST['product'])==0){
                        $arrayP = array('');
                    }else{
                        $arrayP = $_POST['product']; 
                    }   
                    }
                }
                // echo '<pre>';
                // var_dump($_POST);
                // echo '</pre>';
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
                        $selected_quantity = $quantity == $_POST['quantity'][$pRow] ? 'selected' : '';
                        echo "<option value='$quantity' $selected_quantity>$quantity</option>";
                    }
                    echo '</td>';
                }

            

                

                ?>

                <tr>
                    <td>
                        <input type='button' value='Add More' class='add_one btn btn-primary' />
                        <input type='button' value='Delete' class='delete_one btn btn-danger' />

                    </td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='order_read.php' class='btn btn-danger'>Back to read order</a>
                    </td>
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
    </script>
</body>

</html>