<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	if (!(isset($_SESSION['adm']['id']) && is_numeric($_SESSION['adm']['id'])))
	{
		header("Location: login.php");
		exit();
	}

?>