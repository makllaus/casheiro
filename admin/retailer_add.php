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


	$url = "http://";


if (isset($_POST['action']) && $_POST['action'] == "add")
 {
		unset($errors);
		$errors = array();
 
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

		if ($img == "")
		{
			$img = "noimg.gif";
		}

		if (!($rname && $url && $cashback && $cashback_sign && $description))
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

			if (!is_numeric($cashback))
			{
				$errors[] = "Please enter correct cashback value (digits only)";
			}
		}

		if ($cashback_sign == "currency") $cashback_sign = ""; else $cashback_sign = "%";

		if (count($errors) == 0)
		{
				$insert_sql = "INSERT INTO cashbackengine_retailers SET title='$rname', network_id='$network_id', program_id='$program_id', url='$url', image='$img', cashback='".$cashback.$cashback_sign."', conditions='$conditions', description='$description', featured='$featured', deal_of_week='$deal_of_week', status='active', added=NOW()";
				$result = smart_mysql_query($insert_sql);
				$new_retailer_id = mysql_insert_id();

				foreach ($category as $cat_id)
				{
					$cats_insert_sql = "INSERT INTO cashbackengine_retailer_to_category SET retailer_id='$new_retailer_id', category_id='$cat_id'";
					smart_mysql_query($cats_insert_sql);
				}

				header("Location: retailer_add.php?msg=added");
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

	$title = "Add Retailer";
	require_once ("inc/header.inc.php");

?>

    <h2>Add Retailer</h2>

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
	<?php } elseif (isset($_GET['msg']) && ($_GET['msg']) == "added") { ?>
		<div class="success_box">Retailer has been successfully added</div>
	<?php } ?>

      <form action="" method="post" name="form1">
        <table width="100%" cellpadding="2" cellspacing="5" border="0" align="center">
          <tr>
            <td colspan="2" align="right" valign="top"><font color="red">* denotes required field</font></td>
          </tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Title:</td>
            <td width="70%" valign="top"><input type="text" name="rname" id="rname" value="<?php echo getPostParameter('rname'); ?>" size="50" class="textbox" /></td>
          </tr>
			<tr>
            <td nowrap="nowrap" width="30%" valign="middle" align="right" class="tb1">Affiliate Network:</td>
            <td width="70%" valign="top">
			<select class="textbox2" id="network_id" name="network_id" onchange="javascript:hiddenDiv('network_id','program_id')">
			<option value="">-------------------------------</option>
				<?php

					$sql_affs = smart_mysql_query("SELECT * FROM cashbackengine_affnetworks WHERE status='active' ORDER BY network_name ASC");
				
					while ($row_affs = mysql_fetch_array($sql_affs))
					{
						if ($network_id == $row_affs['network_id']) $selected = " selected=\"selected\""; else $selected = "";

						echo "<option value=\"".$row_affs['network_id']."\"".$selected.">".$row_affs['network_name']."</option>";
					}
				?>
			</select>
			</td>
			</tr>
          <tr id="program_id" <?php if (empty($network_id)) { ?>style="display: none;" <?php } ?>>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Program ID:</td>
            <td width="70%" valign="top"><input type="text" name="program_id" value="<?php echo $program_id; ?>" size="15" class="textbox" /><span class="note">Program ID from affiliate network</span></td>
			</tr>
          <tr>
            <td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Category:</td>
            <td width="70%" valign="top">
				<div class="scrollbox">
				<?php

					$allcategories = array();
					$allcategories = CategoriesList(0);
					foreach ($allcategories as $category_id => $category_name)
					{
						$cc++;
						if (is_array($category) && in_array($category_id, $category)) $checked = 'checked="checked"'; else $checked = '';

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
            <td valign="middle" align="right" class="tb1">Image URL:</td>
            <td valign="top"><input type="text" name="image_url" class="textbox" value="<?php echo $image_url; ?>" size="70" /></td>
          </tr>
          <tr>
            <td width="30%" valign="top" align="right" class="tb1"><span class="req">* </span>URL:</td>
            <td nowrap="nowrap" width="70%" valign="top">
				<input type="text" name="url" id="url" value="<?php echo $url; ?>" size="70" class="textbox" />
				<br/>
				<table bgcolor="#F7F7F7" style="border-radius: 10px; border: 1px dotted #EEE; padding: 5px; margin: 5px 0;" width="100%" cellpadding="2" cellspacing="2" border="0" align="left">
					<tr valign="top">
						<td colspan="2" align="left">
							Please DO NOT forget to add '<font color="#E72085">{USERID}</font>' to your affiliate link to track members.<br/><br/>
							Links examples for few popular affiliate networks:
						</td>
					</tr>
					<tr valign="middle">
						<td nowrap="nowrap" align="right"><b>ShareASale</b>:</td>
						<td nowrap="nowrap" align="left"><font color="#30AF08">http://www.shareasale.com/r.cfm?u=zzzzz&b=xxxxx&m=yyyyy</font><font color="#E72085">&afftrack=<b>{USERID}</b></font></td>
					</tr>
					<tr valign="middle">
						<td nowrap="nowrap" align="right"><b>Zanox</b>:</td>
						<td nowrap="nowrap" align="left"><font color="#30AF08">http://ad.zanox.com/ppc/?142171430629117663T</font><font color="#E72085">&zpar0=<b>{USERID}</b></font></td>
					</tr>
					<tr valign="middle">
						<td nowrap="nowrap" align="right"><b>Commission Junction</b>:</td>
						<td nowrap="nowrap" align="left"><font color="#30AF08">http://www.kqzyfj.com/click-2538644-10432491</font><font color="#E72085">?sid=<b>{USERID}</b></font></td>
					</tr>
					<tr>
						<td align="left">&nbsp;</td>
						<td style="border-top: 1px #CFCFCF solid;" align="left">
							where <b>afftrack</b>, <b>zpar0</b> and <b>sid</b> - SubID parameters
						</td>
					</tr>
				</table>
			</td>
			</tr>
           <tr>
				<td width="30%" valign="middle" align="right" class="tb1"><span class="req">* </span>Cashback:</td>
				<td width="70%" valign="top"><input type="text" name="cashback" id="cashback" value="<?php echo getPostParameter('cashback'); ?>" size="4" class="textbox" /><select name="cashback_sign"><option value="currency"><?php echo SITE_CURRENCY; ?></option><option value="%">%</option></select></td>
			</tr>
			<tr>
				<td valign="middle" align="right" class="tb1"><span class="req">* </span>Description:</td>
				<td valign="top"><textarea name="description" cols="75" rows="8" class="textbox2"><?php echo getPostParameter('description'); ?></textarea></td>
            </tr>
			<tr>
				<td valign="middle" align="right" class="tb1">Conditions:</td>
				<td valign="top"><textarea name="conditions" cols="75" rows="2" class="textbox2"><?php echo getPostParameter('conditions'); ?></textarea></td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Featured?</td>
				<td valign="middle"><input type="checkbox" class="checkbox" name="featured" value="1" <?php if (getPostParameter('featured') == 1) echo "checked=\"checked\""; ?> />&nbsp;Yes!</td>
            </tr>
            <tr>
				<td valign="middle" align="right" class="tb1">Deal of the Week?</td>
				<td valign="middle"><input type="checkbox" class="checkbox" name="deal_of_week" value="1" <?php if (getPostParameter('deal_of_week') == 1) echo "checked=\"checked\""; ?> />&nbsp;Yes!</td>
            </tr>
            <tr>
				<td align="center" colspan="2" valign="bottom">
					<input type="hidden" name="action" id="action" value="add">
					<input type="submit" class="submit" name="add" id="add" value="Add Retailer" />
				</td>
            </tr>
          </table>
      </form>

<?php require_once ("inc/footer.inc.php"); ?>