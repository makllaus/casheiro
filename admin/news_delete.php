<?php
/*******************************************************************\
 * CashbackEngine v2.0
 * http://www.CashbackEngine.net
 *
 * Copyright (c) 2010-2013 CashbackEngine Software. All rights reserved.
 * ------------ CashbackEngine IS NOT FREE SOFTWARE --------------
\*******************************************************************/

	session_start();
	require_once("../inc/adm_auth.inc.php");
	require_once("../inc/config.inc.php");


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$news_id = (int)$_GET['id'];

		smart_mysql_query("DELETE FROM cashbackengine_news WHERE news_id='$news_id'");

		header("Location: news.php?msg=deleted");
		exit();
	}

?>