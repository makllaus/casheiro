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
	$cc = 0;

	$query = "SELECT cashbackengine_clickhistory.*, DATE_FORMAT(cashbackengine_clickhistory.added, '%e %b %Y') AS click_date, DATE_FORMAT(cashbackengine_clickhistory.added, '%H:%i:%S') AS click_time, cashbackengine_retailers.* FROM cashbackengine_clickhistory cashbackengine_clickhistory, cashbackengine_retailers cashbackengine_retailers WHERE cashbackengine_clickhistory.user_id='$userid' AND cashbackengine_clickhistory.retailer_id=cashbackengine_retailers.retailer_id ORDER BY cashbackengine_clickhistory.added DESC";
	$result = smart_mysql_query($query);
	$total = mysql_num_rows($result);


	///////////////  Page config  ///////////////
	$PAGE_TITLE = "Click History";

	require_once ("inc/header.inc.php");

?>

    
    <h1>Histórico de Cliques</h1>


    <?php

        if ($total > 0) {
 
    ?>

        <p align="center">Abaixo, você encontrará a lista de todos os lugares que você visitou. Podendo acompanhar toda sua movimentação.</p>

            <table align="center" width="85%" border="0" style="border-bottom: solid 1px #EEEEEE;" cellspacing="0" cellpadding="3">
              <tr>
                <th width="70%">Loja</th>
                <th width="30%">Data/Hora</th>
              </tr>
            <?php while ($row = mysql_fetch_array($result)) { $cc++; ?>
              <tr class="<?php if (($cc%2) == 0) echo "row_even"; else echo "row_odd"; ?>">
                <td valign="middle" align="center"><a class="click_history" href="/go2store.php?id=<?php echo $row['retailer_id']; ?>" target="_blank"><?php echo $row['title']; ?></a></td>
                <td valign="middle" align="center"><?php echo $row['click_date']." ".$row['click_time']; ?></td>
              </tr>
            <?php } ?>
           </table>

    <?php }else{ ?>
                <p align="center">Ainda não há cliques.<br/><br/><a class="goback" href="#" onclick="history.go(-1);return false;">Voltar</a></p>
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