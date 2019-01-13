<?php
// cette fonction retourne la liste déroulante des métiers:

function listePro()
{
	return '<select name="metier" id="job_list">
		        <option value="Plombier">plombier</option>
		        <option value="Maçon">Maçon</option>
		        <option value="Electricien">Electricien</option>
		        <option value="Jardinier">Jardinier</option>
		        <option value="Agent immobilier">Agent immobilier</option>
		        <option value="Architecte">Architecte</option>
		        <option value="Assembleur de meubles">Assembleur de meubles</option>
		        <option value="Bricolage - petits travaux">Bricolage - petits travaux</option>
		        <option value="Carreleur">Carreleur</option>
		        <option value="Charpentier">Charpentier</option>
		        <option value="Chauffagiste">Chauffagiste</option>
		        <option value="Collecte de déchets">Collecte de déchets</option>
		        <option value="Débouchage canalisation">Débouchage canalisation</option>
		        <option value="Déménageur">Déménageur</option>
		        <option value="Diagnostic immobilier">Diagnostic immobilier</option>
		        <option value="employé de maison">employé de maison</option>
		        <option value="Menuisier">Menuisier</option>
		        <option value="Peintre">Peintre</option>
		        <option value="Serrurier">Serrurier</option>
		        <option value="Spécialiste climatisation">Spécialiste climatisation</option>
		        <option value="Terrassier">Terrassier</option>
		        <option value="Vitrier">Vitrier</option>
	    	</select>';
}

function listeDomaines()
{
	return '<select name="domaine" id="domaine">
                <option value="Plomberie">Plomberie</option>
                <option value="Maçonnerie">Maçonnerie</option>
                <option value="Eléctricité">Eléctricité</option>
                <option value="Jardinage">Jardinage</option>
                <option value="Menuiserie">Menuiserie</option>
                <option value="Peinture">Peinture</option>
                <option value="Climatisation">Climatisation</option>
            </select>';
}
// cette fonction génére aléatoirement un identifiant unique ayant le préfixe et la taille passés en parametres:
function gener_aleat_id($prefix,$size)
{
	mt_srand();

	$id = null;
	for($i=0;$i<$size/3;$i++){
		$id.=mt_rand(1,9); 			// nombre aléatoire
		$id.=chr(mt_rand(65,90)); 	// majuscule aléatoire
		$id.=chr(mt_rand(97,122)); 	// miniscule aleatoire
		$id = str_shuffle($id); 	// mixage aléatoire
	}

	return substr($prefix.$id,0,$size);
}

// cette fonction supprime les char spéciaux d'un string:

function cleanString($text) {
    $utf8 = array(
        '/[áàâãªä]/u'   =>   'a',
        '/[ÁÀÂÃÄ]/u'    =>   'A',
        '/[ÍÌÎÏ]/u'     =>   'I',
        '/[íìîï]/u'     =>   'i',
        '/[éèêë]/u'     =>   'e',
        '/[ÉÈÊË]/u'     =>   'E',
        '/[óòôõºö]/u'   =>   'o',
        '/[ÓÒÔÕÖ]/u'    =>   'O',
        '/[úùûü]/u'     =>   'u',
        '/[ÚÙÛÜ]/u'     =>   'U',
        '/ç/'           =>   'c',
        '/Ç/'           =>   'C',
        '/ñ/'           =>   'n',
        '/Ñ/'           =>   'N',
        '/–/'           =>   '-', 
        '/[’‘‹›‚]/u'    =>   ' ',
        '/[“”«»„]/u'    =>   ' ', 
        '/ /'           =>   ' ',
    );
    $text = preg_replace(array_keys($utf8), array_values($utf8), $text);
    $text = str_replace(' ', '', $text);
    $text = preg_replace('/[^A-Za-z0-9]/','', $text);
    return strtolower($text);
    
}

function upload_image($file_ext,$file_destination,$file_error,$file_size,$file_tmp) {
	
	$allowed = array("png","jpg","jpeg");		
	
	if(in_array($file_ext, $allowed)){
		if($file_error === 0){
			if($file_size <= 2097152){
				if(move_uploaded_file($file_tmp, $file_destination)){
					convertImage($file_destination, '200', '200', $file_ext);
				}
			}
		}
	}	
}	
function convertImage($source, $width, $height, $ext) {
	
	$imageSize = getimagesize($source);
	$ext=strtolower($ext);
	switch($ext) {
		case 'png':
		$imageRessource = imagecreatefrompng($source);
			break;		
		case 'jpg':
		$imageRessource = imagecreatefromjpeg($source);
			break;
		case 'jpeg':
		$imageRessource = imagecreatefromjpeg($source);
			break;	
	}
	$imageFinal = imagecreatetruecolor($width, $height);
	$final = imagecopyresampled($imageFinal, $imageRessource, 0, 0, 0, 0, $width, $height, $imageSize[0], $imageSize[1]);
	
	switch($ext) {
		case 'png':
		imagepng($imageFinal, $source);
			break;
		case 'jpg':
		imagejpeg($imageFinal, $source);
			break;
		case 'jpeg':
		imagejpeg($imageFinal, $source);
			break;	
	}
}
function getPhotosProf($dir,$lineSize=4) {
	$dir=str_replace(' ','_',$dir);
    $tabFiles = scandir($dir);
    $k=0;

    $table="<table class='photosTable'>\n";
    foreach($tabFiles as $file){
        if ($file!= "." && $file!= ".."){
			if ($k==0) {
    			$table.="<tr>\n";
    		}
        	$path = $dir.'/'.$file;
        	$lastmod= "Uploaded on : ".date('F d Y ', filectime($path));
			if (is_file($path)) { 
				$image ="<figure>";
				$proName=substr_replace($file,'',strpos($file,'.'));
				$image.="<a href='profil.php?id=".$proName."'><img src='".$path."' alt='".$file."' title='".$lastmod."'></a>";
				$proName=str_replace('_',' ', $proName);
				$image.="<figcaption style='text-align:center;'>".$proName."</figcaption>";
				$image.="</figure>";
				$table.="<td>$image</td>\n";
			}
			$k++;
			if($k==$lineSize) {
    			$table.="</tr>\n";
    			$k=0;
    		}
        } 	
    }
    if ($k>0 && $k<$lineSize) {
    	while ($k<$lineSize) {
    		$table.="<td></td>\n";
    		$k++;
    	}
    	$table.="</tr>\n";
    }
    $table.="</table>\n";
    return $table;
}
?>