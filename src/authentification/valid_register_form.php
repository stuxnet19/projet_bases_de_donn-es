<?php

    require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";
    require_once"../include/valid_form_functions.inc.php";

    if(!empty($_POST['check_nom'])){
        echo check_simple_field($_POST['check_nom'],'TEXT');
        exit();
    }

    if(!empty($_POST['check_prenom'])){
        echo check_simple_field($_POST['check_prenom'],'TEXT');
        exit();
    }

    if(!empty($_POST['check_adrresse'])){
        
        exit();
    }

    if(!empty($_POST['check_mail'])){
        echo check_mail($_POST['check_mail']);
        exit();
    }

    if(!empty($_POST['check_passwd'])){
        echo check_password($_POST['check_passwd']);
        exit();
    }

    if(!empty($_POST['check_tel'])){   
        echo check_phone_nember($_POST['check_tel']);
        exit();
    }

    if(!empty($_POST['check_exp'])){
        echo check_simple_field($_POST['check_exp'],'NUMBER');
        exit();
    }
?>