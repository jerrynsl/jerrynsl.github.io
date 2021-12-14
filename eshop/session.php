<?php

session_start();

if(!isset($_SESSION['username'])){
    
    header('Location:customer_login.php?msg=pleaselogin');

}


?>