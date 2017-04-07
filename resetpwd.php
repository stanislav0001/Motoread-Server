<?php

include_once 'includes/config.php';

if(isset($_SESSION['token']))
{
  header('Location: index.php');
}

if (isset($_POST['change_pwd'])) {

  $post = [
    'action' => $_GET['reset'],
    'pwd' => $_POST['pwd'],
  ];

  $ch = curl_init(API_BASE_URL.'reset_pwd');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  if($result['status']=='success')
  {
    $status_msg = $result['msg'];
    $res_status = 1;
  }
  else
  {
    $status_msg = "Invalid URL";
    $res_status = 0;
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
    <title>Password Reset</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/moto-style.css" rel="stylesheet" type="text/css">
    <link href="css/uikit.min.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <!-- HEADER  -->
    <?php include_once 'includes/header.php' ?>
    <!-- /. HEADER -->

  <!-- login -->
  <div class="loginWrap">
    <div class="container">
      <div class="loginForm">
        <h3>Password Reset</h3>
          <form onsubmit="return ValidationEvent();" action="" method="post">

            <div class="form-group">
              <label for="pwd">Password:</label>
              <input type="password" name="pwd" class="form-control" id="pwd1" required>
            </div>
            <div class="form-group">
              <label for="pwd">Confirm Password:</label>
              <input type="password" class="form-control" id="pwd2" required> <span id="error_match" class="error_match"></span>
            </div>

            <button type="submit" name="change_pwd" class="btn btn-default">Confirm</button>
            <?php
            if($res_status == 1 && isset($res_status))
            {
              echo '<span class="success">'.$status_msg.'</span>';
            }
            elseif($res_status == 0 && isset($res_status))
            {
              echo '<span class="error">'.$status_msg.'</span>';
            }

            ?>
            <div class="form-group text-center">
              <a class="signupBtn" href="login.php">Login Here</a>
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
  <script type="text/javascript">

    function ValidationEvent(){
      if($('#pwd1').val() != $('#pwd2').val())
      {
        $('#error_match').html('Password and Confirm Password Doesn\'t match');
        return false;
      }
      else
      {
        return true;
      }
    }

  </script>
  </body>
  </html>
