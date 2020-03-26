<?php
/*******************************************************************\
 * http://www.casheiro.com
 *
 * Copyright (c) 2010-2013 SPACE-TEK. All rights reserved.
 * ------------ Casheiro - Compre certo e Ganhe mais --------------
\*******************************************************************/

	if (!defined("CBengine_PAGE")) exit();

	$conn = @mysql_connect(DB_HOST, DB_USER, DB_PASSWORD) or die ('Não foi possível conectar ao servidor MySQL');
	@mysql_select_db(DB_NAME, $conn) or die ('Não foi possível selecionar banco de dados');

?>