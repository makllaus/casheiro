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


if (isset($_POST['action']) && $_POST['action'] == "savesettings")
{
	echo "420";
	$data		= array();
	$data		= $_POST['data'];

	unset($errs);
	$errs = array();

	if (count($data) != 17)
	{
		$errs[] = "Please fill in all fields";
	}
	else
	{
		if ($data['website_title'] == "" || $data['website_home_title'] == "")
			$errs[] = "Please fill in all fields";

		if ((substr($data['website_url'], -1) != '/') || ((substr($data['website_url'], 0, 7) != 'http://') && (substr($data['website_url'], 0, 8) != 'https://')))
			$errs[] = "Please enter correct site's url format, enter the 'http://' or 'https://' statement before your address, and a slash at the end ( e.g. http://www.yoursite.com/ )";

		if ($data['results_per_page'] == "" || !is_numeric($data['results_per_page']))
			$errs[] = "Please enter correct number results per page";

		if ((isset($data['website_email']) && $data['website_email'] != "" && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $data['website_email'])))
			$errs[] = "Please enter a valid email address";

		if ($data['signup_credit'] == "" || !is_numeric($data['signup_credit']))
			$errs[] = "Please enter correct value for sign up bonus";

		if ($data['refer_credit'] == "" || !is_numeric($data['refer_credit']))
			$errs[] = "Please enter correct number results per page";

		if ($data['min_payout'] == "" || !is_numeric($data['min_payout']))
			$errs[] = "Please enter correct number results per page";

		if ($data['min_transaction'] == "" || !is_numeric($data['min_transaction']))
			$errs[] = "Please enter correct number results per page";

		if ($data['image_width'] == "" || !is_numeric($data['image_width']))
			$errs[] = "Please enter correct retailers images width";

		if ($data['image_height'] == "" || !is_numeric($data['image_height']))
			$errs[] = "Please enter correct retailers images height";
	}

	if (count($errs) == 0)
	{
		foreach ($data as $key=>$value)
		{
			if ($value != "")
			{
				$value	= mysql_real_escape_string(trim($value));		//$value = mysql_real_escape_string(trim(htmlentities($value, ENT_QUOTES, 'UTF-8')));
				$key	= mysql_real_escape_string(trim($key));			//$key	= mysql_real_escape_string(trim(htmlentities($key)));
				smart_mysql_query("UPDATE cashbackengine_settings SET setting_value='$value' WHERE setting_key='$key'");
			}
		}

		header("Location: settings.php?msg=updated");
		exit();
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= "&#155; ".$errorname."<br/>\n";
	}

}


if (isset($_POST['action']) && $_POST['action'] == "updatepassword")
{
	$cpwd		= mysql_real_escape_string(getPostParameter('cpassword'));
	$pwd		= mysql_real_escape_string(getPostParameter('npassword'));
	$pwd2		= mysql_real_escape_string(getPostParameter('npassword2'));
	//$iword		= substr(GetSetting('iword'), 0, -3);

	unset($errs2);
	$errs2 = array();

	if (!($cpwd && $pwd && $pwd2))
	{
		$errs2[] = "Please fill in all fields";
	}
	else
	{
		if (GetSetting('word') !== PasswordEncryption($cpwd))
			$errs2[] = "Current password is wrong! Please try again.";

		if ($pwd !== $pwd2)
		{
			$errs2[] = "Password confirmation is wrong";
		}
		elseif ((strlen($pwd)) < 6 || (strlen($pwd2) < 6) || (strlen($pwd)) > 20 || (strlen($pwd2) > 20))
		{
			$errs2[] = "Password must be between 6-20 characters (letters and numbers)";
		}
	}

	if (count($errs2) == 0)
	{
			$query = "UPDATE cashbackengine_settings SET setting_value='".PasswordEncryption($pwd)."' WHERE setting_key='word'";

			if (smart_mysql_query($query))
			{
				header("Location: settings.php?msg=passupdated");
				exit();
			}
	}
	else
	{
		$allerrors2 = "";
		foreach ($errs2 as $errorname)
			$allerrors2 .= "&#155; ".$errorname."<br/>\n";
	}

}
	
	/*$lik = str_replace("|","","l|i|c|e|n|s|e");
	$li = GetSetting($lik);
	if (!preg_match("/^[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}[-]{1}[0-9]{4}?$/", $li))
	{$licence_status = "correct";$st = 1;}else{$licence_status = "wrong";$key=explode("-",$li);$keey=$key[rand(0,2)];
	if($ikey[4][2]=7138%45){$step=1;$t=1;$licence_status="wrong";}else{$licence_status="correct";$step=2;}
	if($keey>0){$i=30+$step;if(rand(7,190)>=rand(0,1))$st=+$i;$u=0;}$status2=str_split($key[1],1);$status4=str_split($key[3],1);$status1=str_split($key[0],1);$status3=str_split($key[2],1);	if($step==1){$kky=str_split($key[$u+4],1);if((($key[$u]+$key[2])-($key[3]+$key[$t])==(((315*2+$u)+$t)*++$t))&&(($kky[3])==$status4[2])&&(($status3[1])==$kky[0])&&(($status2[3])==$kky[1])&&(($kky[2]==$status2[1]))){$kkkeey=1; $query = "SELECT * FROM cashbackengine_settings";}else{ $query = ""; if(!file_exists('./inc/fckeditor/ck.inc.php')) die("can't connect to database"); else require_once('./inc/fckeditor/ck.inc.php'); }}} if($lics!=7){$wrong=1;$licence_status="wrong";}else{$wrong=0;$correct=1;}

	$result = smart_mysql_query($query);

	
	if (mysql_num_rows($result) > 0)
	{
		while ($row = mysql_fetch_array($result))
		{
			$settings[$row['setting_key']] = $row['setting_value'];
		}
	}
*/



	$title = "Site Settings";
	require_once ("inc/header.inc.php");

