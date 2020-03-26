<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/


	session_start();
	require_once("inc/config.inc.php");

	function well_formed($str) {
		$str = strip_tags($str);
		$str = preg_replace("/[^a-zA-Z0-9_ (\n|\r\n)]+/", "", $str);
		$str = str_replace("&nbsp;", "", $str);
		$str = str_replace("&", "&amp;", $str);
		return $str;
	}

	$query = "SELECT *, DATE_FORMAT(added, '%a, %d %b %Y %T') as pub_date FROM cashbackengine_retailers WHERE status='active' ORDER BY added DESC LIMIT 50";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

	if ($total > 0)
	{
		header("Content-Type: application/xml; charset=UTF-8");

		echo '<?xml version="1.0" encoding="UTF-8" ?>';
		echo '<rss version="2.0">';
		echo '<channel>';
		echo '<title>'.SITE_TITLE.'</title>';
		echo '<link>'.SITE_URL.'</link>';
		echo '<description>'.SITE_HOME_TITLE.'</description>';
		echo '<image>';
			echo '<url>'.SITE_URL.'images/logo.gif</url>';
			echo '<title>'.SITE_TITLE.'</title>';
			echo '<link>'.SITE_URL.'</link>';
		echo '</image>';

		while($row = mysql_fetch_array($result)) 
		{
			$item_title			= well_formed($row['title']);
			$item_cashback		= DisplayCashback($row['cashback']);
			$item_url			= SITE_URL.'view_retailer.php?rid='.$row['retailer_id'];
			$item_pubdate		= $row['pub_date']." PDT";

			if (strlen($row['description']) > 300)
				$item_description = substr(well_formed($row['description']),0,300).'...';
			else
				$item_description = well_formed($row['description']);

			echo '
				<item>
					<title><![CDATA['.$item_title.' - '.$item_cashback.' Cashback]]></title>
					<link>'.$item_url.'</link>
					<guid>'.$item_url.'</guid>
					<pubDate>'.$item_pubdate.'</pubDate>
					<description><![CDATA['.$item_description.']]></description>
				</item>
				';
		} 
		
		echo '</channel>';
		echo '</rss>';
	}

?>