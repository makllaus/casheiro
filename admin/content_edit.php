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
	require_once("./inc/fckeditor/fckeditor.php");


if (isset($_POST['action']) && $_POST['action'] == "editcontent")
{
	$content_id	= (int)getPostParameter('cid');
	$page_title	= mysql_real_escape_string($_POST['page_title']);
	$page_text	= mysql_real_escape_string($_POST['page_text']);

	unset($errs);
	$errs = array();

	if (!($page_title && $page_text))
	{
		$errs[] = "Please fill in all required fields";
	}

	if (count($errs) == 0)
	{
		$sql = "UPDATE cashbackengine_content SET title='$page_title', description='$page_text', modified=NOW() WHERE content_id='$content_id'";

		if (smart_mysql_query($sql))
		{
			header("Location: content.php?msg=updated");
			exit();
		}
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= "&#155; ".$errorname."<br/>\n";
	}
}


	if (isset($_GET['id']) && is_numeric($_GET['id'])) { $cid = (int)$_GET['id']; } else { $cid = (int)$_POST['cid']; }
	$query = "SELECT * FROM cashbackengine_content WHERE content_id='$cid'";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	$title = "Edit Content";
	require_once ("inc/header.inc.php");

?>
 
      <?php if ($total > 0) {

		  $row = mysql_fetch_array($result);
		  
      ?>

        <h2>Edit Content</h2>

			<?php if (isset($allerrors) && $allerrors != "") { ?>
				<div class="error_box"><?php echo $allerrors; ?></div>
			<?php } ?>

        <form action="" method="post">
          <table width="100%" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
            <td nowrap="nowrap" width="35" valign="middle" align="right" class="tb1"><span class="req">* </span>Page Title:</td>
            <td valign="top"><input type="text" name="page_title" id="page_title" value="<?php echo $row['title']; ?>" size="50" class="textbox" /></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="right" class="tb1"><span class="req">* </span>Page Content:</td>
            <td valign="top">
			<?php

				$cFCKeditor = new FCKeditor('page_text') ;
				$cFCKeditor->BasePath = './inc/fckeditor/';
				$cFCKeditor->Height = '350';
				$cFCKeditor->Value = stripslashes($row['description']);
				$cFCKeditor->Create() ;
			
			?>			
			</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
			<input type="hidden" name="cid" id="cid" value="<?php echo (int)$row['content_id']; ?>" />
			<input type="hidden" name="action" id="action" value="editcontent" />
			<input type="submit" name="update" id="update" class="submit" value="Update" />&nbsp;&nbsp;
			<input type="button" class="cancel" name="cancel" value="Cancel" onClick="javascript:document.location.href='content.php'" />
		  </td>
          </tr>
        </table>
      </form>

      <?php }else{ ?>
				<p align="center">Sorry, no record found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>