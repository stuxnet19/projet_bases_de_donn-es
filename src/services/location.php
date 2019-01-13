<?php
    session_start();
    if(!isset($_SESSION['username']) or !isset($_SESSION['password'])){
        header("refresh:1;url=../authentification/connexion.php");
        echo "<p style='color:red';> redirection vers la page d'authentification ... </p>";
        exit();
    }

    $userspace = $_SESSION['userspace'];

    require_once "../classes/Data_Base.class.php" ;
    require_once "../include/connexion.conf.inc.php";
    require_once "../include/fonctions.inc.php";

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>location de matériel</title>
        <link rel="stylesheet" href="../bootstrap-4.0.0-dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="../style/main_style.css">
    </head>
    <body>
        <header>
            <h1>Services-Batiment.com</h1>
            <h2>Location de matériel</h2>
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
                    if (isset($_GET['prepare_location']))
                    {   
                        if(!empty($_GET['nom_materiel'])){

                            $nom_materiel = $_GET['nom_materiel'];
                            $min_date_debut = null;
                            $no_mat_dispo = null;

                            $req_mat_dispo = $pdo->prepare("SELECT no_materiel FROM materiels
                                                            WHERE nom_materiel = ?
                                                            AND no_materiel NOT IN
                                                            (SELECT no_materiel FROM locations
                                                            WHERE date_fin >= DATE(NOW()))");

                            $req_mat_dispo->execute(array($nom_materiel));

                            if($req_mat_dispo->rowCount()>0){
                                $row_mat_dispo =  $req_mat_dispo->fetch(); 
                                $no_mat_dispo = $row_mat_dispo['no_materiel'];
                                $min_date_debut = date('Y-m-d');
                                $min_date_fin = date('Y-m-d', strtotime($min_date_debut. ' + 1 days'));
                                echo ('
                                    <div class="info_mat">
                                        <h4>Vous avez choisi :'.$_GET['nom_materiel'].'</h4>
                                        <img height="250" width="300" src="'.$_GET['image'].'"/>
                                        <p id="prix_mat">Prix: '.$_GET['prix_loc'].' $/jour </p>
                                        <p id="desc_mat">Description:'.$_GET['description'].'</p>
                                        <p id="succes_loc"><p>
                                        <button type="button" id="show_loc_form_btn"  class="btn" onclick="show_Loc_form();">Louer</button>
                                    </div>
                                    <form  method="post" style="display:none;" id="loc_form" onsubmit="return false;" >
                                        <h4>Prochaine disponibilité: '.$min_date_debut.'</h4>
                                        <label> Date de début : </label>
                                        <input type ="date" name="debut_loc" id="debut_loc" min="'.$min_date_debut.'" required/>
                                        <label> Date de fin : </label>
                                        <input type ="date" name="fin_loc" id="fin_loc" min="'.$min_date_fin.'" required/><br/>
                                        <label>Adresse:</label>
                                        <input type ="text" name="adresse_rem" id="adresse_rem" placeholder="Adresse de remise" required/>
                                        <input type="hidden" name="no_mat_dispo" id="no_mat_dispo" value="'.$no_mat_dispo.'"/>
                                        <input type="submit" value="VALIDER"/>
                                        <p id="location_status"></p>
                                    </form>
                                ');
                            }
                            else{
                                $sql_min_date_dispo = $pdo->prepare("
                                    SELECT MIN(date_fin) FROM locations loc
                                    NATURAL JOIN materiels mat
                                    WHERE mat.nom_materiel = ?
                                    AND DATE(NOW()) < loc.date_fin");
                                $sql_min_date_dispo->execute(array($nom_materiel));
                                $row_min_date_dispo = $sql_min_date_dispo->fetch();

                                echo '<p style="text-align:center;color:Chocolate;">Tous les matériels '.$_GET['nom_materiel'].' sont en cours de location. Prochaine disponibilité vers le : '.$row_min_date_dispo[0].' .</p>';
                            }
                        }    
                    }
                    else{
                        header("refresh:1;url=./materiel.php");
                        echo "<p style='color:red';>Veuillez choisir un matériel</p>";
                        exit();
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

                 $("#loc_form").submit(function(){
                    $.ajax({
                        type: "post",
                        url: "./exec_location.php",
                        data :{
                            'no_mat_dispo':$('#no_mat_dispo').val(),
                            'debut_loc':$('#debut_loc').val(),
                            'fin_loc':$('#fin_loc').val(),
                            'adresse_rem':$('#adresse_rem').val()
                        },
                        datatype : 'text',
                        success: function(result){
                            if(result=='ERROR'){
                                $('#location_status').html('<small style="color:red">Une erreur est survenue lors de traitemet de votre commande.</small>');
                            }
                            else{
                                $('#loc_form').css("display","none");
                                $('#prix_mat').css("display","none");
                                $('#desc_mat').css("display","none");
                                $('#succes_loc').html(result);
                            }
                     
                        }
                    });        
                });

             });
         </script>
         <script type="text/javascript">
             function show_Loc_form()
             {
                document.getElementById('loc_form').style.display = 'block';
                document.getElementById('show_loc_form_btn').style.display = 'none';
             }
         </script>
          <script src="https://maps.googleapis.com/maps/api/js?libraries=places&amp;key=AIzaSyCOYAG1qv50Ee4Qr76o636xWozDAW3AxuM" type="text/javascript"></script>
         <script type="text/javascript">
  // Lie le champs adresse en champs autocomplete afin que l'API puisse afficher les propositions d'adresses
  function initializeAutocomplete(id) {
  var element = document.getElementById(id);
  if (element) {
    var autocomplete = new google.maps.places.Autocomplete(element, { types: ['geocode'] });
    google.maps.event.addListener(autocomplete, 'place_changed', onPlaceChanged);
  }
}
 
  // Injecte les données dans les champs du formulaire lorsqu'une adresse est sélectionnée


function onPlaceChanged() {
  var place = this.getPlace();

  console.log(place);  // Uncomment this line to view the full object returned by Google API.

  for (var i in place.address_components) {
    var component = place.address_components[i];
    for (var j in component.types) {  // Some types are ["country", "political"]
      var type_element = document.getElementById(component.types[j]);
      if (type_element) {
        type_element.value = component.long_name;
      }
    }
  }
}
 
  // Initialisation du champs autocomplete
  google.maps.event.addDomListener(window, 'load', function() {
    initializeAutocomplete('adresse_rem');
  });
</script>
    </body>
</html>
