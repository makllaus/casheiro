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
	$cc = 0;

	$query = "SELECT *, DATE_FORMAT(created, '%e %b %Y') AS date_created, DATE_FORMAT(process_date, '%e %b %Y') AS process_date FROM cashbackengine_transactions WHERE user_id='$userid' AND program_id='' AND status<>'unknown' ORDER BY created DESC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);

	
	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Histórico de Pagamento";

	require_once ("inc/header.inc.php");

?>

          <h1>Histórico de Pagamento</h1>

<?php

		if ($total > 0) {
 
?>

            <table align="center" style="border-bottom: solid 1px #EEEEEE;" width="100%" border="0" cellspacing="0" cellpadding="3">
              <tr>
                <th width="15%">Data</th>
				<th width="17%">ID de referência</th>
                <th width="25%">Tipo de pagamento</th>
                <th width="15%">Status</th>
                <th width="17%">Processado em</th>
				<th width="17%">Quantidade</th>
				<th width="7%"></th>
              </tr>
			<?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
                <tr class="<?php if (($cc%2) == 0) echo "row_even"; else echo "row_odd"; ?>">
                  <td valign="middle" align="center"><?php echo $row['date_created']; ?></td>
                  <td valign="middle" align="center"><a href="/mypayments.php?id=<?php echo $row['transaction_id']; ?>"><?php echo $row['reference_id']; ?></a></td>
                  <td valign="middle" align="center">
					<?php
							switch ($row['payment_type'])
							{
								case "Cashback":				echo "cashback"; break;
								case "Withdrawal":				echo "retirada"; break;
								case "Refer a Friend Bonus":	echo "bônus por indicação"; break;
								case "Sign Up Bonus":			echo "bônus de cadastro"; break;
								default:						echo $row['payment_type']; break;
							}
					?>
				  </td>
                  <td nowrap="nowrap" valign="middle" align="center">
					<?php
							switch ($row['status'])
							{
								case "confirmed":	echo "<span class='confirmed_status'>confirmado</span>"; break;
								case "pending":		echo "<span class='pending_status'>pendente</span>"; break;
								case "declined":	echo "<span class='declined_status'>rejeitado</span>"; break;
								case "failed":		echo "<span class='failed_status'>falhou</span>"; break;
								case "request":		echo "<span class='request_status'>Aguardado aprovação</span>"; break;
								case "paid":		echo "<span class='paid_status'>pago</span>"; break;
								default:			echo "<span class='payment_status'>".$row['status']."</span>"; break;
							}

							if ($row['status'] == "declined" && $row['reason'] != "")
							{
								echo " <div class=\"cashbackengine_tooltip\"><img src=\"/images/info.png\" /><span class=\"tooltip\">".$row['reason']."</span></div>";
							}
					?>
				  </td>
				  <td valign="middle" align="center"><?php echo $row['process_date']; ?></td>
                  <td valign="middle" align="center"><?php echo DisplayMoney($row['amount']); ?></td>
				  <td valign="middle" align="center"><a href="mypayments.php?id=<?php echo $row['transaction_id']; ?>"><img src="/images/icon_view.png" /></a></td>
                </tr>
			<?php } ?>
           </table>


	<?php
	
	// show payment details //
	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$transaction_id = (int)$_GET['id'];
		$payment_result = smart_mysql_query("SELECT *, DATE_FORMAT(created, '%e %b %Y %h:%i %p') AS date_created, DATE_FORMAT(process_date, '%e %b %Y %h:%i %p') AS process_date FROM cashbackengine_transactions WHERE transaction_id='$transaction_id' AND user_id='$userid' AND program_id='' AND status<>'unknown' LIMIT 1");
			
		if (mysql_num_rows($payment_result) > 0)
		{
			$payment_row = mysql_fetch_array($payment_result);

	?>
		<h3>Detalhes de Pagamento</h3>
		<div style="background: #F7F7F7; border-top: 3px dotted #eee; border-bottom: 3px dotted #eee;">
		 <table width="500" cellpadding="5" cellspacing="3" border="0">
            <tr>
              <td width="90" nowrap="nowrap" valign="middle" align="left" class="tb1">ID de referência:</td>
              <td align="left" valign="middle"><?php echo $payment_row['reference_id']; ?></td>
            </tr>
           <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Tipo de pagamento:</td>
            <td align="left" valign="middle">
				<?php
						switch ($payment_row['payment_type'])
						{
							case "Cashback":				echo "cashback"; break;
							case "Withdrawal":				echo "retirada"; break;
							case "Refer a Friend Bonus":	echo "bônus por indicação"; break;
							case "Sign Up Bonus":			echo "bônus de cadastro"; break;
							default:						echo $payment_row['payment_type']; break;
						}
				?>
			</td>
          </tr>
		<?php if ($payment_row['payment_type'] == "Withdrawal" && $payment_row['payment_method'] != "") { ?>
          <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Método de pagamento:</td>
            <td align="left" valign="middle">
					<?php if ($payment_row['payment_method'] == "paypal") { ?><img src="/images/icon_paypal.png" align="absmiddle" />&nbsp;<?php } ?>
					<?php echo GetPaymentMethodByID($payment_row['payment_method']); ?>
			</td>
          </tr>
		<?php } ?>
		<?php if ($payment_row['payment_details'] != "") { ?>
           <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Detalhes de pagamento:</td>
            <td align="left" valign="middle"><?php echo $payment_row['payment_details']; ?></td>
          </tr>
		 <?php } ?>
          <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Quantidade:</td>
            <td align="left" valign="middle"><?php echo DisplayMoney($payment_row['amount']); ?></td>
          </tr>
          <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Data:</td>
            <td align="left" valign="middle"><?php echo $payment_row['date_created']; ?></td>
          </tr>
		  <?php if ($payment_row['process_date'] != "") { ?>
          <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Processado em:</td>
            <td align="left" valign="middle"><?php echo $payment_row['process_date']; ?></td>
          </tr>
		  <?php } ?>
          <tr>
            <td nowrap="nowrap" valign="middle" align="left" class="tb1">Status:</td>
            <td align="left" valign="middle">
					<?php
							switch ($payment_row['status'])
							{
								case "confirmed":	echo "<span class='confirmed_status'>confirmado</span>"; break;
								case "pending":		echo "<span class='pending_status'>pendente</span>"; break;
								case "declined":	echo "<span class='declined_status'>rejeitado</span>"; break;
								case "failed":		echo "<span class='failed_status'>falhou</span>"; break;
								case "request":		echo "<span class='request_status'>aguardado aprovação</span>"; break;
								case "paid":		echo "<span class='paid_status'>pago</span>"; break;
								default:			echo "<span class='payment_status'>".$payment_row['status']."</span>"; break;
							}

							if ($payment_row['status'] == "declined" && $payment_row['reason'] != "")
							{
								echo " <div class=\"cashbackengine_tooltip\"><img src=\"/images/info.png\" /><span class=\"tooltip\">".$payment_row['reason']."</span></div>";
							}
					?>				
			</td>
          </tr>
          </table>
		  </div>
	<?php
		}

	} // end payment details
	?>


	<?php }else{ ?>
		<p align="center">Não há transações em histórico de pagamengo neste momento.</p>
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