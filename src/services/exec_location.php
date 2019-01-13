<?php
	
	session_start();
    if(!isset($_SESSION['username']) or !isset($_SESSION['password'])){
        header("refresh:1;url=../authentification/connexion.php");
        echo "<p style='color:red';> redirection vers la page d'authentification ... </p>";
        exit();
    }

	require_once "../classes/Data_Base.class.php" ;
    require_once "../include/connexion.conf.inc.php";
    require_once "../include/fonctions.inc.php";

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();

    if(!empty($_POST['no_mat_dispo']) && !empty($_POST['debut_loc']) && !empty($_POST['fin_loc'])&&             !empty($_POST['adresse_rem']))
    {   
        $sql_util = $pdo->prepare("SELECT no_util FROM utilisateurs WHERE mail=?");
        $sql_util->execute(array($_SESSION['username']));
        $row_util = $sql_util->fetch();

        $sql_prix = $pdo->query("SELECT prix_loc FROM materiels
                                WHERE no_materiel='".$_POST['no_mat_dispo']."'");
        $row_prix = $sql_prix->fetch();

        $datetime1 = new DateTime($_POST['debut_loc']);
        $datetime2 = new DateTime($_POST['fin_loc']);
        $interval = $datetime1->diff($datetime2);
        $nbr_jours = intval($interval->format("%d"));
        $montant_total = $nbr_jours*$row_prix[0];

        $sql_loc = $pdo->prepare("INSERT INTO locations VALUES(?,?,?,?,?,?)");

        try{
            $sql_loc->execute(array(
                            $row_util['no_util'],
                            $_POST['no_mat_dispo'],
                            $_POST['debut_loc'],
                            $_POST['fin_loc'],
                            $_POST['adresse_rem'],
                            $montant_total
                        ));
        }
        catch( PDOException $Exception ) {}

        if($sql_loc->rowCount()>0){
            echo "Location confirmée entre ".$_POST['debut_loc']." et ".$_POST['fin_loc'].
             ". Votre matériel sera livré dans les plus brefs délais à l'adresse indiquée: ".$_POST['adresse_rem'].". Le montant à payer est ".$montant_total." $.";
        }
        else{
        	echo "ERROR";
        }                         
        
    }
?>