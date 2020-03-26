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


	// results per page
	if (isset($_GET['show']) && is_numeric($_GET['show']) && $_GET['show'] > 0)
		$results_per_page = (int)$_GET['show'];
	else
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

				header("Location: payments.php?msg=deleted");
				exit();
			}
		}

		////////////////// filter  //////////////////////
			if (isset($_GET['column']) && $_GET['column'] != "")
			{
				switch ($_GET['column'])
				{
					case "username": $rrorder = "u.username"; break;
					case "ptype": $rrorder = "t.payment_type"; break;
					case "amount": $rrorder = "t.amount"; break;
					case "status": $rrorder = "t.status"; break;
					case "ids": $rrorder = "t.transaction_id"; break;
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

			if (isset($_GET['filter']) && $_GET['filter'] != "")
			{
				$filter	= mysql_real_escape_string(trim(getGetParameter('filter')));
				$filter_by = " AND (reference_id='$filter' OR payment_type LIKE '%$filter%')";
			}
		///////////////////////////////////////////////////////

		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
		$from = ($page-1)*$results_per_page;

		$query = "SELECT t.*, DATE_FORMAT(t.created, '%e %b %Y') AS payment_date, u.username, u.email FROM cashbackengine_transactions t, cashbackengine_users u WHERE t.user_id=u.user_id $filter_by ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
		$result = smart_mysql_query($query);
		$total_on_page = mysql_num_rows($result);

		$query2 = "SELECT * FROM cashbackengine_transactions WHERE 1=1".$filter_by;
		$result2 = smart_mysql_query($query2);
        $total = mysql_num_rows($result2);

		$cc = 0;


		$title = "Payments";
		require_once ("inc/header.inc.php");

?>

       <h2>Payments</h2>

       <?php if ($total > 0) { ?>


			<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div class="success_box">
				<?php

					switch ($_GET['msg'])
					{
						case "processed": echo "Payment has been successfully processed!"; break;
						case "updated": echo "Payment has been successfully updated!"; break;
						case "deleted": echo "Payment has been successfully deleted!"; break;
					}

				?>
			</div>
			<?php } ?>


		<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
		<td nowrap="nowrap" width="47%" valign="middle" align="left">
            <form id="form1" name="form1" method="get" action="">
           Sort by: 
          <select name="column" id="column" onChange="document.form1.submit()">
			<option value="username" <?php if ($_GET['column'] == "username") echo "selected"; ?>>Username</option>
			<option value="ptype" <?php if ($_GET['column'] == "ptype") echo "selected"; ?>>Payment Type</option>
			<option value="amount" <?php if ($_GET['column'] == "amount") echo "selected"; ?>>Amount</option>
			<option value="ids" <?php if ($_GET['column'] == "ids") echo "selected"; ?>>Date</option>
			<option value="status" <?php if ($_GET['column'] == "status") echo "selected"; ?>>Status</option>
          </select>
          <select name="order" id="order" onChange="document.form1.submit()">
			<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Descending</option>
			<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Ascending</option>
          </select>
		  &nbsp;&nbsp;View: 
          <select name="show" id="order" onChange="document.form1.submit()">
			<option value="10" <?php if ($_GET['show'] == "10") echo "selected"; ?>>10</option>
			<option value="50" <?php if ($_GET['show'] == "50") echo "selected"; ?>>50</option>
			<option value="100" <?php if ($_GET['show'] == "100") echo "selected"; ?>>100</option>
          </select>
			</td>
			<td nowrap="nowrap" width="30%" valign="middle" align="left">
				<div class="admin_filter">
					Filter: <input type="text" name="filter" value="<?php echo $filter; ?>" class="textbox" size="30" title="Reference ID or Payment Type" /> <input type="submit" class="submit" value="Search" />
					<?php if (isset($filter) && $filter != "") { ?><a title="Cancel Search" href="payments.php"><img align="absmiddle" src="images/icons/delete_filter.png" border="0" alt="Cancel Search" /></a><?php } ?> 
				</div>
			</td>
			<td nowrap="nowrap" width="35%" valign="middle" align="right">
			   Showing <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> of <?php echo $total; ?>
			</td>
			</tr>
			</table>
			</form>

			<form id="form2" name="form2" method="post" action="">
            <table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<th width="3%"><input type="checkbox" name="selectAll" onclick="checkAll();" class="checkbox" /></th>
				<th width="20%">Username</th>
				<th width="12%">Reference ID</th>
				<th width="15%">Payment Type</th>
				<th width="8%">Amount</th>
				<th width="9%">Date</th>
				<th width="12%">Status</th>
				<th width="7%">Actions</th>
			</tr>
             <?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
				  <tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td align="center" valign="middle"><input type="checkbox" class="checkbox" name="id_arr[<?php echo $row['transaction_id']; ?>]" id="id_arr[<?php echo $row['transaction_id']; ?>]" value="<?php echo $row['transaction_id']; ?>" /></td>
					<td nowrap="nowrap" align="left" valign="middle"><a href="user_details.php?id=<?php echo $row['user_id']; ?>" class="user"><?php echo $row['username']; ?></a></td>
					<td nowrap="nowrap" align="center" valign="middle"><?php echo $row['reference_id']; ?></td>
					<td nowrap="nowrap" align="center" valign="middle"><?php echo $row['payment_type']; ?></td>
					<td nowrap="nowrap" align="center" valign="middle">
						<?php if (strstr($row['amount'], "-")) $pcolor = "#DD0000"; else $pcolor = "#000000"; ?>
						<span style="color: <?php echo $pcolor; ?>"><?php echo DisplayMoney($row['amount']); ?></span>
					</td>
					<td nowrap="nowrap" align="center" valign="middle"><?php echo $row['payment_date']; ?></td>
					<td nowrap="nowrap" align="center" valign="middle">
					<?php
						switch ($row['status'])
					  {
							case "confirmed": echo "<span class='confirmed_status'>confirmed</span>"; break;
							case "pending": echo "<span class='pending_status'>pending</span>"; break;
							case "declined": echo "<span class='declined_status'>declined</span>"; break;
							case "failed": echo "<span class='failed_status'>failed</span>"; break;
							case "request": echo "<span class='request_status'>awaiting approval</span>"; break;
							case "paid": echo "<span class='paid_status'>failed</span>"; break;
							default: echo "<span class='payment_status'>".$row['status']."</span>"; break;
						}
					?>
					</td>
					<td nowrap="nowrap" align="center" valign="middle">
						<a href="payment_details.php?id=<?php echo $row['transaction_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>" title="View"><img src="images/view.gif" border="0" alt="View" /></a>
						<a href="payment_edit.php?id=<?php echo $row['transaction_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>
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
				  <td colspan="8" align="center" valign="top">
						<?php echo ShowPagination("transactions",$results_per_page,"payments.php?column=$rrorder&order=$rorder&show=$results_per_page&".$filter_by); ?>
				  </td>
				</tr>
            </table>
			</form>

		</table>
        
		 <?php }else{ ?>
				<?php if (isset($filter)) { ?>
					<div class="info_box">Sorry, no results found for your search criteria! <a href='payments.php'>Search again &#155;</a></div>
				<?php }else{ ?>
					<div class="info_box">There are currently no transactions.</div>
				<?php } ?>
        <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>