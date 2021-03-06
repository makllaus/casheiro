<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
		<title><?php echo $PAGE_TITLE." | ".SITE_TITLE; ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<!--<link href="http://fonts.googleapis.com/css?family=Open+Sans+Condensed:700" rel="stylesheet" type="text/css" />-->
		<link href='https://fonts.googleapis.com/css?family=PT+Sans+Narrow' rel='stylesheet' type='text/css'>
		<link href='https://fonts.googleapis.com/css?family=Montserrat' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" type="text/css" href="/css/style.css" />
		<link rel="stylesheet" href="css/general.css">
	    <link rel="stylesheet" href="css/plugins.css">
	    <link rel="stylesheet" href="css/index.css">
	    <link rel="stylesheet" href="css/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

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

		<script type="text/javascript" src="/js/jquery-1.4.2.min.js"></script>
		<script type="text/javascript" src="/js/jquery.autocomplete.js"></script>
		<script type="text/javascript" src="/js/jsCarousel.js"></script>
		<script type="text/javascript" src="/js/cashbackengine.js"></script>
		<script type="text/javascript" src="/js/jquery.tools.tabs.min.js"></script>
		<script type="text/javascript" src="/js/menuhover.js"></script>
		<link rel="shortcut icon" href="/favicon.ico" />
		<link rel="icon" type="image/ico" href="/favicon.ico" />
</head>
<body>


	<script type="text/javascript">
		$(document).ready(function() {
			$(".bt_menuHover").hide();

			$(".li_menu").mouseenter(function() {
				$(this).find(".bt_menuHover").show();

			});

			$(".li_menu").mouseleave(function() {
				$(this).find(".bt_menuHover").hide();
			});


		});

	</script>
	



<div class="geral" >
	
<div id="container">

<div id="menu">
		<div id="posicionador_menu">
			<ul>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/">Ínicio</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/retailers.php">Lojas</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/coupons.php">Cupons</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/featured.php">Ofertas</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/myaccount.php">Acesso</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/myfavorites.php">Favoritos</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/howitworks.php">Como funciona</a></li>
				<li class="li_menu"><div class="bt_menuHover"></div><a href="/help.php">Ajuda</a>
			</ul>
			
		</div>
	</div>
<div id="header">
	<!--<a href="#" class="scrollup">Topo</a>
	<div id="logo"><a href="<?php echo SITE_URL; ?>"><img src="/images/logo.png" alt="<?php echo SITE_TITLE; ?>" title="<?php echo SITE_TITLE; ?>" border="0" /></a></div>-->
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

</div>


