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


		// Delete coupons //
		if (isset($_POST['action']) && $_POST['action'] == "delete")
		{
			$ids_arr	= array();
			$ids_arr	= $_POST['id_arr'];

			if (count($ids_arr) > 0)
			{
				foreach ($ids_arr as $v)
				{
					$couponid = (int)$v;
					DeleteCoupon($couponid);
				}

				header("Location: coupons.php?msg=deleted");
				exit();
			}
		}

		$where = "1=1";

		////////////////// filter  //////////////////////
			if (isset($_GET['column']) && $_GET['column'] != "")
			{
				switch ($_GET['column'])
				{
					case "title": $rrorder = "title"; break;
					case "added": $rrorder = "added"; break;
					case "end_date": $rrorder = "end_date"; break;
					case "retailer_id": $rrorder = "retailer_id"; break;
					case "visists": $rrorder = "visits"; break;
					case "status": $rrorder = "status"; break;
					default: $rrorder = "added"; break;
				}
			}
			else
			{
				$rrorder = "added";
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
				$rorder = "asc";
			}
			if (isset($_GET['filter']) && $_GET['filter'] != "")
			{
				$filter	= mysql_real_escape_string(trim(getGetParameter('filter')));
				$where .= " AND (title LIKE '%$filter%' OR code LIKE '%$filter%') ";
			}
		///////////////////////////////////////////////////////

		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
		$from = ($page-1)*$results_per_page;

		if (isset($_GET['store']) && is_numeric($_GET['store']))
		{
			$store = (int)$_GET['store'];
			$where .= " AND retailer_id='$store' ";
			$title2 = GetStoreName($store);
		}

		$query = "SELECT *, DATE_FORMAT(added, '%e %b %Y') AS date_added, UNIX_TIMESTAMP(end_date) - UNIX_TIMESTAMP() AS time_left, DATE_FORMAT(start_date, '%e %b %Y') AS coupon_start_date, DATE_FORMAT(end_date, '%e %b %Y') AS coupon_end_date FROM cashbackengine_coupons WHERE $where ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
		
		$result = smart_mysql_query($query);
		$total_on_page = mysql_num_rows($result);

		$query2 = "SELECT * FROM cashbackengine_coupons WHERE ".$where;
		$result2 = smart_mysql_query($query2);
        $total = mysql_num_rows($result2);

		$cc = 0;

		// delete all expired coupons //
		smart_mysql_query("UPDATE cashbackengine_coupons SET status='expired' WHERE end_date != '0000-00-00 00:00:00' AND end_date <= NOW()");
		if (isset($_GET['act']) && $_GET['act'] == "delete_expired")
		{
			smart_mysql_query("DELETE FROM cashbackengine_coupons WHERE ((end_date != '0000-00-00 00:00:00' AND end_date <= NOW()) OR status='expired')");
			header("Location: coupons.php?msg=exp_deleted");
			exit();
		}


		$title = $title2." Coupons";
		require_once ("inc/header.inc.php");

