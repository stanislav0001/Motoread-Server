<?php
session_start();

session_unset(); 

session_destroy(); 

if(isset($_COOKIE["moto_user_info"])) {

	setcookie("moto_user_info", "", time() - 3600);

}

header('Location: login.php');

?>
