<?php
    session_start ();
    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
    require_once"../include/fonctions.inc.php";

    $userspace = $_SESSION['userspace'];
    
    if(isset($_GET['id']))
    {
        $name = explode("_",$_GET['id'])[0];
        $sur_name = explode("_",$_GET['id'])[1];
    }

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();

    $query = $db->query("SELECT pic_dest,nom,prenom,no_util FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");
    $row = $query->fetch();

    $query1 = $db->query("SELECT no_util,adresse,telephone,mail,pic_dest,date_up,metier,diplome,annees_exp,note FROM utilisateurs INNER JOIN                           professionnels ON utilisateurs.no_util=professionnels.no_util_pro
                                   WHERE nom ='".$name."' AND prenom ='".$sur_name."'");
    $row1 = $query1->fetch();

    $query2 = $pdo->prepare("INSERT INTO contacter VALUES (?,?,?,?,?,?)");

    if (isset($_POST['valid']))
    {      
        $params=array(
                   $row1['no_util'],
                   $row['no_util'],
                   date("d-m-Y"),
                   $_POST['date_cont'],
                   $_POST['motif'],
                   0
        );
        try{
            $query2->execute($params);
            if($query2->rowCount()>0){
                $rdv_status = "<p style='color:green;'>Votre rendez vous est bien pris en compte, 
                le professionnel vous contactera bientot par mail.</p>";
            }
        }
        catch( PDOException $Exception ) {
            $rdv_status = "<p style='color:red;'> Vous avez déjà un RDV avec ce professionnel à cette même date ".$_POST['date_cont'].". Veuillez choisir une autre date.</p>";
        }
    
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Rendez-Vous</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/main_style.css">
        <style>
            #info_perso{
                box-shadow: 2px 2px 2px 2px;
                padding: 2%;
            }
        </style>
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
            <h2>Rendez-Vous</h2>
        </header>

        <div class="wrapper" style="display: flex;">
            <nav id="sidebar">
                <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                <?php
                    echo('<a href="../utilisateurs/'.$userspace.'" >Mon espace personnel</a>');
                ?>
                <a href="../forum/forum.php">Forum</a>
                <a href="http://servicesbatiment.wordpress.com/a-propos/">A propos</a>
                <a href="../authentification/deconnexion.php">Déconnexion</a>
            </nav>
            <div id="content">
                <button type="button"  class="btn" id="menu_btn">
                    <i class="glyphicon glyphicon-align-left"></i>
                    <span>MENU</span>
                </button> 
                <div id="main">
                    <?php

                        $photo = "<img src='../images/default_prof.JPEG' height='150px' alt='photo_pro'/>\n";
                        if ($row1['pic_dest']!=NULL){
                            $photo = "<img src='".$row1['pic_dest']."' height='150px' alt='photo_pro'/>\n";
                        }
                        
                        echo '<form action="contact.php?id='.$_GET['id'].'" method="post" >';
                        echo   '<ul id="info_perso">
                                <li>'.$photo.'</li>                                   
                                    <li>Nom: '.$name.'</li>
                                    <li>Prenom: '.$sur_name.'</li>
                                    <li>Adresse: '.$row1['adresse'].'</li>
                                    <li>Mail: '.$row1['telephone'].'</li>
                                    <li>Telephone: '.$row1['mail'].'</li>
                                    <li>Métier: '.$row1['metier'].'</li>
                                    <li>Diplome: '.$row1['diplome'].'</li>
                                    <li>Années d\'experience: '.$row1['annees_exp'].'</li>
                                </ul>';
                        echo '
                            <input type="text" name="motif" placeholder="motif de votre rendez-vous" maxlength="100" required/>
                            <input type="date" name="date_cont" min="'.date('Y-m-d').'" required/>
                            <input type="submit" name="valid"/>';
                        if(isset($rdv_status)){
                            echo $rdv_status;
                            unset($rdv_status);
                        }

                        echo '</form>';

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
    </body>
</html>
