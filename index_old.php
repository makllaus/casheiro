<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	if (file_exists("./install.php"))
	{
		header ("Location: install.php");
		exit();
	}

	session_start();
	require_once("inc/config.inc.php");

	// save referral's link //
	if (isset($_GET['ref']) && is_numeric($_GET['ref']))
	{
		$ref_id = (int)$_GET['ref'];
		setReferal($ref_id);

		header("Location: index.php");
		exit();
	}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = SITE_HOME_TITLE;

	require_once("inc/header.inc.php");

?>

<?php

	$content = GetContent('home');

	echo $content['text'];

?>

		<?php
			// show featured retailers //
			$result_featured = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE featured='1' AND status='active' ORDER BY RAND() LIMIT 20");
			$total_fetaured = mysql_num_rows($result_featured);

			if ($total_fetaured > 0 ) { 
		?>
			<div style="clear: both;"></div>
			<h3 class="brd">DESTAQUE <span style="color: #d3900d">LOJAS</span></h3>
			<div class="featured_stores">
			<?php while ($row_featured = mysql_fetch_array($result_featured)) { $cc++; ?>
				<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row_featured['retailer_id']; ?>"><img src="<?php if (!stristr($row_featured['image'], 'http')) echo "/img/"; echo $row_featured['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row_featured['title']; ?>" title="<?php echo $row_featured['title']; ?>" border="0" /></a></div>
			<?php } ?>
			</div>
		<?php } // end featured retailers ?>

		<?php
				// Show 5 recent reviews //
				$reviews_query = "SELECT r.*, DATE_FORMAT(r.added, '%e/%m/%Y') AS review_date, u.user_id, u.username, u.fname, u.lname FROM cashbackengine_reviews r LEFT JOIN cashbackengine_users u ON r.user_id=u.user_id WHERE r.status='active' ORDER BY r.added DESC LIMIT 5";
				$reviews_result = smart_mysql_query($reviews_query);
				$reviews_total = mysql_num_rows($reviews_result);

				if ($reviews_total > 0) {
		?>
			<div style="clear: both"></div>
			<h3 class="brd">RECENTE <span style="color: #d3900d">AVALIAÇÕES</span></h3>
			<?php while ($reviews_row = mysql_fetch_array($reviews_result)) { ?>
            <div id="review">
                <span class="review-author"><?php echo $reviews_row['fname']; ?></span>
				<span class="review-date"><?php echo $reviews_row['review_date']; ?></span><br/><br/>
				<b><a href="/view_retailer.php?rid=<?php echo $reviews_row['retailer_id']; ?>"><?php echo GetStoreName($reviews_row['retailer_id']); ?></a></b><br/>
				<img src="/images/icons/rating-<?php echo $reviews_row['rating']; ?>.gif" />&nbsp;
				<span class="review-title"><?php echo $reviews_row['review_title']; ?></span><br/>
				<div class="review-text"><?php echo $reviews_row['review']; ?></div>
                <div style="clear: both;"></div>
            </div>
			<?php } ?>
			<div style="clear: both"></div>

		<?php } ?>


<?php require_once("inc/footer.inc.php"); ?>