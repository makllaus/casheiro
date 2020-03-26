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


	$results_per_page = 10;
	$cc = 0;

	if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
	$from = ($page-1)*$results_per_page;
	
	$result = smart_mysql_query("SELECT *, DATE_FORMAT(added, '%M %e, %Y') AS news_date FROM cashbackengine_news WHERE status='active' ORDER BY added DESC LIMIT $from, $results_per_page");
	
	$total_result = smart_mysql_query("SELECT * FROM cashbackengine_news WHERE status='active' ORDER BY added DESC");
	$total = mysql_num_rows($total_result);
	$total_on_page = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Notícias";

	require_once ("inc/header.inc.php");

?>

	<h1>Notícias</h1>


<?php

	if ($total > 0) {

?>
		<?php while ($row = mysql_fetch_array($result)) { ?>
			<div class="news_date"><?php echo $row['news_date']; ?></div>
			<div class="news_title"><a href="/news_details.php?id=<?php echo $row['news_id']; ?>"><?php echo $row['news_title']; ?></a></div>
			<div class="news_description">
				<?php
					if (strlen($row['news_description']) > 450)
						$news_description = substr($row['news_description'], 0, 450)."...<a class='more' href='/news_details.php?id=".$row['news_id']."'>read more</a>";
					else
						$news_description = $row['news_description'];
					
					echo $news_description;
				?>
			</div>
		<?php } ?>

		<p align="center">
			<?php
				echo ShowPagination("news",$results_per_page,"news.php?","WHERE status='active'");
			?>
		</p>

	<?php }else{ ?>
				<p>Não há nenhuma notícias neste momento.</p>
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