<?php

session_start();

if(!isset($_SESSION['username'])){
    
    header('Location:index.php?msg=pleaselogin');

}else{
   
}


?>