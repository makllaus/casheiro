<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/


/**
 * Run mysql query
 * @param	$sql		mysql query to run
 * @return	boolean		false if failed run mysql query
*/

function smart_mysql_query($sql)
{
	$res = mysql_query($sql) or die("<p align='center'><span style='font-size:11px; font-family: tahoma, verdana, arial, helvetica, sans-serif; color: #000;'>query failed: ".mysql_error()."</span></p>");
	if(!$res){
		return false;
	}
	return $res;
}


/**
 * Retrieves parameter from POST array
 * @param	$name	parameter name
*/


function getPostParameter($name)
{
	$data = isset($_POST[$name]) ? $_POST[$name] : null;
	if(!is_null($data) && get_magic_quotes_gpc() && is_string($data))
	{
		$data = stripslashes($data);
	}
	$data = trim($data);
	$data = htmlentities($data, ENT_QUOTES, 'UTF-8');
	return $data;
}


/**
 * Retrieves parameter from GET array
 * @param	$name	parameter name
*/


function getGetParameter($name)
{
	return isset($_GET[$name]) ? $_GET[$name] : false;
}


/**
 * Returns random password
 * @param	$length		length of string
 * @return	string		random password
*/

if (!function_exists('generatePassword')) {
	function generatePassword($length = 8)
	{
		$password = "admin";

		/*$possible = "0123456789abcdefghijkmnpqrstvwxyzABCDEFGHJKLMNPQRTVWXYZ!(@)";
		$i = 0; 

		while ($i < $length)
		{ 
			$char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

			if (!strstr($password, $char))
			{ 
				$password .= $char;
				$i++;
			}
		}
		*/
		return $password;
	}
}


/**
 * Calculate percentage
 * @param	$amount				Amount
 * @param	$percent			Percent value
 * @return	string				returns formated money value
*/

if (!function_exists('CalculatePercentage')) {
	function CalculatePercentage($amount, $percent)
	{
		return number_format(($amount/100)*$percent,2,'.','');
	}
}


/**
 * Returns formated money value
 * @param	$amount				Amount
 * @param	$hide_currency		Hide or Show currency sign
 * @return	string				returns formated money value
*/

if (!function_exists('DisplayMoney')) {
	function DisplayMoney($amount, $hide_currency = 0)
	{
		$newamount = number_format($amount, 2, '.', '');

		if ($hide_currency == 1)
			return $newamount;
		else
			return SITE_CURRENCY.$newamount;
	}
}


/**
 * Returns formated cashback value
 * @param	$value				Cashback value
 * @return	string				returns formated cashback value
*/

if (!function_exists('DisplayCashback')) {
	function DisplayCashback($value)
	{
		if (strstr($value,'%')) 
			$cashback = $value;
		else
			$cashback = SITE_CURRENCY.$value;

		return $cashback;
	}
}


/**
 * Returns time left
 * @return	string	time left
*/

if (!function_exists('GetTimeLeft')) {
	function GetTimeLeft($time_left)
	{
		$days		= floor($time_left / (60 * 60 * 24));
		$remainder	= $time_left % (60 * 60 * 24);
		$hours		= floor($remainder / (60 * 60));
		$remainder	= $remainder % (60 * 60);
		$minutes	= floor($remainder / 60);
		$seconds	= $remainder % 60;

		$days == 1 ? $dw = "day" : $dw = "days";
		$hours == 1 ? $hw = "hr" : $hw = "hrs";
		$minutes == 1 ? $mw = "min" : $mw = "mins";
		$seconds == 1 ? $sw = "second" : $sw = "seconds";

		if ($time_left > 0)
		{
			//$new_time_left = $days." $dw ".$hours." $hw ".$minutes." $mw";
			$new_time_left = $days." $dw ".$hours." $hw";
			return $new_time_left;
		}
		else
		{
			return "<span class='expired'>expired</span>";
		}
	}
}


/**
 * Returns member's referrals total
 * @param	$userid		User's ID
 * @return	string		member's referrals total
*/

if (!function_exists('GetReferralsTotal')) {
	function GetReferralsTotal($userid)
	{
		$query = "SELECT COUNT(*) AS total FROM cashbackengine_users WHERE ref_id='".(int)$userid."'";
		$result = smart_mysql_query($query);

		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			return $row['total'];
		}
	}
}


