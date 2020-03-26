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


	$pn = (int)$_GET['pn'];


if (isset($_POST["action"]) && $_POST["action"] == "edit")
{
		unset($errors);
		$errors = array();

		$retailer_id	= (int)getPostParameter('rid');
		$network_id		= (int)getPostParameter('network_id');
		$program_id		= trim(getPostParameter('program_id'));
		$category		= array();
		$category		= $_POST['category_id'];
		$rname			= mysql_real_escape_string(getPostParameter('rname'));
		$img			= mysql_real_escape_string(trim($_POST['image_url']));
		$url			= mysql_real_escape_string(trim($_POST['url']));
		$cashback		= mysql_real_escape_string(getPostParameter('cashback'));
		$cashback_sign	= mysql_real_escape_string(getPostParameter('cashback_sign'));
		$description	= mysql_real_escape_string(nl2br(getPostParameter('description')));
		$conditions		= mysql_real_escape_string(nl2br(getPostParameter('conditions')));
		$featured		= (int)getPostParameter('featured');
		$deal_of_week	= (int)getPostParameter('deal_of_week');
		$status			= mysql_real_escape_string(getPostParameter('status'));

		if ($img == "")
		{
			$img = "noimg.gif";
		}

		if (!($rname && $url && $cashback && $cashback_sign && $description && $status))
		{
			$errors[] = "Please ensure that all fields marked with an asterisk are complete";
		}
		elseif (count($category) == 0)
		{
			$errors[] = "Please select category";
		}
		else
		{
			if (substr($url, 0, 7) != 'http://')
			{
				$errors[] = "Enter correct url format, enter the 'http://' statement before your link";
			}
			elseif ($url == 'http://')
			{
				$errors[] = "Please enter correct URL";
			}

			if (isset($network_id) && $network_id != "")
			{
				if (!$program_id || $program_id == "")
					$errors[] = "Please fill in Program ID field";
			}
			else
			{
				$program_id = 0;
			}

			if (!is_numeric($cashback))
			{
				$errors[] = "Please enter correct cashback value (digits only)";
			}
		}

		if ($cashback_sign == "currency") $cashback_sign = ""; else $cashback_sign = "%";

		if (count($errors) == 0)
		{
			smart_mysql_query("UPDATE cashbackengine_retailers SET title='$rname', network_id='$network_id', program_id='$program_id', url='$url', image='$img', cashback='".$cashback.$cashback_sign."', conditions='$conditions', description='$description', featured='$featured', deal_of_week='$deal_of_week', status='$status' WHERE retailer_id='$retailer_id'");

			smart_mysql_query("DELETE FROM cashbackengine_retailer_to_category WHERE retailer_id='$retailer_id'");

			foreach ($category as $cat_id)
			{
				$cats_insert_sql = "INSERT INTO cashbackengine_retailer_to_category SET retailer_id='$retailer_id', category_id='$cat_id'";
				smart_mysql_query($cats_insert_sql);
			}

			header("Location: retailers.php?msg=updated");
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
		$id	= (int)$_GET['id'];

		$query = "SELECT * FROM cashbackengine_retailers WHERE retailer_id='$id'";
		$rs	= smart_mysql_query($query);
		$total = mysql_num_rows($rs);
	}


	$title = "Edit Retailer";
	require_once ("inc/header.inc.php");

?>


    <h2>Edit Retailer</h2>

	<?php if ($total > 0) {
		
		$row = mysql_fetch_array($rs);

	?>

		<script>
		<!--
			function hiddenDiv(id,showid){
				if(document.getElementById(id).value != ""){
					document.getElementById(showid).style.display = ""
				}else{
					document.getElementById(showid).style.display = "none"
				}
			}
		-->
		</script>


	<?php if (isset($errormsg) && $errormsg != "") { ?>
		<div class="error_box"><?php echo $errormsg; ?></div>
	<?php } ?>

      <form action="" method="post" name="form1">
        <table width="100%" cellpadding="2" cellspacing="5" border="0" align="center">
          <tr>
            <td colspan="2" align="right" valign="top"><font color="red">* denotes required field</font></td>
          </tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Title:</td>
            <td width="70%" valign="top"><input type="text" name="rname" id="rname" value="<?php echo $row['title']; ?>" size="50" class="textbox" /></td>
			</tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1">Affiliate Network:</td>
            <td width="70%" valign="top">
			<select class="textbox2" id="network_id" name="network_id" onchange="javascript:hiddenDiv('network_id','program_id')">
				<option value="">-------------------------------</option>
				<?php

					$sql_affs = smart_mysql_query("SELECT * FROM cashbackengine_affnetworks WHERE status='active' ORDER BY network_name ASC");
				
					while ($row_affs = mysql_fetch_array($sql_affs))
					{
						if ($row['network_id'] == $row_affs['network_id']) $selected = " selected=\"selected\""; else $selected = "";

						echo "<option value=\"".$row_affs['network_id']."\"".$selected.">".$row_affs['network_name']."</option>";
					}
				?>	
			</select>
			</td>
			</tr>
          <tr id="program_id" <?php if ($row['network_id'] == 0) { ?>style="display: none;" <?php } ?>>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Program ID:</td>
            <td width="70%" valign="top"><input type="text" name="program_id" value="<?php echo $row['program_id']; ?>" size="15" class="textbox" /><span class="note">Program ID from affiliate network</span></td>
			</tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Category:</td>
            <td width="70%" valign="top">
				<div class="scrollbox">
				<?php

					unset($retailer_cats);
					$retailer_cats = array();

					$sql_retailer_cats = smart_mysql_query("SELECT category_id FROM cashbackengine_retailer_to_category WHERE retailer_id='$id'");		
					
					while ($row_retailer_cats = mysql_fetch_array($sql_retailer_cats))
					{
						$retailer_cats[] = $row_retailer_cats['category_id'];
					}

					$allcategories = array();
					$allcategories = CategoriesList(0);
					foreach ($allcategories as $category_id => $category_name)
					{
						$cc++;
						if (is_array($retailer_cats) && in_array($category_id, $retailer_cats)) $checked = 'checked="checked"'; else $checked = '';

						if (($cc%2) == 0)
							echo "<div class=\"even\"><input name=\"category_id[]\" value=\"".(int)$category_id."\" ".$checked." type=\"checkbox\">".$category_name."</div>";
						else
							echo "<div class=\"odd\"><input name=\"category_id[]\" value=\"".(int)$category_id."\" ".$checked." type=\"checkbox\">".$category_name."</div>";
					}

				?>
				</div>
			</td>
			</tr>
			<tr>
			<td valign="middle" align="right" class="tb1">Current Image:</td>
			<td align="left" valign="top"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="120" height="60" align="left" alt="" title="" border="0" class="imgs" /></td>
			</tr>
			<tr>
			<td valign="middle" align="right" class="tb1">Image URL:</td>
			<td align="left" valign="top"><input type="text" name="image_url" class="textbox" value="<?php echo $row['image']; ?>" size="70" /></td>
			</tr>
            <tr>
            <td width="30%" valign="top" align="right" class="tb1"><span class="req">* </span>URL:</td>
            <td nowrap="nowrap" width="70%" valign="top">
				<input type="text" name="url" id="url" value="<?php echo $row['url']; ?>" size="70" class="textbox" /><br/>
				<font color="#838383">Please DO NOT forget to add '<font color="#E72085">{USERID}</font>' to your affiliate link to track members.</font>
			</td>
			</tr>
			<?php
					if (strstr($row['cashback'], '%'))
					{
						$cashback = str_replace('%','',$row['cashback']);
						$selected1 = "";
						$selected2 = "selected";
					}
					else
					{
						$cashback = $row['cashback'];
						$selected1 = "selected";
						$selected2 = "";
					}
			?>
            <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Cashback:</td>
            <td width="70%" valign="top">
				<input type="text" name="cashback" id="cashback" value="<?php echo $cashback; ?>" size="4" class="textbox" />
				<select name="cashback_sign">
					<option value="currency" <?php echo $selected1; ?>><?php echo SITE_CURRENCY; ?></option>
					<option value="%" <?php echo $selected2; ?>>%</option>
				</select>
			</td>
			</tr>
            <tr>
            <td valign="middle" align="right" class="tb1"><span class="req">* </span>Description:</td>
            <td valign="top"><textarea name="description" cols="75" rows="8" class="textbox2"><?php echo strip_tags($row['description']); ?></textarea></td>
            </tr>
            <tr>
            <td valign="middle" align="right" class="tb1">Contidions:</td>
            <td valign="top"><textarea name="conditions" cols="75" rows="2" class="textbox2"><?php echo strip_tags($row['conditions']); ?></textarea></td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Featured?</td>
				<td valign="middle"><input type="checkbox" class="checkbox" name="featured" value="1" <?php if ($row['featured'] == 1) echo "checked=\"checked\""; ?> />&nbsp;Yes!</td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Deal of the Week?</td>
				<td valign="middle"><input type="checkbox" class="checkbox" name="deal_of_week" value="1" <?php if ($row['deal_of_week'] == 1) echo "checked=\"checked\""; ?> />&nbsp;Yes!</td>
            </tr>
            <tr>
            <td valign="middle" align="right" class="tb1"><span class="req">* </span>Status:</td>
            <td valign="top">
				<select name="status">
					<option value="active" <?php if ($row['status'] == "active") echo "selected"; ?>>active</option>
					<option value="inactive" <?php if ($row['status'] == "inactive") echo "selected"; ?>>inactive</option>
				</select>
			</td>
            </tr>
            <tr>
              <td align="center" colspan="2" valign="bottom">
				<input type="hidden" name="rid" id="rid" value="<?php echo (int)$row['retailer_id']; ?>" />
				<input type="hidden" name="action" id="action" value="edit">
				<input type="submit" class="submit" name="update" id="update" value="Update Retailer" />&nbsp;&nbsp;
				<input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='retailers.php?page=<?php echo $pn; ?>&column=<?php echo $_GET['column']; ?>&order=<?php echo $_GET['order']; ?>'" />
              </td>
            </tr>
          </table>
      </form>

      <?php }else{ ?>
			<p align="center">Sorry, no retailer found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>


<?php require_once ("inc/footer.inc.php"); ?>