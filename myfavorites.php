<?php
/*******************************************************************\
 * CashbackEngine v2.0
 * http://www.CashbackEngine.net
 *
 * Copyright (c) 2010-2013 CashbackEngine Software. All rights reserved.
 * ------------ CashbackEngine IS NOT FREE SOFTWARE --------------
\*******************************************************************/

	session_start();
	require_once("inc/auth.inc.php");
	require_once("inc/config.inc.php");

	$userid			= (int)$_SESSION['userid'];
	$retailer_id	= (int)$_GET['rid'];


	if (isset($_GET['act']) && $_GET['act'] == "add")
	{
		$check_query = smart_mysql_query("SELECT * FROM cashbackengine_favorites WHERE user_id='$userid' AND retailer_id='$retailer_id'");

		if (mysql_num_rows($check_query) == 0)
		{
			smart_mysql_query("INSERT INTO cashbackengine_favorites SET user_id='$userid', retailer_id='$retailer_id', added=NOW()");
		}

		header("Location: myfavorites.php");
		exit();
	}


	if (isset($_GET['act']) && $_GET['act'] == "del")
	{
		$del_query = "DELETE FROM cashbackengine_favorites WHERE user_id='$userid' AND retailer_id='$retailer_id'";
		if (smart_mysql_query($del_query))
		{
			header("Location: myfavorites.php?msg=deleted");
			exit();
		}
	}

	
	$query = "SELECT cashbackengine_favorites.*, cashbackengine_retailers.* FROM cashbackengine_favorites cashbackengine_favorites, cashbackengine_retailers cashbackengine_retailers WHERE cashbackengine_favorites.user_id='$userid' AND cashbackengine_favorites.retailer_id=cashbackengine_retailers.retailer_id AND cashbackengine_retailers.status='active' ORDER BY cashbackengine_retailers.title ASC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "My Favorite Stores";

	require_once ("inc/header.inc.php");
	
?>

	<h1>Minhas Lojas Favoritas</h1>


		  <?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div class="success_msg">
				<?php

					switch ($_GET['msg'])
					{
						case "deleted": echo "Varejista excluído do seus favoritos!"; break;
					}

				?>
			</div>
		<?php } ?>


	<?php

		if ($total > 0) {
 
	?>

		<p align="center">Abaixo, você encontrará todas as suas lojas favoritas.</p>
		
		<div class="sline"></div><br/>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<?php
					while ($row = mysql_fetch_array($result)) {		
			?>
				<tr class="odd">
					<td width="125" align="center" valign="middle">
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>" target="_blank"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></a></div>
					</td>
					<td align="left" valign="bottom">

						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="80%" align="left" valign="top">
									<a class="retailer_title" href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>" target="_blank"><?php echo $row['title']; ?></a>
								</td>
								<td nowrap="nowrap" width="20%" align="right" valign="top">
									<span class="cashback"><?php echo DisplayCashback($row['cashback']); ?> Cashback</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" valign="middle" align="left"><p class="retailer_description"><?php echo $row['description']; ?>&nbsp;</p></td>
							</tr>
							<tr>
								<td valign="middle" align="left">
								<?php if ($row['conditions'] != "") { ?>
									<div class="cashbackengine_tooltip">
										<a class="conditions" href="#">Condições</a> <span class="tooltip"><?php echo $row['conditions']; ?></span>
									</div>
								<?php } ?>
								</td>
								<td valign="middle" align="right">
									<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank">Ir à Loja</a>
								</td>
							</tr>
						</table>

					</td>
					<td width="20" align="center" valign="middle">
						 <a href="#" onclick="if (confirm('Are You sure You realy want to delete this retailer from your favorites?') )location.href='/myfavorites.php?act=del&rid=<?php echo $row['retailer_id']; ?>'" title="Delete"><img src="images/delete.gif" border="0" alt="Delete" /></a>
					</td>
				</tr>
			<?php } ?>
			  </tr>
           </table>

    <?php }else{ ?>
			<p align="center">Você não tem lojas favoritas no momento.<br/><br/><a class="button" href="/retailers.php">Continue comprando &raquo;</a></p>
     <?php } ?>


<!-- <?php //require_once("inc/footer.inc.php"); ?> -->

<footer>
    <div class="container">
                <div class="row nav2 social">
                    <div class="col col-sm-12">
                        <div class="row">
                            <div class="col col-sm-4">
                                <div class="col col-sm-4 menu-col">

                                        <a href="#" class="textsocial"> <i class="fa fa-facebook"></i> </a>

                                </div>
                                <div class="col col-sm-8 menu-col">

                                        <a href="#" class="textsocial"> <i class="fa fa-youtube"></i> </a>

                                </div>
                            </div>
                            <div class="col col-sm-4">
                                <div class="col col-sm-8 col-sm-offset-2">
                                    <div class="logo-footer">
                                        <a href="index.php">
                                            <img id="logo-footer" src="images/logo-footer.svg"  alt="logo-footer">
                                        </a>
                            
                                     </div>
                                </div>
                                <div class="col col-sm-2">
                                </div>
                            </div>
                            <div class="col col-sm-4">
                                <div class="col col-sm-4 col-sm-offset-4 menu-col right">
                                    <a href="#" class="textsocial"> <i class="fa fa-instagram"></i> </a>
                                </div>
                                <div class="col col-sm-4 menu-col right">
                                    <a href="#" class="textsocial"> <i class="fa fa-twitter"></i> </a>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
        </div>

    
               
</footer>

    </div>

</body>
</html>