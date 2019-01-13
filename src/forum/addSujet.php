<?php session_start();


require_once"../classes/Data_Base.class.php";
require_once"../include/connexion.conf.inc.php";
include_once '../classes/addSujet.class.php';

$bdd = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);

if(isset($_POST['name']) AND isset($_POST['sujet'])){
    
    $addSujet = new addSujet($_POST['name'],$_POST['sujet'],$_POST['categorie']);
    $verif = $addSujet->verif();
    if($verif == "ok"){
        if($addSujet->insert()){
            header('Location: forum.php?sujet='.$_POST['name']);
        }
    }
    else {/*Si on a une erreur*/
        $erreur = $verif;
    }
    
}
?>
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

     <div id="Cforum">
                <?php  echo 'Bienvenue : '.$_SESSION['username'].' :) - <a href="deconnexion.php">Deconnexion</a> '; ?>
                
                <form method="post" action="addSujet.php?categorie=<?php echo $_GET['categorie']; ?>">
                    <p>
                        <br><input type="text" name="name" placeholder="Nom du sujet..." required/><br>
                        <textarea name="sujet" placeholder="Contenu du sujet..."></textarea><br>
                        <input type="hidden" value="<?php echo $_GET['categorie']; ?>" name="categorie" />
                        <input type="submit" value="Ajouter le sujet" />
                        <?php 
                        if(isset($erreur)){
                            echo $erreur;
                        }
                        ?>
                    </p>
                </form>
            </div>
     

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
