
	var nom_valid = false;
	var prenom_valid = false;
	var tel_valid =false;
	var mail_valid =false;
	var passwd_valid =false;
	var exp_valid =false;

	function check(result,selector)
	{
		if(result == "OK"){
			control_error_vars(selector,true);
	        $(selector).html('<img src="../images/valid.png" class="check_img"/>');
	    }else{
	    	control_error_vars(selector,false);
	    	if(result!==''){
	    		$(selector).html('<img src="../images/invalid.png" class="check_img"/>'+result).css("color","red");
	    	}else{
	    		$(selector).html('');
	    	}
	    }
	}

	function control_error_vars(selector,value)
	{
		switch (selector) {
		  case '#nom_valid':
		    nom_valid = value;
		    break;
		  case '#prenom_valid':
		    prenom_valid = value;
		    break;
		  case '#tel_valid':
		    tel_valid = value;
		    break;
		  case '#mail_valid':
		    mail_valid = value;
		    break;
		  case '#passwd_valid':
		    passwd_valid = value;
		    break;
		  case '#exp_valid':
		    exp_valid = value;
		    break;
		}
	}

	function valid_hidden_fields()
	{
		if($("#info_pro").css('display')=='none'){
			exp_valid = true;
			
		}
	}

	function post_request(url,input_id,output_id,data,type)
	{
		$.post(
    		url,
    		data,
    		function(result){
    			check(result,output_id);
    		},
    		type
		);  
	}

	function valid_register_form()
	{
		return	(nom_valid == true &&
				 prenom_valid == true &&
				 tel_valid == true &&
				 mail_valid == true &&
				 passwd_valid == true &&
				 exp_valid == true);
	}

	$(document).ready(function(){

		$("#nom").focusout(function(){
	        post_request("valid_register_form.php","#nom","#nom_valid",{'check_nom' : $('#nom').val()},'text');
	    });

	    $("#prenom").focusout(function(){
	        post_request("valid_register_form.php","#prenom","#prenom_valid",{'check_prenom' : $('#prenom').val()},'text');
	    });

	    $("#adresse").focusout(function(){
	        post_request("valid_register_form.php","#adresse","#adresse_valid",{'check_adrresse' : $('#adresse').val()},'text');
	    });

		$("#telephone").focusout(function(){
	        post_request("valid_register_form.php","#telephone","#tel_valid",{'check_tel' : $('#telephone').val()},'text');
	    });

	    $("#mail").focusout(function(){
	    	post_request("valid_register_form.php","#mail","#mail_valid",{'check_mail' : $('#mail').val()},'text');
	    });

	    $("#password").focusout(function(){
	    	post_request("valid_register_form.php","#password","#passwd_valid",{'check_passwd' : $('#password').val()},'text');
	    });

	    $("#experience").focusout(function(){
	    	post_request("valid_register_form.php","#experience","#exp_valid",{'check_exp' : $('#experience').val()},'text');
	    });

	    $("#register_form").submit(function()
	    {
	    	valid_hidden_fields();
	    	if(valid_register_form()){
	    		$.ajax({
			    	type: "post",
		        	url: "traiter_inscription.php",
			        data : $("#register_form").serialize(),
			        datatype : 'text',
			        success: function(result){
			        	if(result == "OK"){
			        		var msg = "Inscription réussie! un mail de confirmation vous a été envoyé.";
			        		$('#register_status').html('<img src="../images/valid.png" class="check_img"/>'+msg).css("color","green");
			        	}else{
			        		var msg = "Une erreur est survenue lors de traitement de l'inscription.";
			        		$('#register_status').html('<img src="../images/invalid.png" class="check_img"/>'+result).css("color","red");
			        	}
		        	}
	    		});
	    	}else{
	    		var msg = "Erreur, vérifiez votre formulaire.";
			    $('#register_status').html('<img src="../images/invalid.png" class="check_img"/>'+msg).css("color","red");
	    	}
		    
		});
	});