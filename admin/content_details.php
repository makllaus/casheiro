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


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$cid	= (int)$_GET['id'];

		$query = "SELECT *, DATE_FORMAT(modified, '%e %b %Y %h:%i %p') AS modify_date FROM cashbackengine_content WHERE content_id='$cid'";
		$result = smart_mysql_query($query);
		$row = mysql_fetch_array($result);
		$total = mysql_num_rows($result);
	}

	$title = "View Content";
	require_once ("inc/header.inc.php");

?>   
    
      <?php if ($total > 0) { ?>

          <h2>View Content</h2>

          <table width="100%" align="center" cellpadding="2" cellspacing="5" border="0">
          <tr>
			<td bgcolor="#F7F7F7" align="left" valign="top">
				<h1 style="margin:0;padding: 3px;"><?php echo stripslashes($row['title']); ?></h1>
			</td>
          <tr>
            <td><div class="sline"></div></td>
          </tr>
          <tr>
            <td valign="top"><?php echo stripslashes($row['description']); ?></td>
          </tr>
          <tr>
            <td><div class="sline"></div></td>
          </tr>
          <tr>
            <td align="right" valign="top">Last modified: <span class="date"><?php echo $row['modify_date']; ?></span></td>
          </tr>
		  <?php if ($row['name'] == "page") { ?>
           <tr>
            <td colspan="2" height="30" bgcolor="#F7F7F7" align="left" valign="middle">Page URL: <a target="_blank" href="<?php echo SITE_URL."content.php?id=".$row['content_id']; ?>"><?php echo SITE_URL."content.php?id=".$row['content_id']; ?></a></td>
          </tr>
		  <?php } ?>
          <tr>
            <td colspan="2" align="center" valign="bottom">
				<input type="button" class="submit" name="cancel" value="Go Back" onClick="javascript:document.location.href='content.php'" />
            </td>
          </tr>
          </table>

      <?php }else{ ?>
			<p align="center">Sorry, no record found.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Go Back</a></p>
      <?php } ?>

<?php require_once ("inc/footer.inc.php"); ?>