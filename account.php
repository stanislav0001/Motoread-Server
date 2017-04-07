<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: login.php');
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
  <title>My Account</title>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
  <link href="css/moto-style.css" rel="stylesheet" type="text/css">
  <link href="css/uikit.min.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
  <!-- HEADER  -->
  <?php include_once 'includes/header.php'; ?>
  <!-- /. HEADER -->
<div class="accountPageWrap">
  <div class="container">
    <div class="midde-box">
        <h3>My Account</h3>
        <div class="row">
          <div class="col-sm-4">
            <label>Email ID</label>
          </div>
            <div class="col-sm-8">
            <?php

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

                echo '<div class="emailId">'.$email.'</div>';
              }

            ?>
            </div>
          </div>
          <div class="row accountBtns">
            <div class="col-sm-4">
              <!-- <a class="logoutBtn btn" href="logout.php" >Sign Out</a>  -->
            </div>
            <div class="col-sm-4">
              <a class="editAccount btn" href="updateemail.php" >Edit</a>
            </div>
            <div class="col-sm-4">
              <a class="changePass btn" href="updatepwd.php" >Change Password</a>
            </div>

          </div>
</div>
  </div>
</div>
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
