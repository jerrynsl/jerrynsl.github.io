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
    <nav class="navbar navbar-expand-lg navbar-dark navbar-sm-light bg-light ">
      <div class="container-sm border ">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse " id="navbarSupportedContent">
          <ul class="navbar-nav justify-content-end ">
            <li class="nav-item">
              <a class="nav-link active text-primary" aria-current="page" href="home.php">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-primary" href="product_create.php">Create Product</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-primary" href="create_customer.php">Create Customer</a>

            </li>
            <li class="nav-item ">
              <a class="nav-link text-primary" href="contact.php">Contact Us</a>
            </li>
          </ul>

        </div>
      </div>
    </nav>
    <div class="page-header">
      <h1>Create Customer</h1>
    </div>


    <?php
    if ($_POST) {
      // include database connection
      include 'config/database.php';
      try {
        // insert query
        $query = "INSERT INTO customers SET username=:username, email=:email, password=:password, fname=:fname, lname=:lname, gender=:gender, dob=:dob";
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

        $flag = 0;
        $message = "";


        if (!preg_match("/[a-zA-Z]/", $password) || !preg_match("/[0-9]/", $password)) {
          $flag = 1;
          $message = "Your password must contain alphabets and number.";
        }
        
        // else if (!preg_match("/[a-zA-Z0-9]{6,}/",$password)){

        //   $flag=1;
        //   $message = "Your password must contain alphabets and number.";

        // }
        // $checkAl = preg_match("/[a-zA-Z]/",$password);
        // echo $checkAl;
        // if(!preg_match("[a-zA-Z]",$password) ){
        //   echo "<div class='alert alert-danger'>Your password must contain alphabets.</div>";

        // }

        if (empty($_POST['username'])) {

          $flag = 1;
          
          $message = "Please insert your username.";
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


        if(empty($gender)){

          $flag = 1;

          $message = "Please select gender.";

        }

        if(empty($dob)){

          $flag = 1;

          $message = "Please select your date of birth.";

        }

        if ($password == $confirmpassword) {

          $password = md5($password);

         
        } else {
          $flag = 1;
          $message = "Password is not same as Confirm Password.";
        }


        // bind the parameters
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':gender', $gender);
        $stmt->bindParam(':dob', $dob);
    

      

        if($flag == 0) {

          if ($stmt->execute()) {

            echo "<div class='alert alert-success'>Record was saved.</div>";

          } else {
            $flag = 1;
            $message = "Unable to save record.";
          }
         
        }else {

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
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="POST">
      <table class='table table-hover table-responsive table-bordered'>
        <tr>
          <td>Username</td>
          <td><input type='text' name='username' class='form-control' /></td>
        </tr>
        <tr>
          <td>Email</td>
          <td><input type='email' name='email' class='form-control' /></td>
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
          <td><input type='text' name='fname' class='form-control' /></td>
        </tr>
        <tr>
        <tr>
          <td>Last Name</td>
          <td><input type='text' name='lname' class='form-control' /></td>
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
          <td><input type='date' name='dob' class='form-control' /></td>
        </tr>



        <tr>
          <td></td>
          <td>
            <input type='submit' value='Register' class='btn btn-primary' />
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