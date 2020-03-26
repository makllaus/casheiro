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
	require_once("../inc/pagination.inc.php");
	require_once("./inc/admin_funcs.inc.php");


	$results_per_page = 10;


		// Delete payments //
		if (isset($_POST['action']) && $_POST['action'] == "delete")
		{
			$ids_arr	= array();
			$ids_arr	= $_POST['id_arr'];

			if (count($ids_arr) > 0)
			{
				foreach ($ids_arr as $v)
				{
					$pid = (int)$v;
					DeletePayment($pid);
				}

				header("Location: cashout_requests.php?msg=deleted");
				exit();
			}
		}



	////////////////// filter  //////////////////////
			if (isset($_GET['column']) && $_GET['column'] != "")
			{
				switch ($_GET['column'])
				{
					case "username": $rrorder = "u.username"; break;
					case "amount": $rrorder = "t.amount"; break;
					case "ids": $rrorder = "t.transaction_id"; break;
					case "payment_method": $rrorder = "t.payment_method"; break;
					default: $rrorder = "t.transaction_id"; break;
				}
			}
			else
			{
				$rrorder = "t.transaction_id";
			}

			if (isset($_GET['order']) && $_GET['order'] != "")
			{
				switch ($_GET['order'])
				{
					case "asc": $rorder = "asc"; break;
					case "desc": $rorder = "desc"; break;
					default: $rorder = "asc"; break;
				}
			}
			else
			{
				$rorder = "desc";
			}
	///////////////////////////////////////////////////////

		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
		$from = ($page-1)*$results_per_page;

		$query = "SELECT t.*, DATE_FORMAT(t.created, '%e %b %Y') AS date_created, u.username, u.email FROM cashbackengine_transactions t, cashbackengine_users u WHERE t.status='request' AND t.user_id=u.user_id ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
		$result = smart_mysql_query($query);
		$total_on_page = mysql_num_rows($result);

		$query2 = "SELECT * FROM cashbackengine_transactions WHERE status='request'";
		$result2 = smart_mysql_query($query2);
        $total = mysql_num_rows($result2);

		$cc = 0;


	$title = "Cash Out Requests";
	require_once ("inc/header.inc.php");

?>

       <h2>Cash Out Requests</h2>

       <?php if ($total > 0) { ?>

		
			<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div class="success_box">
				<?php

					switch ($_GET['msg'])
					{
						case "updated": echo "Payment has been successfully edited!"; break;
						case "deleted": echo "Payment has been successfully deleted!"; break;
					}

				?>
			</div>
			<?php } ?>

		<table align="center" width="98%" border="0" cellpadding="3" cellspacing="0">
		<tr>
		<td valign="top" align="left" width="50%">
            <form id="form1" name="form1" method="get" action="">
           Sort by: 
          <select name="column" id="column" onChange="document.form1.submit()">
			<option value="ids" <?php if ($_GET['column'] == "ids") echo "selected"; ?>>Date</option>
			<option value="username" <?php if ($_GET['column'] == "username") echo "selected"; ?>>Username</option>
			<option value="amount" <?php if ($_GET['column'] == "amount") echo "selected"; ?>>Amount</option>
			<option value="payment_method" <?php if ($_GET['column'] == "payment_method") echo "selected"; ?>>Payment Method</option>
          </select>
          <select name="order" id="order" onChange="document.form1.submit()">
			<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Descending</option>
			<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Ascending</option>
          </select>
            </form>
			</td>
			<td valign="top" width="45%" align="right">
			   Showing <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> of <?php echo $total; ?>
			</td>
			</tr>
			</table>
			</form>

			<form id="form2" name="form2" method="post" action="">
            <table align="center" width="98%" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<th width="3%"><input type="checkbox" name="selectAll" onclick="checkAll();" class="checkbox" /></th>
				<th width="20%">Username</th>
				<th width="10%">Reference ID</th>
				<th width="7%">Amount</th>
				<th width="13%">Payment Method</th>
				<th width="10%">Request Date</th>
				<th width="10%">Action</th>
				<th width="5%">Actions</th>
			</tr>
             <?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
				  <tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td align="center" valign="middle"><input type="checkbox" class="checkbox" name="id_arr[<?php echo $row['transaction_id']; ?>]" id="id_arr[<?php echo $row['transaction_id']; ?>]" value="<?php echo $row['transaction_id']; ?>" /></td>
					<td nowrap="nowrap" align="left" valign="middle"><a href="user_details.php?id=<?php echo $row['user_id']; ?>" class="user"><?php echo $row['username']; ?></a></td>
					<td nowrap="nowrap" align="center" valign="middle"><?php echo $row['reference_id']; ?></td>
					<td align="center" valign="middle"><?php echo DisplayMoney($row['amount']); ?></td>
					<td align="center" valign="middle">
						<?php if ($row['payment_method'] == "paypal") { ?><img src="images/paypal.gif" align="absmiddle" />&nbsp;<?php } ?>
						<?php echo GetPaymentMethodByID($row['payment_method']); ?>
					</td>
					<td align="center" valign="middle"><?php echo $row['date_created']; ?></td>
					<td align="center" valign="middle">
						<?php if ($row['status'] == "request") { ?>
							<img src="images/process.gif" align="absmiddle" /> <a style="color: #78D710;" href="payment_process.php?id=<?php echo $row['transaction_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>"><u>Process &#155;</u></a>
						<?php } ?>
					</td>
					<td align="center" valign="middle">
						<a href="payment_details.php?id=<?php echo $row['transaction_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>" title="View"><img src="images/view.gif" border="0" alt="View" /></a>
						<a href="#" onclick="if (confirm('Are You sure You realy want to delete this payment?') )location.href='payment_delete.php?id=<?php echo $row['transaction_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>';" title="Delete"><img src="images/delete.gif" border="0" alt="Delete" /></a>
					</td>
				  </tr>
			<?php } ?>

				<tr>
					<td colspan="8" align="left">
						<input type="hidden" name="action" value="delete" />
						<input type="submit" class="submit" name="GoDelete" id="GoDelete" value="Delete Selected" />
					</td>
				</tr>
				  <tr>
					<td align="center" valign="top" colspan="8">
						<?php echo ShowPagination("transactions",$results_per_page,"cashout_requests.php?column=$rrorder&order=$rorder&","WHERE status='request'"); ?>
					</td>
				  </tr>
            </table>
			</form>

		</table>

          <?php }else{ ?>
				<div class="info_box">There are no payment requests at this time.</div>
          <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>