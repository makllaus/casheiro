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
	require_once("inc/pagination.inc.php");

	$userid			= (int)$_SESSION['userid'];
	$ReferralLink	= SITE_URL."?ref=".$userid;


	if (isset($_POST['action']) && $_POST['action'] == "friend")
	{
		unset($errs);
		$errs = array();

		$uname		= $_SESSION['FirstName'];
		$fname		= array();
		$fname		= $_POST['fname'];
		$femail		= array();
		$femail		= $_POST['femail'];

		if(!($fname[1] && $femail[1]))
		{
			$errs[] = "Por favor, insira o nome e e-mail do seu amigo";
		}
		else
		{
			foreach ($fname as $k=>$v)
			{
				if ($femail[$k] != "" && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $femail[$k]))
				{
					$errs[] = "endereço de email invalido"; break;
				}
			}
		}

		if (count($errs) == 0)
		{
			////////////////////////////////  Send Message  //////////////////////////////
			
			$etemplate = GetEmailTemplate('invite_friend');

				foreach ($fname as $k=>$v)
				{
					if (isset($v) && $v != "" && isset($femail[$k]) && $femail[$k] != "")
					{
						$friend_name = $v;
						$friend_email = $femail[$k];
						
						$esubject = $etemplate['email_subject'];
						$emessage = $etemplate['email_message'];

						$emessage = str_replace("{friend_name}", $friend_name, $emessage);
						$emessage = str_replace("{first_name}", $uname, $emessage);
						$emessage = str_replace("{referral_link}", $ReferralLink, $emessage);

						$to_email = $friend_name.' <'.$friend_email.'>';
						$subject = $esubject;
						$message = $emessage;
						$from_email = SITE_MAIL;
		
						$headers  = 'MIME-Version: 1.0' . "\r\n";
						$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
						$headers .= 'From: '.SITE_TITLE.' <'.$from_email.'>' . "\r\n";

						@mail($to_email, $subject, $message, $headers);
					}
				}

			header("Location: invite.php?msg=1");
			exit();

			////////////////////////////////////////////////////////////////////////////////
		}
		else
		{
			$allerrors = "";
			foreach ($errs as $errorname)
				$allerrors .= "&#155; ".$errorname."<br/>\n";
		}

}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Refer a Friend";

	require_once ("inc/header.inc.php");

