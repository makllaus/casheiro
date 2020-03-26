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


	////////////////// filter  //////////////////////
		if (isset($_GET['column']) && $_GET['column'] != "")
		{
			switch ($_GET['column'])
			{
				case "added": $rrorder = "c.added"; break;
				case "visits": $rrorder = "c.visits"; break;
				case "retailer_id": $rrorder = "c.retailer_id"; break;
				case "end_date": $rrorder = "c.end_date"; break;
				default: $rrorder = "c.added"; break;
			}
		}
		else
		{
			$rrorder = "c.added";
		}

		if (isset($_GET['order']) && $_GET['order'] != "")
		{
			switch ($_GET['order'])
			{
				case "asc": $rorder = "asc"; break;
				case "desc": $rorder = "desc"; break;
				default: $rorder = "desc"; break;
			}
		}
		else
		{
			$rorder = "desc";
		}
	//////////////////////////////////////////////////

	if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
	
	$from = ($page-1)*$results_per_page;

	$where = " (start_date<=NOW() AND (end_date='0000-00-00 00:00:00' OR end_date > NOW())) AND status='active'";

	$query = "SELECT c.*, DATE_FORMAT(c.end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(c.end_date) - UNIX_TIMESTAMP() AS time_left, r.image, r.title FROM cashbackengine_coupons c LEFT JOIN cashbackengine_retailers r ON c.retailer_id=r.retailer_id WHERE (c.start_date<=NOW() AND (c.end_date='0000-00-00 00:00:00' OR c.end_date > NOW())) AND c.status='active' AND r.status='active' ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
	$total_result = smart_mysql_query("SELECT * FROM cashbackengine_coupons WHERE $where ORDER BY title ASC");
	$total = mysql_num_rows($total_result);

	$result = smart_mysql_query($query);
	$total_on_page = mysql_num_rows($result);

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Coupons";

	require_once ("inc/header.inc.php");

