<?php

include_once 'includes/config.php';

if(isset($_SESSION['token']))
{
  header('Location: article.php');
}
 // $current_url = 'https://'.$_SERVER['SERVER_NAME'].''.$_SERVER['REQUEST_URI'];
if (isset($_POST['login'])) {

  $post = [
    'email' => $_POST['email'],
    'pwd' => $_POST['password'],
  ];

  $ch = curl_init(API_BASE_URL.'login');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  if($result['status']=='success')
  {
    $_SESSION['user_id']=$result['user_id'];
    $_SESSION['email']=$result['email'];
    $_SESSION['token']=$result['token'];

    $phpsessionid = $_COOKIE['PHPSESSID'];
    $cookie_name = 'moto_user_info';
    setcookie($cookie_name, $phpsessionid, 2147483647, "/");


    if(isset($_GET['save']))
    {
      header('Location: saveart.php?url='.$_GET['save'].'');
    }
    else
    {
      header('Location: article.php');
    }

  }
  else
  {
    $error_status = 1;
  }

}

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
  <body class="login">
    <!-- HEADER  -->
    <?php include_once 'includes/header.php' ?>
    <!-- /. HEADER -->

  <!-- login -->
  <div class="loginWrap">
    <div class="container">
      <div class="loginForm">
        <h3>Login</h3>
          <?php
            if(isset($_GET['save']))
            {
              echo '<div class="alert alert-warning alert-dismissable">
                      <strong>Please sign in to Motoread to save this page.
                    </div>';
            }

            if(isset($_GET['ref']) && !isset($error_status))
            {
              echo '<div class="alert alert-success alert-dismissable">
                      <strong>Account Created Successfully</strong>
                    </div>';
            }
          ?>
          <form action="" method="post">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" name="email" class="form-control" id="email">
            </div>
            <div class="form-group">
              <label for="pwd">Password:</label>
              <input type="password" name="password" class="form-control" id="pwd">
            </div>
            <div class="form-group text-right">
              <a class="forgotPass" href="forgetpwd.php">Forgot Password</a>
            </div>
           <!--  <div class="checkbox">
              <label><input type="checkbox" name="remember"> Remember me</label>
            </div> -->
            <button type="submit" name="login" class="btn btn-default">Submit</button>

            <?php
            if(isset($error_status) && $error_status == 1)
            {
              echo '<span class="error">Email or Password Doesn\'t match</span>';
            }
            ?>
            <div class="form-group text-center">
            <a class="signupBtn" href="register.php">Don't have an account? <span>Sign Up Now</span></a>
          </div>
          </form>
      </div>
    </div>
  </div><!-- /. login -->



  <!-- footer -->
  <?php include_once 'includes/footer.php' ?>
  <!-- /. footer -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/uikit.min.js"></script>
<script src="js/stickyfill.js"></script>
<script src="js/moto.js"></script>
  </body>
  </html>
