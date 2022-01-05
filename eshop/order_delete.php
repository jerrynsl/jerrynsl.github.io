<?php
// include database connection
include 'config/database.php';
try {
    // get record ID
    // isset() is a PHP function used to verify if a value is there or not
    $id = isset($_GET['id']) ? $_GET['id'] :  die('ERROR: Record ID not found.');

    // delete query
    $query = "SELECT * FROM order_details WHERE order_id = ?";
    $stmt = $con->prepare($query);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $num = $stmt->rowCount();

    for ($x = 0; $x <= $num; $x++) {
        $query = "DELETE FROM order_details WHERE order_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bindParam(1, $id);
        $stmt->execute();
    }

    $query2 = "DELETE FROM order_summary WHERE order_id = ?";
    $stmt2 = $con->prepare($query2);
    $stmt2->bindParam(1, $id);
    if ($stmt2->execute()) {
        header('Location: order_read.php?action=deleted');
    } else {
        die('Unable to delete record');
    }
}
// show error
catch (PDOException $exception) {
    die('ERROR: ' . $exception->getMessage());
}
