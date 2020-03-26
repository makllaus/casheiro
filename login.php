<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/iflogged.inc.php");
	require_once("inc/config.inc.php");


if (isset($_POST['action']) && $_POST['action'] == "login")
{
	$username	= mysql_real_escape_string(getPostParameter('username'));
	$pwd		= mysql_real_escape_string(getPostParameter('password'));
	$ip			= getenv("REMOTE_ADDR");

	if (!($username && $pwd))
	{
		$errormsg = "Digite seu e-mail e senha";
	}
	else
	{
		$sql = "SELECT * FROM cashbackengine_users WHERE username='$username' AND password='".PasswordEncryption($pwd)."' LIMIT 1";
		$result = smart_mysql_query($sql);

		if (mysql_num_rows($result) != 0)
		{
				$row = mysql_fetch_array($result);

				if ($row['status'] == 'inactive')
				{
					header("Location: login.php?msg=2");
					exit();
				}

				smart_mysql_query("UPDATE cashbackengine_users SET last_ip='$ip', login_count=login_count+1, last_login=NOW() WHERE user_id='".(int)$row['user_id']."' LIMIT 1"); 

				if (!session_id()) session_start();
				$_SESSION['userid'] = $row['user_id'];
				$_SESSION['FirstName'] = $row['fname'];

				if ($_SESSION['goRetailerID'])
				{
					$redirect_url = "view_retailer.php?rid=".(int)$_SESSION['goRetailerID'];
					unset($_SESSION['goRetailerID']);
				}
				else
				{
					$redirect_url = "myaccount.php";
				}

				header("Location: ".$redirect_url);
				exit();

		}
		else
		{
				header("Location: login.php?msg=1");
				exit();
		}
	}
}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Log in";

	require_once ("inc/header.inc.php");

?>

<table width="100%" align="center" cellpadding="2" cellspacing="0" border="0">
<tr>
<td width="50%" valign="top" align="left">
      
        <h1>Acesso</h1>

		<?php if (isset($errormsg) || isset($_GET['msg'])) { ?>
			<div style="width: 77%;" class="error_msg">
				<?php if (isset($errormsg) && $errormsg != "") { ?>
					<?php echo $errormsg; ?>
				<?php }else{ ?>
					<?php if ($_GET['msg'] == 1) { echo "Email ou senha inválida!"; } ?>
					<?php if ($_GET['msg'] == 2) { echo "Desculpe, sua conta foi desactivada.<br/>Para mais informações, por favor <a href='/contact.php'>contact us</a>"; } ?>
					<?php if ($_GET['msg'] == 3) { echo "Você deve fazer o login em primeiro lugar"; } ?>
					<?php if ($_GET['msg'] == 4) { echo "Você precisa fazer o login para ganhar cashback"; } ?>
				<?php } ?>
			</div>
		<?php } ?>

		<form action="" method="post">
        <table width="95%" align="center" cellpadding="3" cellspacing="0" border="0">
          <tr>
            <td align="right" valign="middle">Email:</td>
            <td valign="top"><input type="text" class="textbox" name="username" value="<?php echo getPostParameter('username'); ?>" size="25" /></td>
          </tr>
          <tr>
            <td align="right" valign="middle">Senha:</td>
            <td valign="top"><input type="password" class="textbox" name="password" value="" size="25" /></td>
          </tr>
          <tr>
            <td valign="top" align="middle">&nbsp;</td>
			<td align="left" valign="bottom">
		  		<input type="hidden" name="action" value="login" />
				<input type="submit" class="submit" name="login" id="login" value="Login" />
			</td>
          </tr>
          <tr>
		   <td valign="top" align="middle">&nbsp;</td>
            <td align="left" valign="bottom">
				<a href="/forgot.php">Esqueceu sua senha?</a>
			</td>
          </tr>
        </table>
      </form>

</td>
<td width="2%" valign="top" align="left">&nbsp;</td>
<td width="48%" valign="top" align="left">
	
	<h1>Ainda não é membro?</h1>
	
	<p>Registre-se e comece a ganhar hoje! É totalmente gratuito!</p>
		
		<p><b>Resumo dos benefícios</b></p>
		<ul id="benefits">
			<li>Entrada livre</li>
			<?php if (SIGNUP_BONUS > 0) echo "<li>Ganhe <b>".DisplayMoney(SIGNUP_BONUS)."</b> Bônus de boas vindas</li>\n"; ?>
			<?php if (REFER_FRIEND_BONUS > 0) echo "<li>Convide seus amigos e ganhe <b>".DisplayMoney(REFER_FRIEND_BONUS)."</b> cada</li>\n"; ?>
			<li>Ganhe até 30% de cashback</li>
			<li>Compra on-line em suas lojas favoritas</li>
		</ul>

	<p align="center"><a class="button" href="/register.php">Cadastre-se!</a></p>

</td>
</tr>
</table>

<?php

	if (isset($_SESSION['goRetailerID']) && $_SESSION['goRetailerID'] != "")
	{
		$retailer_id = (int)$_SESSION['goRetailerID'];

		$result = smart_mysql_query("SELECT * FROM cashbackengine_retailers WHERE retailer_id='$retailer_id' LIMIT 1");
		$row = mysql_fetch_array($result);

		$retailer_website = str_replace("{USERID}", "0", $row['url']);

?>

			<table bgcolor="#F9F9F9" style="border-top: 2px solid #eee" align="center" width="100%" border="0" cellspacing="0" cellpadding="5">
				<tr>
					<td width="125" align="center" valign="top">
						<div class="imagebox"><img src="<?php if (!stristr($row['image'], 'http')) echo "/img/"; echo $row['image']; ?>" width="<?php echo IMAGE_WIDTH; ?>" height="<?php echo IMAGE_HEIGHT; ?>" alt="<?php echo $row['title']; ?>" title="<?php echo $row['title']; ?>" border="0" /></div>
					</td>
					<td align="left" valign="bottom">

						<table width="100%" border="0" cellspacing="0" cellpadding="3">
							<tr>
								<td width="80%" align="left" valign="middle">
									<h2 class="stitle"><?php echo $row['title']; ?></h1>
								</td>
								<td nowrap="nowrap" width="20%" align="right" valign="middle">
									<span class="cashback"><?php echo DisplayCashback($row['cashback']); ?> Cashback</span>
								</td>
							</tr>
							<tr>
								<td colspan="2" valign="top" align="left"><p class="retailer_description"><?php echo $row['description']; ?>&nbsp;</p></td>
							</tr>
							<?php if ($row['conditions'] != "") { ?>
							<tr>
								<td colspan="2" valign="middle" align="left">
									<span class="conditions_title">Condições:</span> <?php echo $row['conditions']; ?>
								</td>
							</tr>
							<?php } ?>
						</table>

					</td>
				</tr>
				<tr>
					<td colspan="2" valign="middle" align="center">
						<div class="sline"></div>
						<h3>Não, obrigado, eu só quero comprar</h3>
						<a class="go2store_b" href="<?php echo $retailer_website; ?>">Continuar a compra sem cashback</a>
					</td>
				</tr>
				<tr>
					<td align="center" colspan="2"><div class="sline"></div></td>
				</tr>
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