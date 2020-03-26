<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/config.inc.php");


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$news_id = (int)$_GET['id'];
	}
	else
	{		
		header ("Location: news.php");
		exit();
	}

	$result = smart_mysql_query("SELECT *, DATE_FORMAT(added, '%M %e, %Y') AS news_date FROM cashbackengine_news WHERE news_id='$news_id' AND status='active' ORDER BY added DESC");
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Notícias";

	require_once ("inc/header.inc.php");

?>

	<h1>Notícias</h1>


	<?php if ($total > 0) { $row = mysql_fetch_array($result); ?>

		<div class="news_date"><?php echo $row['news_date']; ?></div>
		<div class="news_title"><?php echo $row['news_title']; ?></div>
		<div class="news_description"><?php echo $row['news_description']; ?></div>

		<p align="right"><a class="more" href="/news.php">ler outras notícias...</a></p>

	<?php }else{ ?>
				<p align="center">Desculpe, notícia não encontrada.</p>
				<p align="center"><a class="goback" href="/news.php">Voltar</a></p>
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