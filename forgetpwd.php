<?php

include_once 'includes/config.php';

if(isset($_SESSION['token']))
{
  header('Location: index.php');
}

if (isset($_POST['forgot_submit'])) {

  $post = [
    'email' => $_POST['email']
  ];

  $ch = curl_init(API_BASE_URL.'reset_password');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
  $response = curl_exec($ch);
  curl_close($ch);
  $result = json_decode($response,true);

  //echo $result['msg'];

  if($result['status'] == 'success')
  {
    $res_msg = '<span class="success">Passowrd reset link sent to mail</span>';
  }
  else
  {
    $res_msg = '<span class="error">'.$result['msg'].'</span>';
  }

  $res_status = 1;

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
        <h3>Forgot Passowrd</h3>
          <form action="" method="post">
            <div class="form-group">
              <label for="email">Email Address</label>
              <input type="email" name="email" class="form-control" id="email" required>
            </div>
            <button type="submit" name="forgot_submit" class="btn btn-default">Submit</button>
            <?php
            if (isset($res_status) && $res_status == 1) {
              echo $res_msg;
            }
            ?>
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

// function forgot_submit()
// {

//   $.ajax({
//     url: 'forgetpwd.php?delete_article=&article='+article_id,
//     dataType: 'text',
//     success: function(data){
//       if(data.trim() == 'success')
//       {
//         $('#row'+article_id).remove();
//       }
//     }
//   });
// }

</script>
  </body>
  </html>