/**
 * Returns  member's current balance
 * @param	$userid					User's ID
 * @param	$hide_currency_option	Hide or show currency sign
 * @return	string					member's current balance
*/

if (!function_exists('GetUserBalance')) {
	function GetUserBalance($userid, $hide_currency_option = 0)
	{
		$query = "SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='confirmed'";
		$result = smart_mysql_query($query);

		if (mysql_num_rows($result) != 0)
		{
			$row_confirmed = mysql_fetch_array($result);

			if ($row_confirmed['total'] > 0)
			{
				$row_paid = mysql_fetch_array(smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND ((status='paid' OR status='request') OR (payment_type='Withdrawal' AND status='declined'))"));

				$balance = $row_confirmed['total'] - $row_paid['total'];

				return DisplayMoney($balance, $hide_currency_option);
			}
			else
			{
				return DisplayMoney(0, $hide_currency_option);
			}

		}
		else
		{
			return DisplayMoney("0.00", $hide_currecy_option);
		}
	}
}


/**
 * Returns date of last transaction
 * @param	$userid		User's ID
 * @return	mixed		date of last transaction or false
*/

if (!function_exists('GetBalanceUpdateDate')) {
	function GetBalanceUpdateDate($userid)
	{
		$result = smart_mysql_query("SELECT DATE_FORMAT(updated, '%e %b %Y %h:%i %p') AS last_process_date FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' ORDER BY updated DESC LIMIT 1");
		if (mysql_num_rows($result) != 0)
		{
			$row = mysql_fetch_array($result);
			return $row['last_process_date'];
		}
		else
		{
			return false;
		}

	}
}


/**
 * Add/Deduct money from member's balance
 * @param	$userid		User's ID
 * @param	$amount		Amount
 * @param	$action		Action
*/

if (!function_exists('UpdateUserBalance')) {
	function UpdateUserBalance($userid, $amount, $action)
	{
		$userid = (int)$userid;

		if ($action == "add")
		{
			smart_mysql_query("INSERT INTO cashbackengine_transactions SET user_id='$userid', amount='$amount', status='confirmed'");
		}
		elseif ($action == "deduct")
		{
			smart_mysql_query("INSERT INTO cashbackengine_transactions SET user_id='$userid', amount='$amount', status='deducted'");
		}
	}
}


/**
 * Returns total of member's requested money
 * @return	string	total
*/

if (!function_exists('GetRequestsTotal')) {
	function GetRequestsTotal()
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_transactions WHERE status='request'");
		$row = mysql_fetch_array($result);
		return $row['total'];
	}
}


/**
 * Returns member's pending cashback
 * @return	string	member's pending cashback
*/

if (!function_exists('GetPendingBalance')) {
	function GetPendingBalance()
	{
		global $userid;
		$result = smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='pending'");
		$row = mysql_fetch_array($result);
		$total = DisplayMoney($row['total']);
		return $total;
	}
}


/**
 * Returns member's declined cashback
 * @return	string	member's declined cashback
*/

if (!function_exists('GetDeclinedBalance')) {
	function GetDeclinedBalance()
	{
		global $userid;
		$result = smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='declined'");
		$row = mysql_fetch_array($result);
		$total = DisplayMoney($row['total']);
		return $total;
	}
}


/**
 * Returns member's lifetime cashback
 * @return	string	member's lifetime cashback
*/

