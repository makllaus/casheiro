<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	$setts_sql = "SELECT * FROM cashbackengine_settings";
	$setts_result = smart_mysql_query($setts_sql);

	unset($settings);
	$settings = array();

	while ($setts_row = mysql_fetch_array($setts_result))
	{
		$settings[$setts_row['setting_key']] = $setts_row['setting_value'];
	}

	define('SITE_TITLE', $settings['website_title']);
	define('SITE_MAIL', $settings['website_email']);
	define('SITE_URL', $settings['website_url']);
	define('SITE_HOME_TITLE', $settings['website_home_title']);
	define('SITE_LANGUAGE', $settings['website_language']);
	define('SITE_TIMEZONE', $settings['website_timezone']);
	define('SITE_CURRENCY', $settings['website_currency']);
	define('RESULTS_PER_PAGE', $settings['results_per_page']);
	define('MIN_PAYOUT_PER_TRANSACTION', $settings['min_transaction']);
	define('MIN_PAYOUT', $settings['min_payout']);	
	define('SIGNUP_BONUS', $settings['signup_credit']);
	define('REFER_FRIEND_BONUS', $settings['refer_credit']);
	define('IMAGE_WIDTH', $settings['image_width']);
	define('IMAGE_HEIGHT', $settings['image_height']);
	define('SHOW_LANDING_PAGE', $settings['show_landing_page']);
	define('REVIEWS_APPROVE', $settings['reviews_approve']);
	define('MAX_REVIEW_LENGTH', 500); //characters
	define('REVIEWS_PER_PAGE', $settings['results_per_page']);
	define('SHOW_RETAILER_STATS', $settings['show_statistics']);
	define('FACEBOOK_PAGE', $settings['facebook_page']);
	define('TWITTER_PAGE', $settings['twitter_page']);

	// letters for alphabetical order 
	$alphabet = array ("0-9","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z");

?>