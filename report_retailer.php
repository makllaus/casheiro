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

	$userid			= (int)$_SESSION['userid'];


if (isset($_POST['action']) && $_POST['action'] == "report")
{
	unset($errs);
	$errs = array();

	$retailer_id	= (int)getPostParameter('rid');
	$report			= mysql_real_escape_string(nl2br(getPostParameter('report')));

	if (!($report))
	{
		$errs[] = "Por favor, descrever o problema";
	}
	else
	{
		$check_query = smart_mysql_query("SELECT * FROM cashbackengine_reports WHERE reporter_id='$userid' AND retailer_id='$retailer_id'");
		if (mysql_num_rows($check_query) != 0)
		{
			$errs[] = "Você tem atualmente relatado para este varejista.";
		}
	}


	if (count($errs) == 0)
	{
		$query = "INSERT INTO cashbackengine_reports SET reporter_id='$userid', retailer_id='$retailer_id', report='$report', viewed='0', status='active', added=NOW()";
		$result = smart_mysql_query($query);
	
		header("Location: /report_retailer.php?id=$retailer_id&msg=1");
		exit();
	}
	else
	{
		$allerrors = "";
		foreach ($errs as $errorname)
			$allerrors .= "&#155; ".$errorname."<br/>\n";
	}

}


	if (isset($_GET['id']) && is_numeric($_GET['id']))
	{
		$retailer_id = (int)$_GET['id'];
	}
	else
	{		
		header ("Location: index.php");
		exit();
	}

	$query = "SELECT * FROM cashbackengine_retailers WHERE retailer_id='$retailer_id' AND status='active' LIMIT 1"; 
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Report Retailer";

	require_once ("inc/header.inc.php");

?>

	<h1>Report Retailer</h1>


	<?php if (isset($_GET['msg']) && $_GET['msg'] == 1) { ?>
		<div class="success_msg">Thank you! Your report has been sent to us.</div>
	<?php } ?>

	<?php if (isset($allerrors) && $allerrors != "") { ?>
		<div class="error_msg"><?php echo $allerrors; ?></div>
	<?php } ?>


	<?php

		if ($total > 0) {

			$row = mysql_fetch_array($result);

	?>

		<?php if (!(isset($_GET['msg']) && $_GET['msg'] == 1)) { ?>		
			<img src="/images/report.png" align="right" />
			<b><?php echo $row['title']; ?></b>
			
			<p>Incorrect info or broken link? Please let us know.</p>
			<form action="" method="post">
			<textarea name="report" cols="55" rows="5" class="textbox2"><?php echo getPostParameter('report'); ?></textarea>
				<input type="hidden" name="rid" value="<?php echo (int)$row['retailer_id']; ?>" />
				<input type="hidden" name="action" value="report" /><br/><br/>
				<input type="submit" class="submit" value="Submit" />
				<input type="button" class="cancel" name="cancel" value="Cancel" onclick="history.go(-1);return false;" />
			</form>
		<?php } ?>

	<?php }else{ ?>
		<p align="center">Sorry, no retailer found!</p>
		<p align="center"><a class="goback" href="/">Go Back</a></p>
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