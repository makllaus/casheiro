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


if (isset($_POST['action']) && $_POST['action'] == "add")
 {
		unset($errors);
		$errors = array();
 
		$coupon_title	= mysql_real_escape_string(getPostParameter('coupon_title'));
		$retailer_id	= (int)getPostParameter('retailer_id');
		$code			= mysql_real_escape_string(getPostParameter('code'));
		$start_date		= mysql_real_escape_string(getPostParameter('start_date'));
		$start_time		= mysql_real_escape_string(getPostParameter('start_time'));
		$end_date		= mysql_real_escape_string(getPostParameter('end_date'));
		$end_time		= mysql_real_escape_string(getPostParameter('end_time'));
		$coupon_start_date	= $start_date." ".$start_time;
		$coupon_end_date	= $end_date." ".$end_time;
		$description	= mysql_real_escape_string(nl2br(getPostParameter('description')));
		$exclusive		= (int)getPostParameter('exclusive');


		if (!($coupon_title && $retailer_id && $code))
		{
			$errors[] = "Please ensure that all fields marked with an asterisk are complete";
		}

		if (count($errors) == 0)
		{
				$insert_sql = "INSERT INTO cashbackengine_coupons SET title='$coupon_title', retailer_id='$retailer_id', user_id='0', code='$code',  start_date='$coupon_start_date', end_date='$coupon_end_date', description='$description', exclusive='$exclusive', status='active', added=NOW()";
				$result = smart_mysql_query($insert_sql);
				$new_coupon_id = mysql_insert_id();

				header("Location: coupons.php?msg=added");
				exit();
		}
		else
		{
			$errormsg = "";
			foreach ($errors as $errorname)
				$errormsg .= "&#155; ".$errorname."<br/>";
		}

}

	$cc = 0;

	$title = "Add Coupon";
	require_once ("inc/header.inc.php");

?>

    <h2>Add Coupon</h2>


	<?php if (isset($errormsg) && $errormsg != "") { ?>
		<div class="error_box"><?php echo $errormsg; ?></div>
	<?php } elseif (isset($_GET['msg']) && ($_GET['msg']) == "added") { ?>
		<div class="success_box">Coupon has been successfully added</div>
	<?php } ?>


      <form action="" method="post" name="form1">
        <table width="100%" cellpadding="2" cellspacing="5" border="0" align="center">
          <tr>
            <td colspan="2" align="right" valign="top"><font color="red">* denotes required field</font></td>
          </tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Title:</td>
            <td width="70%" valign="top"><input type="text" name="coupon_title" id="coupon_title" value="<?php echo getPostParameter('coupon_title'); ?>" size="40" class="textbox" /></td>
          </tr>
			<tr>
            <td nowrap="nowrap" width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Store:</td>
            <td width="70%" valign="top">
			<select class="textbox2" id="retailer_id" name="retailer_id">
			<option value="">--- Please select store ---</option>
				<?php

					$sql_retailers = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE status='active' ORDER BY title ASC");
				
					while ($row_retailers = mysql_fetch_array($sql_retailers))
					{
						if ($retailer_id == $row_retailers['retailer_id']) $selected = " selected=\"selected\""; else $selected = "";

						echo "<option value=\"".$row_retailers['retailer_id']."\"".$selected.">".$row_retailers['title']."</option>";
					}
				?>
			</select>
			</td>
			</tr>
			<tr>
				<td valign="middle" align="right" class="tb1"><span class="req">* </span>Coupon Code:</td>
				<td valign="top"><input type="text" name="code" id="code" value="<?php echo getPostParameter('code'); ?>" size="26" class="textbox" /></td>
			</tr>
			<script>
				$(function() {
					$('#start_date').calendricalDate();
			        $('#end_date').calendricalDate();
			        $('#start_time').calendricalTime({
						minTime: {hour: 0, minute: 0},
						maxTime: {hour: 23, minute: 59},
						timeInterval: 30
					})
			        $('#end_time').calendricalTime({
						minTime: {hour: 0, minute: 0},
						maxTime: {hour: 23, minute: 59},
						timeInterval: 30
					})
				});
			</script>
            <tr>
				<td valign="middle" align="right" class="tb1">Start Date:</td>
				<td valign="middle"><input type="text" name="start_date" id="start_date" value="<?php echo getPostParameter('start_date'); ?>" size="10"  maxlength="10" class="textbox" />&nbsp; <input type="text" name="start_time" id="start_time" value="<?php echo getPostParameter('start_time'); ?>" size="6" maxlength="8" class="textbox" /><span class="note">YYYY-MM-DD &nbsp; HH:MM</span></td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Expiry Date:</td>
				<td valign="middle"><input type="text" name="end_date" id="end_date" value="<?php echo getPostParameter('end_date'); ?>" size="10"  maxlength="10" class="textbox" />&nbsp; <input type="text" name="end_time" id="end_time" value="<?php echo getPostParameter('end_time'); ?>" size="6" maxlength="8" class="textbox" /><span class="note">YYYY-MM-DD &nbsp; HH:MM</span></td>
            </tr>
			<tr>
				<td valign="middle" align="right" class="tb1">Description:</td>
				<td valign="top"><textarea name="description" cols="50" rows="6" class="textbox2"><?php echo getPostParameter('description'); ?></textarea></td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Exclusive?</td>
				<td valign="middle"><input type="checkbox" class="checkbox" name="exclusive" value="1" <?php if (getPostParameter('exclusive') == 1) echo "checked=\"checked\""; ?> />&nbsp;Yes!</td>
            </tr>
            <tr>
				<td align="center" colspan="2" valign="bottom">
					<input type="hidden" name="action" id="action" value="add">
					<input type="submit" class="submit" name="add" id="add" value="Add Coupon" />
				</td>
            </tr>
          </table>
      </form>

<?php require_once ("inc/footer.inc.php"); ?>