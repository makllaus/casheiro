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

	$userid	= (int)$_SESSION['userid'];

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Minha Conta";

	require_once ("inc/header.inc.php");

?>

	<h1>Minha conta</h1>


			<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
				<div class="success_msg" style="width: 90%">
				<?php
					switch ($_GET['msg'])
					{
						case "Bem-vindo": echo "Parabéns! Sua conta foi ativada com sucesso!"; break;
					}
				?>
				</div>
			<?php } ?>

			<table width="100%" align="center" cellpadding="3" cellspacing="0" border="0">
			<tr>
				<td align="left" valign="top">
						<p>Bem-vindo, <b><?php echo $_SESSION['FirstName']; ?></b>. Você está conectado no momento, para que você <a href="/retailers.php"><b>Comece a ganhar CASHBACK</b></a> AGORA!</p>
	
						<?php if (GetUserBalance($userid, 1) == 0) { ?>
							<p>Seu saldo atual é <?php echo SITE_CURRENCY; ?><b>0.00</b>. Novo em cashback? <a href="/help.php">Saiba como funciona &#155;</a></p>
						<?php } ?>

						<h1>Começar!</h1>

						<ul style="list-style:none; margin: 7px; line-height: 35px;">
							<li style="background: url('images/icons/1.png') no-repeat 0px 5px; padding-left: 35px;"><a href="/retailers.php">Encontre a loja</a></li>
							<li style="background: url('images/icons/2.png') no-repeat 0px 5px; padding-left: 35px;">Clique em "Ir à loja" link</li>
							<li style="background: url('images/icons/3.png') no-repeat 0px 5px; padding-left: 35px;">Comece a ganhar cashback!</li>
						</ul>

						<p>A partir daqui você pode <a href="/withdraw.php">retirar seus ganhos</a>, <a href="/invite.php">convidar seus amigos</a>, veja <a href="/mybalance.php">Histórico de pagamento</a>, <a href="/myclicks.php">Histórico de cliques</a> e muito mais.</p>
						<p>Obrigado por escolher o <?php echo SITE_TITLE; ?>!</p>
  				</td>
			</tr>
        </table>

	<?php
	
		// show featured retailers //
		$result_featured = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE featured='1' AND status='active' ORDER BY added DESC LIMIT 8");
		$total_fetaured = mysql_num_rows($result_featured);

		if ($total_fetaured > 0) {
	?>
		<div style="clear: both;"></div>
		<h1>Confira nossas ofertas em destaque</h1>
		<div class="featured_stores">
		<?php while ($row_featured = mysql_fetch_array($result_featured)) { $cc++; ?>
			<div class="imagebox"><a href="/view_retailer.php?rid=<?php echo $row_featured['retailer_id']; ?>"><img src="<?php if (!stristr($row_featured['image'], 'http')) echo "/img/"; echo $row_featured['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row_featured['title']; ?>" title="<?php echo $row_featured['title']; ?>" border="0" /></a></div>
		<?php } ?>
		</div>
		<div style="clear: both"></div>
	<?php } // end featured retailers ?>


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