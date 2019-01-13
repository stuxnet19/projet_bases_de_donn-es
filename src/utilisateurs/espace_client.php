<?php
    session_start ();
    if (!isset($_SESSION['username']) or !isset($_SESSION['password'])) {
        header("refresh:1;url=../authentification/connexion.php"); 
        echo"<p style='color:red;'>redirection vers la page d'authentification ...</p>";
        exit();
    }

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
    require_once"../include/fonctions.inc.php";

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();

    $query = $db->query("SELECT nom,prenom,adresse,mail,telephone,no_util,pic_dest,date_up 
                                FROM utilisateurs WHERE mail ='".$_SESSION['username']."'");

    $row = $query->fetch();

        if (isset($_POST['upload'])) {
            if (isset($_FILES['file'])) {
                $file = $_FILES['file'];

                $file_name = $file['name'];
                $file_tmp = $file['tmp_name'];
                $file_size = $file['size'];
                $file_error = $file['error'];

                $file_ext = explode('.', $file_name);
                $file_ext = strtolower(end($file_ext));

                $file_name_new = strtoupper($row[0])."_".$row[1].'.'.$file_ext;
                $file_destination = "../ressources/photo_utilisateurs/".$file_name_new;
                $no_util = $row[5];
                $date=date('l-j-m-Y G:m:s',time());

                $db->query("UPDATE utilisateurs SET pic_dest='".$file_destination."' WHERE no_util='".$no_util."'");
                $db->query("UPDATE utilisateurs SET date_up='".$date."' WHERE no_util='".$no_util."'");

                upload_image($file_ext,$file_destination,$file_error,$file_size,$file_tmp);

                header("Location: ./espace_client.php");
            }
        }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Espace client</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
        <link rel="stylesheet" href="../style/main_style.css">
        <style>
            .input-lable:hover{
                background-color: #bfbfbf;
            }
            .submit-lable:hover{
                text-decoration: underline;
            }
            #info_perso{
                box-shadow: 2px 2px 2px 2px;
                padding: 2%;
            }

        </style>
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
            <h2>Mon espace client</h2>
        </header>

        <div class="wrapper" style="display: flex;">
            <nav id="sidebar">
                <a href="https://servicesbatiment.wordpress.com">Accueil</a>
                <a href="#" id="info_href">Mes informations</a>
                <a href="#" id="loc_href">Locations</a>
                <a href="#" id="rdv_href">Prendre rendez-vous</a>
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
                    <div id="div_info_perso">                             
                        <?php

                            echo '<form method="post" action="espace_client.php" enctype="multipart/form-data">';
                            $photo_profil = "<input type='image' alt='photo_profil' src='../images/profil.png' height='150' width='150'/>";

                            if (isset($_POST['upload']) || $row[6]!=NULL){
                                $photo_profil = "<input type='image' alt='photo_profil' src='".$row[6]."' 
                                    height='150' width='150'/>";
                            }

                            $upload_fields = '<p id="upload_fields" style="display: none;">
                            <label for="file" class="input-lable" ><img src="../images/img.png" height="30" /></label>
                            <input id="file" type="file" name="file" style="display: none;" required/>
                            <label for="submit" class="submit-lable">upload</label>
                            <input type="submit" name="upload" id="submit" style="display: none;">
                            <p>';

                            echo '<ul id="info_perso">
                                    <li>'.$photo_profil.'</li>
                                    <li>'.$upload_fields.'</li>
                                    <li>Nom: '.$row[0].'</li>
                                    <li>Prenom: '.$row[1].'</li>
                                    <li>Adresse: '.$row[2].'</li>
                                    <li>Mail: '.$row[3].'</li>
                                    <li>Telephone: '.$row[4].'</li>
                                 </ul>';
                            echo '</form>';
                        ?>
                        <div id="mes_actions" style="margin-left: 20%;">
                            <h3 style="color: #ca2017;">Mes rendez-vous</h3>
                            <?php
                                echo $db->liste_RDV_cli($row['no_util']);
                            ?>
                            <h3 style="color: #ca2017;">Mes locations</h3>
                            <?php
                                echo $db->liste_locations($row['no_util']);
                            ?>
                        </div>
                    </div> 

                    

                    <form id="search_mat_form" class="search_form" onsubmit="return false;" style="display: none;" >
                        <h2>Rechercher un matériel:</h2>
                        <input type="text" spellcheck="true" id="search_mat" class="textbox" placeholder="saisir le nom de matériel ...">
                        <input value="OK" type="submit" class="button">
                        <small id="mat_not_found" class="error_msg"></small>
                    </form>
                    <div id="search_mat_result"></div>
                    <form id="search_pro_form" class="search_form" onsubmit="return false;" style="display: none;" >
                        <h2>Liste de professionnels:</h2>
                        <?php
                            echo $db->selectColumn_HTMLScrList("SELECT DISTINCT metier FROM professionnels",
                                                                "metier","job_list");
                        ?>
                    </form>
                    <div id="search_pro_result"></div>
                </div>  
            </div>            
        </div>

        <footer>            
            <p>Site créé par HACHOUD Rassem & METIDJI Fares</p>
        </footer>
         <script src="../js/jquery-1.12.1.js"></script>
         <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
         <script src="../js/ajax_users.js"></script>
    </body>
</html>
