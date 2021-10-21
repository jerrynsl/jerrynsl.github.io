<!--Student ID: 2030357-BSE 
Name: Ng Si Liang 
Topic: Homework 1 about use PHP to generate date select menu [array]-->
<!doctype html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS → -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
  <title>Homework 2 PHP Select Option</title>
</head>

<body>
  <form>
    <h1>What is your date of birth? </h1>





    <select class="bg-primary fs-2 rounded" id="day" name="day">
      <option class='bg-white' selected>Day</option>
      <?php



      for ($day = 1; $day <= 31; $day++) {

        echo "<option class='bg-white' value='$day'>$day</option>";
      }



      ?>
    </select>


    <select class="bg-warning fs-2 rounded" id="day" name="month">
      <option class='bg-white' selected>Month</option>
      <?php

      $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

      for ($c = 0; $c <= 11; $c++) {

        echo "<option class='bg-white' value='" . ($c + 1) . "'>$month[$c]</option>";
      }



      ?>
    </select>

    <select class="bg-danger fs-2 rounded" id="day" name="year">
      <option class='bg-white' selected>Year</option>
      <?php



      for ($year = 1900; $year <= date("Y"); $year++) {

        echo "<option class='bg-white' value='$year'>$year</option>";
      }



      ?>
    </select>

    <select class="bg-warning fs-2 rounded" id="day" name="month">
      <option class='bg-white' selected>Month</option>
      <?php

      $month = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

      foreach( $month as $c => $aMonth) {

        echo "<option class='bg-white' value='" . ($c + 1) . "'>$aMonth</option>";
      }



      ?>
    </select>

  </form>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-kQtW33rZJAHjgefvhyyzcGF3C5TFyBQBA13V1RKPf4uH+bwyzQxZ6CmMZHmNBEfJ" crossorigin="anonymous"></script>
</body>

</html>