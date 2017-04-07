<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: login.php');
}

if(isset($_GET['article']))
{
  $article_id = $_GET['article'];
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
    <title>My Articles</title>
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="css/moto-style.css" rel="stylesheet" type="text/css">
    <link href="css/uikit.min.css" rel="stylesheet" type="text/css">
    <style type="text/css">
      .article_content{
        margin-top: 30px;
        margin-bottom: 30px;
      }
    </style>
  </head>
  <body>
    <!-- HEADER  -->
    <?php include_once 'includes/header.php'; ?>
    <!-- /. HEADER -->

<?php


$ch = curl_init(API_BASE_URL.'article&user='.$_SESSION['user_id'].'&article='.$article_id);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
'token: '.$_SESSION['token'].'',
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response,true);

?>

<div id="article">
<div class="container">

<h3><?php echo $result['data'][0]['article_title']; ?></h3>
<div id="article_content" >
  <?php

  echo preg_replace('/<figure\b[^>]*>(.*?)<\/figure>/i', '', $result['data'][0]['article_content_html']);

   // echo $result['data'][0]['article_content_html']
  ?>
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
