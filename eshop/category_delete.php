<?php
// include database connection
include 'config/database.php';
try {     
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id=isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    $qconfirm='SELECT categories.category_id, products.category_id FROM categories INNER JOIN products ON products.category_id = categories.category_id WHERE categories.category_id=?';
    $stmt = $con->prepare($qconfirm);
    $stmt->bindParam(1,$id);
    $stmt->execute();
    $num = $stmt->rowCount();
    echo $num;
    if($num>0){
        header('Location: category_read.php?action=deny');
    }else{
    // delete query
    $query = "DELETE FROM categories WHERE category_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);
    
     
    if($stmt->execute()){
        // redirect to read records page and
        // tell the user record was deleted
        header('Location: category_read.php?action=deleted');
    }else{
        die('Unable to delete record.');
    }
    }
}
// show error
catch(PDOException $exception){
    die('ERROR: ' . $exception->getMessage());
}
?>
