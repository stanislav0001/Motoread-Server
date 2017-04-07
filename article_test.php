<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: login.php');
}

if (isset($_GET['delete_article']) &&  isset($_GET['article'])) {

    $ch = curl_init(API_BASE_URL.'delete&user='.$_SESSION['user_id'].'&article='.$_GET['article']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers = [
    'token: '.$_SESSION['token'].'',
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response,true);
    echo $result['status'] ;
    die;
}

if(isset($_GET['favorite']) && isset($_GET['article']) && isset($_GET['action']))
{
  $ch = curl_init(API_BASE_URL.'favorite&user='.$_SESSION['user_id'].'&article='.$_GET['article'].'&action='.$_GET['action']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers = [
    'token: '.$_SESSION['token'].'',
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $response = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($response,true);
    echo $result['status'] ;
    die;
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
      .cursor_pointer{
        cursor: pointer;
      }
    </style>
  </head>
  <body>
    <!-- HEADER  -->
    <?php include_once 'includes/header.php'; ?>
    <!-- /. HEADER -->

<?php

//https://esoftappslive.com/sonu/motoread/api/api.php?rquest=search&user=20&q=trump

if (isset($_GET['q']) && !isset($_GET['h']) && trim($_GET['q']) != '') {
  $ch = curl_init(API_BASE_URL.'search&user='.$_SESSION['user_id'].'&q='.$_GET['q']);

  $search_text = $_GET['q'];
  $checkbox_val = '';
}
elseif(isset($_GET['q']) && $_GET['h'] == 1 && trim($_GET['q']) != '')
{
  $ch = curl_init(API_BASE_URL.'search&user='.$_SESSION['user_id'].'&q='.$_GET['q'].'&h=1');
  $search_text = $_GET['q'];
  $checkbox_val = 'checked';
}
else
{
  $ch = curl_init(API_BASE_URL.'article&user='.$_SESSION['user_id']);
  $search_text = '';
  $checkbox_val = '';
}

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$headers = [
'token: '.$_SESSION['token'].'',
];

curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
$response = curl_exec($ch);
curl_close($ch);
$result = json_decode($response,true);

$articles = '<table class="table">
    <thead>
      <tr>
        <th>Favourite</th>
        <th>Source</th>
        <th>Date</th>
        <th>Headlines</th>

        <th>Delete</th>
      </tr>
    </thead>
    <tbody>';

for ($i=0; $i < count($result['data']); $i++) {
  $j = count($result['data']) - ($i+1);
  $saved_date=date_create($result['data'][$j]['saved_date']);
  $saved_date = date_format($saved_date,"M d, Y");

  if($result['data'][$j]['is_favorite'] == 1)
  {
    $fav_action = 2;
    $fav = 'fav1.png';
  }
  else
  {
    $fav_action = 1;
    $fav = 'fav.png';
  }
  $article_id = $result['data'][$j]['user_article_id'];

  $articles .='<tr id="row'.$article_id.'">
        <td><img class="cursor_pointer" id="fav'.$article_id.'" onclick="favourite('.$fav_action.','.$article_id.');" src="images/'.$fav.'"></td>
        <td><img width="25px" src="'.$result['data'][$j]['favicon_url'].'"/></td>
        <td>'.$saved_date.'</td>
        <td><a href="content.php?article='.$article_id.'">'.$result['data'][$j]['article_title'].'</a></td>

        <td><img class="cursor_pointer" onclick="delete_article('.$article_id.');" src="images/delete.png"></td>
      </tr>';
}


$articles .='</tbody> </table>';

?>

<div id="article_list" class="articlesWrap">
  <div class="container">
      <form action="" method="get">
        <div id="search_div" class="search-wrap">
          <div class="row">
          <div class="col-sm-4">
              <div class="input-group">
                <input type="text" class="form-control" value="<?php echo $search_text; ?>" name="q">
                <span class="input-group-btn">
                  <button class="btn btn-secondary" name="submit" type="submit">Search</button>
                </span>
              </div>
          </div>
          <div class="col-sm-4">
            <label class="checkbox-inline">
              <input type="checkbox" name="h" value="1" <?php echo $checkbox_val; ?> >
              <span>Only Headlines</span>
            </label>
          </div>
        </div>
      </form>
    </div>
    <div class="articlesDiv">
      <?php echo $articles; ?>
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
<script type="text/javascript">

function delete_article(article_id)
{
  $.ajax({
    url: 'article.php?delete_article=&article='+article_id,
    dataType: 'text',
    success: function(data){
      if(data.trim() == 'success')
      {
        $('#row'+article_id).remove();
      }
    }
  });
}

function favourite(action,article_id)
{
  $.ajax({
    url: 'article.php?favorite&article='+article_id+'&action='+action,
    dataType: 'text',
    success: function(data){

      if(data.trim() == 'success')
      {
        if(action == 1)
        {
          $('#fav'+article_id).attr('src','images/fav1.png');
          $('#fav'+article_id).attr('onclick','favourite(2,'+article_id+')');
        }
        else if(action = 2)
        {
          $('#fav'+article_id).attr('src','images/fav.png');
          $('#fav'+article_id).attr('onclick','favourite(1,'+article_id+')');
        }
      }
    }
  });
}

</script>
  </body>
  </html>
