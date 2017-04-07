<?php

if ($_COOKIE['moto_user_info']) {
  session_id($_COOKIE['moto_user_info']);
}

session_start();
if(isset($_SESSION) && isset($_SESSION['token']))
{
	
  	echo 'loggedin';
}
else
{
	
	echo 'loggedout';
}

?>