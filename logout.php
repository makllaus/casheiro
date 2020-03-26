<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	session_start();

	unset($_SESSION['userid'], $_SESSION['FirstName'], $_SESSION['goRetailerID']);
	
	session_destroy();

	header("Location: login.php");
	exit();
	
?>