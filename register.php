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


if (isset($_POST['action']) && $_POST['action'] == "register")
{
	unset($errs);
	$errs = array();

	$fname		= mysql_real_escape_string(ucfirst(strtolower(getPostParameter('fname'))));
	$lname		= mysql_real_escape_string(ucfirst(strtolower(getPostParameter('lname'))));
	$email		= mysql_real_escape_string(strtolower(getPostParameter('email')));
	$cemail		= mysql_real_escape_string(strtolower(getPostParameter('cemail')));
	$pwd		= mysql_real_escape_string(getPostParameter('password'));
	$pwd2		= mysql_real_escape_string(getPostParameter('password2'));
	$country	= mysql_real_escape_string(getPostParameter('country'));
	$newsletter	= intval(getPostParameter('newsletter'));
	$tos		= intval(getPostParameter('tos'));
	$ref_id		= intval(getPostParameter('referer_id'));
	$ip			= getenv("REMOTE_ADDR");

	if (!($fname && $lname && $email && $cemail && $pwd && $pwd2 && $country))
	{
		$errs[] = "Por favor, preencha todos os campos requisitados";
	}

	if (isset($email) && $email != "" && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
	{
		$errs[] = "Por favor digite um endereço de email válido";
	}

	if (isset($email) && $email != "" && isset($cemail) && $cemail != "")
	{
		if ($email !== $cemail)
		{
			$errs[] = "E-mail de confirmação está errado";
		}
	}

	if (isset($pwd) && $pwd != "" && isset($pwd2) && $pwd2 != "")
	{
		if ($pwd !== $pwd2)
		{
			$errs[] = "Confirmação de senha está errada";
		}
		elseif ((strlen($pwd)) < 6 || (strlen($pwd2) < 6) || (strlen($pwd)) > 20 || (strlen($pwd2) > 20))
		{
			$errs[] = "A senha deve ter entre 6-20 caracteres (letras e números)";
		}
		elseif (stristr($pwd, ' '))
		{
			$errs[] = "A senha não deve conter espaços";
		}
	}

	if (!(isset($tos) && $tos == 1))
	{
		$errs[] = "Você deve concordar com os Termos &amp; condições";
	}

	if (count($errs) == 0)
	{
			$query = "SELECT username FROM cashbackengine_users WHERE username='$email' OR email='$email' LIMIT 1";
			$result = smart_mysql_query($query);

			if (mysql_num_rows($result) != 0)
			{
				header ("Location: register.php?msg=exists");
				exit();
			}

			$insert_query = "INSERT INTO cashbackengine_users SET username='$email', password='".PasswordEncryption($pwd)."', email='$email', fname='$fname', lname='$lname', country='$country', ref_id='$ref_id', newsletter='$newsletter', ip='$ip', created=NOW()";

			smart_mysql_query($insert_query);
			$new_user_id = mysql_insert_id();

			// save SIGN UP BONUS transaction //
			$reference_id = GenerateReferenceID();
			smart_mysql_query("INSERT INTO cashbackengine_transactions SET reference_id='$reference_id', user_id='$new_user_id', payment_type='Sign Up Bonus', amount='".SIGNUP_BONUS."', status='confirmed', created=NOW(), process_date=NOW()");
			/////////////////////////////////////

			// add bonus to referral, save transaction //
			if (isset($ref_id) && $ref_id > 0)
			{
				$reference_id = GenerateReferenceID();
				$ref_res = smart_mysql_query("INSERT INTO cashbackengine_transactions SET reference_id='$reference_id', user_id='$ref_id', payment_type='Refer a Friend Bonus', amount='".REFER_FRIEND_BONUS."', status='pending', created=NOW()");
			}
			//////////////////////////////////////////////

			////////////////////////////////  Send Message  //////////////////////////////
			$etemplate = GetEmailTemplate('signup');
			$esubject = $etemplate['email_subject'];
			$emessage = $etemplate['email_message'];

			$emessage = str_replace("{first_name}", $fname, $emessage);
			$emessage = str_replace("{username}", $email, $emessage);
			$emessage = str_replace("{password}", $pwd, $emessage);
			$emessage = str_replace("{login_url}", SITE_URL."login.php", $emessage);

			$to_email = $fname.' '.$lname.' <'.$email.'>';
			$subject = $esubject;
			$message = $emessage;
			$from_email = SITE_MAIL;

			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.SITE_TITLE.' <'.$from_email.'>' . "\r\n";
			
			@mail($to_email, $subject, $message, $headers);
			////////////////////////////////////////////////////////////////////////////////

			if (!session_id()) session_start();
			$_SESSION['userid']		= $new_user_id;
			$_SESSION['FirstName']	= $fname;

			header("Location: myaccount.php?msg=welcome"); // forward new user to member dashboard
			exit();
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= "&#155; ".$errorname."<br/>\n";
	}
}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "CADASTRO";
	
	require_once ("inc/header.inc.php");
	
?>

	<table width="100%" style="border-bottom: 2px solid #EFF0FB;" align="center" cellpadding="2" cellspacing="0" border="0">
	<tr>
		<td align="left" valign="middle"><h1 style="margin-bottom:0;border:none;">Cadastre-se gratuitamente!</h1></td>
		<td align="right" valign="bottom">Já é um membro? <a href="/login.php">Entre</a></td>
	</tr>
	</table>

		<?php if (isset($allerrors) || isset($_GET['msg'])) { ?>
			<div class="error_msg">
					<?php
							if (isset($_GET['msg']) && $_GET['msg'] == "exists")
							{
								echo "&#155; O endereço de e-mail que você digitou já está em uso. <a href='/forgot.php'>Esqueceu sua senha?</a></font><br/>";
							}
							elseif (isset($allerrors))
							{
								echo $allerrors;
							}
					?>
			</div>
		<?php } ?>

		<form action="" method="post">
        <table width="100%" align="center" cellpadding="3" cellspacing="0" border="0">
          <tr>
            <td colspan="2" align="right" valign="top"><span class="req">* indica campo obrigatório</span></td>
          </tr>
          <tr>
            <td width="210" align="right" valign="middle"><span class="req">* </span>Primeiro nome:</td>
            <td align="left" valign="top"><input type="text" id="fname" class="textbox" name="fname" value="<?php echo getPostParameter('fname'); ?>" size="25" /></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>Sobrenome:</td>
            <td align="left" valign="top"><input type="text" id="lname" class="textbox" name="lname" value="<?php echo getPostParameter('lname'); ?>" size="25" /></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>Email:</td>
            <td align="left" valign="top"><input type="text" id="email" class="textbox" name="email" value="<?php echo getPostParameter('email'); ?>" size="25" /></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>Confirmação de Email:</td>
            <td align="left" valign="top"><input type="text" id="cemail" class="textbox" name="cemail" value="<?php echo getPostParameter('cemail'); ?>" size="25" /></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>Senha:</td>
            <td nowrap="nowrap" align="left" valign="top"><input type="password" id="password" class="textbox" name="password" value="" size="25" /><span class="note">&nbsp;&nbsp;6-20 carácter</span></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>Confirmação de senha:</td>
            <td nowrap="nowrap" align="left" valign="top"><input type="password" id="password2" class="textbox" name="password2" value="" size="25" /><span class="note">&nbsp;&nbsp;6-20 carácter</span></td>
          </tr>
          <tr>
            <td align="right" valign="middle"><span class="req">* </span>País:</td>
            <td align="left" valign="top">
				<select name="country" class="textbox2" id="country" style="width: 170px;">
				<option value="">-- Selecione seu País --</option>
				<?php

					$sql_country = "SELECT * FROM cashbackengine_countries ORDER BY name ASC";
					$rs_country = smart_mysql_query($sql_country);
					$total_country = mysql_num_rows($rs_country);

					if ($total_country > 0)
					{
						while ($row_country = mysql_fetch_array($rs_country))
						{
							if ($country == $row_country['name'])
								echo "<option value='".$row_country['name']."' selected>".$row_country['name']."</option>\n";
							else
								echo "<option value='".$row_country['name']."'>".$row_country['name']."</option>\n";
						}
					}

				?>
				</select>
			</td>
          </tr>
          <tr>
            <td align="right" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input type="checkbox" name="newsletter" class="checkboxx" value="1" <?php echo (@$newsletter == 1) ? "checked" : "" ?>/> Gostaria de receber a newsletter</td>
          </tr>
          <tr>
            <td align="right" valign="top">&nbsp;</td>
            <td align="left" valign="top"><input type="checkbox" name="tos" class="checkboxx" value="1" <?php echo (@$tos == 1) ? "checked" : "" ?>/> Eu concordo com o <a href="/terms.php" target="_blank">Termos e Condições</a></td>
          </tr>
        </tr>
          <tr>
            <td align="right" valign="middle">&nbsp;</td>
			<td align="left" valign="middle">
			<?php if (isset($_COOKIE['referer_id']) && is_numeric($_COOKIE['referer_id'])) { ?>
				<input type="hidden" name="referer_id" id="referer_id" value="<?php echo (int)$_COOKIE['referer_id']; ?>" />
			<?php } ?>
				<input type="hidden" name="action" id="action" value="register" />
				<input type="submit" class="submit" name="Register" id="Register" value="Entre" />
		  </td>
          </tr>
        </table>
      </form>

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