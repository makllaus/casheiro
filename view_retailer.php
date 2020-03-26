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


	if (isset($_GET['rid']) && is_numeric($_GET['rid']))
	{
		$retailer_id = (int)$_GET['rid'];
	}
	else
	{		
		header ("Location: index.php");
		exit();
	}

	$query = "SELECT *, DATE_FORMAT(added, '%e %b %Y') AS date_added FROM cashbackengine_retailers WHERE retailer_id='$retailer_id' AND status='active' LIMIT 1";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

	if ($total > 0)
	{
		$row = mysql_fetch_array($result);

		$cashback = DisplayCashback($row['cashback']);
		$ptitle = $row['title'].". Ganhe ".$cashback." CashBack!";


		//// ADD REVIEW //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		if (isset($_POST['action']) && $_POST['action'] == "add_review" && isset($_SESSION['userid']) && is_numeric($_SESSION['userid']))
		{
			$userid			= (int)$_SESSION['userid'];
			$retailer_id	= (int)getPostParameter('retailer_id');
			$rating			= (int)getPostParameter('rating');
			$review_title	= mysql_real_escape_string(getPostParameter('review_title'));
			$review			= mysql_real_escape_string(nl2br(trim(getPostParameter('review'))));
			$review			= ucfirst(strtolower($review));

			unset($errs);
			$errs = array();

			if (!($userid && $retailer_id && $rating && $review_title && $review))
			{
				$errs[] = "Por favor, selecione classificação e escrever seu comentário";
			}
			else
			{
				$number_lines = count(explode("<br />", $review));
				
				if (strlen($review) > MAX_REVIEW_LENGTH)
					$errs[] = "O máximo de palavras na avaliação é".MAX_REVIEW_LENGTH." caracteres";
				else if ($number_lines > 5)
					$errs[] = "Desculpe, muitas quebras de linha na avaliação";
				else if (stristr($review, 'http'))
					$errs[] = "Você não pode postar links na avaliação";
			}

			if (count($errs) == 0)
			{
				$review = substr($review, 0, MAX_REVIEW_LENGTH);
				$check_review = mysql_num_rows(smart_mysql_query("SELECT * FROM cashbackengine_reviews WHERE retailer_id='$retailer_id' AND user_id='$userid'"));

				if ($check_review == 0)
				{
					(REVIEWS_APPROVE == 1) ? $status = "pending" : $status = "active";
					$review_query = "INSERT INTO cashbackengine_reviews SET retailer_id='$retailer_id', rating='$rating', user_id='$userid', review_title='$review_title', review='$review', status='$status', added=NOW()";
					$review_result = smart_mysql_query($review_query);
					$review_added = 1;
				}
				else
				{
					$errormsg = "Você só pode enviar um comentário para uma loja";
				}

				unset($_POST['review']);
			}
			else
			{
				$errormsg = "";
				foreach ($errs as $errorname)
					$errormsg .= "&#155; ".$errorname."<br/>";
			}
		}
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		$ptitle = "Loja não encontrada";
	}


	///////////////  Page config  ///////////////
	$PAGE_TITLE = $ptitle;

	require_once ("inc/header.inc.php");

