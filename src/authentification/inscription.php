<?php
    require_once"../include/fonctions.inc.php";
 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Inscription</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/main_style.css">
           
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
            <h2>Bienvenue</h2>
        </header>

        <div class="wrapper" style="display: flex;">
            <nav id="sidebar">
                <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                <a href="./connexion.php">Connexion</a>
                <a href="http://servicesbatiment.wordpress.com/a-propos/">A propos</a>
            </nav>
            <div id="content">
                <button type="button"  class="btn" id="menu_btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span>MENU</span>
                </button> 
                <div id="main">
                    <form id="register_form" onsubmit="return false;">
                        <h2>Inscription</h2>
                        <label for="nom">Nom :</label><small id="nom_valid"></small>
                        <input type="text" placeholder="Votre nom" id="nom" name="nom" maxlength="30" required/>
                        <label for="prenom">Prénom :</label><small id="prenom_valid"></small>
                        <input type="text" placeholder="Votre prenom" id="prenom" name="prenom" maxlength="30" required/>
                        <label for="adresse">Adresse de domicile :</label><small id="adresse_valid"></small>
                        <input type="text" placeholder="Votre adresse complète" id="adresse" name="adresse" maxlength="60" required/>
                        <label for="telephone">Téléphone :</label><small id="tel_valid"></small>
                        <input type="text" placeholder="Votre telephone" id="telephone" name="telephone" maxlength="15" required/>
                        <label for="mail">Adresse mail :</label><small id="mail_valid"></small><br/>
                        <input type="text" placeholder="Votre mail" id="mail" name="mail" maxlength="50" required/><br/>
                        <label for="password">Mot de passe :</label><small id="passwd_valid"></small>
                        <input type="password" placeholder="votre mot de passe" id="password" name="password" required/>
                        <label for="check_pro">Professionnel:</label>
                        <input type='checkbox' name='check_pro' id="check_pro" onchange="show_hide();" ><br/>
                        <div id="info_pro" style="display:none;">             
                        <?php echo listePro();?>
                        <label for="diplome">Diplome :</label>
                        <input type="text" id="diplome" placeholder="Intitulé de votre diplome" name="diplome" maxlength="50"/>
                        <label for="experience">Experience :</label><small id="exp_valid"></small>
                        <input type="text" id="experience" placeholder="Nombre d'années" name="experience" maxlength="2" />
                        </div>
                        <input type="submit" value="Je m'inscrie" name="inscrire">
                        <div id="register_status"></div>
                </form>
                </div>  
            </div>            
        </div>

        <footer>            
            <p>Site créé par HACHOUD Rassem & METIDJI Fares</p>
        </footer>

        <script src="../js/jquery-1.12.1.js"></script>
        <script src="../js/ajax_inscription.js"></script>
        <script type="text/javascript">
                function show_hide()
                {
                    if(document.getElementById('check_pro').checked){
                        document.getElementById('info_pro').style.display = 'block';
                    }else{
                        document.getElementById('info_pro').style.display = 'none';
                    }
                }
        </script>
         <script type="text/javascript">
             $(document).ready(function () {
                 $('#menu_btn').on('click', function () {
                     $('#sidebar').toggleClass('active');
                 });
             });
         </script>
         <script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCOYAG1qv50Ee4Qr76o636xWozDAW3AxuM" type="text/javascript"></script>
         <script type="text/javascript">
  // Lie le champs adresse en champs autocomplete afin que l'API puisse afficher les propositions d'adresses
  function initializeAutocomplete(id) {
  var element = document.getElementById(id);
  if (element) {
    var autocomplete = new google.maps.places.Autocomplete(element, { types: ['geocode'] });
    google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
  }
}
 
  // Injecte les données dans les champs du formulaire lorsqu'une adresse est sélectionnée


function onPlaceChanged() {
  var place = this.getPlace();

  console.log(place);  // Uncomment this line to view the full object returned by Google API.

  for (var i in place.address_components) {
    var component = place.address_components[i];
    for (var j in component.types) {  // Some types are ["country", "political"]
      var type_element = document.getElementById(component.types[j]);
      if (type_element) {
        type_element.value = component.long_name;
      }
    }
  }
}
 
  // Initialisation du champs autocomplete
  google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('adresse');
  });
</script>

    </body>
</html>

