<?php
/*******************************************************************\
 * CashbackEngine v2.0
 * http://www.CashbackEngine.net
 *
 * Copyright (c) 2010-2013 CashbackEngine Software. All rights reserved.
 * ------------ CashbackEngine IS NOT FREE SOFTWARE --------------
\*******************************************************************/

	if (!function_exists('str_split'))
	{
		function str_split($str)
		{
			$str_array=array();
			$len=strlen($str);
			for($i=0; $i<$len; $i++)
			{
				$str_array[]=$str{$i};
			}
			return $str_array;
		}
	}

	define('is_Setup', TRUE);

	require_once("./inc/config.inc.php");

	$complete = 0;

	$check_install = @mysql_num_rows(@mysql_query("SELECT setting_value from cashbackengine_settings WHERE setting_key='iword' LIMIT 1"));
	if ($check_install != 0)
	{
		die("<p>&nbsp;</p><p>&nbsp;</p><p><center><span style='font-family:tahoma,verdana,arial,helvetica,sans-serif; font-size:20px; color:#777;'><font color='#84C315'>Cashback</font><font color='#5392D5'>Engine</font> Installation complete! Please DELETE 'install.php' file from your server!<br/><br/><br/><a style='color:#0578B7;' href='/'>Yes, I have deleted it &raquo;</a></span></center></p>");
	}


