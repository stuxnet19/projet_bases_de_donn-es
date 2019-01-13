<?php
    require_once "../classes/Data_Base.class.php" ;
    require_once "../include/connexion.conf.inc.php";
    require_once "../include/fonctions.inc.php";

    $db = new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
    $pdo = $db->get_PDO();

    if(isset($_GET['search_mat']))
    {   
        $sql = $pdo->prepare("SELECT DISTINCT nom_materiel,image,prix_loc,description FROM materiels");
        $sql->execute();
        $text_input = cleanString($_GET['search_mat']);
        $found = false;
        $lineSize = 4;
        $k=0;
        $tab = '<table class="img_table">';
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)){
            
            $subject = cleanString($row['nom_materiel']);
            if(preg_match('#'.$text_input.'#',$subject)){
                $found = true;
                if($k==0) {
                    $tab.= "<tr>\n";
                }
                $tab.='<td>
                      <figure>
                        <a href="../services/location.php?prepare_location&nom_materiel='.$row['nom_materiel'].'&prix_loc='.$row['prix_loc'].'&description='.$row['description'].'&image='.$row['image'].'">
                        <img src="'.$row['image'].'" title = "'.$row['prix_loc'].' $/jour" height="150" width="200"></a>
                        <figcaption style="text-align:center;">'.$row['nom_materiel'].'</figcaption>
                      </figure>
                      </td>';
                $k++;
                if($k==$lineSize) {
                    $tab.= "</tr>\n";
                    $k=0;
                }
            }
        }
        if($k>0 && $k<$lineSize){
            while ($k<$lineSize){
                $tab.= "<td></td>\n";
                $k++;
            }
            $tab.= "</tr>\n";
        }
        $tab.= "</table>";

        if($found){
            echo $tab;
        }
        else{
            echo "NOT_FOUND";
        }       
        exit();
    }

    if (isset($_POST['search_pro']))
    {
        echo $db->selectPictures_Professionels("SELECT pic_dest,nom,prenom FROM utilisateurs
                                                INNER JOIN professionnels
                                                ON utilisateurs.no_util=professionnels.no_util_pro
                                                WHERE metier='".$_POST['search_pro']."'");
        exit();
    }

    if(isset($_GET['term']))
    {   
        $sql = $pdo->prepare("SELECT DISTINCT nom_materiel FROM materiels");
        $sql->execute();
        $text_input = cleanString($_GET['term']);

        $data = array();
        while ($row = $sql->fetch(PDO::FETCH_ASSOC)){           
            $subject = cleanString($row['nom_materiel']);
            if(preg_match('#'.$text_input.'#',$subject)){
                array_push($data,$row['nom_materiel']);
            }
        }
        echo json_encode($data);
        exit();
    }
    
?>