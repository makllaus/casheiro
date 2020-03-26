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
		$id = (int)$_GET['id'];

		$query = "SELECT * FROM cashbackengine_categories WHERE category_id='$id'";
		$rs = smart_mysql_query($query);
		$total = mysql_num_rows($rs);
	}


	if (isset($_POST["action"]) && $_POST["action"] == "edit")
	{
		unset($errors);
		$errors = array();
 
		$catid					= (int)getPostParameter('catid');
		$catname				= mysql_real_escape_string(getPostParameter('catname'));
		$category_description	= mysql_real_escape_string(nl2br(getPostParameter('description')));
		$parent_category		= (int)getPostParameter('parent_id');
		

		if (!($catname && $catid))
		{
			$errors[] = "Please enter category name";
		}

		if (count($errors) == 0)
		{
			smart_mysql_query("UPDATE cashbackengine_categories SET parent_id='$parent_category', name='$catname', description='$category_description', category_url='' WHERE category_id='$catid'");

			header("Location: categories.php?msg=updated");
			exit();
		}
		else
		{
			$errormsg = "";
			foreach ($errors as $errorname)
				$errormsg .= "&#155; ".$errorname."<br/>";
		}
	}


	$title = "Edit Category";
	require_once ("inc/header.inc.php");

?>


    <h2>Edit Category</h2>

	<?php if ($total > 0) {
	
		$row = mysql_fetch_array($rs);

	?>

			<?php if (isset($errormsg) && $errormsg != "") { ?>
					<div style="width:75%;" class="error_box"><?php echo $errormsg; ?></div>
			<?php } ?>

      <form action="" method="post">
        <table width="75%" cellpadding="2" cellspacing="5" border="0" align="center">
          <tr>
            <td colspan="2" align="right" valign="top"><font color="red">* denotes required field</font></td>
          </tr>
          <tr>
            <td width="150" nowrap="nowrap" valign="middle" align="right" class="tb1"><span class="req">* </span>Category Name:</td>
            <td valign="top"><input type="text" name="catname" id="catname" value="<?php echo $row['name']; ?>" size="35" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Parent Category:</td>
			<td align="left">
				<select name="parent_id">
					<option value=""> ---------- None ---------- </option>
					<?php CategoriesDropDown (0,"",$row['category_id'],$row['parent_id']); ?>
				</select>
			</td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1">Description:</td>
			<td align="left" valign="top"><textarea name="description" cols="45" rows="5" class="textbox2"><?php echo strip_tags($row['description']); ?></textarea></select>
			</td>
          </tr>
            <tr>
              <td align="center" colspan="2" valign="bottom">
			  <input type="hidden" name="catid" id="catid" value="<?php echo (int)$row['category_id']; ?>" />
			  <input type="hidden" name="action" id="action" value="edit">
			  <input type="submit" class="submit" name="update" id="update" value="Update" />&nbsp;&nbsp;
              <input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='categories.php'" /></td>
            </tr>
          </table>
      </form>
      
	  <?php }else{ ?>
			<p align="center">Sorry, no record found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>