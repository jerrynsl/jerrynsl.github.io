<?php

session_start();

// remove all session variables
session_unset(); 

// destroy the session 
session_destroy();

header('Location:customer_login.php?msg=logout');

?>