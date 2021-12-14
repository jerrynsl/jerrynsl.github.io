<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS → -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
  <title>Customer Login</title>
</head>

<body class="p-5 mt-5">
  <?php
  session_start();
  if ($_POST) {
    $flag = 0;
    $message = '';
    // include database connection
    include 'config/database.php';

    if (empty($_POST['username'])) {
      $flag = 1;
      $message = "Please insert your username.<br>";
    }
    if (empty($_POST['password'])) {
      $flag = 1;
      $message .= "Please insert your password.";
    }

    if ($flag == 0) {
      $username = $_POST['username'];
      if (filter_var($username, FILTER_VALIDATE_EMAIL)) {
        $q = 'SELECT username, email, password, accountstatus from customers WHERE email= ?';
      } else {
        $q = 'SELECT username, email, password, accountstatus from customers WHERE username= ?';
      }
      $stmt = $con->prepare($q);
      $stmt->bindParam(1, $username);
      $stmt->execute();
      $num = $stmt->rowCount();
      if ($num > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (md5($_POST['password']) == $row['password']) {
          if ($row['accountstatus'] == 'Active') {
            $_SESSION['username']=$username;
            header("Location:home.php");
          } else {
            $flag = 1;
            $message = 'Please tell admin to activate your account.';
          }
        } else {
          $flag = 1;
          $message = 'Your username or password is incorrect';
        }
      } else {
        $flag = 1;
        $message = "Username does not exists.";
      }
    }
  }
  ?>

  <div class="container container-sm text-center w-50 ">
    <h3>Please sign in</h3>
    <?php
   if (isset($_GET['msg']) && $_GET['msg']=='logout') {
    echo "<div class='alert alert-success'>Log Out Succesful</div>";
   }
   if (isset($_GET['msg']) && $_GET['msg']=='pleaselogin'){
    $flag = 1;
    $message = 'Please login first, then can access to next page.';
   }
    if (isset($flag) && $flag == 1) {
      echo "<div class='alert alert-danger'>$message</div>";
    }
    ?>
    <form method='POST' action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="row d-inline-flex p-2 mt-3">
      <div class="mb-3 items-align-center">

        <input type="text" class="form-control form-control-lg  col col-sm-4" id="username" placeholder="Username" name='username' />
        <input type="password" class="form-control form-control-lg  col col-sm-4" id="password" placeholder="Password" name='password' />

      </div>
      <button type="submit" class="btn btn-primary btn-lg ">Sign in</button>

      <p>Don't have an account? <a href="create_customer.php">Sign up now</a>.</p>




  </div>


  </form>

  </div>



  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>