if (isset($_POST['action']) && $_POST['action'] == "install")
{
	$license_key = trim($_POST['license']);

	unset($errs);
	$errs = array();

	if (!$license_key)
	{
		$errs[] = "Please enter your license key";
	}
	/*
	else
	{
		if (!preg_match("/^[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}?$/", $license_key))
		{
			$errs[] = "License key is wrong! Please try again!";
			$wrong_key = 1;
		}
		else
		{
			if ($wrong_key == 1)
			{$licence_status = "correct";$st = 1;}else{$licence_status = "wrong";$key=explode("-",$license_key);$keey=$key[rand(0,2)];
			if($ikey[4][2]=7138%45){$step=1;$t=1;$licence_status="wrong";}else{$licence_status="correct";$step=2;}
			if($keey>0){$i=30+$step;if(rand(7,190)>=rand(0,1))$st=+$i;$u=0;}$status2=str_split($key[1],1);$status4=str_split($key[3],1);$status1=str_split($key[0],1);$status3=str_split($key[2],1);
			if($step==1){$kky=str_split($key[$u+4],1);if((($key[$u]+$key[2])-($key[3]+$key[$t])==(((315*2+$u)+$t)*++$t))&&(($kky[3])==$status4[2])&&(($status3[1])==$kky[0])&&(($status2[3])==$kky[1])&&(($kky[2]==$status2[1]))){$rnd_num = rand(100,999);}else{$rnd_num = rand(11,49);}}} if($licenses!=7){$wrong=1;$licence_status="wrong";}else{$wrong=0;$correct=1;}
		}


	}
	*/

	if (count($errs) == 0)
	{
		// checking license //
		//$words_arr = array(1 => 'GreeNraBBit');
		//$wrnd = 1;
		//$rnd_word = $words_arr[$wrnd];
		$rnd_pwd = generatePassword(11);
		//$my_word = $rnd_word;
		$my_pwd = PasswordEncryption($rnd_pwd);


mysql_query("DROP TABLE IF EXISTS `cashbackengine_affnetworks`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_affnetworks` (
  `network_id` int(11) unsigned NOT NULL auto_increment,
  `network_name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `website` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `image` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `csv_format` text collate utf8_unicode_ci NOT NULL,
  `confirmeds` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `pendings` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `declineds` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `status` enum('active','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `last_csv_upload` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`network_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_affnetworks</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_affnetworks` (`network_id`, `network_name`, `website`, `image`, `csv_format`, `confirmeds`, `pendings`, `declineds`, `status`, `added`, `last_csv_upload`) VALUES
(1, 'Commission Junction', 'http://www.cj.com/', 'cj.gif', '\"8-May-2010 10:01 PDT\",\"8-May-2010 08:08 PDT\",\"Registration\",\"{TRANSACTIONID}\",\"sim_sale\",\"{STATUS}\",\"No\",\"{AMOUNT}\",\"{COMMISSION}\",\"0.00\",\"3201921\",\"Free Bets Extra\",\"{PROGRAMID}\",\"2815954\",\"EurosportBET\",\"{USERID}\",\"345007\",\"8-May-2010 08:07 PDT\",\"332394\",\"2815954\"', 'closed', 'extended|new|locked', '', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'TradeDoubler', 'http://www.tradedoubler.com/', 'tradedoubler.gif', '', 'A', 'P', 'D', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Affiliate Window', 'http://www.affiliatewindow.com/', 'affiliatewindow.gif', '', 'confirmed', 'pending', 'declined', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'Buy.at', 'http://buy.at/', 'buyat.gif', '', 'Approved', 'Pending', 'Held', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'Webgains', 'http://www.webgains.com/', 'webgains.gif', '', 'confirmed', 'delayed', 'cancelled', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'Zanox', 'http://www.zanox.com/', 'zanox.gif', '', 'Approved|Confirmed', 'Open', 'Rejected', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(7, 'pepperjamNETWORK', 'http://www.pepperjamnetwork.com/', 'pepperjam.gif', '', 'paid', 'pending', 'locked|delayed', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(8, 'ShareASale', 'http://www.shareasale.com', 'shareasale.gif', '', 'confirmed', 'pending', 'declined', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(9, 'Linkshare', 'http://www.linkshare.com/', 'linkshare.gif', '', '', '', '', 'active', '0000-00-00 00:00:00', '0000-00-00 00:00:00')") or die("Inserting into <b>cashbackengine_affnetworks</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_categories`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_categories` (
  `category_id` int(9) unsigned NOT NULL auto_increment,
  `parent_id` int(11) NOT NULL default '0',
  `name` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci NOT NULL default '',
  `category_url` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`category_id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_categories</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_categories` (`category_id`, `parent_id`, `name`, `description`) VALUES
		(1, 0, 'Automotive', ''),
		(2, 0, 'Baby &amp; Kids', ''),
		(3, 0, 'Clothes', ''),
		(4, 0, 'Computer Shops', ''),
		(5, 0, 'Dating Sites', ''),
		(6, 0, 'Education', ''),
		(7, 0, 'Electronics', ''),
		(8, 0, 'Food &amp; Drink', ''),
		(9, 0, 'Gift Shops', ''),
		(10, 0, 'Health &amp; Beauty', ''),
		(11, 0, 'Music &amp; Movies', ''),
		(12, 0, 'Other', ''),
		(13, 0, 'Sports &amp; Fitness', ''),
		(14, 0, 'Toys &amp; Games', ''),
		(15, 0, 'Travel', '')") or die("Inserting into <b>cashbackengine_categories</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_clickhistory`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_clickhistory` (
  `click_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL default '0',
  `retailer_id` int(11) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`click_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_clickhistory</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_content`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_content` (
  `content_id` int(11) unsigned NOT NULL auto_increment,
  `name` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `title` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci NOT NULL default '',
  `page_url` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_content</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_content` (`content_id`, `name`, `title`, `description`, `modified`) VALUES
(1, 'home', 'Home page', '<p style=\'text-align: center;\'><a href=\'/register.php\'><img src=\'/images/welcome.gif\' alt=\'\' border=\'0\' /></a></p>\r\n<h1 style=\'border:none;text-align:center;\'>Welcome to our cashback website!</h1>\r\n<img src=\'/images/home_img.gif\' align=\'left\' border=\'0\' alt=\'\' />\r\n<p style=\'text-align: center;\'><strong>Start earning cash back on your online purchases!</strong></p>\r\n<p style=\'text-align: justify;\'>Open your own free account now and start to earn cashback. Its totally free and simple. Save money on online shopping now! Our site helps you to earn on cash back rewards, simply sign up for free and you will start earning immediately on your purchases. Earn cashback by shopping with your favorite stores.</p>\r\n<p style=\'text-align: justify;\'>Start to earn cash back rewards right away on every purchase made using our cashback website. Remember, the more you spend on your shopping the more you will get money back.</p>\r\n<br/><p align=\'center\'><a class=\'start_link\' href=\'/register.php\'>Start Earning!</a></p>', NOW()),
(2, 'aboutus', 'About Us', '<p>Information about your site.</p>', '0000-00-00 00:00:00'),
(3, 'howitworks', 'How it works', '<p>Earn cashback on your online shopping with our site, here is how:</p>\r\n<ul>\r\n    <li>Sign Us</li>\r\n    <li>Find your favorite shops</li>\r\n    <li>Make shopping as usually</li>\r\n    <li>Earn great cashback!</li>\r\n</ul>\r\n<p>Shop from hundreds of your favorite merchants and earn cash back today. Find new stores daily. Join now and start earning cashback! You can earn cash back for performing various shopping - you can even get paid cashback when you buy gifts, flowers, along with loads of other things.</p>', NOW()),
(4, 'help', 'Help', '<p><b>Here is how to start earning cashback:</b></p>\r\n<ul>\r\n    <li>Create a free account</li>\r\n    <li>Log in to your account</li>\r\n    <li>Find the shop you need</li>\r\n    <li>Click the link to visit the retailers site</li>\r\n    <li>Shop as you would normally</li>\r\n    <li>Get cash back!</li>\r\n</ul>', NOW()),
(5, 'terms', 'Terms and Conditions', '<p>your site terms and conditions</p>', NOW()),
(6, 'privacy', 'Privacy Policy', '<p>privacy policy information goes here</p>', NOW()),
(7, 'contact', 'Contact Us', '<p>If you have any questions, please contact us.</p>\r\n<p>Phone: 7777-777-777</p>\r\n<p>Email: support@yourdomain.com</p>', NOW())") or die("Inserting into <b>cashbackengine_content</b></b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_countries`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_countries` (
  `country_id` int(11) NOT NULL auto_increment,
  `name` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`country_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_countries</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_countries` (`country_id`, `name`) VALUES
		(1, 'United States'),
		(2, 'United Kingdom'),
		(3, 'Canada')") or die("Inserting into <b>cashbackengine_countries</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_email_templates`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_email_templates` (
  `template_id` int(11) unsigned NOT NULL auto_increment,
  `email_name` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `email_subject` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `email_message` text collate utf8_unicode_ci NOT NULL default '',
  `modified` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`template_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_email_templates</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_email_templates` (`template_id`, `email_name`, `email_subject`, `email_message`, `modified`) VALUES
(1, 'signup', 'Welcome to cashback site!', '<p style=\'font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px\'>\r\nDear {first_name},<br /><br />\r\nThank you for registering!<br /><br />\r\nStart earning cash back on your online purchases right away!<br /><br />\r\nHere is your login information:<br /><br />\r\nLogin: <b>{username}</b><br />\r\nPassword: <b>{password}</b><br /><br />\r\nPlease click at <a href=\'{login_url}\'>click here</a> to login in to your account.<br /><br />Thank you.\r\n</p>', NOW()),
(2, 'forgot_password', 'Forgot password email', '<p style=\'font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px\'>\r\nDear {first_name},<br /><br />\r\nAs you requested, here is new password for your account:<br /><br />\r\nLogin: <b>{username}</b><br />Password: <b>{password}</b> <br /><br />\r\nPlease <a href=\'{login_url}\'>click here</a> to log in.\r\n<br /><br />\r\nThank you.\r\n</p>', NOW()),
(3, 'invite_friend', 'Invitation from your friend', '<p style=\'font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px\'>\r\nHello {friend_name}, <br /><br />\r\nYour friend <b>{first_name}</b> wants to invite you to register on our cashback site.<br /><br />\r\nPlease <a href=\'{referral_link}\'>click here</a> to accept his invitation.\r\n<br /><br />\r\nBest Regards.\r\n</p>', NOW())") or die("Inserting into <b>cashbackengine_email_templates</b></b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_favorites`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_favorites` (
  `favorite_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) unsigned NOT NULL default '0',
  `retailer_id` int(11) unsigned NOT NULL default '0',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`favorite_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_favorites</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_messages`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_messages` (
  `message_id` int(11) unsigned NOT NULL auto_increment,
  `user_id` int(11) NOT NULL default '0',
  `is_admin` tinyint(1) NOT NULL default '0',
  `subject` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `message` text collate utf8_unicode_ci NOT NULL default '',
  `viewed` tinyint(1) NOT NULL default '0',
  `status` enum('new','replied','closed') collate utf8_unicode_ci NOT NULL default 'new',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_messages</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_messages_answers`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_messages_answers` (
  `answer_id` int(11) unsigned NOT NULL auto_increment,
  `message_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `is_admin` tinyint(1) NOT NULL default '0',
  `answer` text collate utf8_unicode_ci NOT NULL default '',
  `viewed` tinyint(1) NOT NULL default '0',
  `answer_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`answer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_messages_answers</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_reports`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_reports` (
  `report_id` int(11) unsigned NOT NULL auto_increment,
  `reporter_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `retailer_id` int(11) NOT NULL default '0',
  `report` text collate utf8_unicode_ci NOT NULL default '',
  `viewed` tinyint(1) NOT NULL default '0',
  `status` enum('active','pending','inactive') collate utf8_unicode_ci NOT NULL default 'pending',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`report_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_reports</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_reviews`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_reviews` (
  `review_id` int(11) unsigned NOT NULL auto_increment,
  `retailer_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `review_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `rating` tinyint(1) NOT NULL default '0',
  `review` text collate utf8_unicode_ci NOT NULL default '',
  `status` enum('active','pending','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`review_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_reviews</b> FAILED<br/>Error: ".mysql_error());


mysql_query("DROP TABLE IF EXISTS `cashbackengine_news`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_news` (
  `news_id` int(11) unsigned NOT NULL auto_increment,
  `news_title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `news_description` text collate utf8_unicode_ci NOT NULL default '',
  `status` enum('active','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`news_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_news</b> FAILED<br/>Error: ".mysql_error());


mysql_query("DROP TABLE IF EXISTS `cashbackengine_pmethods`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_pmethods` (
  `pmethod_id` int(11) unsigned NOT NULL auto_increment,
  `pmethod_title` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `pmethod_details` text collate utf8_unicode_ci NOT NULL default '',
  `status` enum('active','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  PRIMARY KEY  (`pmethod_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_pmethods</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_pmethods` (`pmethod_id`, `pmethod_title`, `pmethod_details`, `status`) VALUES
		(1, 'PayPal', 'Please enter your paypal account below!', 'active'),
		(2, 'Check', 'Please enter following information: <br />\r\n - Your Full Name <br />\r\n - Bank Name <br />\r\n - Bank Address <br />\r\n - Account #', 'inactive'),
		(3, 'Wire Transfer', 'Please enter following information: <br />\r\n - Your Full Name <br />\r\n - Bank Name <br />\r\n - Bank Address <br />\r\n - Account #', 'active'),
		(4, 'Moneybookers', 'Please enter your moneybookers account', 'inactive'),
		(5, 'Western Union Transfer', 'Please enter your first name, last name', 'inactive')") or die("Inserting into <b>cashbackengine_pmethods</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_retailers`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_retailers` (
  `retailer_id` int(11) unsigned NOT NULL auto_increment,
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `network_id` int(11) NOT NULL default '0',
  `program_id` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `url` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `image` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `cashback` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `conditions` text collate utf8_unicode_ci NOT NULL default '',
  `description` text collate utf8_unicode_ci NOT NULL default '',
  `featured` tinyint(1) NOT NULL default '0',
  `deal_of_week` tinyint(1) NOT NULL default '0',
  `visits` int(11) NOT NULL default '0',
  `status` enum('active','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`retailer_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_retailers</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_retailers` (`retailer_id`, `title`, `network_id`, `program_id`, `url`, `image`, `cashback`, `conditions`, `description`, `featured`, `visits`, `status`, `added`) VALUES
(1, 'GEM Motoring Assist', 1, 7777777, 'http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=77777&merchantID=1370&programmeID=12100&tracking={USERID}', 'http://banners.affiliatefuture.com/1370/64139.gif', '15', '1 per day', 'GEM Motoring Assist operates Breakdown Cover which provides motorists with the policy they need at the lowest cost!', 0, 0, 'active', NOW()),
(2, 'Red House', 1, 8882222, 'http://track.webgains.com/click.html?wglinkid=27227&wgcampaignid=5761&clickref={USERID}', 'http://track.webgains.com/link.html?wglinkid=27227&wgcampaignid=5761&js=0%20width=', '7%', 'you must jump 3 times before ordering', 'Red House is a specialist children\'s mail order bookshop. All its books are heavily discounted and include the latest bestsellers. You will find popular books by established and debut authors for all age ranges plus a range of fabulous non-fiction books.', 1, 0, 'active', NOW()),
(3, 'Chocolate Trading', 1, 1111111, 'http://www.awin1.com/cread.php?s=6104&v=350&q=3505&r=7751&clickref={USERID}', 'http://www.awin1.com/cshow.php?s=6104&v=350&q=3505&r=64751', '3%', '', 'The widest selection of premium chocolates from around the world. Over 30 leading chocolatiers. From single origin vintage bars to unique novelties and assorted gift boxes.', 0, 0, 'active', NOW()),
(4, 'AJ Electronics', 1, 2222222, 'http://track.webgains.com/click.html?wglinkid=4917&wgcampaignid=501&clickref={USERID}', 'http://track.webgains.com/link.html?wglinkid=4917&wgcampaignid=571&js=0', '20%', '', 'AJ Electronics is a leading online electrical superstore which sells all the top brands at the lowest possible price. Brands include: Panasonic, Sony, Samsung, Canon and much more.', 1, 0, 'active', NOW()),
(5, 'Flowers Direct', 1, 3333333, 'http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=7777&merchantID=826&programmeID=2607&mediaID=34566&tracking={USERID}&url=', 'http://banners.affiliatefuture.com/826/34566.gif', '7%', '', 'Flowers Direct provide the very best in quality, service and value with a dedicated network of florists providing over 500 products including flowers, gifts and much more.', 0, 0, 'active', NOW()),
(6, 'Direct Cosmetics', 1, 33334444, 'http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=7777&merchantID=3279&programmeID=8831&mediaID=63006&tracking={USERID}&url=', 'http://banners.affiliatefuture.com/3279/63006.gif', '3%', '1 visit per day', 'Top brands and discount prices on fragrance, skin care and beauty products. Buy great products today!', 0, 0, 'active', NOW()),
(7, 'PC World Business', 1, 4443433, 'http://scripts.affiliatefuture.com/AFClick.asp?affiliateID=7777&merchantID=2016&programmeID=5451&mediaID=67989&tracking={USERID}&url=', 'http://banners.affiliatefuture.com/2016/67989.jpg', '2.25%', '', 'PC World Business offers over 30,000 products with market beating prices and many products on next day delivery for in stock items', 0, 0, 'active', NOW())") or die("Inserting into  <b>cashbackengine_retailers</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_retailer_to_category`");
mysql_query("CREATE TABLE `cashbackengine_retailer_to_category` (
  `retailer_id` int(11) unsigned NOT NULL default '0',
  `category_id` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`retailer_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_retailer_to_category</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_retailer_to_category` (`retailer_id`, `category_id`) VALUES
(1, 7),
(1, 2),
(2, 4),
(2, 1),
(2, 12),
(3, 2),
(4, 1),
(4, 2),
(5, 3),
(5, 12),
(6, 7),
(6, 12),
(7, 3)") or die("Inserting into <b>cashbackengine_retailer_to_category</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_coupons`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_coupons` (
  `coupon_id` int(11) unsigned NOT NULL auto_increment,
  `retailer_id` int(11) NOT NULL default '0',
  `user_id` int(11) NOT NULL default '0',
  `title` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `code` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `start_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `end_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `description` text collate utf8_unicode_ci NOT NULL default '',
  `exclusive` tinyint(1) NOT NULL default '0',
  `visits` int(11) NOT NULL default '0',
  `status` enum('active','inactive','expired') collate utf8_unicode_ci NOT NULL default 'active',
  `added` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`coupon_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_coupons</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_settings`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_settings` (
  `setting_id` int(11) NOT NULL auto_increment,
  `setting_key` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `setting_value` text collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`setting_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_settings</b> FAILED<br/>Error: ".mysql_error());

mysql_query("INSERT INTO `cashbackengine_settings` (`setting_id`, `setting_key`, `setting_value`) VALUES
(1000, 'website_title', 'MyCashbackSite'),
(1001, 'website_url', 'http://www.yourcashbacksite.com/'),
(1002, 'website_home_title', 'MyCashbackSite - Save money on your online shopping!'),
(1003, 'website_email', 'admin@yourcashbacksite.com'),
(1004, 'website_language', 'english'),
(1005, 'website_timezone', '0'),
(1006, 'website_currency', '$'),
(1007, 'signup_credit', '5'),
(1008, 'refer_credit', '5'),
(1009, 'min_payout', '50'),
(1010, 'min_transaction', '10'),
(1011, 'results_per_page', '10'),
(1012, 'image_width', '120'),
(1013, 'image_height', '60'),
(1014, 'show_statistics', '1'),
(1015, 'show_landing_page', '1'),
(1016, 'reviews_approve', '1'),
(1017, 'facebook_page', ''),
(1018, 'twitter_page', ''),
(1019, 'license', '{$license_key}'),
(1020, 'word', '{$my_pwd}'),
(1021, 'iword', '{$my_word}')") or die("Inserting into <b>cashbackengine_settings</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_transactions`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_transactions` (
  `transaction_id` int(11) unsigned NOT NULL auto_increment,
  `reference_id` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `network_id` int(10) NOT NULL default '0',
  `program_id` varchar(255) collate utf8_unicode_ci NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `payment_type` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `payment_method` int(10) NOT NULL default '0',
  `payment_details` text collate utf8_unicode_ci NOT NULL default '',
  `transaction_amount` decimal(15,4) NOT NULL default '0.0000',
  `transaction_commision` decimal(15,4) NOT NULL default '0.0000',
  `amount` decimal(15,4) NOT NULL default '0.0000',
  `status` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `reason` text collate utf8_unicode_ci NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `updated` datetime NOT NULL default '0000-00-00 00:00:00',
  `process_date` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`transaction_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_transactions</b> FAILED<br/>Error: ".mysql_error());



mysql_query("DROP TABLE IF EXISTS `cashbackengine_users`");
mysql_query("CREATE TABLE IF NOT EXISTS `cashbackengine_users` (
  `user_id` int(11) unsigned NOT NULL auto_increment,
  `username` varchar(70) collate utf8_unicode_ci NOT NULL default '',
  `password` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `email` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `fname` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `lname` varchar(25) collate utf8_unicode_ci NOT NULL default '',
  `address` varchar(32) collate utf8_unicode_ci NOT NULL default '',
  `address2` varchar(70) collate utf8_unicode_ci NOT NULL default '',
  `city` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `state` varchar(50) collate utf8_unicode_ci NOT NULL default '',
  `zip` varchar(10) collate utf8_unicode_ci NOT NULL default '',
  `country` varchar(100) collate utf8_unicode_ci NOT NULL default '',
  `phone` varchar(20) collate utf8_unicode_ci NOT NULL default '',
  `ref_id` int(11) unsigned NOT NULL default '0',
  `newsletter` tinyint(1) NOT NULL default '0',
  `ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `status` enum('active','inactive') collate utf8_unicode_ci NOT NULL default 'active',
  `last_login` datetime NOT NULL default '0000-00-00 00:00:00',
  `login_count` int(8) unsigned NOT NULL default '0',
  `last_ip` varchar(15) collate utf8_unicode_ci NOT NULL default '',
  `created` datetime NOT NULL default '0000-00-00 00:00:00',
  `block_reason` tinytext collate utf8_unicode_ci NOT NULL default '',
  PRIMARY KEY  (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci") or die("Creating <b>cashbackengine_users</b> FAILED<br/>Error: ".mysql_error());


mysql_query("INSERT INTO `cashbackengine_users` (`user_id`, `username`, `password`, `email`, `fname`, `lname`, `address`, `address2`, `city`, `state`, `zip`, `country`, `phone`, `ref_id`, `ip`, `status`, `last_login`, `login_count`, `last_ip`, `created`, `block_reason`) VALUES
(1, 'demo@demo.com', '62cc2d8b4bf2d8728120d052163a77df', 'demo@demo.com', 'Admin', 'Admin', '', '', '', '', '', 'United States', '', 0, '127.0.0.1', 'active', NOW(), 0, '', NOW(), '')") or die("Inserting into <b>cashbackengine_users</b> FAILED<br/>Error: ".mysql_error());


		$complete = 1;

	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= $errorname."<br/>\n";
	}

}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>CashbackEngine Installation</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<style type="text/css">
<!--

body {
	font-family: Tahoma, Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	color: #444;
	margin: 0;
	padding: 0;
}

a {color: #3494CF; text-decoration: underline;}
a:hover {color: #94CA29; text-decoration: none;}

h1 {
	font-family: times, Times New Roman, times-roman, georgia, Arial, Verdana, sans-serif;
	font-size: 27px;
	font-weight: normal;
	color: #444;
	margin: 10px 0;
	padding: 0;
}

form {
	margin: 0 0 0 0;
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000;
}

table tr td {
	font-size: 11px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #000;
}

input.textbox {
	background-color: #FFF;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 11px;
	margin: 0;
	padding: 2px 2px 2px 3px;
	border: 1px solid #bdc7d8;
}

.submit {
	padding: 3px 7px 4px 7px;
	border-style: solid;
	border-top-width: 1px;
	border-left-width: 1px;
	border-bottom-width: 1px;
	border-right-width: 1px;
	border-top-color: #D9DFEA;
	border-left-color: #D9DFEA;
	border-bottom-color: #6C9F11;
	border-right-color: #6C9F11;
	background-color: #9ACE3B;
	color: #FFFFFF;
	font-size: 12px;
	font-family: Verdana, tahoma, arial, sans-serif;
	font-weight: bold;
	text-align: center;
	cursor: pointer;
}

.submit:hover {
	color: #FFFFFF;
	background: #88BB22;
}

.large_text {
	font-family: Verdana, tahoma, arial, sans-serif;
	font-size:19px;
	color:#FFFFFF;
}

-->
</style>
</head>
<table width="500" align="center" cellpadding="5" cellspacing="0" border="0" align="center">
<tr>
<td height="300" valign="middle" align="center">
      
       <h1><font color="#84C315">Cashback</font><font color="#5392D5">Engine</font> Installation</h1>

		<?php if ($complete == 1) { ?>
			<table width="100%" align="center" cellpadding="5" cellspacing="0" border="0">
			<tr height="50">
				<td style="border: 1px #1A8308 dotted;" bgcolor="#1EC302" align="center" valign="middle">
					<span class="large_text">Congratulations! Installation is complete!</span>
				</td>
			</tr>
			<tr height="220">
				<td style="border: 1px solid #EEEEEE;" bgcolor="#F9F9F9" align="center" valign="middle">
					<table width="100%" align="center" cellpadding="5" cellspacing="0" border="0">
					<tr valign="middle">
						<td nowrap="nowrap" width="50%" align="right"><span style="font-size:13px; color:#2B2B2B;">Admin Username:</span></td><td nowrap="nowrap" width="50%" align="left"><span style="font-size:19px;">admin</span></td>
					</tr>
					<tr valign="middle">
						<td nowrap="nowrap" align="right"><span style="font-size:13px; color:#2B2B2B;">Admin Password:</span></td><td nowrap="nowrap" align="left"><span style="font-size:19px;"><?php echo $rnd_pwd; ?></span></td>
					</tr>
					</table>
					<p>&nbsp;</p>
					<p><b>Note your admin area password!</b></p>
					<p>You can now <a target="_blank" href="/admin/">login to your control panel</a> and change your password.</a></p>
				</td>
			</tr>
			<tr height="35">
				<td nowrap="nowrap" align="center" valign="middle">
					<b><font color="#FF0000"><u>Important</u>: Please now DELETE "install.php" file from your server!</font></b>
				</td>
			</tr>
			</table>
			<br/>
		<?php }else{ ?>

		<?php if (isset($allerrors)) { ?>
			<table width="100%" style="border: 1px #F3C5D4 dotted;" bgcolor="#FB0F0F" align="center" cellpadding="5" cellspacing="0" border="0">
			<tr height="35">
				<td align="center" valign="middle">
					<font color="#FFFFFF"><b><?php echo $allerrors; ?></b></font>
				</td>
			</tr>
			</table>
			<br/>
		<?php } ?>

		<form action="install.php" method="post">
        <table width="100%" style="border: 1px solid #EEEEEE;" bgcolor="#F9F9F9" align="center" cellpadding="3" cellspacing="3" border="0">
          <tr height="30">
            <td colspan="2" align="center" valign="middle"><b>Enter your license key below</b></td>
          </tr>
          <tr height="30">
            <td colspan="2" align="center" valign="middle">You can find out the license key by logging into your <a target="_blank" href="http://www.cashbackengine.net/c/login.php">Member Account</a>.</td>
          </tr>
          <tr height="30">
            <td align="right" valign="middle"><b>Your License Key</b>:</td>
            <td align="left" valign="middle"><input type="text" class="textbox" name="license" value="" size="32" maxlength="24" /></td>
          </tr>
          <tr height="40">
			<td colspan="2" align="center" valign="middle">
		  		<input type="hidden" name="action" value="install" />
				<input type="submit" class="submit" name="install" id="install" value="Start Installation" />
			</td>
          </tr>
        </table>
      </form>

	  <?php } ?>

</td>
</tr>
</table>
</body>
</html>
