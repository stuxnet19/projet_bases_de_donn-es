<?php

class Data_Base
{	
	// informations de connexion:

	private $sgbd;		
	private $hostname;
	private $database;
	private $username;
	private $password;

	// objet PDO:

	private $pdo = null;

	// connexion:

	private $connected = false;

	// constructeur:

	public function __construct($sgbd, $hostname, $database, $username, $password)
	{
		$this->sgbd=$sgbd;
		$this->hostname=$hostname;
		$this->database=$database;
		$this->username=$username;
		$this->password=$password;

		$this->connect($sgbd,$hostname, $database, $username, $password);
	}

	// fonction de connexion à la base de données:

	private function connect($sgbd,$hostname, $database, $username, $password)
	{
		if($this->pdo === null){
			$dsn = $sgbd.':dbname='.$database.';host='.$hostname;
			try {
				$this->pdo = new PDO($dsn, $username, $password);
				$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$this->connected = true;
			}catch (PDOException $e){
				exit('PDO error in ' . $e->getFile() . ' - Line.' . $e->getLine() . ' : ' . $e->getMessage());
			}
		}
	}

	// fonction de déconnexion:

	public function close_connection()
	{
	 	$this->pdo = null;
	 	$this->connected = false;
	}

	// primitives pdo:

	public function get_PDO(){
		return $this->pdo;
	}

	public function query($statement){	
		return $this->pdo->query($statement);
	}

	public function exec($statement){
		return $this->pdo->exec($statement);
	}

	public function prepare($statement){
		return $this->pdo->prepare($statement);
	}

	public function execute($prepared_query,$parameters){
		return $prepared_query->execute($parameters);
	}

	// Cette fonction prend en paramètre une requete SELECT et retourne le résultat sous forme d'un tableau HTML

	public function selectQuery_HTMLTable($selectQuery)
	{	
		$result = $this->query($selectQuery);
		$result->setFetchMode(PDO::FETCH_ASSOC);
		$row = $result->fetch();
		$fields = array_keys($row);
		$table="<table>\n";
		$table.= "\t<tr><th>".implode("</th><th>",$fields)."</th></tr>\n";
		do{
    		$table.="\t<tr><td>".implode("</td><td>", $row)."</td></tr>\n";
		}while($row = $result->fetch());
		$table.="</table>\n";
		return $table;
	}

	// Cette fonction prend en parametre une requete SELECT sur une colone de la table SQL, et retourne le résultat sous forme d'une liste deroulante HTML

	public function selectColumn_HTMLScrList($selectQuery,$name,$id)
	{
		$result = $this->query($selectQuery);
		$row = $result->fetch();
		$fields = array_keys($row);

		$scrList="\t<select name='".$name."' id='".$id."'>\n";
		do{
			$scrList.="\t\t<option value='".$row[0]."'>".$row[0]."</option>\n";
		}while($row = $result->fetch());
		$scrList.="\t</select>";
		return $scrList;
	}

	// Cette fonction prend en parametre une requete SELECT sur les imges des professionels, et retourne le résultat sous forme d'un tableau HTML

	public function selectPictures_Professionels($selectQuery,$lineSize=5)
	{
		$result = $this->query($selectQuery);
		$row = $result->fetch();
		$k=0;

		$table="<table class='img_table'>\n";
		do{
			if ($k==0) 
			{
    			$table.="<tr>\n";
    		}
			$lastmod= "Uploaded on : ".date('F d Y ', filectime($row[0]));
			
			if ($row[0]==NULL) 
				$src="../images/default_prof.JPEG";
			else
				$src=$row[0];

				$image ="<figure>";
				$image.="<a href='../services/contact.php?id=".$row[1]."_".$row[2]."'><img src='".$src."' 
							alt='".$row[1]."' title='".$lastmod."' height='150' width='150'></a>";
				$image.="<figcaption style='text-align:center;'>".$row[1]." ".$row[2]."</figcaption>";
				$image.="</figure>";
				$table.="<td>$image</td>\n";
			$k++;
			if($k==$lineSize) {
    			$table.="</tr>\n";
    			$k=0;
    		}
		}while($row = $result->fetch());
		$table.="</table>\n";
		return $table;
	}

	// Cette fonction prend en parametre la clé d'un professionnel et retourn sa liste de RDV

	public function liste_RDV_pro($no_util)
	{
		$result = $this->query("SELECT no_util_cli,date_contacte,rendez_vous,motif,nom,prenom,valider,mail FROM utilisateurs INNER JOIN contacter ON utilisateurs.no_util=contacter.no_util_cli WHERE no_util_pro ='".$no_util."'");

		$row = $result->fetch();
		$form=null;

		if($row!=null)
		{
			do{
			$form.="<form  class='form_rdv'>\n
					    <p>Client: ".$row['nom']." ".$row['prenom'].".<br/>
					    	Date: ".$row['rendez_vous'].".<br/>
					    	Motif: ".$row['motif'].".<br/></p>
					    <a href='mailto:".$row['mail']."' class='reponse_btn'>Répondre</a>					    
					</form>\n";
			}while($row = $result->fetch());
			return $form;
		}
		else
			return "<p>(aucun)</p>";
	}

	// Cette fonction prend en parametre la clé d'un client et retourn sa liste de RDV

	public function liste_RDV_cli($no_util)
	{
		$result = $this->query("SELECT nom,prenom,rendez_vous,valider FROM utilisateurs INNER JOIN contacter 
                                                ON utilisateurs.no_util=contacter.no_util_pro
                                                WHERE no_util_cli='".$no_util."'");
		$row = $result->fetch();
		$liste = "<ul>";
		if($row!=null)
		{	
			do{
				$liste.="<li>RDV avec ".$row['nom']." ".$row['prenom']." le ".$row['rendez_vous']."</li>\n";
			}while($row = $result->fetch());
			$liste .= "</ul>";
			return $liste;
		}
		else
			return "<p>(aucun)</p>";
	}

	// Cette fonction prend en parametre la clé d'un client et retourn sa liste de locations:

	public function liste_locations($no_util)
	{
		$result = $this->query("SELECT nom_materiel,date_debut,date_fin  FROM locations NATURAL JOIN materiels
                                WHERE no_util='".$no_util."'");
		$row = $result->fetch();
		$liste = "<ul>";
		if($row!=null)
		{	
			do{
				$liste.="<li>".$row['nom_materiel']." de ".$row['date_debut']." à ".$row['date_fin']."</li>\n";
			}while($row = $result->fetch());
			$liste .= "</ul>";
			return $liste;
		}
		else
			return "<p>(aucune)</p>";
	}

}

?>