<!DOCTYPE HTML>
<html>

<head>
    <title>Create Category</title>
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
            <h1>Create Category</h1>
        </div>


        <?php
        if ($_POST) {
            // include database connection
            include 'config/database.php';
            try {
                // insert query
                $query = "INSERT INTO categories SET category_name=:category_name, category_description=:category_description";
                // prepare query for execution
                $stmt = $con->prepare($query);
                $cname = $_POST['name'];
                $cdescription = $_POST['description'];

                // bind the parameters
                $stmt->bindParam(':category_name', $cname);
                $stmt->bindParam(':category_description', $cdescription);

                // Execute the query

                $flag=0;
                $message='';

                if(empty($cname)){

                    $flag=1;
                    $message.='Please enter category name.';

                }

                if(empty($cdescription)){

                    $flag=1;
                    $message.='Please enter description.';

                }

                

                if ($flag==0){

                if ($stmt->execute()) {
                    echo "<div class='alert alert-success'>Record was saved.</div>";
                } else {
                    $message= 'Unable to save record.';
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
                    <td>Category Name</td>
                    <td><input type='text' name='name' class='form-control' /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea type='text' name='description' class='form-control' ></textarea></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type='submit' value='Save' class='btn btn-primary' />
                        <a href='category_read.php' class='btn btn-danger'>Back to read category</a>
                    </td>
                </tr>
            </table>
        </form>





    </div>
    <!-- end .container -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>