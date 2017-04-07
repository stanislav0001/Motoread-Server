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
  <body class="main">
    <!-- HEADER  -->
    <?php include_once 'includes/header.php'; ?>
    <!-- /. HEADER -->

<!-- Banner -->
<div class="moto-banner">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <h1 data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:false, delay:300}">Listen to your articles on your smartphone</h1>
      </div>
      <div class="col-sm-6">
        <div class="motoread-mobile text-right" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">
          <img src="images/moto-mobile.png" alt="Motoread Mobile">
        </div>
      </div>
    </div>
  </div>
</div><!-- /. Banner-->

<!-- App Store Buttons -->
<div class="app-store-btns">
  <div class="app-store-btns-inner">
    <div class="container">
      <div class="row">
        <div class="col-sm-4">
          <div class="storeBtn text-center" data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:false, delay:300}">
            <a href="https://chrome.google.com/webstore/detail/motoread/gahlocjoecnofhfhjlidompcnhcnigii" target="_blank"><img src="images/crome-store.png" alt="Crome Store"></a>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="storeBtn text-center text-center" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">
            <a href="#"><img src="images/app-store.png" alt="App Store"></a>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="storeBtn text-center" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">
            <a href="#"><img src="images/play-store.png" alt="Play Store"></a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div><!-- /. App Store Buttons -->

<!-- Add Article -->
<div class="articleSec addArticle">
  <div class="container">
    <div class="row">
      <div class="col-sm-5">
        <h1 data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:false, delay:300}">Add articles from Chrome to your playlist</h1>
      </div>
      <div class="col-sm-6 col-sm-offset-1">
        <div class="articleSec-right" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">
          <img src="images/crome-store2.png" alt="Crome Store">
          <h4>To add articles to your playlist click on the Motoread plugin icon on the top of your browser when you are viewing an article, or right click a link and select “Add to Motoread”.  </h4>
        </div>
      </div>
    </div>
  </div>
</div><!-- /. Add Article -->

<!-- Syns Article -->
<div class="articleSec articleSyns">
  <div class="container">
    <div class="row">
      <div class="col-sm-6 pull-right">
        <h1 data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">Articles will sync to your mobile device </h1>
      </div>
      <div class="col-sm-6">
        <div class="articleSec-right" data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:false, delay:300}">
          <a href="#"><img src="images/app-store.png" alt="app Store"></a>
          <a href="#"><img src="images/play-store.png" alt="app Store"></a>
        </div>
      </div>
    </div>
  </div>
</div><!-- /. Syns Article -->

<!-- Listen Article -->
<div class="articleSec listenArticle">
  <div class="container">
    <div class="row">
      <div class="col-sm-6">
        <h1 data-uk-scrollspy="{cls:'uk-animation-slide-left', repeat:false, delay:300}">Listen to your articles like they are podcasts </h1>
      </div>
      <div class="col-sm-5 col-sm-offset-1">
        <div class="articleSec-right" data-uk-scrollspy="{cls:'uk-animation-slide-right', repeat:false, delay:300}">
          <h3>ADJUSTABLE PLAYBACK SPEED SAVED ARTICLE HISTORY </h3>
        </div>
      </div>
    </div>
  </div>
</div><!-- /. Listen Article -->

<!-- Need Help Section -->
<div class="needHelp">
  <div class="container">
    <div class="needHelpInner" >
      <h3 data-uk-scrollspy="{cls:'uk-animation-slide-top', repeat:false, delay:300}">Need some help?</h3>
      <a href="btn supportBtn" data-uk-scrollspy="{cls:'uk-animation-slide-top', repeat:false, delay:300}">Get Support</a>
    </div>
  </div>
</div><!-- /. Need Help Section -->


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
