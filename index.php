<?php
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Casheiro !!-Compre Certo e Ganhe Mais-!!</title>
    <!--Import materialize.css-->
    <link type="text/css" rel="stylesheet" href="css/materialize.min.css"  media="screen,projection"/>
    <!--Import Google Icon Font-->
    <link href="http://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!--Let browser know website is optimized for mobile-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
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
          <li><nav class="amber darken-3 z-depth-0">
           <div class="nav-wrapper">
             <form>
               <div class="input-field">
                 <input id="search" type="search" required>
                 <label class="label-icon" for="search"><i class="material-icons">search</i></label>
                 <i class="material-icons">close</i>
               </div>
             </form>
           </div>
         </nav></li>
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

    <!--Import jQuery before materialize.js-->
     <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
     <script type="text/javascript" src="js/materialize.min.js"></script>
    <script type="text/javascript">
      $( document ).ready(function(){
        $(".button-collapse").sideNav();
        $(".dropdown-button").dropdown();

      })
    </script>

  </body>
</html>
