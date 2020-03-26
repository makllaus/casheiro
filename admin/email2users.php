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


	$query = "SELECT * FROM cashbackengine_users WHERE email<>'' AND newsletter='1' AND status='active'";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


if (isset($_POST['action']) && $_POST['action'] == "email2users")
{
	$msubject	= trim($_POST['msubject']);
	$allmessage = nl2br($_POST['allmessage']);

	unset($errs);
	$errs = array();

	if (!($msubject && $allmessage))
	{
		$errs[] = "Please enter subject and message";
	}

	if (count($errs) == 0)
	{
		while ($row = mysql_fetch_array($result))
		{
			////////////////////////////////  Send Message  //////////////////////////////
			$message = "<html>
						<head>
							<title>".$subject."</title>
						</head>
						<body>
						<table width='80%' border='0' cellpadding='10'>
						<tr>
							<td>";
			$message .= $allmessage;
			$message .= "	</td>
						</tr>
						</table>
						</body>
						</html>";

			$to_email = $row['fname'].' '.$row['lname'].' <'.$row['email'].'>';
			
			$subject = $msubject;		
			
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.SITE_TITLE.' <'.SITE_MAIL.'>' . "\r\n";
			
			@mail($to_email, $subject, $message, $headers);
			////////////////////////////////////////////////////////////////////////////////
		}

		header ("Location: email2users.php?msg=1");
		exit();
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= $errorname."<br/>\n";
	}
}

	$title = "Send Email to All Members";
	require_once ("inc/header.inc.php");

?>
 

      <?php if ($total > 0) { ?>

        <h2>Send Email to All Members</h2>

		<?php if (isset($_GET['msg']) && $_GET['msg'] == 1) { ?>
			<div style="width:90%;" class="success_box">Message has been sent to all members!</div>
		<?php }else{ ?>

		<?php if (isset($allerrors) && $allerrors != "") { ?>
			<div style="width:90%;" class="error_box"><?php echo $allerrors; ?></div>
		<?php } ?>

        <form action="" method="post">
          <table width="90%" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td nowrap="nowrap" width="35" valign="middle" align="right" class="tb1"><span class="req">* </span>Subject:</td>
            <td valign="top"><input type="text" name="msubject" id="msubject" value="<?php echo $msubject; ?>" size="70" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1"><span class="req">* </span>Message:</td>
            <td valign="top">
			<?php

				$cFCKeditor = new FCKeditor('allmessage') ;
				$cFCKeditor->BasePath = './inc/fckeditor/';
				$cFCKeditor->Height = '350';
				$cFCKeditor->Value = $allmessage;
				$cFCKeditor->Create() ;
			
			?>			
			</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
			<input type="hidden" name="action" id="action" value="email2users" />
			<input type="submit" name="Send" id="Send" class="submit" value="Send Message" />&nbsp;&nbsp;
			<input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='index.php'" />
		  </td>
          </tr>
        </table>
      </form>

		<?php } ?>

      <?php }else{ ?>
				<div class="info_box">There are no registered members at this time.</div>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>