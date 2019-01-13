<?php 
require_once"../classes/Data_Base.class.php";
    require_once"../include/connexion.conf.inc.php";

class addPost{
    
    private $sujet;
    private $name;
    private $bdd;
    
    public function __construct($name,$sujet) {
        
        
        $this->sujet = htmlspecialchars($sujet);
        $this->name = htmlspecialchars($name);
        $this->bdd =  new Data_Base(SGBD,HOST,DBNAME,USER,PASSWORD);
        
    }
    
    
    public function verif(){
        
           if(strlen($this->sujet) > 0){ /*Si on a bien un sujet*/
                
                return 'ok';
            }
            else {/*Si on a pas de contenu*/
                $erreur = 'Veuillez entrer le contenu du sujet';
                return $erreur;
            }
            
      
        
    }
    
    public function insert(){
       
        
        $requete2 = $this->bdd->prepare('INSERT INTO publications(propri,contenu,date_post,sujet) VALUES(:propri,:contenu,NOW(),:sujet)');
        $requete2->execute(array('propri'=>$_SESSION['username'],'contenu'=>  $this->sujet,'sujet'=>  $this->name));
        
        return 1;
    }
    
}