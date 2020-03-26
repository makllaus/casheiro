<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/


	session_start();
	require_once("inc/config.inc.php");
	require_once("inc/pagination.inc.php");

	$results_per_page	= RESULTS_PER_PAGE;
	$cc = 0;

	////////////////// filter  //////////////////////
		if (isset($_GET['column']) && $_GET['column'] != "")
		{
			switch ($_GET['column'])
			{
				case "title": $rrorder = "title"; break;
				case "added": $rrorder = "added"; break;
				case "visits": $rrorder = "visits"; break;
				default: $rrorder = "title"; break;
			}
		}
		else
		{
			$rrorder = "title";
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
	//////////////////////////////////////////////////

	if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }

	$from = ($page-1)*$results_per_page;


	if (isset($_GET['action']) && $_GET['action'] == "search" && isset($_GET['searchtext']) && $_GET['searchtext'] != "")
	{
		$stext = strtolower(mysql_real_escape_string(getGetParameter('searchtext')));
		$stext = substr(trim($stext), 0, 100);

		$query = "SELECT * FROM cashbackengine_retailers WHERE (title LIKE '%".$stext."%' OR description LIKE '%".$stext."%') AND status='active' ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
		$total_result = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE (title LIKE '%".$stext."%' OR description LIKE '%".$stext."%') AND status='active' ORDER BY title ASC");
	}
	else
	{
		header ("Location: index.php");
		exit();
	}

	$total = mysql_num_rows($total_result);
	$result = smart_mysql_query($query);
	$total_on_page = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Search results for ".$stext;

	require_once ("inc/header.inc.php");

?>

	<h1>Buscar resultados para '<?php echo $stext; ?>'</h1>

<?php

	if ($total > 0) {

?>

	<div class="browse_top">
		<div class="sortby">
			<form action="search.php?<?php echo $_SERVER['QUERY_STRING']; ?>" id="form1" name="form1" method="get">
				<span>Ordenar por:</span>
				<select name="column" id="column" onChange="document.form1.submit()">
					<option value="title" <?php if ($_GET['column'] == "title") echo "selected"; ?>>Nome</option>
					<option value="visits" <?php if ($_GET['column'] == "visits") echo "selected"; ?>>Popularidade</option>
					<option value="added" <?php if ($_GET['column'] == "added") echo "selected"; ?>>Recentes</option>
				</select>
				<select name="order" id="order" onChange="document.form1.submit()">
					<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Decrescente</option>
					<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Crescente</option>
				</select>
				<input type="hidden" name="searchtext" value="<?php echo $stext; ?>" />
				<input type="hidden" name="page" value="<?php echo $page; ?>" />
				<input type="hidden" name="action" value="search" />
			</form>
		</div>
		<div class="results">
			Mostrando <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> de <?php echo $total; ?> resultados
		</div>
	</div>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<?php
					while ($row = mysql_fetch_array($result))
					{
						$cc++;
			?>

				<tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td width="125" align="center" valign="middle">
						<?php if ($row['featured'] == 1) { ?><span class="featured" alt="Featured Retailer" title="Featured Retailer"></span> <?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="" border="0" /></a></div>
						<?php echo GetStoreRating($row['retailer_id'], $show_start = 1); ?>
					</td>
					<td align="left" valign="bottom">

						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="65%" align="left" valign="top">
									<a class="retailer_title" href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><?php echo $row['title']; ?></a>
								</td>
								<td nowrap="nowrap" width="10%" align="right" valign="top">
									<a class="coupons" href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>#coupons" title="<?php echo $row['title']; ?> Coupons"><?php echo GetStoreCouponsTotal($row['retailer_id']); ?></a>
								</td>
								<td nowrap="nowrap" width="30%" align="right" valign="top">
									<span class="cashback"><?php echo DisplayCashback($row['cashback']); ?> Cashback</span>
								</td>
							</tr>
							<tr>
								<td colspan="3" valign="middle" align="left"><p class="retailer_description"><?php echo $row['description']; ?>&nbsp;</p></td>
							</tr>
							<tr>
								<td valign="middle" align="left">
								<?php if ($row['conditions'] != "") { ?>
									<div class="cashbackengine_tooltip">
										<a class="conditions" href="#">Condições</a> <span class="tooltip"><?php echo $row['conditions']; ?></span>
									</div>
								<?php } ?>
									<a class="favorites" href="#" onclick="if (confirm('Are You sure You realy want to add this retailer to your favorites?') )location.href='/myfavorites.php?act=add&rid=<?php echo $row['retailer_id']; ?>'">Add aos Favorites</a>
								</td>
								<td colspan="2" valign="middle" align="right">
									<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank">Ir à Loja</a>
								</td>
							</tr>
						</table>

					</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><div class="sline"></div></td>
				</tr>
			<?php } ?>
				<tr>
				  <td valign="middle" align="center" colspan="2">
					<?php echo ShowPagination("retailers",$results_per_page,"search.php?action=search&searchtext=$stext&column=$rrorder&order=$rorder&", "WHERE (title LIKE '%".$stext."%' OR description LIKE '%".$stext."%') AND status='active'"); ?>
				  </td>
				  </tr>
				  </table>

	<?php }else{ ?>
		<p align="center">Desculpe, nenhum resultado encontrado para a sua pesquisa!<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
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
