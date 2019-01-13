<?php

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
    require_once"../include/valid_form_functions.inc.php";
    require_once"../include/fonctions.inc.php";

    $succes  = false;

    function register()
    {   
        global $succes;
        $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
        $pdo = $db->get_PDO();
        $query = $pdo->prepare("INSERT INTO utilisateurs VALUES (?,?,?,?,?,?,?,?,?,?)");


        $hashedPassWord=password_hash($_POST['password'],PASSWORD_BCRYPT);

        $is_pro = false;

        $no_util = null;
        
        $diplome = "Non renseigné";
        $experience = 0;
        $note = 0;
        $confirmed = FALSE;

        if(isset($_POST['check_pro'])){
            $is_pro = true;


            $no_util = gener_aleat_id('pro_',50);

            if(!empty($_POST['diplome'])) $diplome = $_POST['diplome'];
            if(!empty($_POST['experience'])) $experience = $_POST['experience'];

        }else{
            $no_util = gener_aleat_id('cli_',50);
        }
      
      	$sql = $pdo->query("SELECT DATE( NOW() )");
      	$rowDate = $sql->fetch();

        $params = array(
                        $no_util,
                        $_POST['nom'],
                        $_POST['prenom'],
                        $_POST['adresse'],
                        $_POST['telephone'],
                        $_POST['mail'],
                        $hashedPassWord,
                        "../images/profil.png",
                        $rowDate[0],
                        0
                    );

        $query->execute($params);

        if($is_pro){
            $pdo->exec("INSERT INTO professionnels VALUES ('".$no_util."','".$_POST['metier']."','".$diplome."',".$note.",'".$experience."')");
        }else{
            $pdo->exec("INSERT INTO clients VALUES ('".$no_util."')");
        }

        $message = 'Pour confirmer votre inscription sur servicesbatiment.wordpress.com,
                merci de cliquer sur ce lien: http://servicesbatiment.devwebucp.fr/authentification/confirme_compte.php?key='.urlencode($no_util);

        mail($_POST['mail'],'Confirmation de votre compte',$message);

        $succes = true;
    }

    if(!empty($_POST['nom']) && !empty($_POST['prenom']) && !empty($_POST['adresse']) && !empty($_POST['telephone']) && !empty($_POST['mail']) && !empty($_POST['password'])){

        register();

        if($succes){
            echo "OK";
        }
        else{
            echo "ERROR";
        }
    }
?>