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

		$query = "SELECT *, DATE_FORMAT(created, '%e %b %Y %h:%i %p') AS created FROM cashbackengine_users WHERE user_id='$uid'";
		$result = smart_mysql_query($query);
		$total = mysql_num_rows($result);
	}  

?>

	<?php if ($total > 0) { $row = mysql_fetch_array($result); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $row['fname']." ".$row['lname']; ?> | User Details</title>
<link href="css/cashbackengine.css" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body style="background: #FFFFFF">
<table width="100%" bgcolor="#FFFFFF" align="center" border="0" cellpadding="3" cellspacing="0">
<tr>
 <td colspan=valign="top" align="left">

		<h2>User Details</h2>
		 <table width="98%" align="center" cellpadding="4" cellspacing="3" border="0">
            <tr>
              <td nowrap="nowrap" valign="middle" align="right" class="tb1">User ID:</td>
              <td align="left" valign="middle"><?php echo $row['user_id']; ?></td>
            </tr>
           <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Username:</td>
            <td align="left" valign="middle"><?php echo $row['username']; ?></td>
          </tr>
           <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">First Name:</td>
            <td align="left" valign="middle"><?php echo $row['fname']; ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Last Name:</td>
            <td align="left" valign="middle"><?php echo $row['lname']; ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Email:</td>
            <td align="left" valign="middle"><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Country:</td>
            <td align="left" valign="middle"><?php echo $row['country']; ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Balance:</td>
            <td align="left" valign="middle"><span class="amount"><?php echo GetUserBalance($row['user_id']); ?></span></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Newsletter:</td>
            <td align="left" valign="middle"><?php echo ($row['newsletter'] == 1) ? "<img src='./images/icons/yes.png' align='absmiddle'>" : "<img src='./images/icons/no.png' align='absmiddle'>"; ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Signup Date:</td>
            <td align="left" valign="middle"><?php echo $row['created']; ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Status:</td>
            <td align="left" valign="middle">
				<?php if ($row['status'] == "inactive") echo "<span class='inactive_s'>".$row['status']."</span>"; else echo "<span class='active_s'>".$row['status']."</span>"; ?>
			</td>
          </tr>
          </table>
	  
	  <?php }else{ ?>
			<p align="center">Sorry, no user found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

	<hr size="1" color="#EEEEEE">
	<div align="right"><a onclick="window.close(); return false;" href="#"> x <b>Close this window</b></a>&nbsp;</div>

 </td>
</tr>
</table>
</body>
</html>