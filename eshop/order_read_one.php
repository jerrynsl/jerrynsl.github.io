<!DOCTYPE HTML>
<html>

<head>
    <title>Read Order</title>
    <!-- Latest compiled and minified Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">


</head>

<body>
    <?php
        include 'session.php';
        include 'navbar.php';
        ?>
    <!-- container -->
    <div class="container">
        
        <div class="page-header">
            <h1>Read Order</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record username not found.');

        //include database connection
        include 'config/database.php';

        $action = isset($_GET['action']) ? $_GET['action'] : "";

        // if it was redirected from delete.php
        if ($action == 'updsuccess') {
            echo "<div class='alert alert-success'>Order was updated.</div>";
        }else if($action == 'crtsuccess'){
            echo "<div class='alert alert-success'>Order was added.</div>";
        }
        // read current record's data
        try {
            // prepare select query
            $query = "SELECT order_details.orderDetail_id, order_details.order_id, order_details.product_id, order_details.quantity, products.name, products.price FROM order_details INNER JOIN products ON order_details.product_id = products.id WHERE order_id=:order_id";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam("order_id", $id);

            // execute our query
            $stmt->execute();

            $num = $stmt->rowCount();

            $qc = "SELECT order_summary.order_id, customers.fname, customers.lname, order_summary.order_create FROM order_summary INNER JOIN customers ON order_summary.username = customers.username WHERE order_id=$id";

            $stmt2 = $con->prepare($qc);
            $stmt2->execute();

            // store retrieved row to a variable
            $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $fname = $row2['fname'];
            $lname = $row2['lname'];
            $order_create = $row2['order_create'];

            // values to fill up our form
            if ($num > 0) {

                echo "<table class='table table-hover table-responsive table-bordered'>";
                echo "<tr>";
                echo "<th>Order ID</th><td>" . $id . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Customer Name</th><td>" . $fname . " " . $lname . "</td>";
                echo "</tr>";
                echo "<tr>";
                echo "<th>Order Create</th><td>".$order_create."</td>";
                echo "<tr";
                echo "</table>";

                echo "<table class='table table-hover table-responsive table-bordered text-center'>"; //start table
                echo "<th>Product Name</th>";
                echo "<th>Price Per Item</th>";
                echo "<th>Quantity</th>";
                echo "<th class='text-end'>Total Price</th>";
                echo "</tr>";
                $grand_total=0;
                // retrieve our table contents
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $items_total=$row['quantity']*$row['price'];

                    echo "<tr>";
                    echo "<td>" . $row['name'] . "</td>";
                    echo "<td>" .number_format($row['price'],2) . "</td>";
                    echo "<td>" . $row['quantity'] . "</td>";
                    echo "<td class='text-end'>".number_format($items_total,2)."</td>";
                    echo "</tr>";
                    
                    $grand_total=$grand_total+$items_total;
                }

                echo "<tr>";
                echo "<th colspan='3' class='text-end'>Grand Total:</th>";
                echo "<td class='col text-end'>".number_format($grand_total,2)."</td>";
                echo "</tr>";

                echo "<tr>";
                echo "<td colspan='4'> <a href='order_update.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>";
                echo "<a href='order_read.php' class='btn btn-danger'>Back to order list</a>";
                echo "</td>";
                echo "</tr>";
                echo "</table>";
            }

            // shorter way to do that is extract($row)
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>



        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->




    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>


</body>

</html>