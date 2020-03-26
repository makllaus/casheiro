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

	$results_per_page = RESULTS_PER_PAGE;
	$cc = 0;


	function getCategory($category_id, $description = 0)
	{
		if (isset($category_id) && is_numeric($category_id) && $category_id != 0)
		{
			$query = "SELECT name, description FROM cashbackengine_categories WHERE category_id='".(int)$category_id."'";
			$result = smart_mysql_query($query);
			if (mysql_num_rows($result) > 0)
			{
				$row = mysql_fetch_array($result);
				if ($description == 1) return $row['description']; else return $row['name'];
			}else
			{
				return "Categoria não encontrada";
			}
		}
		else
		{
			if ($description != 1) return "Lojas";
		}
	}

	////////////////// filter  //////////////////////
		if (isset($_GET['column']) && $_GET['column'] != "")
		{
			switch ($_GET['column'])
			{
				case "title": $rrorder = "title"; break;
				case "added": $rrorder = "added"; break;
				case "visits": $rrorder = "visits"; break;
				case "cashback": $rrorder = "cashback"; break;
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
	$where = "";

	if (isset($_GET['cat']) && is_numeric($_GET['cat']) && $_GET['cat'] > 0)
	{
		$cat_id = (int)$_GET['cat'];
		
		unset($retailers_per_category);
		$retailers_per_category = array();
		$retailers_per_category[] = "111111111111111111111";

		$sql_retailers_per_category = smart_mysql_query("SELECT retailer_id FROM cashbackengine_retailer_to_category WHERE category_id='$cat_id'");
		while ($row_retailers_per_category = mysql_fetch_array($sql_retailers_per_category))
		{
			$retailers_per_category[] = $row_retailers_per_category['retailer_id'];
		}

		$where .= "retailer_id IN (".implode(",",$retailers_per_category).") AND";
	}
	
	if (isset($_GET['letter']) && in_array($_GET['letter'], $alphabet))
	{
		$ltr = mysql_real_escape_string(getGetParameter('letter'));
		
		if ($ltr == "0-9")
		{
			$where .= " title REGEXP '^[0-9]' AND";
		}
		else
		{
			$ltr = substr($ltr, 0, 1);
			$where .= " UPPER(title) LIKE '$ltr%' AND";
		}

		$totitle = " - $ltr";
	}

	$where .= " status='active'";
	
	if ($rrorder == "cashback")
		$query = "SELECT * FROM cashbackengine_retailers WHERE $where ORDER BY ABS(cashback) $rorder LIMIT $from, $results_per_page";
	else
		$query = "SELECT * FROM cashbackengine_retailers WHERE $where ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
	
	$total_result = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE $where ORDER BY title ASC");
	$total = mysql_num_rows($total_result);

	$result = smart_mysql_query($query);
	$total_on_page = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = getCategory($_GET['cat']).$totitle;

	require_once ("inc/header.inc.php");

?>

	<h1><?php echo getCategory($_GET['cat']).$totitle; ?></h1>

	<p class="category_description"><?php echo getCategory($_GET['cat'], 1); ?></p>

	<div id="alphabet">
		<ul>
			<li><a href="/retailers.php">TODAS</a></li>
			<?php

				$numLetters = count($alphabet);
				$i = 0;

				foreach ($alphabet as $letter)
				{
					$i++;
					if ($i == $numLetters) $lilast = ' class="last"'; else $lilast = '';

					if (isset($ltr) && $ltr == $letter) $liclass = ' class="active"'; else $liclass = '';
			
					if (isset($cat_id) && is_numeric($cat_id))
						echo "<li".$lilast."><a href=\"/retailers.php?cat=$cat_id&letter=$letter\" $liclass>$letter</a></li>";
					else
						echo "<li".$lilast."><a href=\"/retailers.php?letter=$letter\" $liclass>$letter</a></li>";
				}

			?>
		</ul>
	</div>


<?php

	if ($total > 0) {

?>

	<?php
	
		// show 12 random featured retailers //
		$result_featured = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE featured='1' AND status='active' ORDER BY RAND() LIMIT 12");
		$total_fetaured = mysql_num_rows($result_featured);

		if ($total_fetaured > 0) { 
	?>
		<h3 class="featured_title">Lojas de destaque</h3>
		<div id="scrollstores">
		<?php while ($row_featured = mysql_fetch_array($result_featured)) { $cc++; ?>
		<div>
			<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row_featured['retailer_id']; ?>"><img src="<?php if (!stristr($row_featured['image'], 'http')) echo "/img/"; echo $row_featured['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row_featured['title']; ?>" title="<?php echo $row_featured['title']; ?>" border="0" /></a></div>
			<span class="thumbnail-text">
				<?php echo DisplayCashback($row_featured['cashback']); ?> Cashback
			</span>
		</div>
		<?php } ?>
		</div>
		<div style="clear: both"></div>
	<?php } // end featured retailers ?>


	<div class="browse_top">
		<div class="sortby">
			<form action="" id="form1" name="form1" method="get">
				<span>Ordenar por:</span>
				<select name="column" id="column" onChange="document.form1.submit()">
					<option value="title" <?php if ($_GET['column'] == "title") echo "selected"; ?>>Name</option>
					<option value="visits" <?php if ($_GET['column'] == "visits") echo "selected"; ?>>Popularidade</option>
					<option value="added" <?php if ($_GET['column'] == "added") echo "selected"; ?>>Recentes</option>
					<option value="cashback" <?php if ($_GET['column'] == "cashback") echo "selected"; ?>>Cashback</option>
				</select>
				<select name="order" id="order" onChange="document.form1.submit()">
					<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Decrescente</option>
					<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Crescente</option>
				</select>
				<?php if ($cat_id) { ?><input type="hidden" name="cat" value="<?php echo $cat_id; ?>" /><?php } ?>
				<?php if ($ltr) { ?><input type="hidden" name="letter" value="<?php echo $ltr; ?>" /><?php } ?>
				<input type="hidden" name="page" value="<?php echo $page; ?>" />
			</form>
		</div>
		<div class="results">
			Mostrando <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> de <?php echo $total; ?>
		</div>
	</div>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<?php
					while ($row = mysql_fetch_array($result)) { $cc++;
			?>
				<tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td width="125" align="center" valign="middle">
						<?php if ($row['featured'] == 1) { ?><span class="featured" alt="Featured Retailer" title="Featured Retailer"></span><?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></a></div>
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
									<a href="http://www.facebook.com/sharer.php?u=<?php echo SITE_URL; ?>view_retailer.php?rid=<?php echo $row['retailer_id']; ?>" target="_blank" title="Share on Facebook"><img src="/images/icon_facebook.png"  alt="Share on Facebook" align="absmiddle" /></a> &nbsp;
									<a href="http://twitter.com/home?status=Earn Cashback <?php echo SITE_URL; ?>view_retailer.php?rid=<?php echo $row['retailer_id']; ?>" target="_blank" title="Share on Twitter"><img src="/images/icon_twitter.png" alt="Share on Twitter" align="absmiddle" /></a>
									&nbsp;&nbsp;
									<?php if ($row['conditions'] != "") { ?>
										<div class="cashbackengine_tooltip">
											<a class="conditions" href="#">Condições</a> <span class="tooltip"><?php echo $row['conditions']; ?></span>
										</div>
									<?php } ?>
									<a class="favorites" href="#" onclick="if (confirm('Tem certeza que você realmente quer adicionar esta loja aos seus favoritos?') )location.href='/myfavorites.php?act=add&rid=<?php echo $row['retailer_id']; ?>'">Add aos Favoritos</a>
								</td>
								<td colspan="2" valign="middle" align="right">
									<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank">Ir à Loja</a>
								</td>
							</tr>
						</table>
	
					</td>
				</tr>
				

			<?php } ?>
				<tr>
				  <td valign="middle" align="center" colspan="2">
					<?php
							$params = "";
							if (isset($cat_id) && $cat_id > 0) { $params = "cat=$cat_id&"; }
							if (isset($ltr) && $ltr != "") { $params = "letter=$ltr&"; }

							echo ShowPagination("retailers",$results_per_page,"retailers.php?".$params."column=$rrorder&order=$rorder&","WHERE ".$where);
					?>
				  </td>
				</tr>
				</table>

	<?php }else{ ?>
		<p align="center">Não há lojas para essa categoria!<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
	<?php } ?>

<!-- <?php //require_once ("inc/footer.inc.php"); ?> -->

</div>
   
<footer class="footer">

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