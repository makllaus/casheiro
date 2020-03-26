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


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$uid = (int)$_GET['id'];

		$query = "SELECT *, DATE_FORMAT(created, '%e %b %Y') AS date_created FROM cashbackengine_transactions WHERE user_id='$uid' ORDER BY transaction_id DESC";
		$result = smart_mysql_query($query);
		$total = mysql_num_rows($result);
	}  

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Payments History</title>
<link href="css/cashbackengine.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="background: #FFFFFF">
<table width="100%" bgcolor="#FFFFFF" align="center" border="0" cellpadding="3" cellspacing="0">
<tr>
 <td colspan=valign="top" align="left">

		<h2>Payments History</h2>

<?php

		if ($total > 0) {
 
?>
            <table align="center" width="98%" border="0" cellspacing="0" cellpadding="3">
              <tr>
				<th width="20%">Reference ID</th>
                <th width="22%">Payment Type</th>
                <th width="20%">Status</th>
                <th width="15%">Date</th>
				<th width="15%">Amount</th>
              </tr>
				<?php
					while ($row = mysql_fetch_array($result)) { $cc++;
				?>
                <tr bgcolor="<?php if (($cc%2) == 0) echo "#F1F0FF"; else echo "#FFFFFF"; ?>">
                  <td valign="middle" align="center"><?php echo $row['reference_id']; ?></td>
                  <td valign="middle" align="center"><?php echo $row['payment_type']; ?></td>
                  <td valign="middle" align="center">
					<?php
							switch ($row['status'])
							{
								case "confirmed": echo "<span class='confirmed_status'>confirmed</span>"; break;
								case "pending": echo "<span class='pending_status'>pending</span>"; break;
								case "declined": echo "<span class='declined_status'>declined</span>"; break;
								case "failed": echo "<span class='failed_status'>failed</span>"; break;
								case "request": echo "<span class='request_status'>awaiting approval</span>"; break;
								case "paid": echo "<span class='paid_status'>paid</span>"; break;
								default: echo "<span class='payment_status'>".$row['status']."</span>"; break;
							}

							if ($row['status'] == "declined" && $row['reason'] != "")
								echo " <a href=\"#\" onmouseover=\"tooltip('".$row['reason']."<br/><span class=\'tip\'></span>');\" onmouseout=\"exit();\"><img src=\"/images/info.png\" alt=\"\" align=\"absmiddle\" border=\"0\" /></a>";
					?>
				  </td>
                  <td valign="middle" align="center"><?php echo $row['date_created']; ?></td>
                  <td valign="middle" align="center"><?php echo DisplayMoney($row['amount']); ?></td>
                </tr>
				<?php } ?>

			  </tr>
           </table>
	  
	  <?php }else{ ?>
			<div class="info_box">There are no transactions at this time.</div>
      <?php } ?>

	<hr size="1" color="#EEEEEE">
	<div align="right"><a onclick="window.close(); return false;" href="#"> x <b>Close this window</b></a></div>

 </td>
</tr>
</table>
</body>
</html>