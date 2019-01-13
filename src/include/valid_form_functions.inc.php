<?php

	function check_mail($mail)
    {
        if(!filter_var($mail,FILTER_VALIDATE_EMAIL)){
            return "Mail non valide!";
        }
        else{
            $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
            $pdo = $db->get_PDO();
            $query = $pdo->query("SELECT * FROM utilisateurs WHERE mail='".$mail."'");
            if($query->rowCount()>0){
                return "Mail déjà utilisé!";
            }else{
                return "OK";
            }
        }
    }

    function check_password($password)
    {	
    	$size = strlen($password);
        if($size<6){
            return "Veuillez saisir plus 5 caractères!";
        }
        elseif($size>30){
        	return "Veuillez saisir moins de 30 caractères!";
        }
        else{
            return "OK";
        }
    }

    function check_phone_nember($phNember)
    {	
    	$valid = (
			preg_match("#^\s*\(?(020[7,8]{1}\)?[ ]?[1-9]{1}[0-9{2}[ ]?[0-9]{4})|(0[1-8]{1}[0-9]{3}\)?[ ]?[1-9]{1}[0-9]{2}[ ]?[0-9]{3})\s*$#",$phNember) ||
			preg_match("#^(?:0|\(?\+33\)?\s?|0033\s?)[1-79](?:[\.\-\s]?\d\d){4}$#",$phNember) ||
			preg_match("#\d{3}[\-]\d{3}[\-]\d{4}#",$phNember)
		);

		if ($valid){
            return "OK";
        }else{
            return "numéro de téléphone non valide!";
        }
    }

    function check_simple_field($val,$type)
    {
    	if($type == 'TEXT'){
    		if(!preg_match("#[^a-zA-Z]#",$val)){
    			return "OK";
    		}
    		else return "Entrez des lettres uniquement!";
    	}
    	elseif($type == 'NUMBER'){
    		if(!preg_match("#[^0-9]#",$val)){
    			return "OK";
    		}
    		else return "Veuillez saisir un nombre valide!";
    	}
        elseif($type='MIXTE'){
            if(!preg_match("#[^a-zA-Z0-9]#",$val)){
                return "OK";
            }
            else return "Sauf les lettres et les chiffres sont permis!";
        }
    }

    function check_date($date)
    {
        if (preg_match("#^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$#",$date)) {
            return "OK";
        } else {
            return "Veuillez saisir une date au format aaaa-mm-jj";
        }
    }

?>