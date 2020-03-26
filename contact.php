<?php
/*******************************************************************\
  * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/
	session_start();
	require_once("inc/config.inc.php");

	$content = GetContent('contact');

if (isset($_POST['action']) && $_POST['action'] == 'contact')
{
	unset($errs);
	$errs = array();

	$fname			= getPostParameter('fname');
	$email			= getPostParameter('email');
	$email_subject	= trim(getPostParameter('email_subject'));
	$umessage		= nl2br(getPostParameter('umessage'));

	if (!($fname && $email && $email_subject && $umessage))
	{
		$errs[] = "Please fill in all required fields";
	}
	else
	{
		if (isset($email) && $email !="" && !preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $email))
		{
			$errs[] = "Please enter a valid email address";
		}
	}


	if (count($errs) == 0)
	{
			////////////////////////////////  Send Message  //////////////////////////////
			$message = "<html>
						<head>
							<title>".SITE_TITLE."</title>
						</head>
						<body>
						<table width='80%' border='0' cellpadding='10'>
						<tr>
							<td>";
			$message .= "<p style='font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px;'>";
			$message .= $umessage;
			$message .= "</p>
							</td>
						</tr>
						</table>
						</body>
					</html>";

			$to_email = SITE_MAIL;
		
			$headers  = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
			$headers .= 'From: '.$fname.' <'.$email.'>' . "\r\n";

			if (@mail($to_email, $email_subject, $message, $headers))
			{
				header ("Location: contact.php?msg=1");
				exit();
			}
			////////////////////////////////////////////////////////////////////////////////
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= $errorname."<br/>\n";
	}
}

	///////////////  Page config  ///////////////
	$PAGE_TITLE = $content['title'];

	require_once ("inc/header.inc.php");
	
?>


	<h1><?php echo $content['title']; ?></h1>
	<p><?php echo $content['text']; ?></p>


	<?php if (isset($_GET['msg']) && $_GET['msg'] == 1) { ?>
		<div class="success_msg">Obrigado! Sua mensagem foi enviada</div>
	<?php }?>

	<?php if (isset($allerrors) && $allerrors != "") { ?>
		<div class="error_msg"><?php echo $allerrors; ?></div>
	<?php } ?>

  <form action="" method="post">
  <table width="470" border="0" cellspacing="0" cellpadding="3" align="center">
    <tr>
      <td colspan="2" align="right" valign="top"><span class="req">* indica campo obrigatório</span></td>
    </tr>
    <tr>
      <td valign="middle" align="right"><span class="req">* </span>Seu nome:</td>
      <td align="left" valign="top"><input name="fname" class="textbox" type="text" value="<?php echo getPostParameter('fname'); ?>" size="25" /></td>
    </tr>
    <tr>
      <td valign="middle" align="right"><span class="req">* </span>Seu email:</td>
      <td align="left" valign="top"><input name="email" class="textbox" type="text" value="<?php echo getPostParameter('email'); ?>" size="25" /></td>
    </tr>
        <tr>
      <td valign="middle" align="right"><span class="req">* </span>Assunto:</td>
      <td align="left" valign="top"><input name="email_subject" class="textbox" type="text" value="<?php echo getPostParameter('email_subject'); ?>" size="25" /></td>
	  </td>
    </tr>
    <tr>
      <td valign="top" align="right"><span class="req">* </span>Mensagem:</td>
      <td align="left" valign="top"><textarea rows="10" cols="50" class="textbox2" name="umessage"><?php echo getPostParameter('umessage'); ?></textarea></td>
    </tr>
    <tr>
      <td valign="top">&nbsp;</td>
      <td align="left" valign="middle">
		<input type="hidden" name="action" id="action" value="contact" />
		<input type="submit" class="submit" name="Submit" value="Send message" />
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