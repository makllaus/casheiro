<?php
/*******************************************************************\
  * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/config.inc.php");
	
	$result = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE featured='1' AND status='active' ORDER BY title ASC");
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Featured Retailers";

	require_once ("inc/header.inc.php");

?>


	<h1>Varejistas em Destaque</h1>


<?php

	if ($total > 0) {

?>

	<p class="category_description">Nós sempre promovemos ofertas especiais que particularmente são benéficas para nossos membros. Veja abaixo uma seleção de nossas ofertas existentes.</p>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<?php
					while ($row = mysql_fetch_array($result))
					{
						$cc++;
			?>
				<tr class="<?php if (($cc%2) == 0) echo "even"; else echo "odd"; ?>">
					<td width="125" align="center" valign="middle">
						<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></a></div>
					</td>
					<td align="left" valign="bottom">
	
						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="80%" align="left" valign="top">
									<a class="retailer_title" href="/view_retailer.php?rid=<?php echo $row['retailer_id']; ?>"><?php echo $row['title']; ?></a>
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
									<a class="favorites" href="#" onclick="if (confirm('Are You sure You realy want to add this retailer to your favorites?') )location.href='/myfavorites.php?act=add&rid=<?php echo $row['retailer_id']; ?>'">Add aos favoritos</a>
								</td>
								<td valign="middle" align="right">
									<a class="go2store" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank">Ir à Loja</a>
								</td>
							</tr>
						</table>
	
					</td>
				</tr>

			<?php } ?>
				  </table>

	<?php }else{ ?>
		<p align="center">Não há ofertas em destaque neste momento!<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
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