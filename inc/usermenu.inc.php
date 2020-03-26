	
	<div class="box">
		<div class="top">Minha Conta</div>
		<div class="middle">
				<ul id="account_menu">
					<li><a href="/myaccount.php">Painel</a></li>
					<li><a href="/myclicks.php">Histórico de Cliques</a></li>
					<li><a href="/myreviews.php">Avaliações</a></li>
					<li><a href="/myfavorites.php">Favoritos</a></li>
					<li><a href="/mybalance.php">Saldo &amp; Histórico</a></li>
					<li><a href="/withdraw.php">Área de Saque</a></li>
					<li><a href="/invite.php">Indicar Amigo</li> 
					<li><a href="/myprofile.php">Editar Perfil</a></li>
					<li><a href="/mysupport.php">Suporte</a><?php if (GetMemberMessagesTotal() > 0) { ?> <span class="newnum"><?php echo GetMemberMessagesTotal(); ?></span><?php } ?></li>
					<li><a href="/logout.php">Sair</a></li>
				</ul>
		</div>
		<div class="bottom">&nbsp;</div>
	</div>