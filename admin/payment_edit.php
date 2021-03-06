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


if (isset($_POST["action"]) && $_POST["action"] == "edit_payment")
{
	unset($errors);
	$errors = array();

	$transaction_id	= (int)getPostParameter('tid');
	$amount			= mysql_real_escape_string(getPostParameter('amount'));
	$status			= mysql_real_escape_string(getPostParameter('status'));

	if (!($status && is_numeric($amount) && $amount > 0))
	{
		$errors[] = "Please select payment status and enter correct amount";
	}
	else
	{
		switch ($status)
		{
			case "confirmed":	$status="confirmed"; break;
			case "pending":		$status="pending"; break;
			case "declined":	$status="declined"; break;
			default:			$status="unknown"; break;
		}
	}

	if (count($errors) == 0)
	{
		$sql = "UPDATE cashbackengine_transactions SET amount='$amount', status='$status', updated=NOW() WHERE transaction_id='$transaction_id'";
		$result = smart_mysql_query($sql);

		header("Location: payments.php?msg=updated");
		exit();
	}
	else
	{
		$errormsg = "";
		foreach ($errors as $errorname)
			$errormsg .= "&#155; ".$errorname."<br/>";
	}
}

	if (isset($_GET['id']) && is_numeric($_GET['id'])) { $id = (int)$_GET['id']; } elseif (isset($_POST['tid']) && is_numeric($_POST['tid'])) { $id = (int)$_POST['tid']; }

	if (isset($id) && is_integer($id))
	{
		$query = "SELECT t.*, DATE_FORMAT(t.created, '%e %b %Y %h:%i %p') AS payment_date, u.fname, u.lname FROM cashbackengine_transactions t, cashbackengine_users u WHERE t.user_id=u.user_id AND t.transaction_id='$id'";
		$result = smart_mysql_query($query);
		$total = mysql_num_rows($result);
	}


	$title = "Edit Payment";
	require_once ("inc/header.inc.php");

?>
    
    
     <h2>Edit Payment</h2>

		<?php if ($total > 0) { 

				$row = mysql_fetch_array($result);

		 ?>

		<?php if (isset($errormsg)) { ?>
			<div style="width:450px;" class="error_box"><?php echo $errormsg; ?></div>
		<?php } ?>

			<form action="" method="post" name="form1">
            <table width="450" bgcolor="#F9F9F9" style="border-radius: 7px;" cellpadding="2" cellspacing="5" border="0" align="center">
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
                <td valign="middle" align="right" class="tb1">Payment type:</td>
                <td valign="top"><?php echo $row['payment_type']; ?></td>
              </tr>
			  <?php if ($row['payment_type'] == "Withdrawal" && $row['payment_method'] != "") { ?>
              <tr>
                <td valign="middle" align="right" class="tb1">Payment method:</td>
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
                <td valign="top"><?php SITE_CURRENCY; ?><input type="text" class="textbox" name="amount" value="<?php echo DisplayMoney($row['amount'], 1); ?>" size="6" /></td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Created:</td>
                <td valign="top"><?php echo $row['payment_date']; ?></td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Status:</td>
                <td valign="top">
					<?php
						switch ($row['status'])
						{
							case "confirmed": echo "<span style='margin:0;' class='confirmed_status'>confirmed</span>"; break;
							case "pending": echo "<span style='margin:0;' class='pending_status'>pending</span>"; break;
							case "declined": echo "<span style='margin:0;' class='declined_status'>declined</span>"; break;
							case "failed": echo "<span style='margin:0;' class='failed_status'>declined</span>"; break;
							case "request": echo "<span style='margin:0;' class='request_status'>awaiting approval</span>"; break;
							case "paid": echo "<span style='margin:0;' class='paid_status'>paid</span>"; break;
							default: echo "<span style='margin:0;' class='payment_status'>".$row['status']."</span>"; break;
						}
					?>
				</td>
              </tr>
              <tr>
                <td colspan="2" style="border-bottom: 2px solid #EEE;">&nbsp;</td>
              </tr>
              <tr>
                <td valign="middle" align="right" class="tb1">Change Status to:</td>
                <td valign="top">
					<select name="status" id="status">
						<option value="confirmed">confirmed</option>
						<option value="pending">pending</option>
						<option value="declined">declined</option>
					</select>
				</td>
              </tr>
            <tr>
              <td align="center" colspan="2" valign="bottom">
				<input type="hidden" name="tid" id="tid" value="<?php echo (int)$row['transaction_id']; ?>" />
				<input type="hidden" name="action" id="action" value="edit_payment">
				<input type="submit" class="submit" name="process" value="Update" />
				<input type="button" class="submit" name="cancel" value="Go Back" onclick="history.go(-1);return false;" />
			  </td>
            </tr>
          </table>
		  </form>
      
	  <?php }else{ ?>
			<p align="center">Sorry, no payment found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>