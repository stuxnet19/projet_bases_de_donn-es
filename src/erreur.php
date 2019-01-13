
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Connexion</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="../style/styleforum.css">
        <script src="https://www.google.com/recaptcha/api.js"></script>
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
                <a href="../utilisateurs/espace_client.php#" id="info_href">Mes informations</a>
                <a href="../utilisateurs/espace_client.php#" id="loc_href">Locations</a>
                <a href="../utilisateurs/espace_client.php#" id="rdv_href">Prendre rendez-vous</a>
                <a href="../forum/forum.php">Forum</a>
                <a href="http://servicesbatiment.wordpress.com/a-propos/">A propos</a>
                <a href="../authentification/deconnexion.php">Déconnexion</a>
            </nav>
            <div id="content">
                <button type="button"  class="btn" id="menu_btn">
                    <span>MENU</span>
                </button> 
                <div id="main">

   
                
<?php
switch($_GET['erreur'])
{
   case '400':
   echo 'Échec de l\'analyse HTTP.';
   break;
   case '401':
   echo 'Le pseudo ou le mot de passe n\'est pas correct !';
   break;
   case '402':
   echo 'Le client doit reformuler sa demande avec les bonnes données de paiement.';
   break;
   case '403':
   echo 'Requête interdite !';
   break;
   case '404':
   echo 'La page n\'existe pas ou plus !';
   break;
   case '405':
   echo 'Méthode non autorisée.';
   break;
   case '500':
   echo 'Erreur interne au serveur ou serveur saturé.';
   break;
   case '501':
   echo 'Le serveur ne supporte pas le service demandé.';
   break;
   case '502':
   echo 'Mauvaise passerelle.';
   break;
   case '503':
   echo ' Service indisponible.';
   break;
   case '504':
   echo 'Trop de temps à la réponse.';
   break;
   case '505':
   echo 'Version HTTP non supportée.';
   break;
   default:
   echo 'Erreur !';
}
?>




     

                </div>  
            </div>            
        </div>

        <footer>            
            <p>Site créé par HACHOUD Rassem & METIDJI Fares</p>
        </footer>

        <script src="../js/jquery-1.12.1.js"></script>
        <script type="text/javascript">
             $(document).ready(function () {
                 $('#menu_btn').on('click', function () {
                     $('#sidebar').toggleClass('active');
                 });
             });
        </script>
        <?php
            $db->close_connection();
        ?>
    </body>
</html>
