<!DOCTYPE HTML>
<html>

<head>
    <title>Read Customer</title>
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
            <h1>Read Customer</h1>
        </div>

        <!-- PHP read one record will be here -->
        <?php
        // get passed parameter value, in this case, the record ID
        // isset() is a PHP function used to verify if a value is there or not
        $id = isset($_GET['id']) ? $_GET['id'] : die('ERROR: Record username not found.');

        //include database connection
        include 'config/database.php';
        $action = isset($_GET['action']) ? $_GET['action'] : "";

        if ($action == 'regsuccess') {
            echo "<div class='alert alert-success'>Customer was added.</div>";
        }else if($action == 'updsuccess'){
            echo "<div class='alert alert-success'>Profile was updated.</div>";
        }

        // read current record's data
        try {
            // prepare select query
            $query = "SELECT username, fname, lname, gender, dob, customer_img, regdatetime, accountstatus FROM customers WHERE username = :username ";
            $stmt = $con->prepare($query);

            // Bind the parameter
            $stmt->bindParam(":username", $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $gender = $row['gender'];
            $dob = $row['dob'];
            $customer_img = $row['customer_img'];
            $regdatetime = $row['regdatetime'];
            $accountstatus = $row['accountstatus'];


            // shorter way to do that is extract($row)
        }

        // show error
        catch (PDOException $exception) {
            die('ERROR: ' . $exception->getMessage());
        }
        ?>



        <!-- HTML read one record table will be here -->
        <!--we have our html table here where the record will be displayed-->
        <table class='table table-hover table-responsive table-bordered'>
            <tr>
                <td>Customer Image</td>

                <?php
                if ($customer_img == '') {
                    echo '<td>No image</td>';
                } else {
                    echo '<td><img src="imagesC/' . $customer_img . '"width="200px"></td>';
                }

                ?>
            </tr>
            <tr>
                <td>Userame</td>
                <td><?php echo htmlspecialchars($username, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><?php echo htmlspecialchars($fname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><?php echo htmlspecialchars($lname, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Gender</td>
                <td><?php echo htmlspecialchars($gender, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Date of Birth</td>
                <td><?php echo htmlspecialchars($dob, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Register Date and Time</td>
                <td><?php echo htmlspecialchars($regdatetime, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td>Account Status</td>
                <td><?php echo htmlspecialchars($accountstatus, ENT_QUOTES);  ?></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php echo "<a href='update_customer.php?id={$id}' class='btn btn-primary m-r-1em'>Edit</a>"; ?>
                    <a href='read_customer.php' class='btn btn-danger'>Back to read customers</a>

                </td>
            </tr>
        </table>



    </div> <!-- end .container -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>


</body>

</html>