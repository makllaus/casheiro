<!DOCTYPE html>
<html>
  <head>
		<title><?php echo $PAGE_TITLE." | ".SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
		<!--Import Google Icon Font-->
		<link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<!--Let browser know website is optimized for mobile-->
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
		<!-- Piwik -->
		<script type="text/javascript">
		  var _paq = _paq || [];
		  // tracker methods like "setCustomDimension" should be called before "trackPageView"
		  _paq.push(['trackPageView']);
		  _paq.push(['enableLinkTracking']);
		  (function() {
		    var u="//www.casheiro.com.br/analytics/";
		    _paq.push(['setTrackerUrl', u+'piwik.php']);
		    _paq.push(['setSiteId', '1']);
		    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
		    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
		  })();
		</script>
		<!-- End Piwik Code -->


		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body>
	<!-- Dropdown Structure -->
	<ul id='dropdown1' class='dropdown-content'>
		<li><a href="#"><i class="material-icons">input</i>Login</a></li>
		<li><a href="#"><i class="material-icons">card_giftcard</i>Cupons</a></li>
		<li><a href="#"><i class="material-icons">shopping_basket</i>Ofertas</a></li>
		<li class="divider"></li>
		<li><a href="#"><i class="material-icons">info_outline</i>Como funciona</a></li>
	</ul>
	<div class="row">
		<nav class="col s12 m12 l12 grey darken-3">
			<div class="col s12 m12 l12 nav-wrapper">
				<a href="index.php" class="brand-logo valing-wrapper"><img class="valing" src="images/logo.svg" alt=""></a>
				<a href="#" data-activates="mobile-demo" class="button-collapse"><i class="material-icons">menu</i></a>

				<ul class="right hide-on-med-and-down">
					<li>
						<nav class="grey darken-2 z-depth-0">
						 <div class="nav-wrapper">
							 <form action="search.php" method="get" id="searchfrm" name="searchfrm">
								 <div class="input-field">
									 <input id="search" type="search" required>
									 <label class="label-icon" for="search"><i class="material-icons">search</i></label>
									 <i class="material-icons">close</i>
								 </div>
							 </form>
						 </div>
					 </nav>
					</li>
					<li><a class="dropdown-button" data-activates='dropdown1' href="#"><i class="material-icons left">menu</i>Menu</a></li>
					<li><a id="bt-sign" href="#" class="waves-effect waves-light btn grey darken-1 z-depth-5">Cadastre-se</a></li>
					<li><a href="#"><i class="material-icons right z-depth-5">help</i>Ajuda</a></li>
				</ul>
					<ul class="side-nav" id="mobile-demo">
						<li>
							<nav class="amber darken-3 z-depth-0">
						 		<div class="nav-wrapper">
								 <form>
									 <div class="input-field">
										 <input id="search" type="search" required>
										 <label class="label-icon" for="search"><i class="material-icons">search</i></label>
										 <i class="material-icons">close</i>
									 </div>
								 </form>
						 		</div>
					 		</nav>
						</li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">input</i>Login</a></li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">card_giftcard</i>Cupons</a></li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">shopping_basket</i>Ofertas</a></li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">add_circle_outline</i>Cadastro</a></li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">favorite_border</i>Favoritos</a></li>
						<li><a href="#"><i class="material-icons amber-text text-darken-3">info_outline</i>Como funciona</a></li>
					</ul>
			</div>
		</nav>
	</div>

	<div id="links">
		 <?php if (isset($_SESSION['userid']) && is_numeric($_SESSION['userid'])) { ?>
			Bem-vindo, <a href="/myprofile.php"><?php echo $_SESSION['FirstName']; ?></a> | Saldo: <span class="mbalance"><?php echo GetUserBalance($_SESSION['userid']); ?></span> | Meus convidados: <a href="/invite.php#referrals"><b><?php echo GetReferralsTotal($_SESSION['userid']); ?></b></a> | <a href="/logout.php">Sair</a>
		<?php }else{ ?>
			<a href="/logi.php"></a>
			<a href="/regist.php"></a>
		<?php } ?>
    </div>
	<div id="searchbox">
		<form action="search.php" method="get" id="searchfrm" name="searchfrm">
			<input type="text" id="searchtext" name="searchtext" class="search_textbox" value="<?php if (isset($stext)) echo $stext; else echo "Pesquisar lojas..."; ?>" onclick="if (this.defaultValue==this.value) this.value=''" onkeydown="this.style.color='#000000'" onblur="if (this.value=='') this.value=this.defaultValue" />
			<input type="hidden" name="action" value="search" />
			<input type="submit" class="search_button" value="" />
		</form>
	</div>
</div>

<div class="separador_logo"></div>

<!--

<div id="column_left">

	<?php if (isset($_SESSION['userid']) && is_numeric($_SESSION['userid'])) { ?>

		<?php require_once ("inc/usermenu.inc.php"); ?>

	<?php }else{ ?>

		<div class="box">
			<div class="top">Área de Membros</div>
			<div class="middle">
				<form action="login.php" method="post">
					E-mail:<br/>
					<input type="text" class="textbox" name="username" value="" size="22" />
					Senha:<br/>
					<input type="password" class="textbox" name="password" value="" size="22" />
		  			<input type="hidden" name="action" value="login" />
					<input type="submit" class="submit" style="margin-top: 2px;" name="login" id="login" value="Entrar" />
					<br/><br/><a href="/forgot.php">Esqueceu sua senha?</a>
					<br/><br/>Não é membro? <a href="/register.php">Cadastre-se!</a>
				</form>
			</div>
			<div class="bottom">&nbsp;</div>
		</div>

	  <?php } ?>
	         <div class="box">
			<div class="top">Lojas por Categoria</div>
			<div class="middle">
				<ul id="categories">
						<li><a href="/retailers.php">Todas Lojas</a></li>
						<?php ShowCategories(0); ?>
				</ul>
			</div>
			<div class="bottom">&nbsp;</div>
		</div> -->

<!--Import jQuery before materialize.js-->
		 <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
		 <script type="text/javascript" src="js/materialize.min.js"></script>
		<script type="text/javascript">
			$( document ).ready(function(){
				$(".button-collapse").sideNav();
				$(".dropdown-button").dropdown();

			})
		</script>

</div>
