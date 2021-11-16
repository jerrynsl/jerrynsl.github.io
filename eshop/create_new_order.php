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

        $qp = "SELECT id, name, price FROM products";

        $stmt = $con->prepare($qp);
        $stmt->execute();

        $qc = "SELECT username, email, fname, lname FROM customers";

        $stmt2 = $con->prepare($qc);
        $stmt2->execute();

        if ($_POST) {

            $flag=0;
            $message='';
    
            if(empty($_POST['customer'])){
    
                $flag=1;
                $message='Please select customer.';
    
            }
    
            if(empty($_POST['product'][0])){
    
                $flag=1;
                $message='Please select first item.';
    
            }
            if(empty($_POST['quantity'][0])){

                $flag=1;
                $message='Please enter the quantity.';
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
    
                if($flag==0){
                if ($stmt->execute()) {
                    $last_id = $con->lastInsertId();
                    for ($c = 0; $c < 3; $c++) {
                        $qd = 'INSERT INTO order_details SET order_id=:order_id, product_id=:product_id, quantity=:quantity';
                        $stmt = $con->prepare($qd);
                        $stmt->bindParam(':order_id', $last_id);
                        $stmt->bindParam(':product_id', $_POST['product'][$c]);
                        $stmt->bindParam(':quantity', $_POST['quantity'][$c]);
    
    
                        $stmt->execute();
                    
                    
                    }
                    echo "<div class='alert alert-success'>Record was saved. Last Insert ID is $last_id</div>";
                } else {
                    $message='Unable to save record.';
                }
            }else{
    
                echo "<div class='alert alert-danger'>".$message."</div>";
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
                    
                    echo '<select class="fs-4 rounded" id="" name="customer">';
                    echo  '<option selected></option>';

                    while($row = $stmt2->fetch(PDO::FETCH_ASSOC)){
                    echo "<option value='" . $row['username'] ."'>" . $row['fname']." ". $row['lname'] ."(".$row['email'].")</option>";
                  
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


                $pArrayID = array();
                $pArrayName = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {


                    array_push($pArrayID, $row['id']);
                    array_push($pArrayName, $row['name']);
                }


                for ($x = 0; $x <= 2; $x++) {

                    echo "<tr>";
                    echo '<td><select class="fs-4 rounded" id="" name="product[]">';
                    echo  '<option class="bg-white" selected></option>';

                    for ($pCount = 0; $pCount < count($pArrayName); $pCount++) {
                        echo "<option value='" . $pArrayID[$pCount] . "'>" . $pArrayName[$pCount] . "</option>";
                    }

                    echo "</select>
                          
                        </td>
                         <td>
                         <input type='number' name='quantity[]' class='form-control' min='1' max='5'/>
                         </td> 
                         </tr>";
                }

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



    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>