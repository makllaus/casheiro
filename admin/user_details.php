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
	require_once("./inc/admin_funcs.inc.php");


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$uid	= (int)$_GET['id'];
		$pn		= (int)$_GET['pn'];

		if (isset($_GET['action']) && $_GET['action'] == "block") BlockUnblockUser($uid);
		if (isset($_GET['action']) && $_GET['action'] == "unblock") BlockUnblockUser($uid,1);

		$query = "SELECT *, DATE_FORMAT(created, '%e %b %Y %h:%i %p') AS created, DATE_FORMAT(last_login, '%e %b %Y %h:%i %p') AS last_login FROM cashbackengine_users WHERE user_id='$uid'";
		$result = smart_mysql_query($query);
		$row = mysql_fetch_array($result);
		$total = mysql_num_rows($result);
	}


	$title = "User Details";
	require_once ("inc/header.inc.php");

?>   
    
      <?php if ($total > 0) { ?>

          <h2>User Details</h2>

		  <img src="images/user.png" class="imgs" align="right" />

          <table align="center" cellpadding="3" cellspacing="5" border="0">
          <tr>
           <td width="150" valign="middle" align="right" class="tb1">User ID:</td>
           <td align="left" valign="middle"><?php echo $row['user_id']; ?></td>
          </tr>
           <tr>
            <td valign="middle" align="right" class="tb1">Username:</td>
            <td align="left" valign="middle"><?php echo $row['username']; ?></td>
          </tr>
           <tr>
            <td valign="middle" align="right" class="tb1">First Name:</td>
            <td align="left" valign="middle"><?php echo $row['fname']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Last Name:</td>
            <td align="left" valign="middle"><?php echo $row['lname']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Email:</td>
            <td align="left" valign="middle"><a href="mailto:<?php echo $row['email']; ?>"><?php echo $row['email']; ?></a></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Country:</td>
            <td align="left" valign="middle"><?php echo $row['country']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Balance:</td>
            <td align="left" valign="middle">
				<span class="amount"><?php echo GetUserBalance($row['user_id']); ?></span>
				<?php if (GetUserBalance($row['user_id'], 1) > 0) { ?>
					&nbsp; <a href='javascript:openWindow("user_payments.php?id=<?php echo $row['user_id']; ?>",600,450)'>View payments history &#155;</a>
				<?php } ?>
			</td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Reviews:</td>
            <td align="left" valign="middle"><a href="reviews.php?user=<?php echo $row['user_id']; ?>"><?php echo GetUserReviewsTotal($row['user_id']); ?></a>
			</td>
          </tr>
		  <?php if ($row['ref_id'] > 0) { ?>
          <tr>
            <td valign="middle" align="right" class="tb1">Referred by:</td>
            <td align="left" valign="middle"><a href='javascript:openWindow("user_details_window.php?id=<?php echo $row['ref_id']; ?>",300,300)'><?php echo GetUsername($row['ref_id']); ?></a></td>
          </tr>
		  <?php } ?>
          <tr>
            <td valign="middle" align="right" class="tb1">Referrals:</td>
            <td align="left" valign="middle"><a href='javascript:openWindow("user_referrals.php?id=<?php echo $row['ref_id']; ?>",600,450)'><?php echo GetReferralsTotal($row['user_id']); ?></a></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Newsletter:</td>
            <td align="left" valign="middle"><?php echo ($row['newsletter'] == 1) ? "<img src='./images/icons/yes.png' align='absmiddle'>" : "<img src='./images/icons/no.png' align='absmiddle'>"; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Signup Date:</td>
            <td align="left" valign="middle"><?php echo $row['created']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">IP Address:</td>
            <td align="left" valign="middle"><?php echo $row['ip']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Login Count:</td>
            <td align="left" valign="middle"><?php echo $row['login_count']; ?></td>
          </tr>
		  <?php if ($row['login_count'] > 0) { ?>
          <tr>
            <td valign="middle" align="right" class="tb1">Last Login:</td>
            <td align="left" valign="middle"><?php echo $row['last_login']; ?></td>
          </tr>
          <tr>
            <td valign="middle" align="right" class="tb1">Last Login IP address:</td>
            <td align="left" valign="middle"><?php echo $row['last_ip']; ?></td>
          </tr>
		  <?php } ?>
          <tr>
            <td valign="middle" align="right" class="tb1">Status:</td>
            <td align="left" valign="middle">
				<?php if ($row['status'] == "inactive") echo "<span class='inactive_s'>".$row['status']."</span>"; else echo "<span class='active_s'>".$row['status']."</span>"; ?>
			</td>
          </tr>
		  <?php if ($row['status'] == "active") { ?>
          <tr>
            <td height="50" style="border-top: 1px solid #eeeeee; border-bottom: 1px solid #eeeeee;" colspan="2" align="center" valign="middle">
				<p><a class="blockit" href="user_details.php?id=<?php echo $row['user_id']; ?>&pn=<?php echo $pn; ?>&action=block">Block User</a></p>
			</td>
          </tr>
		  <?php }else{ ?>
          <tr>
            <td height="50" style="border-top: 1px solid #eeeeee; border-bottom: 1px solid #eeeeee;" colspan="2" align="center" valign="middle">
				<p><a class="unblockit" href="user_details.php?id=<?php echo $row['user_id']; ?>&pn=<?php echo $pn; ?>&action=unblock">UnBlock User</a></p>
			</td>
          </tr>
		  <?php } ?>
          <tr>
            <td colspan="2" align="center" valign="bottom">
				<input type="button" class="submit" name="edit" value="Edit User" onClick="javascript:document.location.href='user_edit.php?id=<?php echo $row['user_id']; ?>&page=<?php echo $pn; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>'" /> &nbsp; 
				<input type="button" class="cancel" name="cancel" value="Go Back" onClick="javascript:document.location.href='users.php?page=<?php echo $pn; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>'" />
		    </td>
          </tr>
          </table>
      
	  <?php }else{ ?>
			<p align="center">Sorry, no user found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>