if (!function_exists('GetLifetimeCashback')) {
	function GetLifetimeCashback()
	{
		global $userid;
		// all confirmed payments
		$row = mysql_fetch_array(smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='confirmed'"));
		// "paid" payments
		$row2 = mysql_fetch_array(smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='paid'"));
		$total = $row['total'] - $row['total2'];
		$total = DisplayMoney($total);
		return $total;
	}
}


/**
 * Returns cash out requested for member
 * @return	string	requested cash value
*/

if (!function_exists('GetCashOutRequested')) {
	function GetCashOutRequested()
	{
		global $userid;
		$result = smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='request'");
		$row = mysql_fetch_array($result);
		$total = DisplayMoney($row['total']);
		return $total;
	}
}


/**
 * Returns cash out processed for member
 * @return	string	cash out processed value
*/

if (!function_exists('GetCashOutProcessed')) {
	function GetCashOutProcessed()
	{
		global $userid;
		$result = smart_mysql_query("SELECT SUM(amount) AS total FROM cashbackengine_transactions WHERE user_id='".(int)$userid."' AND status='paid'");
		$row = mysql_fetch_array($result);
		$total = DisplayMoney($row['total']);
		return $total;
	}
}


/**
 * Returns total of new member's messages from administrator
 * @return	integer		total of new messages for member from administrator
*/

if (!function_exists('GetMemberMessagesTotal')) {
	function GetMemberMessagesTotal()
	{
		$userid	= $_SESSION['userid'];
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_messages_answers WHERE user_id='".(int)$userid."' AND is_admin='1' AND viewed='0'");
		$row = mysql_fetch_array($result);

		if ($row['total'] == 0)
		{
			$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_messages WHERE user_id='".(int)$userid."' AND is_admin='1' AND viewed='0'");
			$row = mysql_fetch_array($result);
		}
		return (int) $row['total'];
	}
}


/**
 * Returns total of new messages for admin
 * @return	integer		total of new messages for admin from members
*/

if (!function_exists('GetMessagesTotal')) {
	function GetMessagesTotal()
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_messages WHERE is_admin='0' AND viewed='0'");
		$row = mysql_fetch_array($result);
		return (int) $row['total'];
	}
}


/**
 * Returns total of users which added retialer to their favorites list
 * @return	integer		total of new messages for admin from members
*/

if (!function_exists('GetFavoritesTotal')) {
	function GetFavoritesTotal($retailer_id)
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_favorites WHERE retailer_id='".(int)$retailer_id."'");
		$row = mysql_fetch_array($result);
		return (int) $row['total'];
	}
}


/**
 * Returns payment method name by payment method ID
 * @return	string	payment method name
*/

if (!function_exists('GetPaymentMethodByID')) {
	function GetPaymentMethodByID($pmethod_id)
	{
		$result = smart_mysql_query("SELECT pmethod_title FROM cashbackengine_pmethods WHERE pmethod_id='".(int)$pmethod_id."'");
		$total = mysql_num_rows($result);

		if ($total > 0)
		{
			$row = mysql_fetch_array($result);
			return $row['pmethod_title'];
		}
		else
		{
			return "Unknown";
		}
	}
}


/**
 * Returns random string
 * @param	$len	string length
 * @param	$chars	chars in the string
 * @return	string	random string
*/

if (!function_exists('GenerateRandString')) {
	function GenerateRandString($len, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789')
	{
		$string = '';
		for ($i = 0; $i < $len; $i++)
		{
			$pos = rand(0, strlen($chars)-1);
			$string .= $chars{$pos};
		}
		return $string;
	}
}


/**
 * Returns random payment's reference ID
 * @return	string	Reference ID
*/

if (!function_exists('GenerateReferenceID')) {
	function GenerateReferenceID()
	{
		unset($num);

		$num = GenerateRandString(15,"0123456789");
    
		$check = smart_mysql_query("SELECT * FROM cashbackengine_transactions WHERE reference_id='$num'");
    
		if (mysql_num_rows($check) == 0)
		{
			return $num;
		}
		else
		{
			return GenerateOrderID();
		}
	}
}


/**
 * Returns Encrypted password
 * @param	$password	User's ID
 * @return	string		encrypted password
*/

if (!function_exists('PasswordEncryption')) {
	function PasswordEncryption($password)
	{
		return md5($password);
	}
}


/**
 * Returns most popular retailer's ID of the week
 * @return	integer		retailer's ID
*/

if (!function_exists('GetDealofWeek')) {
	function GetDealofWeek()
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total, retailer_id FROM cashbackengine_clickhistory WHERE date_sub(curdate(), interval 7 day) <= added GROUP BY retailer_id ORDER BY total DESC LIMIT 1");
	
		if (mysql_num_rows($result) == 0)
		{
			$result = smart_mysql_query("SELECT retailer_id FROM cashbackengine_retailers WHERE status='active' ORDER BY RAND() LIMIT 1");
		}

		$row = mysql_fetch_array($result);
		return (int) $row['retailer_id'];
	}
}


