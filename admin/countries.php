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


	if (isset($_POST['action']) && $_POST['action'] == "add")
	{
		$country_name = mysql_real_escape_string(getPostParameter('country_name'));

		if (isset($country_name) && $country_name != "")
		{
			$check_query = smart_mysql_query("SELECT * FROM cashbackengine_countries WHERE name='$country_name'");
			
			if (mysql_num_rows($check_query) == 0)
			{
				$sql = "INSERT INTO cashbackengine_countries SET name='$country_name'";

				if (smart_mysql_query($sql))
				{
					header("Location: countries.php?msg=added");
					exit();
				}
			}
			else
			{
				header("Location: countries.php?msg=exists");
				exit();
			}
		}
	}


	$query = "SELECT * FROM cashbackengine_countries ORDER BY name ASC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

	$cc = 0;


	$title = "Countries";
	require_once ("inc/header.inc.php");

?>

		<h2>Countries</h2>

		<p align="center"><img src="images/icons/countries.gif" /></p>
		<p align="center">These countries will be displayed on <a target="_blank" href="<?php echo SITE_URL."register.php"; ?>">Sign Up</a> page.</p>

        <?php if ($total > 0) { ?>

			<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div style="width:300px;" class="success_box">
				<?php

					switch ($_GET['msg'])
					{
						case "added":	echo "Country was successfully added!"; break;
						case "exists":	echo "Sorry, country exists!"; break;
						case "updated": echo "Country has been successfully edited!"; break;
						case "deleted": echo "Country has been successfully deleted!"; break;
					}

				?>
			</div>
			<?php } ?>


			<table align="center" style="border-bottom: 1px solid #DCEAFB;" width="300" border="0" cellpadding="3" cellspacing="0">
			<tr bgcolor="#DCEAFB" align="center">
				<th class="noborder" width="1%">&nbsp;</th>
				<th width="75%">Country Name</th>
				<th width="20%">Actions</th>
			</tr>
             <?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
				  <tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td nowrap="nowrap" valign="middle" align="center"></td>
					<td nowrap="nowrap" align="left" valign="middle" class="row_title"><a href="country_edit.php?id=<?php echo $row['country_id']; ?>"><?php echo $row['name']; ?></a></td>
					<td nowrap="nowrap" align="center" valign="middle">
						<a href="country_edit.php?id=<?php echo $row['country_id']; ?>" title="Edit"><img src="images/edit.gif" border="0" alt="Edit" /></a>
						<a href="#" onclick="if (confirm('Are You sure You realy want to delete this country?') )location.href='country_delete.php?id=<?php echo $row['country_id']; ?>'" title="Delete"><img src="images/delete.gif" border="0" alt="Delete" /></a>
					</td>
				  </tr>
			<?php } ?>
            </table>
          
		  <?php }else{ ?>
				<div class="info_box">There are no countries at this time.</div>
          <?php } ?>

		  <br/>

		  <form action="" method="post">
		  <table align="center" width="300" border="0" cellpadding="3" cellspacing="0">
          <tr>
            <td valign="middle" align="right" class="tb1">Name:</td>
			<td align="left">
				<input type="text" name="country_name" id="country_name" value="" size="25" class="textbox" />
			</td>
			<td align="left">
				<input type="hidden" name="action" id="action" value="add" />
				<input type="submit" name="add" id="add" class="submit" value="Add Country" />
		    </td>
          </tr>
		  </table>
		  </form>
		  <br/><br/>

<?php require_once ("inc/footer.inc.php"); ?>