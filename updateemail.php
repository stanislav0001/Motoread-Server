<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: login.php');
}

if(isset($_POST['submit']))
{
  $email = $_POST['email'];
  $pwd = $_POST['pwd'];
  $post = [
    'new_email' => $email ,
    'pwd' => $pwd,
    'user_id' => $_SESSION['user_id']
  ];

  $headers = [
  'token:'.$_SESSION['token'].' ',
  ];

  $ch = curl_init(API_BASE_URL.'update_email');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  if($result['status'] == 'success')
  {
    $_SESSION['email'] = $email;
    $res_status = 1;
    $status_msg = 'Email Updated Successfully';
  }
  else
  {
    $res_status = 0;
    $status_msg = $result['msg'];
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
  <title>Update Email</title>
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
        <h3>Update Email</h3>
          <form action="" method="post">
            <div class="form-group">
              <label for="email">New Email</label>
              <input type="email" name="email" class="form-control" name="email" id="email" required>
            </div>
            <div class="form-group">
              <label for="pwd">Password:</label>
              <input type="password" name="pwd" class="form-control" name="pwd" id="pwd" required>
            </div>
            <button type="submit" name="submit" class="btn btn-default">Update</button>

            <?php
            if(isset($res_status) && $res_status == 1)
            {
              // echo $status_msg;
              echo '<span class="success">'.$status_msg.'</span>';
            }
            elseif(isset($res_status) && $res_status == 0)
            {
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
</body>
</html>