?>

		<div id="addnew"><a style="margin-right: 30px;" href="coupons.php?act=delete_expired"><img src="images/idelete.png" align="absmiddle" /> Delete expired coupons</a> &nbsp;&nbsp;&nbsp; <a class="addnew" href="coupon_add.php">Add Coupon</a></div>

		<h2><?php echo $title2; ?> Coupons</h2>		

        <?php if ($total > 0) { ?>


			<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div class="success_box">
				<?php

					switch ($_GET['msg'])
					{
						case "added": echo "Coupon has been successfully added!"; break;
						case "updated": echo "Coupon has been successfully edited!"; break;
						case "deleted": echo "Coupon has been successfully deleted!"; break;
						case "exp_deleted": echo "All expired coupons have been successfully deleted!"; break;
					}

				?>
			</div>
			<?php } ?>


		<form id="form1" name="form1" method="get" action="">
		<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
		<tr>
		<td nowrap="nowrap" width="47%" valign="bottom" align="left">
           Sort by: 
          <select name="column" id="column" onChange="document.form1.submit()">
			<option value="added" <?php if ($_GET['column'] == "added") echo "selected"; ?>>Newest</option>
			<option value="title" <?php if ($_GET['column'] == "title") echo "selected"; ?>>Name</option>
			<option value="retailer_id" <?php if ($_GET['column'] == "retailer_id") echo "selected"; ?>>Store</option>
			<option value="end_date" <?php if ($_GET['column'] == "end_date") echo "selected"; ?>>End Soonest</option>
			<option value="visits" <?php if ($_GET['column'] == "visits") echo "selected"; ?>>Popularity</option>
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
			<?php if ($store) { ?><input type="hidden" name="store" value="<?php echo $store; ?>" /><?php } ?>
		</td>
		<td nowrap="nowrap" width="30%" valign="middle" align="left">
			<div style="background: #F7F7F7; padding: 7px; border-radius: 7px;">
				Store: &nbsp;
				<select name="store" id="store" onChange="document.form1.submit()" style="width: 150px;" class="textbox2">
				<option value="">--- all stores ---</option>
				<?php
					$sql_retailers = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE status='active' ORDER BY title ASC");
					if (mysql_num_rows($sql_retailers) > 0)
					{
						while ($row_retailers = mysql_fetch_array($sql_retailers))
						{
							if ($store == $row_retailers['retailer_id']) $selected = " selected=\"selected\""; else $selected = "";
							echo "<option value=\"".$row_retailers['retailer_id']."\"".$selected.">".$row_retailers['title']." (".GetStoreCouponsTotal($row_retailers['retailer_id']).")</option>";
						}
					}
				?>
				</select>
				<?php if ($store > 0) { ?><a href="coupons.php"><img align="absmiddle" src="images/icons/delete_filter.png" border="0" alt="Delete Filter" /></a><?php } ?>
				<br/>
				<div class="admin_filter">
					Search: <input type="text" name="filter" value="<?php echo $filter; ?>" class="inputt" size="25" /> <input type="submit" class="butt" value="Search" />
					<?php if (isset($filter) && $filter != "") { ?><a title="Cancel Search" href="coupons.php"><img align="absmiddle" src="images/icons/delete_filter.png" border="0" alt="Cancel Search" /></a><?php } ?>
				</div>
			</div>
		</td>
		<td nowrap="nowrap" width="35%" valign="bottom" align="right">
			   Showing <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> of <?php echo $total; ?>
		</td>
		</tr>
		</table>
		</form>

			<form id="form2" name="form2" method="post" action="">
			<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
			<tr>
				<th width="3%"><input type="checkbox" name="selectAll" onclick="checkAll();" class="checkbox" /></th>
				<th width="10%"><b>ID</b></th>
				<th width="20%"><b>Title</b></th>
				<th width="25%"><b>Store</b></th>
				<th width="17%"><b>Code</b></th>
				<th width="12%"><b>Expiry Date</b></th>
				<th width="10%"><b>Status</b></th>
				<th width="10%"><b>Actions</b></th>
			</tr>
			<?php while ($row = mysql_fetch_array($result)) { $cc++; ?>				  
				  <tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td nowrap="nowrap" align="center" valign="middle"><input type="checkbox" class="checkbox" name="id_arr[<?php echo $row['coupon_id']; ?>]" id="id_arr[<?php echo $row['coupon_id']; ?>]" value="<?php echo $row['coupon_id']; ?>" /></td>
					<td align="center" valign="middle"><?php echo $row['coupon_id']; ?></td>
					<td align="left" valign="middle" class="row_title">
					<?php 
							if (strlen($row['title']) > 100)
								echo substr($row['title'], 0, 100)."...";
							else
								echo $row['title'];
					?>
					<?php if ($row['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon"></span><?php } ?>
					</td>
					<td align="left" valign="middle"><a href="retailer_details.php?id=<?php echo $row['retailer_id']; ?>"><?php echo GetStoreName($row['retailer_id']); ?></a></td>
					<td align="center" valign="middle"><?php echo $row['code']; ?></td>
					<td nowrap="nowrap" align="center" valign="middle" title="<?php echo GetTimeLeft($row['time_left']); ?> left"><?php echo $row['coupon_end_date']; ?></td>
					<td align="center" valign="middle">
					<?php
						switch ($row['status'])
						{
							case "active": echo "<span class='active_s'>".$row['status']."</span>"; break;
							case "inactive": echo "<span class='inactive_s'>".$row['status']."</span>"; break;
							case "expired": echo "<span class='expired_status'>".$row['status']."</span>"; break;
							default: echo "<span class='default_status'>".$row['status']."</span>"; break;
						}
					?>
					</td>
					<td nowrap="nowrap" align="center" valign="middle">
						<a href="coupon_details.php?id=<?php echo $row['coupon_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>" title="View"><img src="images/view.gif" border="0" alt="View" /></a>
						<a href="coupon_edit.php?id=<?php echo $row['coupon_id']; ?>&pn=<?php echo $page; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>
						<a href="#" onclick="if (confirm('Are You sure You realy want to delete this coupon?') )location.href='coupon_delete.php?id=<?php echo $row['coupon_id']; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>&pn=<?php echo $page?>'" title="Delete"><img src="images/delete.gif" border="0" alt="Delete" /></a>
					</td>
				  </tr>
			<?php } ?>
				<tr>
				<td colspan="8" align="left">
					<input type="hidden" name="column" value="<?php echo $rrorder; ?>" />
					<input type="hidden" name="order" value="<?php echo $rorder; ?>" />
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
					<input type="hidden" name="action" value="delete" />
					<input type="submit" class="submit" name="GoDelete" id="GoDelete" value="Delete Selected" />
				</td>
				</tr>
				  <tr>
				  <td colspan="8" align="center">
					<?php
							$params = "";
							if ($store)		$params .= "store=$store&";
							
							echo ShowPagination("coupons",$results_per_page,"coupons.php?".$params."column=$rrorder&order=$rorder&show=$results_per_page&", "WHERE ".$where);
					?>
				  </td>
				  </tr>
            </table>
			</form>

          <?php }else{ ?>
				<?php if (isset($filter)) { ?>
					<div class="info_box">No coupons found. <a href="coupons.php">Search again &#155;</a></div>
				<?php }else{ ?>
					<div class="info_box">There are no coupons at this time. <?php if ($store) { ?><a href="coupons.php">View all coupons &#155;</a><?php } ?></div>
				<?php } ?>
          <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>