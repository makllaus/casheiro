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
	require_once("./inc/admin_funcs.inc.php");

	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$pn			= (int)$_GET['pn'];
		$report_id	= (int)$_GET['id'];

		DeleteReport($report_id);

		if ($_GET['type'] == "retailers")
		{
			header("Location: retailer_reports.php?msg=deleted&page=".$pn);
			exit();
		}
	}

?>