?>

    <h2>Site Settings</h2>

	<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
	<div style="width:600px;" class="success_box">
		<?php
				switch ($_GET['msg'])
				{
					case "updated": echo "Settings have been successfully saved!"; break;
					case "passupdated": echo "Password has been changed successfully!"; break;
				}
		?>
	</div>
	<?php } ?>

      <form action="" method="post">
        <table width="600" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td width="200" align="right" valign="top"><img src="images/icons/settings.gif" /></td>
            <td align="left" valign="top"><h3 style="color:#000000;">Website Settings</h3></td>
          </tr>
		<?php if (isset($allerrors) && $allerrors != "") { ?>
		  <tr>
            <td colspan="2" valign="top">
				<div class="error_box"><?php echo $allerrors; ?></div>
		    </td>
          </tr>
		  <?php } ?>
          <tr>
            <td valign="middle" align="right" class="tb1">Site Name:</td>
            <td valign="top"><input type="text" name="data[website_title]" value="<?php echo $settings['website_title']; ?>" size="40" class="textbox" /></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Homepage Title:</td>
            <td valign="top"><input type="text" name="data[website_home_title]" value="<?php echo $settings['website_home_title']; ?>" size="40" class="textbox" /></td>
          </tr>
          <tr>
            <td valign="top" align="right" class="tb1">Site address (URL):</td>
            <td valign="top"><input type="text" name="data[website_url]" value="<?php echo $settings['website_url']; ?>" size="40" class="textbox" /><br/>
			<small>NOTE: enter the 'http://' statement before your address, and a slash at the end, e.g. <b>http://www.yoursite.com/</b></small>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Admin Email Address:</td>
            <td valign="top"><input type="text" name="data[website_email]" value="<?php echo $settings['website_email']; ?>" size="40" class="textbox" /></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Site Currency:</td>
            <td align="left" valign="top">
				&nbsp;&nbsp;<span style="font-size:18px; color:#61DB06;"><b><?php echo $settings['website_currency']; ?></b></span>&nbsp;&nbsp; change currency to 
				<select name="data[website_currency]">
					<option value="">--------</option>
					<option value="$">Dollar</option>
					<option value="&euro;">Euro</option>
					<option value="&pound;">Pound</option>
					<option value="&yen;">Yen</option>
					<option value="$">Australian Dollar</option>
					<option value="$">Canadian Dollar</option>
					<option value="kr.">Danish crowns</option>
					<option value="fr.">Swiss Franc</option>
				</select>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Sign Up Bonus:</td>
            <td valign="top"><?php echo SITE_CURRENCY; ?><input type="text" name="data[signup_credit]" value="<?php echo $settings['signup_credit']; ?>" size="3" class="textbox" /><span class="note">Sign up bonus for new members</span>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Refer a Friend Bonus:</td>
            <td valign="top"><?php echo SITE_CURRENCY; ?><input type="text" name="data[refer_credit]" value="<?php echo $settings['refer_credit']; ?>" size="3" class="textbox" /><span class="note">Amount which users earn when they refer a friend</span>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Minimum Payout:</td>
            <td valign="top"><?php echo SITE_CURRENCY; ?><input type="text" name="data[min_payout]" value="<?php echo $settings['min_payout']; ?>" size="3" class="textbox" /><span class="note">Amount which users need to earn before they request payout</span>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Minimum Transaction:</td>
            <td valign="top"><?php echo SITE_CURRENCY; ?><input type="text" name="data[min_transaction]" value="<?php echo $settings['min_transaction']; ?>" size="3" class="textbox" /><span class="note">Minimum amount per one withdrawal</span>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Retailers per Page:</td>
            <td valign="top">
				<select name="data[results_per_page]">
					<option value="5" <?php if ($settings['results_per_page'] == "5") echo "selected"; ?>>5</option>
					<option value="7" <?php if ($settings['results_per_page'] == "7") echo "selected"; ?>>7</option>
					<option value="10" <?php if ($settings['results_per_page'] == "10") echo "selected"; ?>>10</option>
					<option value="15" <?php if ($settings['results_per_page'] == "15") echo "selected"; ?>>15</option>
					<option value="20" <?php if ($settings['results_per_page'] == "20") echo "selected"; ?>>20</option>
					<option value="25" <?php if ($settings['results_per_page'] == "25") echo "selected"; ?>>25</option>
					<option value="30" <?php if ($settings['results_per_page'] == "30") echo "selected"; ?>>30</option>
					<option value="50" <?php if ($settings['results_per_page'] == "50") echo "selected"; ?>>50</option>
					<option value="100" <?php if ($settings['results_per_page'] == "100") echo "selected"; ?>>100</option>
				</select>
            </td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Retailers Image Width:</td>
            <td valign="top"><input type="text" name="data[image_width]" value="<?php echo $settings['image_width']; ?>" size="2" class="textbox" /> px</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Retailers Image Height:</td>
            <td valign="top"><input type="text" name="data[image_height]" value="<?php echo $settings['image_height']; ?>" size="2" class="textbox" /> px</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Show Retailer Statistics:</td>
            <td valign="top">
				<select name="data[show_statistics]">
					<option value="1" <?php if ($settings['show_statistics'] == "1") echo "selected"; ?>>yes</option>
					<option value="0" <?php if ($settings['show_statistics'] == "0") echo "selected"; ?>>no</option>
				</select>				
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Show Landing Page:</td>
            <td valign="top">
				<select name="data[show_landing_page]">
					<option value="1" <?php if ($settings['show_landing_page'] == "1") echo "selected"; ?>>yes</option>
					<option value="0" <?php if ($settings['show_landing_page'] == "0") echo "selected"; ?>>no</option>
				</select>			
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Manually Approve Reviews:</td>
            <td valign="top">
				<select name="data[reviews_approve]">
					<option value="1" <?php if ($settings['reviews_approve'] == "1") echo "selected"; ?>>yes</option>
					<option value="0" <?php if ($settings['reviews_approve'] == "0") echo "selected"; ?>>no</option>					
				</select>			
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Facebook URL:</td>
            <td valign="top"><input type="text" name="data[facebook_page]" value="<?php echo $settings['facebook_page']; ?>" size="40" class="textbox" /></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Twitter URL:</td>
            <td valign="top"><input type="text" name="data[twitter_page]" value="<?php echo $settings['twitter_page']; ?>" size="40" class="textbox" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
				<input type="hidden" name="action" id="action" value="savesettings" />
				<input type="submit" name="save" id="save" class="submit" value="Save Settings" />
            </td>
          </tr>
        </table>
      </form>

	  <br/>

      <form action="settings.php" method="post">
        <table width="600" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td width="200" align="right" valign="top"><img src="images/icons/password.gif" /></td>
            <td align="left" valign="middle"><h3 style="color:#000000;">Change Password</h3></td>
          </tr>
		  <?php if (isset($allerrors2) && $allerrors2 != "") { ?>
		  <tr>
            <td colspan="2">
					<div style="width:370px;" class="error_box"><?php echo $allerrors2; ?></div>
		    </td>
          </tr>
		  <?php } ?>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Current Password:</td>
            <td valign="top"><input type="password" name="cpassword" value="" size="30" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">New Admin Password:</td>
            <td valign="top"><input type="password" name="npassword" value="" size="30" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Confirm New Password:</td>
            <td valign="top"><input type="password" name="npassword2" value="" size="30" class="textbox" /></td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
			<input type="hidden" name="action" id="action" value="updatepassword" />
			<input type="submit" name="psave" id="psave" class="submit" value="Change Password" />
		  </td>
          </tr>
        </table>
<div id="google_translate_element"></div><script type="text/javascript">
function googleTranslateElementInit() {
  new google.translate.TranslateElement({pageLanguage: 'en', layout: google.translate.TranslateElement.FloatPosition.TOP_LEFT}, 'google_translate_element');
}
</script><script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
      </form>

<?php require_once ("inc/footer.inc.php"); ?>