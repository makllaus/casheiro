<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();
	require_once("inc/config.inc.php");


if (isset($_POST['action']) && $_POST['action'] == "forgot")
{
	$email = strtolower(mysql_real_escape_string(getPostParameter('email')));

	if (!($email) || $email == "")
	{
		header("Location: forgot.php?msg=1");
		exit();
	}
	else
	{
		if (!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
		{
			header("Location: forgot.php?msg=2");
			exit();
		}
	}
	
	$query = "SELECT * FROM cashbackengine_users WHERE email='$email' AND status='active' LIMIT 1";
	$result = smart_mysql_query($query);

	if (mysql_num_rows($result) > 0)
	{
		$row = mysql_fetch_array($result);
		$newPassword = generatePassword(11);
		$update_query = "UPDATE cashbackengine_users SET password='".PasswordEncryption($newPassword)."' WHERE user_id='".(int)$row['user_id']."'";
		
		if (smart_mysql_query($update_query))
		{
			////////////////////////////////  Send Message  //////////////////////////////
			$etemplate = GetEmailTemplate('forgot_password');
			$esubject = $etemplate['email_subject'];
			$emessage = $etemplate['email_message'];

			$emessage = str_replace("{first_name}", $row['fname'], $emessage);
			$emessage = str_replace("{username}", $row['username'], $emessage);
			$emessage = str_replace("{password}", $newPassword, $emessage);
			$emessage = str_replace("{login_url}", SITE_URL."login.php", $emessage);	
			
			$to_email = $row['fname'].' '.$row['lname'].' <'.$email.'>';
			$subject = $esubject;
			$message = $emessage;
			$from_email = SITE_MAIL;				
		
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.SITE_TITLE.' <'.$from_email.'>' . "\r\n";

			if (@mail($to_email, $subject, $message, $headers))
			{
				header("Location: forgot.php?msg=4");
				exit();
			}
			////////////////////////////////////////////////////////////////////////////////
		}
	}
	else
	{
		header("Location: forgot.php?msg=3");
		exit();
	}

}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Esqueceu a senha";

	require_once "inc/header.inc.php";
	
?>

	<h1>Esqueceu a senha</h1>

    <form action="" method="post">
	<?php if (isset($_GET['msg']) && is_numeric($_GET['msg']) && $_GET['msg'] != 4) { ?>
		<div class="error_msg">
				<?php if ($_GET['msg'] == 1) { echo "Digite seu e-mail"; } ?>
				<?php if ($_GET['msg'] == 2) { echo "Por favor digite um endereço de email válido"; } ?>
				<?php if ($_GET['msg'] == 3) { echo "Desculpe, não conseguimos encontrar o seu endereço de e-mail em nosso sistema!"; } ?>
		</div>
	<?php
			}
			elseif($_GET['msg'] == 4)
			{
				echo "<div class='success_msg'>Sua nova senha foi enviada para seu endereço de e-mail!</div>";
			}
			else
			{ 
				echo "<p align='center'>Por favor, digite seu endereço de email abaixo e nós enviaremos um e-mail que contém a nova senha.</p>";
			}
	?>

	<?php if (isset($_GET['msg']) && $_GET['msg'] == 4) { ?>
		<p align="center"><a class="goback" href="/login.php">Voltar para página de login</a></p>
	<?php } else { ?>
        <table width="70%" align="center" cellpadding="3" cellspacing="0" border="0">
          <tr>
            <td width="50%" align="right" valign="middle" nowrap="nowrap"><b>Seu email:</b></td>
            <td width="25%" align="right" valign="middle"><input type="text" class="textbox" name="email" size="25" value="" /></td>
			<td width="25%" align="left" valign="middle">
		  		<input type="hidden" name="action" value="forgot" />
				<input type="submit" class="submit" name="send" id="send" value="Enviar senha" />
			</td>
          </tr>
        </table>
      </form>
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