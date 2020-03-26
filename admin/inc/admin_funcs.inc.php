<?php
/*******************************************************************\
 * CashbackEngine v2.0
 * http://www.CashbackEngine.net
 *
 * Copyright (c) 2010-2013 CashbackEngine Software. All rights reserved.
 * ------------ CashbackEngine IS NOT FREE SOFTWARE --------------
\*******************************************************************/


function CategoriesDropDown ($parent_id, $sep = "", $current = 0, $parent = 0)
{
	$result = smart_mysql_query("SELECT name, category_id FROM cashbackengine_categories WHERE category_id<>'$current' AND parent_id='$parent_id' ORDER BY name");
	$total = mysql_num_rows($result);

	if ($total > 0)
	{
		while ($row = mysql_fetch_array($result))
		{
			$category_id = $row['category_id'];
			$category_name = $row['name'];
			if ($parent > 0 && $category_id == $parent) $selected = " selected=\"selected\""; else $selected = "";
			echo "<option value=\"".$category_id."\"".$selected.">".$sep.$category_name."</option>\n";
			CategoriesDropDown($category_id, $sep.$category_name." &gt; ", $current, $parent);
		}
	}
}

function CategoriesList ($parent_id, $sep = "")
{
	static $allcategories;
	$result = smart_mysql_query("SELECT name, category_id FROM cashbackengine_categories WHERE parent_id='$parent_id' ORDER BY name");
	$total = mysql_num_rows($result);

	if ($total > 0)
	{
		while ($row = mysql_fetch_array($result))
		{
			$category_id = $row['category_id'];
			$category_name = $row['name'];
			$allcategories[$category_id] = $sep.$category_name;
			CategoriesList($category_id, $sep.$category_name." &gt; ");
		}
	}
	return $allcategories;
}

function CategoryTotalItems ($category_id)
{
	$result = smart_mysql_query("SELECT COUNT(retailer_id) as total FROM cashbackengine_retailer_to_category WHERE category_id='$category_id'");
	$row = mysql_fetch_array($result);
	return $row['total'];
}

function DeleteUser ($user_id)
{
	$userid = (int)$user_id;
	smart_mysql_query("DELETE FROM cashbackengine_users WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_favorites WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_clickhistory WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_messages WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_messages_answers WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_transactions WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_reports WHERE user_id='$userid'");
	smart_mysql_query("DELETE FROM cashbackengine_reviews WHERE user_id='$userid'");
}

function DeleteRetailer ($retailer_id)
{
	$rid = (int)($retailer_id);
	smart_mysql_query("DELETE FROM cashbackengine_retailers WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_retailer_to_category WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_clickhistory WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_favorites WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_reviews WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_coupons WHERE retailer_id='$rid'");
	smart_mysql_query("DELETE FROM cashbackengine_reports WHERE retailer_id='$rid'");
}

function DeleteCoupon ($coupon_id)
{
	$couponid = (int)($coupon_id);
	smart_mysql_query("DELETE FROM cashbackengine_coupons WHERE coupon_id='$couponid'");
}

function DeleteNews ($news_id)
{
	$newsid = (int)$news_id;
	smart_mysql_query("DELETE FROM cashbackengine_news WHERE news_id='$newsid'");
}

function DeleteReview ($review_id)
{
	$reviewid = (int)($review_id);
	smart_mysql_query("DELETE FROM cashbackengine_reviews WHERE review_id='$reviewid'");
}

function DeleteReport ($report_id)
{
	$report_id = (int)$report_id;
	smart_mysql_query("DELETE FROM cashbackengine_reports WHERE report_id='$report_id'");
}

function DeleteMessage ($message_id)
{
	$mid = (int)$message_id;
	smart_mysql_query("DELETE FROM cashbackengine_messages WHERE message_id='$mid'");
	smart_mysql_query("DELETE FROM cashbackengine_messages_answers WHERE message_id='$mid'");
}

function DeletePayment ($payment_id)
{
	$pid = (int)$payment_id;
	smart_mysql_query("DELETE FROM cashbackengine_transactions WHERE transaction_id='$pid'");
}

function BlockUnblockUser ($user_id, $unblock=0)
{
	$userid = (int)$user_id;

	if ($unblock == 1)
		smart_mysql_query("UPDATE cashbackengine_users SET status='active' WHERE user_id='$userid'");
	else
		smart_mysql_query("UPDATE cashbackengine_users SET status='inactive' WHERE user_id='$userid'");
}

function GetRetailerCategory($retailer_id)
{
		unset($retailer_cats);
		$retailer_cats = array();

		$sql_retailer_cats = smart_mysql_query("SELECT category_id FROM cashbackengine_retailer_to_category WHERE retailer_id='$retailer_id'");		
					
		while ($row_retailer_cats = mysql_fetch_array($sql_retailer_cats))
		{
			$retailer_cats[] = $row_retailer_cats['category_id'];
		}

		$categories_list = array();
		$allcategories = array();
		$allcategories = CategoriesList(0);
		foreach ($allcategories as $category_id => $category_name)
		{
			if (is_array($retailer_cats) && in_array($category_id, $retailer_cats))
			{
				$categories_list[] = $category_name;
			}
		}
		
		foreach ($categories_list as $cat_name)
		{
			echo "&raquo; ".$cat_name."<br/>";
		}
}

function GetNetworkName($network_id)
{
	$sql = "SELECT network_name FROM cashbackengine_affnetworks WHERE network_id='$network_id' LIMIT 1";
	$result = smart_mysql_query($sql);
	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		return $row['network_name'];
	}
}

?>