/**
 * Saves referral's ID in cookies
 * @param	$ref_id		Referrals's ID
*/

if (!function_exists('setReferal')) {
	function setReferal($ref_id)
	{
		//set up cookie for one month period
		setcookie("referer_id", $ref_id, time()+(60*60*24*30));
	}
}


/**
 * Returns user's information
 * @param	$user_id	User ID
 * @return	string		user name, or "User not found"
*/

if (!function_exists('GetUsername')) {
	function GetUsername($user_id)
	{
		$result = smart_mysql_query("SELECT * FROM cashbackengine_users WHERE user_id='".(int)$user_id."' LIMIT 1");
		
		if (mysql_num_rows($result) != 0)
		{
			$row = mysql_fetch_array($result);
			return $row['fname']." ".$row['lname'];
		}
		else
		{
			return "Usuário não encontrado";
		}
	}
}


/**
 * Returns setting value by setting's key
 * @param	$setting_key	Setting's Key
 * @return	string	setting's value
*/

if (!function_exists('GetSetting')) {
	function GetSetting($setting_key)
	{
		$setting_result = smart_mysql_query("SELECT setting_value FROM cashbackengine_settings WHERE setting_key='".$setting_key."'");
		$setting_total = mysql_num_rows($setting_result);

		if ($setting_total > 0)
		{
			$setting_row = mysql_fetch_array($setting_result);
			$setting_value = $setting_row['setting_value'];
		}
		else
		{
			die ("definições de configuração não encontrada");
		}

		return $setting_value;
	}
}


/**
 * Returns content for static pages
 * @param	$content_name	Content's Name or Content ID
 * @return	array	(1) - Page Title, (2) - Page Text
*/

if (!function_exists('GetContent')) {
	function GetContent($content_name)
	{
		if (is_numeric($content_name))
		{
			$content_id = (int)$content_name;
			$content_result = smart_mysql_query("SELECT * FROM cashbackengine_content WHERE content_id='".$content_id."' LIMIT 1");
		}
		else
		{
			$content_result = smart_mysql_query("SELECT * FROM cashbackengine_content WHERE name='".$content_name."' LIMIT 1");
		}

		$content_total = mysql_num_rows($content_result);

		if ($content_total > 0)
		{
			$content_row = mysql_fetch_array($content_result);
			$contents['title'] = stripslashes($content_row['title']);
			$contents['text'] = stripslashes($content_row['description']);
		}
		else
		{
			$contents['title'] = "Página Inválida";
			$contents['text'] = "<p align='center'>Página não encontrada.<br/><br/><a class='goback' href='/'>Volte para página inicial</a></p>";
		}

		return $contents;
	}
}


/**
 * Returns content for email template
 * @param	$email_name	Email Template Name
 * @return	array	(1) - Email Subject, (2) - Email Message
*/

if (!function_exists('GetEmailTemplate')) {
	function GetEmailTemplate($email_name)
	{
		$etemplate_result = smart_mysql_query("SELECT * FROM cashbackengine_email_templates WHERE email_name='".$email_name."' LIMIT 1");
		$etemplate_total = mysql_num_rows($etemplate_result);

		if ($etemplate_total > 0)
		{
			$etemplate_row = mysql_fetch_array($etemplate_result);
			$etemplate['email_subject'] = stripslashes($etemplate_row['email_subject']);
			$etemplate['email_message'] = stripslashes($etemplate_row['email_message']);

			$etemplate['email_message'] = "<html>
								<head>
									<title>".$etemplate['email_subject']."</title>
								</head>
								<body>
								<table width='80%' border='0' cellpadding='10'>
								<tr>
									<td align='left' valign='top'>".$etemplate['email_message']."</td>
								</tr>
								</table>
								</body>
							</html>";
		}

		return $etemplate;
	}
}


/**
 * Returns list of categories
 * @param	$cat_id Category ID
 * @param	$level	Level
 * @return	string	categories list
*/

