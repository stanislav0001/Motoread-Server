<?php

if(isset($_SESSION['token']))
{
  $header_menu = '<li>
                  <a href="article.php">My Articles</a>
                </li>
                <li>
                  <a href="account.php">My Account</a>
                </li>
                <li>
                  <a href="logout.php">Log Out</a>
                </li>';
}
else
{
  $header_menu = '<li>
                  <a href="login.php">Log in</a>
                </li>';
}

?>
<!-- <a href="logout.php">Log out</a> -->
<header>
      <div class="container">
        <div class="row">
          <div class="col-xs-6">
            <div class="moto-logo">
              <a href="/main.php">
                <img src="/images/logo-white.png" />
              </a>
            </div>
          </div>
          <div class="col-xs-6">
            <div class="moto-menu text-right">
              <ul>
               <?php
                echo $header_menu;
               ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <div class="clearfix"></div>
    </header>
