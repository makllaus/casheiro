<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/auth.inc.php");
	require_once("inc/config.inc.php");

	$userid			= (int)$_SESSION['userid'];
	$retailer_id	= (int)$_GET['rid'];


	if (isset($_GET['act']) && $_GET['act'] == "del")
	{
		$del_query = "DELETE FROM cashbackengine_reviews WHERE user_id='$userid' AND retailer_id='$retailer_id'";
		if (smart_mysql_query($del_query))
		{
			header("Location: myreviews.php?msg=deleted");
			exit();
		}
	}

	
	$query = "SELECT cashbackengine_reviews.*, cashbackengine_retailers.* FROM cashbackengine_reviews cashbackengine_reviews, cashbackengine_retailers cashbackengine_retailers WHERE cashbackengine_reviews.user_id='$userid' AND cashbackengine_reviews.retailer_id=cashbackengine_retailers.retailer_id AND cashbackengine_retailers.status='active' ORDER BY cashbackengine_retailers.title ASC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Minhas Avaliações";

	require_once ("inc/header.inc.php");
	
?>

	<h1>Minhas Avaliações</h1>


		  <?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div class="success_msg">
				<?php

					switch ($_GET['msg'])
					{
						case "deleted": echo "Avaliação excluída com sucesso!"; break;
					}

				?>
			</div>
		<?php } ?>

	
	<table align="center" style="border-bottom: solid 1px #EEEEEE;" width="100%" border="0" cellspacing="0" cellpadding="3">
	<tr>
		<th width="40%">Nome da Loja</th>
		<th width="10%">Classificação</th>
		<th width="15%">Data de Envio</th>
		<th width="10%">Status</th>
		<th width="5%"></th>
	</tr>
	<?php
			$cc = 0;
			$query_reviews = "SELECT *, DATE_FORMAT(added, '%e %b %Y') AS date_added FROM cashbackengine_reviews WHERE user_id='$userid' ORDER BY added DESC";
			$result_reviews = smart_mysql_query($query_reviews);
			$total_reviews = mysql_num_rows($result_reviews);

			if ($total_reviews > 0) {
	?>
		<p>Abaixo, você pode encontrar suas avaliações apresentadas.</p>

		<?php while ($row_reviews = mysql_fetch_array($result_reviews)) { $cc++; ?>
		 <tr>
			<td valign="middle" align="left"><a href="/view_retailer.php?rid=<?php echo $row_reviews['retailer_id']; ?>"><?php echo GetStoreName($row_reviews['retailer_id']); ?></a></td>
			<td valign="middle" align="center"><img src="/images/icons/rating-<?php echo $row_reviews['rating']; ?>.gif" /></td>
			<td valign="middle" align="center"><?php echo $row_reviews['date_added']; ?></td>
			<td valign="middle" align="center">
				<?php
						switch ($row_reviews['status'])
						{
							case "active": echo "<span class='active_s'>".$row_reviews['status']."</span>"; break;
							case "inactive": echo "<span class='inactive_s'>".$row_reviews['status']."</span>"; break;
							default: echo "<span class='default_status'>".$row_reviews['status']."</span>"; break;
						}
				?>			
			</td>
			<td valign="middle" align="center"><a href="#" onclick="if (confirm('Tem certeza que você realmente quer apagar esta avaliação?') )location.href='/myreviews.php?act=del&rid=<?php echo $row_reviews['retailer_id']; ?>'" title="Delete"><img src="images/delete.gif" border="0" alt="Delete" /></a></td>
		</tr>
		<tr>
			<td  style="border-bottom: 3px dotted #EEE;" colspan="5" align="left" valign="top">
				<div class="myreview">
					<b><?php echo $row_reviews['review_title']; ?></b>
					<p><i><?php echo $row_reviews['review']; ?></i></p>
				</div>
			</td>
		</tr>
		<?php } ?>

	<?php }else{ ?>
			<tr><td colspan="5"><p align="center">Você não enviou nenhuma avaliação ainda.</p></td></tr>
	<?php } ?>
	</table>


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