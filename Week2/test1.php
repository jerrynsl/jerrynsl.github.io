<!--Student ID: 2030357-BSE 
Name: Ng Si Liang 
Topic: Homework 1 about use PHP to generate date select menu-->
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS → -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
  <title>Homework 1 PHP generate select menu droplist</title>
</head>

<body>

  <h1>What is your date of birth? </h1>

  <div class="btn-group">
    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      Day
    </button>
    <ul class="dropdown-menu">
      <?php



      for ($day = 1; $day <= 31; $day++) {

        echo "<li><a class='dropdown-item fs-6'>" . $day . "</a></li>";
      }



      ?>
    </ul>
  </div>

  <div class="btn-group">
    <button class="btn btn-warning btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      Month
    </button>
    <ul class="dropdown-menu">
      <?php



      for ($month = 1; $month <= 12; $month++) {

        echo "<li><a class='dropdown-item fs-6'>" . $month . "</a></li>";
      }



      ?>
    </ul>
  </div>

  <div class="btn-group">
    <button class="btn btn-danger btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
      Year
    </button>
    <ul class="dropdown-menu">
      <?php



      for ($year = 1900; $year <= 2021; $year++) {

        echo "<li><a class='dropdown-item fs-6'>" . $year . "</a></li>";
      }



      ?>
    </ul>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>