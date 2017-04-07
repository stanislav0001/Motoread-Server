<?php

include_once 'includes/config.php';

if(!isset($_SESSION['token']))
{
  header('Location: index.php');
}

if(isset($_GET['url']) && isset($_GET['progress']))
{
	$url = $_GET['url'];

	if (filter_var($url, FILTER_VALIDATE_URL) === false) {
	    echo("Please send valid URL");
	    die;
	}
	else
	{
		$ch = curl_init(API_BASE_URL.'save&user='.$_SESSION['user_id'].'&url='.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$headers = [
		'token: '.$_SESSION['token'].'',
		];

		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
		$result = json_decode($response,true);
		echo $result['msg'];	
		
		die;
	}
	
}


?>

<!DOCTYPE html>
<html>
<head>
<title></title>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i" rel="stylesheet"> 

<style type="text/css">
	body, html {
	  height: 100%;
	  margin: 0;
	  padding: 0;
	  width: 100%;
	}
	.tableDiv {
	    display: table;
	    height: 100%;
	    width: 100%;
	}
	.tableCellDiv {
	      display: table-cell;
	    vertical-align: middle;
	    text-align: center;
	    background: black;
	    color: #fff;
	    font-size: 35px;
	    height: 100%;
	    width: 100%;
	}
	.loaderDots,
	.loaderDots:before,
	.loaderDots:after {
	  border-radius: 50%;
	  width: 6px;
	  height: 6px;
	  -webkit-animation-fill-mode: both;
	  animation-fill-mode: both;
	  -webkit-animation: load7 1s infinite ease-in-out;
	  animation: load7 1s infinite ease-in-out;
	}
	.loaderDots {
	  color: #fff;
	  font-size: 10px;
	  margin: 0px auto;
	  text-indent: -9999em;
	  -webkit-transform: translateZ(0);
	  -ms-transform: translateZ(0);
	  transform: translateZ(0);
	  -webkit-animation-delay: -0.16s;
	  animation-delay: -0.16s;
	  position: absolute;
	  right: 0;
	  top: -6px;
	}
	.loaderDots:before,
	.loaderDots:after {
	  content: '';
	  position: absolute;
	  top: 0;
	}
	.loaderDots:before {
	  left: -1.5em;
	  -webkit-animation-delay: -0.32s;
	  animation-delay: -0.32s;
	}
	.loaderDots:after {
	  left: 1.5em;
	}
	@-webkit-keyframes load7 {
	  0%,
	  80%,
	  100% {
	    box-shadow: 0 2.5em 0 -1.3em;
	  }
	  40% {
	    box-shadow: 0 2.5em 0 0;
	  }
	}
	@keyframes load7 {
	  0%,
	  80%,
	  100% {
	    box-shadow: 0 2.5em 0 -1.3em;
	  }
	  40% {
	    box-shadow: 0 2.5em 0 0;
	  }
	}
	.saveWrap {
	  display: inline-block;
	  font-family: 'Open Sans', sans-serif;
	  font-size: 22px;
	  position: relative;
	  text-align: left;
	  width: 95px;
	}
	.motologo img {
	  max-width: 140px;
	}
</style>
</head>
<body>

	<div id="wrapper" class="tableDiv">

	<div id="main" class="tableCellDiv">
			<div class="motologo text-center">
				<img src="./images/moto-read-white.png" alt="logo">
			</div>
			<div class="saveWrap">
				Saving
				<div class="loaderDots">Loading...</div>
			</div>

	</div>
	</div>

<script type="text/javascript">
	function save()
	{
		var url = '<?php echo $_GET['url']; ?>';
	  $.ajax({
	    url: 'saveart.php?progress=&url='+url,
	    dataType: 'text',
	    success: function(data){	      
	      	$('#main').html(data);
	        location.href = url;
	    }
	  });
	}

	//save();
	 
</script>

</body>
</html>