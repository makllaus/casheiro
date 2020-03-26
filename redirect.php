<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/config.inc.php");

	$userid = (int)$_SESSION['userid'];


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$retailer_id = (int)$_GET['id'];
	}
	else
	{		
		header ("Location: index.php");
		exit();
	}

	$query = "SELECT *, DATE_FORMAT(added, '%e %b %Y') AS date_added FROM cashbackengine_retailers WHERE retailer_id='$retailer_id' AND status='active' LIMIT 1";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

	if ($total > 0)
	{
		$row = mysql_fetch_array($result);
		$cashback = DisplayCashback($row['cashback']);
		$website_url = str_replace("{USERID}", $userid, $row['url']);

	}
	else
	{
		header ("Location: index.php");
		exit();
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Visite <?php echo $row['title']; ?> e Ganhe <?php echo $cashback; ?> Cashback - <?php echo SITE_TITLE; ?></title>
<meta http-equiv="refresh" content="2; url=<?php echo $website_url; ?>" />
<link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700" rel="stylesheet" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" />
<link rel="icon" type="image/ico" href="/favicon.ico" />
<style type="text/css">
<!--
body {
	background: #5B5B5B;
	font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #000000;
	margin: 0;
	padding: 0;
}

.box {
	float: left;
	height: 220px;
	width: 450px;
	padding: 0 20px 20px 20px;
	text-align: left;
	border: 1px solid #DDD;
	background: #FFFFFF;
	-moz-border-radius: 5px;
	-webkit-border-radius: 5px;
	border-radius: 5px;
	position: relative;
	-moz-box-shadow: 0 0 5px 5px #333333;
	-webkit-box-shadow: 0 0 5px 5px #333333;
	box-shadow: 0 0 5px 5px #333333;
}

.msg {
	font-family: 'Open Sans Condensed', Times, "Lucida Grande", "Lucida Sans Unicode", Arial, Verdana, sans-serif;
	font-size: 20px;
	font-weight: 600;
	color: #777777;
	line-height: 30px;
	text-align: left;
}

.msg .username {
	color:#000;
}

.cashback {
	font-family: 'Open Sans Condensed', Times, "Lucida Grande", "Lucida Sans Unicode", Arial, Verdana, sans-serif;
	font-size: 26px;
	font-weight: 600;
	color: #e69700;
	line-height: 30px;
	text-align: left;
}

.store-name {
	line-height: 30px;
	font-family: 'Open Sans Condensed', Times, "Lucida Grande", "Lucida Sans Unicode", Arial, Verdana, sans-serif;
	font-size: 22px;
	font-weight: 500;
	color: #62AAF7;
	text-align: left;
}

.logo {
	position: absolute;
	float: right;
	top: 105px;
	right: 15px;
	border: 5px solid #EEEEEE;
}

-->
</style>
</head>
<body>
<div style="width:100%; height:100%; position:absolute; top:25%; left:35%; width:350px">
	<div class="box">
		<p align="center">Um momento por favor...<br/><br/><img src="/images/loading.gif"></p>
		<div class="msg">
			<span class="username"><?php echo $_SESSION['FirstName']; ?></span>, você está no seu caminho para ganhar:
			<br/>
			<span class="cashback">até <?php echo $cashback; ?> CASHBACK</span>
			<br/>Na loja
		</div>
		<div class="store-name"><?php echo $row['title']; ?></div>
		<?php if ($row['image'] != "noimg.gif") { ?>
			<img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" class="logo" />
		<?php } ?>
	</div>
</div>     
</body>
</html>