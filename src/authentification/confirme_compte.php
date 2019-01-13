<?php
	require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";

    if(isset($_GET['key']) && !empty($_GET['key'])){

    	$db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    	$pdo = $db->get_PDO();

    	$key = urldecode($_GET['key']);

    	$query = $pdo->prepare("SELECT mail,mot_de_passe FROM utilisateurs WHERE no_util=?");
    	$result = $query->execute(array($key));
    	$row = $query->fetch();

    	if($query->rowCount()>0){

    		$query = $pdo->prepare("UPDATE utilisateurs SET confirme = 1 WHERE no_util = ?");
    		$result = $query->execute(array($key));

            header("refresh:2;url=../authentification/connexion.php");
            echo"<p style='color:green;'>Compte confirmé, vous pouvez vous connecter ...</p>";

    	}else{

    		header("refresh:1;url=../authentification/inscription.php"); 
        	echo"<p style='color:red;'>Compte non valide, redirection vers la page d'inscription ...</p>"; 	
    	
    	}
    	exit();
    }else{
    	exit("<p style='color:red;'>Accès interdit. <p/>");
    }
?>