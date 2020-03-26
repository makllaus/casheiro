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


if (isset($_POST["action"]) && $_POST["action"] == "process_payment")
{
	unset($errors);
	$errors = array();

	$transaction_id	= (int)getPostParameter('tid');
	$user_id		= (int)getPostParameter('uid');
	$status			= mysql_real_escape_string(getPostParameter('status'));
	$reason			= mysql_real_escape_string(nl2br(getPostParameter('reason')));

	if (!($status))
	{
		$errors[] = "Please select payment status";
	}

	if (count($errors) == 0)
	{
		$uresult = smart_mysql_query("SELECT * FROM cashbackengine_users WHERE user_id='$user_id'");
		$utotal = mysql_num_rows($uresult);

		// Confirm Refer a Friend Bonus //
		if ($status == "paid" && $utotal > 0)
		{
			$urow = mysql_fetch_array($uresult);
			$ref_res = smart_mysql_query("UPDATE cashbackengine_transactions SET status='confirmed', process_date=NOW() WHERE user_id='".(int)$urow['ref_id']."' AND payment_type='Refer a Friend Bonus' AND status='pending'");
		}
		///////////////////////////////

		$sql = "UPDATE cashbackengine_transactions SET status='$status', reason='$reason', process_date=NOW() WHERE transaction_id='$transaction_id'";
		$result = smart_mysql_query($sql);

		header("Location: payments.php?msg=processed");
		exit();
	}
	else
	{
		$errormsg = "";
		foreach ($errors as $errorname)
			$errormsg .= "&#155; ".$errorname."<br/>";
	}
}


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$id = (int)$_GET['id'];

		$query = "SELECT t.*, DATE_FORMAT(t.created, '%e %b %Y %h:%i %p') AS payment_date, u.fname, u.lname FROM cashbackengine_transactions t, cashbackengine_users u WHERE t.user_id=u.user_id AND t.transaction_id='$id' AND t.status<>'confirmed'";
		$result = smart_mysql_query($query);
		$total = mysql_num_rows($result);
	}


	$title = "Process Payment";
	require_once ("inc/header.inc.php");

?>
    
    
     <h2>Process Payment</h2>

		<?php if ($total > 0) { 

				$row = mysql_fetch_array($result);

		 ?>

		<script>
		<!--
			function hiddenDiv(id,showid){
				if(document.getElementById(id).value == "declined"){
					document.getElementById(showid).style.display = ""
				}else{
					document.getElementById(showid).style.display = "none"
				}
			}
		-->
		</script>


		<?php if (isset($errormsg)) { ?>
			<div style="width:70%;" class="error_box"><?php echo $errormsg; ?></div>
		<?php } ?>

			<form action="" method="post" name="form1">
            <table bgcolor="#F9F9F9" style="border-radius: 7px;" width="70%" cellpadding="2" cellspacing="5" border="0" align="center">
              <tr>
                <td width="200" valign="middle" align="right" class="tb1">Payment ID:</td>
                <td valign="top"><?php echo $row['transaction_id']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" valign="middle" align="right" class="tb1">Reference ID:</td>
                <td valign="top"><?php echo $row['reference_id']; ?></td>
              </tr>
              <tr>
                <td nowrap="nowrap" valign="middle" align="right" class="tb1">Member Name:</td>
                <td valign="top"><a href='javascript:openWindow("user_details_window.php?id=<?php echo $row['user_id']; ?>",300,300)'><?php echo $row['fname']." ".$row['lname']; ?></a></td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Payment Type:</td>
                <td valign="top"><?php echo $row['payment_type']; ?></td>
              </tr>
			  <?php if ($row['payment_type'] == "Withdrawal" && $row['payment_method'] != "") { ?>
              <tr>
                <td valign="middle" align="right" class="tb1">Payment Method:</td>
                <td valign="top">
					<?php if ($row['payment_method'] == "paypal") { ?><img src="images/paypal.gif" align="absmiddle" />&nbsp;<?php } ?>
					<?php echo GetPaymentMethodByID($row['payment_method']); ?>
				</td>
              </tr>
			  <?php } ?>
			  <?php if ($row['payment_details'] != "") { ?>
              <tr>
                <td valign="middle" align="right" class="tb1">Payment Details:</td>
                <td valign="top"><?php echo $row['payment_details']; ?></td>
              </tr>
			  <?php } ?>
              <tr>
                <td valign="middle" align="right" class="tb1">Amount:</td>
                <td valign="top"><span class="amount"><?php echo DisplayMoney($row['amount']); ?></span></td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Status:</td>
                <td valign="top">
					<?php
						switch ($row['status'])
						{
							case "confirmed": echo "<span class='confirmed_status'>confirmed</span>"; break;
							case "pending": echo "<span class='pending_status'>pending</span>"; break;
							case "declined": echo "<span class='declined_status'>declined</span>"; break;
							case "failed": echo "<span class='failed_status'>failed</span>"; break;
							case "request": echo "<span class='request_status'>awaiting approval</span>"; break;
							default: echo "<span class='payment_status'>".$row['status']."</span>"; break;
						}
					?>
				</td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Created:</td>
                <td valign="top"><?php echo $row['payment_date']; ?></td>
              </tr>
              <tr>
                <td colspan="2" style="border-bottom: 2px solid #EEE;">&nbsp;</td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Mark as:</td>
                <td valign="top">
					<select name="status" id="status" onchange="javascript:hiddenDiv('status','reason')">
							<option value="paid">Paid</option>
							<option value="declined">Declined</option>
					</select>
				</td>
              </tr>
              <tr id="reason" style="display: none;">
                <td valign="middle" align="right" class="tb1">Reason:</td>
                <td valign="top"><textarea cols="35" rows="4" name="reason" class="textbox2"><?php echo getPostParameter('reason'); ?></textarea></td>
              </tr>
            <tr>
              <td align="center" colspan="2" valign="bottom">
				<input type="hidden" name="tid" id="tid" value="<?php echo (int)$row['transaction_id']; ?>" />
				<input type="hidden" name="uid" id="uid" value="<?php echo (int)$row['user_id']; ?>" />
				<input type="hidden" name="action" id="action" value="process_payment">
				<input type="submit" class="submit" name="process" value="Process Payment" />
				<input type="button" class="submit" name="cancel" value="Go Back" onclick="history.go(-1);return false;" />
			  </td>
            </tr>
          </table>
		  </form>
      
	  <?php }else{ ?>
			<p align="center">Sorry, no payment found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>