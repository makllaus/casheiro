<?php
/*******************************************************************\
 * CashbackEngine v2.0
 * http://www.CashbackEngine.net
 *
 * Copyright (c) 2010-2013 CashbackEngine Software. All rights reserved.
 * ------------ CashbackEngine IS NOT FREE SOFTWARE --------------
\*******************************************************************/

	session_start();
	require_once("inc/auth.inc.php");
	require_once("inc/config.inc.php");

	$userid = (int)$_SESSION['userid'];
	$amount = DisplayMoney(MIN_PAYOUT_PER_TRANSACTION, $hide_currency = 1);

	if (isset($_POST['amount']) && is_numeric($_POST['amount'])) $amount = mysql_real_escape_string(getPostParameter('amount'));

	if (isset($_POST['withdraw']) && $_POST['withdraw'] != "")
	{
		unset($errs);
		$errs = array();

		$amount				= mysql_real_escape_string(getPostParameter('amount'));
		$payment_method		= intval(getPostParameter('payment_method'));
		$payment_details	= mysql_real_escape_string(nl2br(getPostParameter('payment_details')));
		$current_balance	= GetUserBalance($userid, 1);

		if (!(is_numeric($amount) && $amount > 0))
		{
			$errs[] = "Indique uma quantidade correta";
			$amount = "";
		}
		elseif (!(isset($payment_method) && $payment_method != 0))
		{
			$errs[] = "Selecione o método de pagamento";
		}
		elseif (!(isset($payment_details) && $payment_details != ""))
		{
			$errs[] = "Indique os detalhes de pagamento";
		}
		else
		{
			if ($amount < MIN_PAYOUT_PER_TRANSACTION)
			{
				$errs[] = "O valor mínimo para a transação é ".DisplayMoney(MIN_PAYOUT_PER_TRANSACTION);
			}

			if ($amount > $current_balance)
			{
				$errs[] = "Você não tem tanto dinheiro";
			}

			if ($current_balance < MIN_PAYOUT)
			{
				$errs[] = "O valor mínimo para pagamento pedido é ".DisplayMoney(MIN_PAYOUT);
			}
		}


		if (count($errs) == 0)
		{
			$reference_id = GenerateReferenceID();
			$rp_query = "INSERT INTO cashbackengine_transactions SET reference_id='$reference_id', user_id='$userid', payment_type='Withdrawal', payment_method='$payment_method', payment_details='$payment_details', amount='$amount', status='request', created=NOW()";
		
			if (smart_mysql_query($rp_query))
			{
				header("Location: withdraw.php?msg=sent");
				exit();
			}
		}
		else
		{
			$allerrors = "";
			foreach ($errs as $errorname)
				$allerrors .= "&#155; ".$errorname."<br/>\n";
		}
	}


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Sacar dinheiro";

	require_once ("inc/header.inc.php");

?>

		<h1>Saque</h1>


		<?php if (isset($_GET['msg']) && $_GET['msg'] != "") { ?>
			<div style="width: 85%;" class="success_msg">
				<?php

					switch ($_GET['msg'])
					{
						case "sent": echo "Obrigado por solicitar o saque, seu pedido será processado em breve!"; break;
					}

				?>
			</div>
		<?php }else{ ?>


		<?php if (GetUserBalance($userid, 1) > 0) { ?>
		    <div class="abalance">
				Saldo disponivel<br/>
				<span><?php echo GetUserBalance($userid); ?></span>
           </div>
		<?php }else{ ?>
				<p align="center">Desculpe, você não tem fundos suficientes para solicitar Cashback.</p>
				<p align="center">Seu saldo atual: <b><?php echo DisplayMoney($row['balance']); ?></b>. O valor mínimo para pagamento é <b><?php echo DisplayMoney(MIN_PAYOUT); ?></b>.</p>
		<?php } ?>


		<?php if (isset($allerrors)) { ?>
			<div class="error_msg"><?php echo $allerrors; ?></div>
		<?php } ?>


		<?php if (GetUserBalance($userid, 1) >= MIN_PAYOUT) { ?>
		<form action="" method="post">
        <table width="100%" bgcolor="#F7F7F7" style="padding: 10px;" align="center" cellpadding="3" cellspacing="0" border="0">
		<tr>
			<td height="30" colspan="2" align="center" valign="middle">
				<b>Saque</b>
				<br/><div class="sline"></div>
			</td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap">Quantidade:</td>
			<td align="left" valign="middle">
				<?php echo SITE_CURRENCY; ?><input type="text" class="textbox" name="amount" value="<?php echo @$amount; ?>" size="6" />
			</td>
		</tr>
		<tr>
			<td align="right" nowrap="nowrap">Método de pagamento:</td>
			<td align="left" valign="middle">
				<select name="payment_method" onchange="this.form.submit();">
					<option value="">-- Por favor, selecione o método de pagamento --</option>
				<?php

					$sql_pmethods = smart_mysql_query("SELECT * FROM cashbackengine_pmethods WHERE status='active' ORDER BY pmethod_title ASC");
				
					while ($row_pmethods = mysql_fetch_array($sql_pmethods))
					{
						if ($payment_method == $row_pmethods['pmethod_id'] || $_POST['payment_method'] == $row_pmethods['pmethod_id']) $selected = " selected=\"selected\""; else $selected = "";
						
						echo "<option value=\"".$row_pmethods['pmethod_id']."\"".$selected.">".$row_pmethods['pmethod_title']."</option>";
					}
				?>
				</select>
			</td>
		</tr>
		<?php if (isset($_POST['payment_method']) && is_numeric($_POST['payment_method'])) { ?>
		<tr>
			<td align="right" valign="bottom" nowrap="nowrap">Detalhes de Pagamento:<br/><br/><br/><br/></td>
			<td bgcolor="#F7F7F7" align="left" valign="middle">
				<?php 
					$payment_method_id = (int)$_POST['payment_method'];
					$pquery = "SELECT pmethod_details FROM cashbackengine_pmethods WHERE pmethod_id='$payment_method_id' AND status='active' LIMIT 1";
					$prow = mysql_fetch_array(smart_mysql_query($pquery));
					echo $prow['pmethod_details'];
				?>
				<br/>
				<textarea name="payment_details" cols="40" rows="4" class="textbox2"><?php echo getPostParameter('payment_details'); ?></textarea>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<td>&nbsp;</td>
			<td align="left" valign="top">
		  		<input type="hidden" name="action" value="rpayment" />
				<input type="submit" class="submit" name="withdraw" id="withdraw" value="Withdraw" />
			</td>
		</tr>
        </table>
		</form>

		<?php }else{ ?>
			<p align="center">Você pode solicitar pagamento apenas se o saldo da conta corrente for mais que <b><?php echo DisplayMoney(MIN_PAYOUT); ?></b>.</p>
			<p align="center"><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
		<?php } ?>


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