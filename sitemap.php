<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/config.inc.php");

	header("Content-Type: text/xml;charset=UTF-8");
   
	$query = "select * from cashbackengine_retailers WHERE status='active' ORDER BY added DESC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

if ($total > 0)
{

	echo '<?xml version="1.0" encoding="UTF-8"?> 
		<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

	while ($row = mysql_fetch_array($result))
	{  
		$i_url = SITE_URL.'view_retailer.php?rid='.$row['retailer_id'];
		$year = substr($row['added'],0,4);
		$month  = substr($row['added'],5,2);
		$day  = substr($row['added'],8,2);
		$i_date = ''.$year.'-'.$month.'-'.$day.'';

		// you can assign whatever changefreq and priority you like
		// changefreg - optional
		// priority - optional
		echo  
		'
		<url>
		<loc>'.$i_url.'</loc>
		<lastmod>'.$i_date.'</lastmod>
		<changefreq>daily</changefreq>
		<priority>0.8</priority>
		</url>
		';
	}

}
	echo  
	'</urlset>'; 

?>