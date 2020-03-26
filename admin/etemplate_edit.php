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
	require_once("./inc/fckeditor/fckeditor.php");


if (isset($_POST['action']) && $_POST['action'] == "editetemplate")
{
	$etemplate_id	= (int)getPostParameter('eid');
	$email_subject	= mysql_real_escape_string($_POST['esubject']);
	$email_message	= mysql_real_escape_string($_POST['emessage']);

	unset($errs);
	$errs = array();

	if (!($email_subject && $email_message))
	{
		$errs[] = "Please fill in all required fields";
	}

	if (count($errs) == 0)
	{
		$sql = "UPDATE cashbackengine_email_templates SET email_subject='$email_subject', email_message='$email_message', modified=NOW() WHERE template_id='$etemplate_id'";

		if (smart_mysql_query($sql))
		{
			header("Location: etemplates.php?msg=updated");
			exit();
		}
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= "&#155; ".$errorname."<br/>\n";
	}
}


	if (isset($_GET['id']) && is_numeric($_GET['id'])) { $eid = (int)$_GET['id']; } else { $eid = (int)$_POST['eid']; }
	$query = "SELECT * FROM cashbackengine_email_templates WHERE template_id='$eid'";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	$title = "Edit Email Template";
	require_once ("inc/header.inc.php");

?>
 
      <?php if ($total > 0) {

		  $row = mysql_fetch_array($result);
		  
      ?>

        <h2>Edit Email Template</h2>

			<?php if (isset($allerrors) && $allerrors != "") { ?>
				<div class="error_box"><?php echo $allerrors; ?></div>
			<?php } ?>

        <form action="" method="post">
          <table width="100%" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td nowrap="nowrap" width="35" valign="middle" align="right" class="tb1"><span class="req">* </span>Email Subject:</td>
            <td valign="top"><input type="text" name="esubject" id="esubject" value="<?php echo $row['email_subject']; ?>" size="50" class="textbox" /></td>
          </tr>
           <tr>
            <td>&nbsp;</td>
            <td height="50" style="border: 1px solid #D7D7D7;" bgcolor="#F7F7F7" align="center" valign="middle">
				<p>Please use following variables for your email template:</p>
				<table width="95%" align="center" cellpadding="2" cellspacing="2" border="0">
					<?php if ($row['email_name'] == "signup") { ?>
						<tr><td nowrap="nowrap" align="right"><b>{first_name}</b></td><td nowrap="nowrap" align="left"> - Member First Name</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{username}</b></td><td nowrap="nowrap" align="left"> - Member Username</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{password}</b></td><td nowrap="nowrap" align="left"> - Member Password</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{login_url}</b></td><td nowrap="nowrap" align="left"> - Login Link</td></tr>
					<?php }elseif($row['email_name'] == "forgot_password") { ?>
						<tr><td nowrap="nowrap" align="right"><b>{first_name}</b></td><td nowrap="nowrap" align="left"> - Member First Name</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{username}</b></td><td nowrap="nowrap" align="left"> - Member Username</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{password}</b></td><td nowrap="nowrap" align="left"> - Member Password</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{login_url}</b></td><td nowrap="nowrap" align="left"> - Login Link</td></tr>
					<?php }elseif($row['email_name'] == "invite_friend") { ?>
						<tr><td nowrap="nowrap" align="right"><b>{friend_name}</b></td><td nowrap="nowrap" align="left"> - Friend First Name</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{first_name}</b></td><td nowrap="nowrap" align="left"> - Member First Name</td></tr>
						<tr><td nowrap="nowrap" align="right"><b>{referral_link}</b></td><td nowrap="nowrap" align="left"> - Referral Link</td></tr>
					<?php } ?>
				</table>
			</td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1"><span class="req">* </span>Email Message:</td>
            <td valign="top">
			<?php

				$cFCKeditor = new FCKeditor('emessage') ;
				$cFCKeditor->BasePath = './inc/fckeditor/';
				$cFCKeditor->Height = '300';
				$cFCKeditor->Value = stripslashes($row['email_message']);
				$cFCKeditor->Create() ;
			
			?>			
			</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
			<input type="hidden" name="eid" id="eid" value="<?php echo (int)$row['template_id']; ?>" />
			<input type="hidden" name="action" id="action" value="editetemplate" />
			<input type="submit" name="update" id="update" class="submit" value="Update" />&nbsp;&nbsp;
			<input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='etemplates.php'" />
		  </td>
          </tr>
        </table>
      </form>

      <?php }else{ ?>
				<p align="center">Sorry, no record found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>