if (!function_exists('ShowCategories')) {
	function ShowCategories($cat_id, $level=0)
	{
		$result = smart_mysql_query("SELECT category_id, parent_id, name FROM cashbackengine_categories WHERE parent_id='$cat_id' ORDER BY name");
		
		if (mysql_num_rows($result) >= 1)
		{
			while($row = mysql_fetch_array($result))
			{
				$pxs = $level*10;
				if ($_GET['cat'] === $row['category_id']) $actives = " class=\"active\""; else $actives = "";
				echo "<ul style='padding-left:".$pxs."px;margin:0;'><li".$actives."><a href=\"/retailers.php?cat=".$row['category_id']."\">".$row['name']."</a></li></ul>";
				ShowCategories($row['category_id'], $level+1);
			}
		}
	}
}


/**
 * Returns retailer name
 * @param	$retailer_id	Retailer ID
 * @return	string			retailer name
*/

if (!function_exists('GetStoreName')) {
	function GetStoreName($retailer_id)
	{
		$result = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE retailer_id='".(int)$retailer_id."' LIMIT 1");
		$row = mysql_fetch_array($result);
		return $row['title'];
	}
}


/**
 * Returns username name
 * @param	$user_id		User ID
 * @return	string			user name
*/

if (!function_exists('GetUsername')) {
	function GetUsername($user_id)
	{
		$result = smart_mysql_query("SELECT * FROM cashbackengine_users WHERE user_id='".(int)$user_id."' LIMIT 1");
		if (mysql_num_rows($result) != 0)
		{
			$row = mysql_fetch_array($result);
			return $row['fname']." ".$row['lname'];
		}
		else
		{
			return "usuário não encontrado";
		}
	}
}


/**
 * Returns retailer's rating
 * @param	$retailer_id	Retailer ID
 * @return	string			rating
*/

if (!function_exists('GetStoreRating')) {
	function GetStoreRating($retailer_id, $show_stars = 0)
	{
		$result = smart_mysql_query("SELECT AVG(rating) as store_rating FROM cashbackengine_reviews WHERE retailer_id='".(int)$retailer_id."'");
		if (mysql_num_rows($result) > 0)
		{
			$row = mysql_fetch_array($result);
			$rating = $row['store_rating'];
			$rating = number_format($rating, 2, '.', '');
		}
		else
		{
			return "----";
		}

		if ($show_stars == 1)
		{
			$rating_stars = $rating*20;
			$store_rating = "<div class='rating'><div class='cover'></div><div class='progress' style='width: ".$rating_stars."%;'></div></div>";
			return $store_rating;
		}
		else
		{
			return $rating;
		}		
	}
}


/**
 * Returns store's coupons total
 * @param	$retailer_id	Retailer ID
 * @return	integer			store's coupons total
*/

if (!function_exists('GetStoreCouponsTotal')) {
	function GetStoreCouponsTotal($retailer_id)
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_coupons WHERE retailer_id='".(int)$retailer_id."' AND status='active'");
		$row = mysql_fetch_array($result);
		return (int)$row['total'];
	}
}


/**
 * Returns store's reviews total
 * @param	$retailer_id	Retailer ID
 * @param	$all			calculates all review
 * @return	integer			store's reviews total
*/

if (!function_exists('GetStoreReviewsTotal')) {
	function GetStoreReviewsTotal($retailer_id, $all = 0)
	{
		if ($all == 1)
			$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_reviews WHERE retailer_id='".(int)$retailer_id."'");
		else
			$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_reviews WHERE retailer_id='".(int)$retailer_id."' AND status='active'");
		
		$row = mysql_fetch_array($result);
		return (int)$row['total'];
	}
}


/**
 * Returns user's reviews total
 * @param	$user_id	User ID
 * @return	integer			user's reviews total
*/

if (!function_exists('GetUserReviewsTotal')) {
	function GetUserReviewsTotal($user_id)
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_reviews WHERE user_id='".(int)$user_id."' AND status='active'");
		$row = mysql_fetch_array($result);
		return (int)$row['total'];
	}
}


/**
 * Returns store reports total number
 * @return	integer		store reports total
*/

if (!function_exists('GetRetailerReportsTotal')) {
	function GetRetailerReportsTotal()
	{
		$result = smart_mysql_query("SELECT COUNT(*) AS total FROM cashbackengine_reports WHERE retailer_id<>'0'"); // AND viewed='0'
		$row = mysql_fetch_array($result);
		return (int)$row['total'];
	}
}



?>