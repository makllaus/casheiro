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

	$userid = (int)$_SESSION['userid'];

	$query	= "SELECT * FROM cashbackengine_users WHERE user_id='$userid' AND status='active' LIMIT 1";
	$result = smart_mysql_query($query);
	$total	= mysql_num_rows($result);

	if ($total > 0)
	{
		$row = mysql_fetch_array($result);
	}
	else
	{
		header ("Location: logout.php");
		exit();
	}

	
if (isset($_POST['action']) && $_POST['action'] == "editprofile")
{
	$fname			= mysql_real_escape_string(ucfirst(strtolower(getPostParameter('fname'))));
	$lname			= mysql_real_escape_string(ucfirst(strtolower(getPostParameter('lname'))));
	$email			= mysql_real_escape_string(strtolower(getPostParameter('email')));
	$newsletter		= (int)getPostParameter('newsletter');
	
	unset($errs);
	$errs = array();

	if(!($fname && $lname && $email))
	{
		$errs[] = "Por favor preencha todos os campos";
	}

	if(isset($email) && $email !="" && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
	{
		$errs[] = "Por favor digite um endereço de email válido";
	}

	if (count($errs) == 0)
	{
		$up_query = "UPDATE cashbackengine_users SET email='$email', fname='$fname', lname='$lname', newsletter='$newsletter' WHERE user_id='$userid'";
	
		if (smart_mysql_query($up_query))
		{
			$_SESSION['FirstName'] = $fname;
			header("Location: myprofile.php?msg=1");
			exit();
		}
	}

}


if (isset($_POST['action']) && $_POST['action'] == "changepwd")
{
	$pwd		= mysql_real_escape_string(getPostParameter('password'));
	$newpwd		= mysql_real_escape_string(getPostParameter('newpassword'));
	$newpwd2	= mysql_real_escape_string(getPostParameter('newpassword2'));

	$errs2 = array();

	if (!($pwd && $newpwd && $newpwd2))
	{
		$errs2[] = "Por favor preencha todos os campos";
	}
	else
	{
		if (PasswordEncryption($pwd) !== $row['password'])
		{
			$errs2[] = "Sua senha atual está errada. Por favor, tente novamente!";
		}

		if ($newpwd !== $newpwd2)
		{
			$errs2[] = "Confirmação de senha está errada";
		}
		elseif ((strlen($newpwd)) < 6 || (strlen($newpwd2) < 6) || (strlen($newpwd)) > 20 || (strlen($newpwd2) > 20))
		{
			$errs2[] = "A senha deve ter entre 6-20 caracteres (letras e números).";
		}
		elseif (stristr($newpwd, ' '))
		{
			$errs2[] = "A senha não deve conter espaços";
		}
	}

	if (count($errs2) == 0)
	{
		$upp_query = "UPDATE cashbackengine_users SET password='".PasswordEncryption($newpwd)."' WHERE user_id='$userid'";
	
		if (smart_mysql_query($upp_query))
		{
			header("Location: myprofile.php?msg=2");
			exit();
		}	
	}

}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Meu Perfil";

	require_once ("inc/header.inc.php");

?>

        <h1>Meu Perfil</h1>

		<?php if (isset($_GET['msg']) && is_numeric($_GET['msg'])) { ?>
			<div class="success_msg">
				<?php

					switch ($_GET['msg'])
					{
						case "1": echo "Seu perfil foi atualizado com sucesso!"; break;
						case "2": echo "Senha foi alterada com sucesso!"; break;
					}

				?>
			</div>
		<?php } ?>



		<?php
				if (count($errs) > 0)
				{
					foreach ($errs as $errorname)
					{
						$allerrors .= "&#155; ".$errorname."<br/>\n";
					}
					echo "<div class='error_msg'>".$allerrors."</div>";
				}
		?>

		<center><h3>Meus Detalhes</h3></center>

		<form action="" method="post">
          <table width="70%" align="center" cellpadding="5" cellspacing="0" border="0">
            <tr>
              <td width="150" align="right" valign="middle">Usuário:</td>
              <td align="left" valign="top"><span class="username"><?php echo $row['username']; ?></span></td>
            </tr>
            <tr>
              <td align="right" valign="middle">Primeiro nome:</td>
              <td align="left" valign="top"><input type="text" class="textbox" name="fname" id="fname" value="<?php echo $row['fname']; ?>" size="25" /></td>
            </tr>
            <tr>
              <td align="right" valign="middle">Ultimo nome:</td>
              <td align="left" valign="top"><input type="text" class="textbox" name="lname" id="lname" value="<?php echo $row['lname']; ?>" size="25" /></td>
            </tr>
            <tr>
              <td align="right" valign="middle">E-mail:</td>
              <td align="left" valign="top"><input type="text" class="textbox" name="email" id="email" value="<?php echo $row['email']; ?>" size="25" /></td>
            </tr>
			<tr>
				<td align="right" valign="top">Notícias:</td>
				<td align="left" valign="top"><input type="checkbox" name="newsletter" class="checkboxx" value="1" <?php echo (@$row['newsletter'] == 1) ? "checked" : "" ?>/> Gostaria de receber as notícias
				</td>
			</tr>
           <tr>
            <td colspan="2" align="center" valign="bottom">
				<input type="hidden" name="action" value="editprofile" />
				<input name="uid" type="hidden" value="<?php echo (int)$row['user_id']; ?>" />
				<input type="submit" class="submit" name="Update" id="Update" value="Atualizar detalhes" />&nbsp;&nbsp;
				<input type="button" class="cancel" name="cancel" value="Cancelar" onClick="javascript:document.location.href='myaccount.php'" />
			</td>
          </tr>
          </table>
        </form>
		<br/>


		<center><h3>Alterar Senha</h3></center>

		<?php
				if (count($errs2) > 0)
				{
					foreach ($errs2 as $errorname)
					{
						$allerrors .= "&#155; ".$errorname."<br/>\n";
					}
					echo "<div class='error_msg'>".$allerrors."</div>";
				}
		?>
		  <form action="" method="post">
          <table width="70%" align="center" cellpadding="5" cellspacing="0" border="0">
            <tr>
              <td width="150" nowrap="nowrap" align="right" valign="middle">Senha atual:</td>
              <td align="left" valign="top"><input type="password" class="textbox" name="password" id="password" value="" size="25" /></td>
            </tr>
            <tr>
              <td nowrap="nowrap" align="right" valign="middle">Nova senha:</td>
              <td align="left" valign="top"><input type="password" class="textbox" name="newpassword" id="newpassword" value="" size="25" /></td>
            </tr>
            <tr>
              <td nowrap="nowrap" align="right" valign="middle">Confirmar nova senha:</td>
              <td align="left" valign="top"><input type="password" class="textbox" name="newpassword2" id="newpassword2" value="" size="25" /></td>
            </tr>
          <tr>
            <td colspan="2" align="center" valign="bottom">
				<input type="hidden" name="action" value="changepwd" />
				<input name="uid" type="hidden" value="<?php echo (int)$row['user_id']; ?>" />
				<input type="submit" class="submit" name="Change" id="Change" value="Alterar senha" />&nbsp;&nbsp;
				<input type="button" class="cancel" name="cancel" value="Cancelar" onClick="javascript:document.location.href='myaccount.php'" />
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