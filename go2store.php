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

		$query = "SELECT * FROM cashbackengine_retailers WHERE retailer_id='$retailer_id'";
		$result = smart_mysql_query($query);

		if (mysql_num_rows($result) > 0)
		{
			if (!(isset($_SESSION['userid']) && is_numeric($_SESSION['userid'])))
			{
				$_SESSION['goRetailerID'] = $retailer_id;
				header("Location: login.php?msg=4");
				exit();
			}

			// update retailer visits //
			smart_mysql_query("UPDATE cashbackengine_retailers SET visits=visits+1 WHERE retailer_id='$retailer_id'");

			// update coupon visits //
			if (isset($_GET['c']) && is_numeric($_GET['c']) && $_GET['c'] > 0)
			{
				$coupon_id = (int)$_GET['c'];
				smart_mysql_query("UPDATE cashbackengine_coupons SET visits=visits+1 WHERE coupon_id='$coupon_id'");
			}

			// save member's click in history //
			$check = smart_mysql_query("SELECT * FROM cashbackengine_clickhistory WHERE user_id='".(int)$userid."' AND retailer_id='".(int)$retailer_id."'");
			if (mysql_num_rows($check) == 0)
				@smart_mysql_query("INSERT INTO cashbackengine_clickhistory SET user_id='".(int)$userid."', retailer_id='".(int)$retailer_id."', added=NOW()");
			else
				@smart_mysql_query("UPDATE cashbackengine_clickhistory SET added=NOW() WHERE retailer_id='$retailer_id'");


			$row = mysql_fetch_array($result);

			if ($row['url'] != "")
			{
				$retailer_website = str_replace("{USERID}", $userid, $row['url']);

				if (SHOW_LANDING_PAGE == 1)
				{
					// show landing page
					header("Location: /redirect.php?id=".$retailer_id);
					exit();
				}
				else
				{
					// directly open retailer's website
					header("Location: ".$retailer_website);
					exit();
				}
			}
		}
		else
		{
			///////////////  Page config  ///////////////
        	$PAGE_TITLE = "Nenhuma loja encontrada";

			require_once ("inc/header.inc.php");

				echo "<p align='center'>Desculpe, nenhuma loja encontrado.<br/><br/><a class='goback' href='#' onclick='history.go(-1);return false;'>Voltar</a></p>";

			require_once ("inc/footer.inc.php");
		}
	}
	else
	{	
		header("Location: index.php");
		exit();
	}

?>

