<!DOCTYPE HTML>
<html>

<head>
    <title>Customer Update</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
<?php 
        include 'session.php';
        include 'navbar.php'; ?>
    <div class="container">
       
        <div class="page-header">
            <h1>Update Customer</h1>
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
            $query = "SELECT * FROM customers WHERE username = ? LIMIT 0,1";
            $stmt = $con->prepare($query);

            // this is the first question mark
            $stmt->bindParam(1, $id);

            // execute our query
            $stmt->execute();

            // store retrieved row to a variable
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            // values to fill up our form
            $username = $row['username'];
            $email = $row['email'];
            $password = $row['password'];
            $fname = $row['fname'];
            $lname = $row['lname'];
            $gender = $row['gender'];
            $accountstatus = $row['accountstatus'];
            $dob = $row['dob'];
            $customer_img = $row['customer_img'];
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
                $query = "UPDATE customers SET email=:email, password=:password,  fname=:fname, lname=:lname, gender=:gender, dob=:dob, customer_img=:customer_img, accountstatus=:accountstatus WHERE username=:username";
                // prepare query for excecution
                $stmt = $con->prepare($query);
                // posted values
                $email = htmlspecialchars(strip_tags($_POST['email']));
                $fname = htmlspecialchars(strip_tags($_POST['fname']));
                $lname = htmlspecialchars(strip_tags($_POST['lname']));
                $gender = htmlspecialchars(strip_tags($_POST['gender']));
                $accountstatus = htmlspecialchars(strip_tags($_POST['accountstatus']));
                $dob = htmlspecialchars(strip_tags($_POST['dob']));
                $customer_img = basename($_FILES["cimg"]["name"]);
                $old_pass = $_POST['old_pass'];
                $new_pass = $_POST['new_pass'];
                $confirm_new_pass = $_POST['confirm_new_pass'];
                $flag = 0;
                $message = '';
                if (!empty($_FILES['cimg']['name'])) {
                    $target_dir = "imagesC/";
                    if($row['customer_img']!='no_photo_c.png'){
                    unlink($target_dir . $row['customer_img']);
                    }
                    $target_file = $target_dir . basename($_FILES["cimg"]["name"]);
                    $isUploadOK = TRUE;
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $check = getimagesize($_FILES["cimg"]["tmp_name"]);
                    if ($check !== false) {
                        $isUploadOK = TRUE;
                    } else {
                        $flag = 1;
                        $message .= "File is not an image.<br>";
                        $isUploadOK = FALSE;
                    }


                    if ($_FILES["cimg"]["size"] > 5000000) {
                        $flag = 1;
                        $message .= "Sorry, your file is too large.<br>";
                        $isUploadOK = FALSE;
                    }
                    // Allow certain file formats
                    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                        $flag = 1;
                        $message .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
                        $isUploadOK = FALSE;
                    }
                    // Check if $uploadOk is set to 0 by an error
                    if ($isUploadOK == FALSE) {
                        $flag = 1;
                        $message .= "Sorry, your file was not uploaded."; // if everything is ok, try to upload file
                    } else {
                        if (move_uploaded_file($_FILES["cimg"]["tmp_name"], $target_file)) {
                          
                        } else {
                            $flag = 1;
                            $message .= "Sorry, there was an error uploading your file.<br>";
                        }
                    }
                }  
                else{
                    $customer_img=$row['customer_img'];

                }

                if (empty($old_pass) && empty($new_pass) && empty($confirm_new_pass)) {
                    $flag = 0;
                    $unchange_new_password = $row['password'];
                    $stmt->bindParam(':password', $unchange_new_password);
                }


                if (empty($email)) {
                    $flag = 1;
                    $message = "Please insert your email.";
                }

                if (empty($fname)) {
                    $flag = 1;
                    $message = "Please insert your first name.";
                }
                if (empty($lname)) {
                    $flag = 1;
                    $message = "Please insert your last name.";
                }


                if (empty($gender)) {
                    $flag = 1;
                    $message = "Please select gender.";
                }

                if (empty($dob)) {
                    $flag = 1;
                    $message = "Please select your date of birth.";
                }
                // bind the parameters


                $stmt->bindParam(':email', $email);
                $stmt->bindParam(':fname', $fname);
                $stmt->bindParam(':lname', $lname);
                $stmt->bindParam(':gender', $gender);
                $stmt->bindParam(':accountstatus', $accountstatus);
                $stmt->bindParam(':dob', $dob);
                $stmt->bindParam(':customer_img', $customer_img);
                $stmt->bindParam(':username', $id);
                // Execute the query

                if ($flag == 0) {
                    if ($stmt->execute()) {
                        echo "<div class='alert alert-success'>Record was updated.</div>";
                    } else {
                        echo $message = "Unable to update record. Please try again.";
                    }
                } else {


                    echo "<div class='alert alert-danger'>" . $message . "</div>";
                }
            }
            // show errors
            catch (PDOException $exception) {
                die('ERROR: ' . $exception->getMessage());
            }
        } 
        
       
        if(isset($_POST['delete_img'])){
            $target_dir = "imagesC/";
            unlink($target_dir . $row['customer_img']);
            $customer_img = 'no_photo_c.png';
            $target_file = $target_dir . basename($_FILES["cimg"]["name"]);

            $deleteQ='UPDATE customers SET customer_img=:customer_img WHERE username = :username';
            $stmt = $con->prepare($deleteQ);
            $stmt->bindParam(':username', $id);
            $stmt->bindParam(':customer_img', $customer_img);
            $stmt->execute();

        }
        ?>



        <!--we have our html form here where new record information can be updated-->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$username}"); ?>" method="post" enctype="multipart/form-data">
            <table class='table table-hover table-responsive table-bordered'>
                <tr>
                    <td>Image</td>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"] . "?id={$username}"); ?>" method="post" enctype="multipart/form-data">
                    <td><img src="imagesC/<?php echo $customer_img; ?>" width="200px"><br>
                    <input type="file" name="cimg" id="fileToUpload" />
                     <input type="submit" class="btn btn-danger" name="delete_img" value='Delete Photo' /></td>
                    </form>
                </tr>
                <tr>
                    <td>Username</td>
                    <td><input type='text' name='username' value="<?php echo htmlspecialchars($username, ENT_QUOTES);  ?>" class='form-control' disabled /></td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><input type='email' name='email' value="<?php echo htmlspecialchars($email, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>First Name</td>
                    <td><input type='text' name='fname' value="<?php echo htmlspecialchars($fname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Last Name</td>
                    <td><input type='text' name='lname' value="<?php echo htmlspecialchars($lname, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><input type='radio' name='gender' value='Male' <?php if ($gender == 'Male') {
                                                                            echo "checked";
                                                                        } ?> />
                        <label class="form-check-label">Male</label>
                        <input type='radio' name='gender' value='Female' <?php if ($gender == 'Female') {
                                                                                echo "checked";
                                                                            } ?> />
                        <label class="form-check-label">Female</label>

                    </td>
                </tr>
                <tr>
                    <td>Account Status</td>
                    <td><input type='radio' name='accountstatus' value='Active' <?php if ($accountstatus == 'Active') {
                                                                                    echo "checked";
                                                                                } ?> />
                        <label class="form-check-label">Active</label>
                        <input type='radio' name='accountstatus' value='Inactive' <?php if ($accountstatus == 'Inactive') {
                                                                                        echo "checked";
                                                                                    } ?> />
                        <label class="form-check-label">Inactive</label>

                    </td>
                </tr>
                <tr>
                    <td>Date of Birth</td>
                    <td><input type='date' name='dob' value="<?php echo htmlspecialchars($dob, ENT_QUOTES);  ?>" class='form-control' /></td>
                </tr>
                <tr>
                    <td>Old Password</td>
                    <td><input type='text' name='old_pass' class='form-control' /></td>
                </tr>
                <tr>
                    <td>New Password</td>
                    <td><input type='text' name='new_pass' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Comfirm Password</td>
                    <td><input type='text' name='confirm_new_pass' class='form-control' /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save Changes' class='btn btn-primary' />
                        <a href='read_customer.php' class='btn btn-danger'>Back to read customer</a>
                    </td>
                </tr>
            </table>
        </form>


    </div>
    <!-- end .container -->
</body>

</html>