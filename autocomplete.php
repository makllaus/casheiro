<?php
/*******************************************************************\
  * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	require_once("inc/config.inc.php");

	$q = $_GET["q"];
	if (!$q) return;

	$q = strtolower(mysql_real_escape_string($q));
	$q = substr(trim($q), 0, 100);

	$query = smart_mysql_query("SELECT DISTINCT retailer_id,title FROM cashbackengine_retailers WHERE title LIKE '%$q%' AND status='active' LIMIT 20");

	while ($row = @mysql_fetch_array($query))
	{
		echo $row['title']."\n";
	}

?>