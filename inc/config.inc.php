<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	// Error Reporting
	@error_reporting(E_ALL ^ E_NOTICE);

	/// Database Settings ///
	define('DB_NAME', 'casheiro_com_br');				// MySQL database name
	define('DB_USER', 'casheiro_com_br');				// MySQL database user
	define('DB_PASSWORD', 'zNd7yMan');			// MySQL database password
	define('DB_HOST', 'casheiro.com.br.mysql:3306');		// MySQL database host name (in most cases, it's localhost)

	define('CBengine_ROOT', dirname(__FILE__) . '/');
	define('CBengine_PAGE', TRUE);

	require_once(CBengine_ROOT."db.inc.php");
	require_once(CBengine_ROOT."functions.inc.php");

	if (!defined('is_Setup'))
	{
		require_once(CBengine_ROOT."siteconfig.inc.php");
	}

?>