?>

	<h1>Cupons</h1>

		<div id="tabs_container">
			<ul id="tabs">
				<li class="active"><a href="#all"><span>TODOS</span></a></li>
				<li><a href="#top-coupons"><span>MAIS POPULAR</span></a></li>
				<li><a href="#exclusive"><span>EXCLUSIVO</span></a></li>
				<li><a href="#latest"><span>MAIS RECENTES</span></a></li>
				<li><a href="#expiring"><span>EXPIRA EM BREVE</span></a></li>
			</ul>
		</div>


		<div id="all" class="tab_content">
		<?php

		if ($total > 0) {

		?>
		<div class="browse_top">
			<div class="sortby">
				<form action="" id="form1" name="form1" method="get">
					<span>Ordenar por:</span>
					<select name="column" id="column" onChange="document.form1.submit()">
						<option value="added" <?php if ($_GET['column'] == "added") echo "selected"; ?>>Mais novo</option>
						<option value="visits" <?php if ($_GET['column'] == "visits") echo "selected"; ?>>Popularidade</option>
						<option value="retailer_id" <?php if ($_GET['column'] == "retailer_id") echo "selected"; ?>>Loja</option>
						<option value="end_date" <?php if ($_GET['column'] == "end_date") echo "selected"; ?>>Data final</option>
					</select>
					<select name="order" id="order" onChange="document.form1.submit()">
						<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Decrescente</option>
						<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Crescente</option>
					</select>
					<?php if ($cat_id) { ?><input type="hidden" name="cat" value="<?php echo $cat_id; ?>" /><?php } ?>
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
				</form>
			</div>
			<div class="results">
				Mostrando <?php echo ($from + 1); ?> - <?php echo min($from + $total_on_page, $total); ?> de <?php echo $total; ?>
			</div>
		</div>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($row = mysql_fetch_array($result)) { ?>
				<tr>
					<td class="td_coupon" width="125" align="center" valign="top">
						<?php if ($row['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon">Cupom exclusivo!</span><?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></a></div>
						<br/><a class="more" href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>#coupons">Veja todos os cupons</a>
					</td>
					<td class="td_coupon" align="left" valign="top">
						<a class="retailer_title" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>&c=<?php echo $row['coupon_id']; ?>" target="_blank"><b><?php echo $row['title']; ?></b></a><br/><br/>
						<?php if ($row['code'] != "") { ?>
							<b>Código do cupom</b>: <span class="coupon_code"><?php echo $row['code']; ?></span><br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>&c=<?php echo $row['coupon_id']; ?>" target="_blank">Compre agora</a>
						<?php if ($row['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $row['coupon_end_date']; ?></span><br/>
							<span class="time_left">Temo restante: <?php echo GetTimeLeft($row['time_left']).""; ?></span>
						<?php } ?>
						<?php if ($row['description'] != "") { ?>
							<p><?php echo $row['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				<tr>
				  <td valign="middle" align="center" colspan="2">
					<?php
							$params = "";
							if (isset($cat_id) && $cat_id > 0) { $params = "cat=$cat_id&"; }

							echo ShowPagination("coupons",$results_per_page,"coupons.php?".$params."column=$rrorder&order=$rorder&","WHERE ".$where);
					?>
				  </td>
			  </tr>
			</table>

			<?php }else{ ?>
				<p align="center">Ainda não há cupons.</p>
			<?php } ?>

		</div>


		<div id="top-coupons" class="tab_content">
		<?php
				// show exclusive coupons //
				$top_query = "SELECT c.*, DATE_FORMAT(c.end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(c.end_date) - UNIX_TIMESTAMP() AS time_left, r.image, r.title FROM cashbackengine_coupons c LEFT JOIN cashbackengine_retailers r ON c.retailer_id=r.retailer_id WHERE (c.start_date<=NOW() AND (c.end_date='0000-00-00 00:00:00' OR c.end_date > NOW())) AND c.status='active' AND r.status='active' ORDER BY c.visits DESC LIMIT 20";
				$top_result = smart_mysql_query($top_query);
				$top_total = mysql_num_rows($top_result);

				if ($top_total > 0)
				{
			?>
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($top_row = mysql_fetch_array($top_result)) { ?>
				<tr>
					<td class="td_coupon" width="125" align="center" valign="top">
						<?php if ($top_row['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon">Cupom exclusivo!</span><?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $top_row['retailer_id']; ?>"><img src="<?php if (!stristr($top_row['image'], 'http')) echo "/img/"; echo $top_row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $top_row['title']; ?>" title="<?php echo $top_row['title']; ?>" border="0" /></a></div>
						<br/><a class="more" href="/view_retailer.php?rid=<?php echo $top_row['retailer_id']; ?>#coupons">Veja todos os cupons</a>
					</td>
					<td class="td_coupon" align="left" valign="top">
						<a class="retailer_title" href="/go2store.php?id=<?php echo $top_row['retailer_id']; ?>&c=<?php echo $top_row['coupon_id']; ?>" target="_blank"><b><?php echo $top_row['title']; ?></b></a><br/><br/>
						<?php if ($top_row['code'] != "") { ?>
							<b>Código do cupom</b>: <span class="coupon_code"><?php echo $top_row['code']; ?></span><br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $top_row['retailer_id']; ?>&c=<?php echo $top_row['coupon_id']; ?>" target="_blank">Compre agora</a>
						<?php if ($top_row['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $top_row['coupon_end_date']; ?></span><br/>
							<span class="time_left">Tempo restante: <?php echo GetTimeLeft($top_row['time_left']).""; ?></span>
						<?php } ?>
						<?php if ($top_row['description'] != "") { ?>
							<p><?php echo $top_row['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				</table>
		<?php
				}
				else
				{
					echo "<p align='center'>Não há cupons no momento.</p>";
				}
		?>
		</div>


		<div id="latest" class="tab_content">
		<?php
				// show latest coupons //
				$last_query = "SELECT c.*, DATE_FORMAT(c.end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(c.end_date) - UNIX_TIMESTAMP() AS time_left, r.image, r.title FROM cashbackengine_coupons c LEFT JOIN cashbackengine_retailers r ON c.retailer_id=r.retailer_id WHERE (c.start_date<=NOW() AND (c.end_date='0000-00-00 00:00:00' OR c.end_date > NOW())) AND c.status='active' AND r.status='active' ORDER BY c.added DESC LIMIT 20";
				$last_result = smart_mysql_query($last_query);
				$last_total = mysql_num_rows($last_result);

				if ($last_total > 0)
				{
			?>
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($last_row = mysql_fetch_array($last_result)) { ?>
				<tr>
					<td class="td_coupon" width="125" align="center" valign="top">
						<?php if ($last_row['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon">Cupom Exclusivo!</span><?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $last_row['retailer_id']; ?>"><img src="<?php if (!stristr($last_row['image'], 'http')) echo "/img/"; echo $last_row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $last_row['title']; ?>" title="<?php echo $last_row['title']; ?>" border="0" /></a></div>
						<br/><a class="more" href="/view_retailer.php?rid=<?php echo $last_row['retailer_id']; ?>#coupons">Veja todos os cupons</a>
					</td>
					<td class="td_coupon" align="left" valign="top">
						<a class="retailer_title" href="/go2store.php?id=<?php echo $last_row['retailer_id']; ?>&c=<?php echo $last_row['coupon_id']; ?>" target="_blank"><b><?php echo $last_row['title']; ?></b></a><br/><br/>
						<?php if ($last_row['code'] != "") { ?>
							<b>Código do cupom</b>: <span class="coupon_code"><?php echo $last_row['code']; ?></span><br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $last_row['retailer_id']; ?>&c=<?php echo $last_row['coupon_id']; ?>" target="_blank">Compre agora</a>
						<?php if ($last_row['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $last_row['coupon_end_date']; ?></span><br/>
							<span class="time_left">Tempo restante: <?php echo GetTimeLeft($last_row['time_left']).""; ?></span>
						<?php } ?>
						<?php if ($last_row['description'] != "") { ?>
							<p><?php echo $last_row['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				</table>
		<?php
				}
				else
				{
					echo "<p align='center'>Não há cupons no momento.</p>";
				}
		?>
		</div>


		<div id="exclusive" class="tab_content">
		<?php
				// show exclusive coupons //
				$ex_query = "SELECT c.*, DATE_FORMAT(c.end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(c.end_date) - UNIX_TIMESTAMP() AS time_left, r.image, r.title FROM cashbackengine_coupons c LEFT JOIN cashbackengine_retailers r ON c.retailer_id=r.retailer_id WHERE (c.start_date<=NOW() AND (c.end_date='0000-00-00 00:00:00' OR c.end_date > NOW())) AND c.exclusive='1' AND c.status='active' AND r.status='active' ORDER BY c.added DESC LIMIT 20";
				$ex_result = smart_mysql_query($ex_query);
				$ex_total = mysql_num_rows($ex_result);

				if ($ex_total > 0)
				{
			?>
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($ex_row = mysql_fetch_array($ex_result)) { ?>
				<tr>
					<td class="td_coupon" width="125" align="center" valign="top">
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $ex_row['retailer_id']; ?>"><img src="<?php if (!stristr($ex_row['image'], 'http')) echo "/img/"; echo $ex_row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $ex_row['title']; ?>" title="<?php echo $ex_row['title']; ?>" border="0" /></a></div>
						<br/><a class="more" href="/view_retailer.php?rid=<?php echo $ex_row['retailer_id']; ?>#coupons">Veja todos os cupons</a>
					</td>
					<td class="td_coupon" align="left" valign="top">
						<a class="retailer_title" href="/go2store.php?id=<?php echo $ex_row['retailer_id']; ?>&c=<?php echo $ex_row['coupon_id']; ?>" target="_blank"><b><?php echo $ex_row['title']; ?></b></a><br/><br/>
						<?php if ($ex_row['code'] != "") { ?>
							<b>Código do cupom</b>: <span class="coupon_code"><?php echo $ex_row['code']; ?></span><br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $ex_row['retailer_id']; ?>&c=<?php echo $ex_row['coupon_id']; ?>" target="_blank">Compre agora</a>
						<?php if ($ex_row['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $ex_row['coupon_end_date']; ?></span><br/>
							<span class="time_left">Tempo restante: <?php echo GetTimeLeft($ex_row['time_left']).""; ?></span>
						<?php } ?>
						<?php if ($ex_row['description'] != "") { ?>
							<p><?php echo $ex_row['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				</table>
		<?php
				}
				else
				{
					echo "<p align='center'>Não há cupons no momento.</p>";
				}
		?>
		</div>


		<div id="expiring" class="tab_content">
		<?php
				// show expires in 3 days coupons //
				$exp_query = "SELECT c.*, DATE_FORMAT(c.end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(c.end_date) - UNIX_TIMESTAMP() AS time_left, r.image, r.title FROM cashbackengine_coupons c LEFT JOIN cashbackengine_retailers r ON c.retailer_id=r.retailer_id WHERE (c.start_date<=NOW() AND (c.end_date='0000-00-00 00:00:00' OR c.end_date > NOW())) AND (end_date < end_date - INTERVAL 3 DAY) AND c.status='active' AND r.status='active' ORDER BY c.added DESC LIMIT 20";
				$exp_result = smart_mysql_query($exp_query);
				$exp_total = mysql_num_rows($exp_result);

				if ($exp_total > 0)
				{
			?>
				<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($exp_row = mysql_fetch_array($exp_result)) { ?>
				<tr>
					<td class="td_coupon" width="125" align="center" valign="top">
						<?php if ($exp_row['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon">Cupom Exclusivo!</span><?php } ?>
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $exp_row['retailer_id']; ?>"><img src="<?php if (!stristr($exp_row['image'], 'http')) echo "/img/"; echo $exp_row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $exp_row['title']; ?>" title="<?php echo $exp_row['title']; ?>" border="0" /></a></div>
						<br/><a class="more" href="/view_retailer.php?rid=<?php echo $exp_row['retailer_id']; ?>#coupons">Veja todos os cupons</a>
					</td>
					<td class="td_coupon" align="left" valign="top">
						<a class="retailer_title" href="/go2store.php?id=<?php echo $exp_row['retailer_id']; ?>&c=<?php echo $exp_row['coupon_id']; ?>" target="_blank"><b><?php echo $exp_row['title']; ?></b></a><br/><br/>
						<?php if ($exp_row['code'] != "") { ?>
							<b>Código do cupom</b>: <span class="coupon_code"><?php echo $exp_row['code']; ?></span><br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $exp_row['retailer_id']; ?>&c=<?php echo $exp_row['coupon_id']; ?>" target="_blank">Compre agora</a>
						<?php if ($exp_row['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $exp_row['coupon_end_date']; ?></span><br/>
							<span class="time_left">Tempo restante: <span style="color: #F9682A"><?php echo GetTimeLeft($exp_row['time_left']).""; ?></span></span>
						<?php } ?>
						<?php if ($exp_row['description'] != "") { ?>
							<p><?php echo $exp_row['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
				</table>
		<?php
				}
				else
				{
					echo "<p align='center'>Não há cupons no momento.</p>";
				}
		?>
		</div>


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