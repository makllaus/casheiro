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


if (isset($_POST['action']) && $_POST['action'] == "addnetwork")
{
	unset($errs);
	$errs = array();

	$network_name	= mysql_real_escape_string(getPostParameter('network_name'));
	$csv_format		= mysql_real_escape_string($_POST['csv_format']);
	$confirmeds		= mysql_real_escape_string(str_replace("\r\n", "|", getPostParameter('confirmeds')));
	$pendings		= mysql_real_escape_string(str_replace("\r\n", "|", getPostParameter('pendings')));
	$declineds		= mysql_real_escape_string(str_replace("\r\n", "|", getPostParameter('declineds')));

	if(!($network_name && $csv_format && $confirmeds && $pendings))
	{
		$errs[] = "Please fill in all fields";
	}
	else
	{
		$check_query = smart_mysql_query("SELECT * FROM cashbackengine_affnetworks WHERE network_name='$network_name'");
		if (mysql_num_rows($check_query) != 0)
		{
			$errs[] = "Sorry, affiliate network exists";
		}
	}

	if (count($errs) == 0)
	{
		$sql = "INSERT INTO cashbackengine_affnetworks SET network_name='$network_name', csv_format='$csv_format', confirmeds='$confirmeds', pendings='$pendings', declineds='$declineds', status='active', added=NOW()";

		if (smart_mysql_query($sql))
		{
			header("Location: affnetworks.php?msg=added");
			exit();
		}
	}
	else
	{
		$errormsg = "";
		foreach ($errs as $errorname)
			$errormsg .= "&#155; ".$errorname."<br/>";
	}

}

	$title = "Add New Affiliate Network";
	require_once ("inc/header.inc.php");

?>
 
        <h2>Add New Affiliate Network</h2>

		<?php if (isset($errormsg) && $errormsg != "") { ?>
			<div class="error_box"><?php echo $errormsg; ?></div>
		<?php } ?>

        <form action="" method="post">
          <table width="100%" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Network Name:</td>
            <td valign="top"><input type="text" name="network_name" id="network_name" value="<?php echo getPostParameter('network_name'); ?>" size="35" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" colspan="2" valign="top" align="center">
				<p>Please enter one record from your CSV-report in the field below. And replace needed values with our required variables.</p>
				<p>
					<table width="60%" align="center" cellpadding="2" cellspacing="5" border="0">
						<tr valign="middle"><td align="right"><b>{TRANSACTIONID}</b></td><td align="left">- Transaction ID (Order ID)</td></tr>
						<tr valign="middle"><td align="right"><b>{PROGRAMID}</b></td><td align="left">- Program ID from your affiliate network</td></tr>
						<tr valign="middle"><td align="right"><b>{USERID}</b></td><td align="left">- SubID (SID)</td></tr>
						<tr valign="middle"><td align="right"><b>{AMOUNT}</b></td><td align="left">- Sale Amount</td></tr>
						<tr valign="middle"><td align="right"><b>{COMMISSION}</b></td><td align="left">- Commission Amount</td></tr>
						<tr valign="middle"><td align="right"><b>{STATUS}</b></td><td align="left">- Transaction Status</td></tr>
					</table>
				</p>
				<p>
					<table width="100%" align="center" cellpadding="2" cellspacing="2" border="0">
						<tr valign="middle"><td align="left"><font color="#FF9213"><b><u>EXAMPLE</u></b></font></td></tr>
						<tr valign="middle"><td align="left"><b>row from your CSV-report</b>:</td></tr>
						<tr valign="middle"><td bgcolor="#EFDFFB" align="left">"4th May 2010","Sale","945643431","sim_sale","closed","No","40.95","12.28","3201921","UNIBET","296"</td></tr>
						<tr valign="middle"><td align="left"><b>must be replaced to</b>:</td></tr>
						<tr valign="middle"><td bgcolor="#E3FBD8" align="left">"4th May 2010","Sale","<span style="color: #E72085;">{TRANSACTIONID}</span>","sim_sale","<span style="color: #E72085;">{STATUS}</span>","No","<span style="color: #E72085;">{AMOUNT}</span>","<span style="color: #E72085;">{COMMISSION}</span>","<span style="color: #E72085;">{PROGRAMID}</span>","UNIBET","<span style="color: #E72085;">{USERID}</span>"</td></tr>
					</table>				
				</p>
			</td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">CSV Format:</td>
            <td valign="top"><textarea wrap="off" cols="90" rows="1" style="padding-top:5px;" name="csv_format" class="textbox2"><?php echo getPostParameter('csv_format'); ?></textarea></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Transaction Statuses:<br/><span class="help">(one status per row)</span></td>
            <td align="center" valign="top">
				<table bgcolor="#F7F7F7" style="border: 1px solid #DDDDDD" width="100%" align="left" cellpadding="2" cellspacing="5" border="0">
					<tr>
						<td align="center" valign="top">
							<font color="#07D706"><b>Confirmed</b></font><br/><br/>
							<textarea wrap="off" cols="20" rows="2" style="padding-top:5px;" name="confirmeds" class="textbox2"><?php echo getPostParameter('confirmeds'); ?></textarea>
						<td>
						<td align="center" valign="top">
							<font color="#F37007"><b>Pending</b></font><br/><br/>
							<textarea wrap="off" cols="20" rows="2" style="padding-top:5px;" name="pendings" class="textbox2"><?php echo getPostParameter('pendings'); ?></textarea>
						<td>
						<td align="center" valign="top">
							<font color="#FF000A"><b>Declined</b></font><br/><br/>
							<textarea wrap="off" cols="20" rows="2" style="padding-top:5px;" name="declineds" class="textbox2"><?php echo getPostParameter('declineds'); ?></textarea>
						<td>
					</tr>
				</table>
			</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="middle">
				<input type="hidden" name="action"id="action" value="addnetwork" />
				<input type="submit" name="add" id="add" class="submit" value="Add Affiliate Network" />&nbsp;&nbsp;
				<input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='affnetworks.php'" />
		  </td>
          </tr>
        </table>
      </form>


<?php require_once ("inc/footer.inc.php"); ?>