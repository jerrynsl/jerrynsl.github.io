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
        include 'config/database.php';

        if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
            $query = 'SELECT fname, lname, gender from customers WHERE email= ?';
        } else {
            $query = 'SELECT fname, lname, gender FROM customers WHERE username=?';
        }

        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $_SESSION['username']);
        $stmt->execute();
        $numCustomer = $stmt->rowCount();
        if ($numCustomer > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $fname = $row['fname'];
            $lname = $row['lname'];
            $gender = $row['gender'];

            $a = '';

            if ($gender == 'Male') {
                $a = "Mr. $fname $lname";
            } else {
                $a = "Ms. $fname $lname";
            }
        }

        $qOrder='SELECT * FROM order_summary';
        $stmtOrder = $con->prepare($qOrder);
        $stmtOrder->execute();
        $numOrder = $stmtOrder->rowCount();

        $qLast='SELECT * FROM order_summary ORDER BY order_id DESC LIMIT 1';
        $stmtLast = $con->prepare($qLast);
        $stmtLast->execute();
        $numLast=$stmtLast->rowCount();

        ?>
        <div class="page-header">
            <h1>Home</h1>
            <h2>Welcome! <?php echo $a; ?></h2>
        </div>

        <table class='table table-hover table-responsive table-bordered'>
        <tr>
            <td class='text-end'>Total Order:</td>
            <td><?php echo $numOrder?></td>
        </tr>
        <tr>
            <td class='text-end'>Total Customer:</td>
            <td><?php echo $numCustomer?></td>
        </tr>

        </table>
        <h3>Last Order</h3>
        <?php
        if($numLast>0){
           while($rowLast=$stmtLast->fetch(PDO::FETCH_ASSOC)){
            extract($rowLast);
            $order_id=$rowLast['order_id'];
            $create=$rowLast['order_create'];
        
           
            echo "<table class='table table-hover table-responsive table-bordered'>";
            echo "<tr>";
            echo "<th>Order ID</th><td>" . $order_id . "</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<th>Order Create Date</th><td>" . $create . "</td>";
            echo "</tr>";
            echo "</table>";
           }
          
        
        }
        ?>


    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>