?>

	<h1>Indique um Amigo</h1>

	<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
	<?php if (REFER_FRIEND_BONUS > 0) { ?>
	<tr>
		<td align="left" valign="top">
			Diga a seus amigos sobre <?php echo SITE_TITLE; ?> e vamos creditar em sua conta um <b><?php echo DisplayMoney(REFER_FRIEND_BONUS); ?></b> bônus uma vez que seu amigo ganhou qualquer quantia.
			<p>Não há limite de quantos amigos você pode consultar, isso significa que a quantidade que você pode ganhar é ilimitada!</p>
			<p>Use seu link de referência para convidar seus amigos<?php echo SITE_TITLE; ?>. Você pode adicionar este link em sua assinatura de e-mail ou publicá-lo em um fórum, site, blog, etc.</p>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<td align="left" valign="middle">
			<div class="referral_link">
				<b>Seu link de referência:</b>
				<input type="text" class="reflink_textbox" size="60" readonly="readonly" onfocus="this.select();" onclick="this.focus();this.select();" value="<?php echo $ReferralLink; ?>" />
			</div>
		</td>
	</tr>
	</table>
	<br />


	<h1>Enviar convite</h1>

	<?php if (REFER_FRIEND_BONUS > 0) { ?>
	<table align="center" width="100%" border="0" cellpadding="3" cellspacing="0">
	<tr>
		<td align="center" valign="top">
			Indique seus amigos e ganhe <b><?php echo DisplayMoney(REFER_FRIEND_BONUS); ?></b>! Isso é fácil. Digite até 5 endereços de e-mail de seus amigos e familiares.<br/> Cada amigo receberá um link para se juntar a nós e você receberá <b><?php echo DisplayMoney(REFER_FRIEND_BONUS); ?></b> por cada um dos membros que se inscreveu.<br/><br/>
		</td>
	</tr>
	</table>
	<?php } ?>

	<form action="" method="post">
	<table align="center" width="65%" border="0" cellpadding="3" cellspacing="0">
		<?php if (isset($_GET['msg']) and $_GET['msg'] == 1) { ?>
			<div class="success_msg">Obrigado! Mensagem enviada aos seus amigos.</div>
			<p align="center"><a href="/invite.php">Enviar mais convites &raquo;</a></p>
		<?php }else{ ?>
          
			<?php if (isset($allerrors) and $allerrors != "") { ?>
				<div class="error_msg"><?php echo $allerrors; ?></div>
			<?php } ?>

		  <?php for ($i=1; $i<=5; $i++) { ?>
          <tr>
			<td colspan="2" align="left" valign="top">
				<table width="100%" cellpadding="0" cellspacing="1" border="0">
                    <tr>
						<td align="left" valign="top">Amigo #<?php echo $i; ?> Primeiro nome: <?php if ($i == 1) { ?><span class="req">* </span><?php } ?><br/>
							<input type="text" name="fname[<?php echo $i; ?>]" class="textbox" value="<?php echo $fname[$i]; ?>" size="27" />
						</td>
						<td width="15">&nbsp;</td>
						<td align="left" valign="top">Amigo #<?php echo $i; ?> E-mail: <?php if ($i == 1) { ?><span class="req">* </span><?php } ?><br/>
							<input type="text" name="femail[<?php echo $i; ?>]" class="textbox" value="<?php echo $femail[$i]; ?>" size="27" />
						</td>
					</tr>
				</table>
			</td>
          </tr>
		  <?php } ?>
          <tr>
			<td colspan="2" align="center" valign="middle">
				<input type="hidden" name="action" id="action" value="friend" />
				<input type="submit" class="submit" name="Send" id="Send" value="Enviar convites!" />
			</td>
          </tr>

		  <?php } ?>
	</table>
	</form>


	<h1>Meus convidados</h1>
	<a name="referrals"></a>

	<?php

	$results_per_page = 10;
	$cc = 0;

	////////////////// filter  //////////////////////
		if (isset($_GET['column']) && $_GET['column'] != "")
		{
			switch ($_GET['column'])
			{
				case "fname": $rrorder = "fname"; break;
				case "country": $rrorder = "country"; break;
				case "created": $rrorder = "created"; break;
				default: $rrorder = "created"; break;
			}
		}
		else
		{
			$rrorder = "created";
		}

		if (isset($_GET['order']) && $_GET['order'] != "")
		{
			switch ($_GET['order'])
			{
				case "asc": $rorder = "asc"; break;
				case "desc": $rorder = "desc"; break;
				default: $rorder = "desc"; break;
			}
		}
		else
		{
			$rorder = "desc";
		}
	//////////////////////////////////////////////////


		if (isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0) { $page = (int)$_GET['page']; } else { $page = 1; }
		$from = ($page-1)*$results_per_page;


		$refs_query = "SELECT *, DATE_FORMAT(created, '%e %b %Y %h:%i %p') AS signup_date FROM cashbackengine_users WHERE ref_id='".(int)$userid."' ORDER BY $rrorder $rorder LIMIT $from, $results_per_page";
		$total_refs_result = smart_mysql_query("SELECT * FROM cashbackengine_users WHERE ref_id='".(int)$userid."'");
		$total_refs = mysql_num_rows($total_refs_result);

		$refs_result = smart_mysql_query($refs_query);
		$total_refs_on_page = mysql_num_rows($refs_result);

		if ($total_refs > 0)
		{
	?>

			<div class="browse_top">
			<div class="sortby">
				<form action="" id="form1" name="form1" method="get">
					<span>Ordenar por:</span>
					<select name="column" id="column" onChange="document.form1.submit()">
						<option value="created" <?php if ($_GET['column'] == "added") echo "created"; ?>>Data de registro</option>
						<option value="fname" <?php if ($_GET['column'] == "fname") echo "selected"; ?>>Nome</option>
						<option value="country" <?php if ($_GET['column'] == "country") echo "selected"; ?>>País</option>
					</select>
					<select name="order" id="order" onChange="document.form1.submit()">
						<option value="desc"<?php if ($_GET['order'] == "desc") echo "selected"; ?>>Decrescente</option>
						<option value="asc" <?php if ($_GET['order'] == "asc") echo "selected"; ?>>Crescente</option>
					</select>
					<input type="hidden" name="page" value="<?php echo $page; ?>" />
				</form>
			</div>
			<div class="results">
				Mostrando <?php echo ($from + 1); ?> - <?php echo min($from + $total_refs_on_page, $total_refs); ?> de <?php echo $total_refs; ?>
			</div>
			</div>

			<table align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
			<tr>
				<th width="50%">Nome</th>
				<th width="25%">País</th>
				<th width="25%">Data de registro</th>
			</tr>
			<?php while ($refs_row = mysql_fetch_array($refs_result)) { $cc++; ?>
			<tr class="<?php if (($cc%2) == 0) echo "row_even"; else echo "row_odd"; ?>">
				<td align="left" valign="middle"><img src="/images/referral_icon.png" align="absmiddle" /> &nbsp; <b><?php echo $refs_row['fname']." ".$refs_row['lname']; ?></b></td>
				<td align="center" valign="middle"><?php echo $refs_row['country']; ?></td>
				<td align="center" valign="middle"><?php echo $refs_row['signup_date']; ?></td>
			</tr>
			<?php } ?>
			<tr>
				<td valign="middle" align="center" colspan="3">
					<?php echo ShowPagination("users",$results_per_page,"invite.php?column=$rrorder&order=$rorder&", "WHERE ref_id='".(int)$userid."'"); ?>
				</td>
			</tr>
			</table>
		
		<?php }else{ ?>
			<p>Nenhum convidado se cadastrou até o momento</p>
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