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


	function getRetailer($program_id)
	{
		$res = smart_mysql_query("SELECT title FROM cashbackengine_retailers WHERE program_id='$program_id'");
		$row = mysql_fetch_array($res);
		$retailer_name = $row['title'];
		return $retailer_name;
	}


	$userid	= (int)$_SESSION['userid'];
	$cc = 0;

	$query = "SELECT *, DATE_FORMAT(created, '%e %b %Y') AS date_created, DATE_FORMAT(updated, '%e %b %Y') AS updated_date FROM cashbackengine_transactions WHERE user_id='$userid' AND program_id<>0 AND status<>'unknown' ORDER BY created DESC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Balance &amp; History";

	require_once ("inc/header.inc.php");

?>

          <h1>Saldo &amp; Histórico</h1>

		    <table align="center" style="border: solid 1px #EEEEEE;" width="300" border="0" cellspacing="0" cellpadding="7">
            <tr bgcolor="#80D721">
                <td valign="middle" align="left"><font color="#FFFFFF"><b>Saldo disponível</b></font></td>
                <td valign="middle" align="right"><font color="#FFFFFF"><b><?php echo GetUserBalance($userid); ?></b></font></td>
            </tr>
            <tr bgcolor="#FF8727">
                <td valign="middle" align="left"><font color="#FFFFFF"><b>Cashback pendente</b></font></td>
                <td valign="middle" align="right"><font color="#FFFFFF"><b><?php echo GetPendingBalance(); ?></b></font></td>
            </tr>
            <tr bgcolor="#FF3F59">
                <td valign="middle" align="left"><font color="#FFFFFF"><b>Cashback recusado</b></font></td>
                <td valign="middle" align="right"><font color="#FFFFFF"><b><?php echo GetDeclinedBalance(); ?></b></font></td>
            </tr>
            <tr bgcolor="#EBEBEB">
                <td valign="middle" align="left">Saque solicitado</td>
                <td valign="middle" align="right"><?php echo GetCashOutRequested(); ?></td>
            </tr>
            <tr bgcolor="#D6F7AD">
                <td valign="middle" align="left">Saque processado</td>
                <td valign="middle" align="right"><?php echo GetCashOutProcessed(); ?></td>
            </tr>
            <tr bgcolor="#FFFFFF">
                <td valign="middle" align="left"><b>Cashback Total, do início até aqui</b></td>
                <td valign="middle" align="right"><b><?php echo GetLifetimeCashback(); ?></b></td>
            </tr>
			</table>

			<p align="center">Você pode ver um histórico de todos os pagamentos feitos a você em <a href="/mypayments.php">Histórico de pagamento</a> </a>.</p>

			<?php if (GetBalanceUpdateDate($userid)) { ?>
				<p align="center"><b>Observação</b>: As estatísticas do Cashback não são atualizadas em tempo real.<br/> Suas estatísticas cashback são atualizados em <?php echo GetBalanceUpdateDate($userid); ?>.</p>
			<?php } ?>

	<?php

		if ($total > 0) {
 
	?>

            <table align="center" style="border-bottom: solid 1px #EEEEEE;" width="97%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <td colspan="4" align="left" valign="middle"><b>Saldo Detalhado</b></td>
              </tr>
              <tr>
				<th width="15%">Data</th>
				<th width="50%">Loja</th>
                <th width="15%">Cashback</th>
                <th width="20%">Status</th>
              </tr>

			<?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
                <tr class="<?php if (($cc%2) == 0) echo "row_even"; else echo "row_odd"; ?>">
                  <td valign="middle" align="center"><?php echo $row['date_created']; ?></td>
                  <td valign="middle" align="center"><?php echo getRetailer($row['program_id']); ?></td>
                  <td valign="middle" align="center"><?php echo DisplayMoney($row['amount']); ?></td>
                  <td valign="middle" align="center">
					<?php
							switch ($row['status'])
							{
								case "confirmed":	echo "<span class='confirmed_status'>confirmado</span>"; break;
								case "pending":		echo "<span class='pending_status'>pendente</span>"; break;
								case "declined":	echo "<span class='declined_status'>rejeitado</span>"; break;
								case "failed":		echo "<span class='failed_status'>falhou</span>"; break;
								case "request":		echo "<span class='request_status'>aguardando aprovação</span>"; break;
								case "paid":		echo "<span class='paid_status'>pago</span>"; break;
								default: echo "<span class='payment_status'>".$row['status']."</span>"; break;
							}

							if ($row['status'] == "declined" && $row['reason'] != "")
							{
								echo " <div class=\"cashbackengine_tooltip\"><img src=\"/images/info.png\" /><span class=\"tooltip\">".$row['reason']."</span></div>";
							}
					?>
				  </td>
                </tr>
			<?php } ?>

           </table>

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