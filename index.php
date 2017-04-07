<?php

if(isset($_COOKIE['moto_user_info']))
{
  session_id($_COOKIE['moto_user_info']);
}

session_start();
// if(!isset($_SESSION['token']))
// {
//   header('Location: login.php');
// }

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>MOTOREAD</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/moto-style.css" rel="stylesheet" type="text/css">
    <link href="css/uikit.min.css" rel="stylesheet" type="text/css">
  </head>
  <body class="comingSoon">
    <!-- HEADER  -->
    <?php // include_once 'includes/header.php'; ?>
    <!-- /. HEADER -->

<div class="comingSoonWrap">
	<img src="images/motoread-icon-white.png" />
	<h3>REVOLUTIONIZE THE WAY YOU CONSUME NEWS</h3>
	<p>coming soon</p>
</div>

<!-- footer -->
<?php // include_once 'includes/footer.php' ?>
<!-- /. footer -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/uikit.min.js"></script>
  </body>
  </html>
