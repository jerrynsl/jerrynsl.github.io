<!DOCTYPE HTML>
<html>

<head>
  <title>Create Customer</title>
  <!-- Latest compiled and minified Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>
  <!-- container -->
  <div class="container">
    <?php
    session_start();
    if (isset($_SESSION['username'])){
      include 'navbar.php';
    }
    ?>
    <div class="page-header">
      <h1>Create Customer</h1>
    </div>


    <?php
    if ($_POST) {

      // include database connection
      include 'config/database.php';
      try {
        // insert query
        $query = "INSERT INTO customers SET username=:username, email=:email, password=:password, fname=:fname, lname=:lname, gender=:gender, dob=:dob, customer_img=:customer_img";
        // prepare query for execution
        $stmt = $con->prepare($query);
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = ($_POST['password']);
        $confirmpassword = ($_POST['confirmpassword']);
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $gender = $_POST['gender'];
        $dob = $_POST['dob'];
        $customer_img =  basename($_FILES["cimg"]["name"]);

        $flag = 0;
        $message = "";

        if(!empty($_FILES['cimg']['name'])){
        $target_dir = "imagesC/";
        $target_file = $target_dir . basename($_FILES["cimg"]["name"]);
        $isUploadOK = TRUE;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $check = getimagesize($_FILES["cimg"]["tmp_name"]);
        if ($check !== false) {
          echo "File is an image - " . $check["mime"] . ".";
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
            echo "The file " . basename($_FILES["cimg"]["name"]) . " has been uploaded.";
          } else {
            $flag = 1;
            $message .= "Sorry, there was an error uploading your file.<br>";
          }
        }
      }else{

        $customer_img='no_photo_c.png';
      }


        if (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
          $flag = 1;
          $message = "Your password must contain alphabets and number.";
        }


        if (empty($_POST['username'])) {

          $flag = 1;

          $message .= "Please insert your username.<br>";
        }

        if (empty($email)) {

          $flag = 1;

          $message .= "Please insert your email.<br>";
        }

        if (empty($fname)) {

          $flag = 1;

          $message .= "Please insert your first name.<br>";
        }

        if (empty($lname)) {

          $flag = 1;

          $message .= "Please insert your last name.<br>";
        }


        if (empty($gender)) {

          $flag = 1;

          $message .= "Please select gender.<br>";
        }

        if (empty($dob)) {

          $flag = 1;

          $message .= "Please select your date of birth.<br>";
        }

        if ($password == $confirmpassword) {

          $password = md5($password);
        } else {
          $flag = 1;
          $message .= "Password is not same as Confirm Password.<br>";
        }


        // bind the parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
        $stmt->bindParam(':customer_img', $customer_img);



        if ($flag == 0) {

          if ($stmt->execute()) {

            header('Location:index.php');
          } else {
            $flag = 1;
            $message = "Unable to save record.";
          }
        } else {

          echo "<div class='alert alert-danger'>" . $message . "</div>";
        }
        // Execute the query

      }
      // show error
      catch (PDOException $exception) {
        die('ERROR: ' . $exception->getMessage());
      }
    }

    ?>

    <!-- html form here where the product information will be entered -->
    <?php


    ?>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST" enctype="multipart/form-data">
      <table class='table table-hover table-responsive table-bordered'>
        <tr>
          <td>Username</td>
          <td><input type='text' name='username' value='<?php if($_POST) {echo $_POST['username'];} ?>' class='form-control' /></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type='email' name='email' value='<?php if($_POST) {echo $_POST['email'];} ?>'class='form-control' /></td>
        </tr>
        <tr>
          <td>Password</td>
          <td><input type='password' name='password' class='form-control' /></td>
        </tr>

        <tr>
          <td>Confirm Password</td>
          <td><input type='password' name='confirmpassword' class='form-control' /></td>
        </tr>

        <tr>
          <td>First Name</td>
          <td><input type='text' name='fname' value='<?php if($_POST) {echo $_POST['fname'];} ?>'class='form-control' /></td>
        </tr>
        <tr>
        <tr>
          <td>Last Name</td>
          <td><input type='text' name='lname' value='<?php if($_POST) {echo $_POST['lname'];} ?>'class='form-control' /></td>
        </tr>

        <tr>
          <td>Gender</td>
          <td><input type='radio' name='gender' value='Male' class='form-check-input' />
            <label class="form-check-label">Male</label>
            <input type='radio' name='gender' value='female' class='form-check-input' />
            <label class="form-check-label">Female</label>

          </td>

        </tr>

        <tr>
          <td>Date of Birth</td>
          <td><input type='date' name='dob' value='<?php if($_POST) {echo $_POST['dob'];} ?>'class='form-control' /></td>
        </tr>

        <tr>
          <td>Select image (optional):</td>
          <td> <input type="file" name="cimg" id="fileToUpload">
          </td>
        </tr>

        <tr>
          <td></td>
          <td>
            <input type='submit' value='Register' class='btn btn-primary' />
            <?php
            if (isset($_SESSION['username'])){
            echo "<a href='read_customer.php' class='btn btn-danger'>Back to Read Customer</a>";
          }else{
            echo "<a href='index.php' class='btn btn-danger'>Back to Login Page</a>";
          }
            ?>
          </td>
        </tr>
      </table>
    </form>





  </div>
  <!-- end .container -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>