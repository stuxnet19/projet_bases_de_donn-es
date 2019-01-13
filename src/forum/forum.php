<?php session_start();

include_once '../classes/addPost.class.php';

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
   

    $bdd = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);


if(!isset($_SESSION['username'])){

    header('Location: ../authentification/inscription.php');
}
else {
    
    if(isset($_POST['name']) AND isset($_POST['sujet'])){
    
    $addPost = new addPost($_POST['name'],$_POST['sujet']);
    $verif = $addPost->verif();
    if($verif == "ok"){
        if($addPost->insert()){
            
        }
    }
    else {/*Si on a une erreur*/
        $erreur = $verif;
    }
    
}
$query = $bdd->query("SELECT nom,prenom,adresse,mail,telephone,no_util,pic_dest,date_up 
                                FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");
$row = $query->fetch();
    
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
                <?php 
                
                 echo 'Bienvenue : '.$_SESSION['username'];
                if(isset($_GET['categorie'])){ /*SI on est dans une categorie*/
                    $_GET['categorie'] = htmlspecialchars($_GET['categorie']);
                    ?>
                    <div class="categories">
                      <h1> <?php echo $_GET['categorie']; ?> </h1>
                    </div>
                <a href="addSujet.php?categorie=<?php echo $_GET['categorie']; ?>">Ajouter un sujet</a>
                <?php 
                $requete = $bdd->prepare('SELECT * FROM sujet WHERE categorie = :categorie ');
                $requete->execute(array('categorie'=>$_GET['categorie']));
                while($reponse = $requete->fetch()){
                    ?>
                     <div class="categories">
                         <a href="forum.php?sujet=<?php echo $reponse['name'] ?>"><p><?php echo $reponse['name'] ?></p></a>
                    </div>
                    <?php
                }
                ?>
                
                    
                    <?php
                }
                
                else if(isset($_GET['sujet'])){ /*SI on est dans un sujet*/
                    $_GET['sujet'] = htmlspecialchars($_GET['sujet']);
                    ?>
                    <div class="categories">
                      <h2><?php echo $_GET['sujet']; ?></h2>
                    </div>
                
                <?php 
                $query = $bdd->prepare('SELECT nom,prenom,contenu FROM utilisateurs INNER JOIN 
                    publications ON utilisateurs.mail=publications.propri WHERE sujet = ?');
                $query->execute(array($_GET['sujet']));
                while($row = $query->fetch()){
                    ?>
                <div class="reponse">
                    <?php 
                            echo $row['nom'].'_'.$row['prenom'].': <br>';
                     
                            echo $row['contenu'];
                    ?>
                 </div> 
                <?php  
                }
                ?>
                
                 <form method="post" action="forum.php?sujet=<?php echo $_GET['sujet']; ?>">
                        <textarea name="sujet" placeholder="Votre reponse" ></textarea>
                        <input type="hidden" name="name" value="<?php echo $_GET['sujet']; ?>" />
                        <input type="submit" value="Ajouter à la conversation" />
                        <?php 
                        if(isset($erreur)){
                            echo $erreur;
                        }
                        ?>
                    </form>
                <?php
                }
                else { /*Si on est sur la page normal*/
                    
                       
                
                        $requete = $bdd->query('SELECT * FROM categories');
                        while($reponse = $requete->fetch()){
                        ?>
                            <div class="categories">
                                <a href="forum.php?categorie=<?php echo $reponse['categorie']; ?>"><?php echo $reponse['categorie']; ?></a>
                              </div>
                
                    <?php 
                    }
                    
                }
                 ?>
                
                
                
                
                
            </div>

    <?php
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



 
    
