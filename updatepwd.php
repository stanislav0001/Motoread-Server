<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: login.php');
}

if(isset($_POST['submit']))
{
  $new_pwd = $_POST['new_pwd'];
  $pwd = $_POST['pwd'];

//https://esoftappslive.com/sonu/motoread/api/api.php?rquest=update_email

  $post = [
    'new_pwd' => $new_pwd ,
    'pwd' => $pwd,
    'user_id' => $_SESSION['user_id']
  ];

  $headers = [
  'token:'.$_SESSION['token'].' ',
  ];

  $ch = curl_init(API_BASE_URL.'change_password');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  if($result['status'] == 'success')
  {
    $res_status = 1;
    $status_msg = $result['msg'];
  }
  else
  {
    $res_status = 0;
    $status_msg = $result['msg'];
  }

}



  $post = [

    'user' => $_SESSION['user_id']
  ];

  $headers = [
  'token:'.$_SESSION['token'].' ',
  ];

  $ch = curl_init(API_BASE_URL.'account');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  if ($result['status'] == 'success') {
    $email = $result['email'];
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
  <title>Change Password</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="css/moto-style.css" rel="stylesheet" type="text/css">
  <link href="css/uikit.min.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
  <!-- HEADER  -->
  <?php include_once 'includes/header.php'; ?>
  <!-- /. HEADER -->

  <!-- update email -->
  <div class="accountPageWrap">
    <div class="container">
      <div class="midde-box">
          <form onsubmit="return ValidationEvent();" action="" method="post">
            <div class="form-group">
              <h2><?php echo $email; ?></h2>
            </div>
            <div class="form-group">
              <label for="pwd">Current Password:</label>
              <input type="password" class="form-control" name="pwd" required>
            </div>
            <div class="form-group">
              <label for="pwd">New Password:</label>
              <input type="password" class="form-control" name="new_pwd" id="pwd1" required>
            </div>
            <div class="form-group">
              <label for="pwd">Confirm Password:</label>
              <input type="password" class="form-control" id="pwd2" required><span id="error_match" class="error_match"></span>
            </div>
            <button type="submit" name="submit" class="btn btn-default">Change Password</button>

            <?php
            if(isset($res_status) && $res_status == 1)
            {
              // echo $status_msg;
              echo '<span class="success">'.$status_msg.'</span>';
            }
            elseif(isset($res_status) && $res_status == 0)
            {
              // echo $status_msg;
              echo '<span class="error">'.$status_msg.'</span>';
            }

            ?>
          </form>
      </div>
    </div>
  </div>
  <!-- update email -->

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

      // var x = jQuery('#pwd1').val();
      // alert(x);
      if($('#pwd1').val() != $('#pwd2').val())
      {
        $('#error_match').html('New Password and Confirm Password Doesn\'t match');
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