?>

	<h1><?php echo $ptitle; ?></h1>

	<?php

		if ($total > 0) {

	?>
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr class="odd">
					<td width="125" align="center" valign="middle">
						<?php if ($row['featured'] == 1) { ?><span class="featured" alt="Featured Retailer" title="Featured Retailer"></span><?php } ?>
						<div class="imagebox"><a href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></a></div>
						<?php echo GetStoreRating($row['retailer_id'], $show_start = 1); ?>
					</td>
					<td align="left" valign="bottom">
	
						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="80%" align="left" valign="top">
									<a class="stitle" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank"><?php echo $row['title']; ?></a>
								</td>
								<td nowrap="nowrap" width="20%" align="right" valign="middle">
									<span class="cashback"><?php echo $cashback; ?> Cashback</span>
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
									<a class="favorites" href="#" onclick="if (confirm('Tem certeza que vai adicionar esta loja aos seus favoritos?') )location.href='/myfavorites.php?act=add&rid=<?php echo $row['retailer_id']; ?>'">Add aos Favoritos</a>
									<a class="report" href="/report_retailer.php?id=<?php echo $row['retailer_id']; ?>">Reportar</a>
								</td>
								<td valign="middle" align="right">
									<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank">Ir à loja</a>
								</td>
							</tr>
						</table>
	
					</td>
				</tr>
				<tr>
				<td style="border-right: 1px dotted #D7D7D7; border-bottom: 1px dotted #D7D7D7;" bgcolor="#F7F7F7" nowrap="nowrap">
					<?php if (SHOW_RETAILER_STATS == 1) { ?>
					<div class="retailer_statistics">
						<center><b>Estatísticas</b></center>
						<label>Cupons:</label> <?php echo GetStoreCouponsTotal($row['retailer_id']); ?><br/>
						<label>Avaliações:</label> <?php echo GetStoreReviewsTotal($row['retailer_id']); ?><br/>
						<label>Favoritos:</label> <?php echo GetFavoritesTotal($row['retailer_id']); ?><br/>
						<label>Inclusão:</label> <?php echo $row['date_added']; ?><br/>
					 </div>
					 <?php } ?>
				</td>				
				<td style="border-bottom: 1px dotted #D7D7D7;" bgcolor="#F7F7F7" align="center">
							<div style="display: block; width: 300px;">    	
								<!-- AddThis Button BEGIN -->
								<div class="addthis_toolbox addthis_default_style">
									<a class="addthis_button_facebook_like"></a>
									<a class="addthis_button_facebook"></a>
									<a class="addthis_button_twitter"></a>
									<a class="addthis_button_digg"></a>
									<a class="addthis_button_linkedin"></a>
									<a class="addthis_button_blogger"></a>
									<a class="addthis_button_email"></a>
									<a class="addthis_button_favorites"></a>
									<span class="addthis_separator"> ~ </span>
									<a href="http://addthis.com/bookmark.php?v=250" class="addthis_button_expanded at300m">Mais</a>
								</div>
								<script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#username=YOUR-ACCOUNT-ID"></script>
								<!-- AddThis Button END -->
							</div>
						<font color="#808080">Compartilhe:</font>
						<input type="text" class="share_textbox" size="53" READONLY onfocus="this.select();" onclick="this.focus();this.select();" value="<?php echo SITE_URL."view_retailer.php?rid=".$row['retailer_id']; ?>" />
				</td>
				</tr>
			</table>


		<?php
				// start coupons //
				$query_coupons = "SELECT *, DATE_FORMAT(end_date, '%d %b %Y') AS coupon_end_date, UNIX_TIMESTAMP(end_date) - UNIX_TIMESTAMP() AS time_left FROM cashbackengine_coupons WHERE retailer_id='$retailer_id' AND (start_date<=NOW() AND (end_date='0000-00-00 00:00:00' OR end_date > NOW())) AND status='active' ORDER BY added DESC";
				$result_coupons = smart_mysql_query($query_coupons);
				$total_coupons = mysql_num_rows($result_coupons);

				if ($total_coupons > 0)
				{
		?>
			<a name="coupons"></a>
			<h3><?php echo $row['title']; ?> Cupom</h3>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<?php while ($row_coupons = mysql_fetch_array($result_coupons)) { ?>
				<tr>
					<td class="td_coupon" align="left" valign="top">
						<?php if ($row_coupons['exclusive'] == 1) { ?><span class="exclusive" alt="Exclusive Coupon" title="Exclusive Coupon">Cupom exclusivo</span><br/><br/><?php } ?>
						<a href="/go2store.php?id=<?php echo $row['retailer_id']; ?>&c=<?php echo $row_coupons['coupon_id']; ?>" target="_blank"><b><?php echo $row_coupons['title']; ?></b></a>
						<?php if ($row_coupons['code'] != "") { ?>
						<div style="float: right;"><div class="coupon_code2" id="coupon_code2"><?php echo $row_coupons['code']; ?></div></div>
						<br/><br/>
						<small>Clique duas vezes no código, aperte Ctrl + C para copiar, e Compre agora.</small>
						<br/><br/>
						<?php } ?>
						<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>&c=<?php echo $row_coupons['coupon_id']; ?>" target="_blank">Comprar Agora</a>
						<?php if ($row_coupons['end_date'] != "0000-00-00 00:00:00") { ?>
							<span class="expires">Expira: <?php echo $row_coupons['coupon_end_date']; ?></span><br/>
							<span class="time_left">Resta: <?php echo GetTimeLeft($row_coupons['time_left']).""; ?></span>
						<?php } ?>
						<?php if ($row_coupons['description'] != "") { ?>
							<p><?php echo $row_coupons['description']; ?></p>
						<?php } ?>
					</td>
				</tr>
				<?php } ?>
			</table>
		
		<?php } // end coupons // ?>


		
		<?php
				// show reviews //
				$results_per_page = REVIEWS_PER_PAGE;

				if (isset($_GET['cpage']) && is_numeric($_GET['cpage']) && $_GET['cpage'] > 0) { $page = (int)$_GET['cpage']; } else { $page = 1; }
				$from = ($page-1)*$results_per_page;

				$reviews_query = "SELECT r.*, DATE_FORMAT(r.added, '%e/%m/%Y') AS review_date, u.user_id, u.username, u.fname, u.lname FROM cashbackengine_reviews r LEFT JOIN cashbackengine_users u ON r.user_id=u.user_id WHERE r.retailer_id='$retailer_id' AND r.status='active' ORDER BY r.added DESC LIMIT $from, $results_per_page";
				$reviews_result = smart_mysql_query($reviews_query);
				$reviews_total = mysql_num_rows(smart_mysql_query("SELECT * FROM cashbackengine_reviews WHERE retailer_id='$retailer_id' AND status='active'"));
		?>

		<div id="add_review_link"><a id="add-review" href="javascript:void(0);">Escreva</a></div>
		<a name="reviews"></a>
		<h3 class="store_reviews"><?php echo $row['title']; ?> Avaliações <?php echo ($reviews_total > 0) ? "($reviews_total)" : ""; ?></h3>

		<script>
		$("#add-review").click(function () {
			$("#review-form").toggle("slow");
		});
		</script>

		<div id="review-form" class="review-form" style="<?php if (!(isset($_POST['action']) && $_POST['action'] == "add_review")) { ?>display: none;<?php } ?>">
			<?php if (isset($errormsg) && $errormsg != "") { ?>
				<div style="width: 94%;" class="error_msg"><?php echo $errormsg; ?></div>
			<?php } ?>
			<?php if (REVIEWS_APPROVE == 1 && $review_added == 1) { ?>
				<div style="width: 94%;" class="success_msg">Obrigado. Seu comentário foi enviado e está aguardando aprovação.</div>
			<?php } ?>
			<?php if (isset($_SESSION['userid']) && is_numeric($_SESSION['userid'])) { ?>
				<form method="post" action="#reviews">
					<select name="rating">
						<option value="">- classificação -</option>
						<option value="5" <?php if ($rating == 5) echo "selected"; ?>>&#9733;&#9733;&#9733;&#9733;&#9733; - Excelente</option>
						<option value="4" <?php if ($rating == 4) echo "selected"; ?>>&#9733;&#9733;&#9733;&#9733; - Muito bom</option>
						<option value="3" <?php if ($rating == 3) echo "selected"; ?>>&#9733;&#9733;&#9733; - Bom</option>
						<option value="2" <?php if ($rating == 2) echo "selected"; ?>>&#9733;&#9733; - Razoável</option>
						<option value="1" <?php if ($rating == 1) echo "selected"; ?>>&#9733; - Ruim</option>					
					</select><br/>
					Título do comentário<br/>
					<input type="text" name="review_title" id="review_title" value="<?php echo getPostParameter('review_title'); ?>" size="47" class="textbox" /><br/>
					Sua avaliação<br/>
					<textarea id="review" name="review" cols="45" rows="5" class="textbox2"><?php echo getPostParameter('review'); ?></textarea><br/>
					<input type="hidden" id="retailer_id" name="retailer_id" value="<?php echo $retailer_id; ?>" />
					<input type="hidden" name="action" value="add_review" />
					<input type="submit" class="submit" value="Enviar" />
				</form>
			<?php }else{ ?>
				Você deve estar <a href="/login.php">logado</a> para enviar uma crítica.
			<?php } ?>
		</div>


		<div style="clear: both"></div>
		<?php if ($reviews_total > 0) { ?>

			<?php while ($reviews_row = mysql_fetch_array($reviews_result)) { ?>
            <div id="review">
                <span class="review-author"><?php echo $reviews_row['fname']; ?></span>
				<span class="review-date"><?php echo $reviews_row['review_date']; ?></span><br/><br/>
				<img src="/images/icons/rating-<?php echo $reviews_row['rating']; ?>.gif" />&nbsp;
				<span class="review-title"><?php echo $reviews_row['review_title']; ?></span><br/>
				<div class="review-text"><?php echo $reviews_row['review']; ?></div>
                <div style="clear: both"></div>
            </div>
			<?php } ?>
		
			<?php echo ShowPagination("reviews",REVIEWS_PER_PAGE,"?id=$retailer_id&","WHERE retailer_id='$retailer_id' AND status='active'"); ?>
		
		<?php }else{ ?>
				Ainda não há comentários. Seja o primeiro a comentar!</a>
		<?php } ?>



		<?php
				// start related retailers //
				$query_like = "SELECT * FROM cashbackengine_retailers WHERE retailer_id<>'$retailer_id' AND status='active' ORDER BY RAND() LIMIT 5";
				$result_like = smart_mysql_query($query_like);
				$total_like = mysql_num_rows($result_like);

				if ($total_like > 0)
				{
		?>
			<div style="clear: both"></div>

			<h3>você pode gostar também</h3>
			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr>
				<?php while ($row_like = mysql_fetch_array($result_like)) { ?>
					<td class="like" width="125" align="center" valign="middle">
						<?php echo $row_like['title']; ?><br/>
						<a href="/view_retailer.php?rid=<?php echo $row_like['retailer_id']; ?>"><img src="<?php if (!stristr($row_like['image'], 'http')) echo "/img/"; echo $row_like['image']; ?>" width="<?php echo IMAGE_WIDTH/2; ?>" height="<?php echo IMAGE_HEIGHT/2; ?>" alt="<?php echo $row_like['title']; ?>" title="<?php echo $row_like['title']; ?>" border="0" style="margin:5px;" class="imgs" /></a><br/>
						<span class="cashback"><?php echo DisplayCashback($row_like['cashback']); ?></span> Cashback
					</td>
				<?php } ?>
				</tr>
			</table>
			<br/><div class="sline"></div>
		
		<?php } // end related retailers // ?>


	<?php }else{ ?>
		<p align="center">Desculpe, nenhuma loja encontrada!</p>
		<p align="center"><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
	<?php } ?>


<!-- <?php //require_once ("inc/footer.inc.php"); ?> -->

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