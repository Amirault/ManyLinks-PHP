<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once("isBlank.php");
	chdir("../");
	require_once ('config.php');
	chdir($wd_was);
?> 
<?php
function get_file_title($file)
{
$timeout = 5;
//L'url saisie est valide
$old = ini_set('default_socket_timeout', $timeout);	
$cont = file_get_contents($file);
ini_set('default_socket_timeout', $old);
stream_set_timeout($file, $timeout);
stream_set_blocking($file, 0);

preg_match( "/\<title\b.*\>(.*)\<\/title\>/i", $cont, $match );
return strip_tags($match[0]);

}
function delFav($Favovis,$CatParent)
{
	if (isset($Favovis))
	{
		// Suppression du ou des favoris selectionné
		foreach ($Favovis as $URL_Favoris)
		{
			if ($CatParent != null)
			{
				// Suppression du favoris dans la catégorie appartenant à l'utilisateur
				$requete = "DELETE FROM categorie_favoris WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND URL_Favoris = '".mysql_real_escape_string($URL_Favoris)."' AND ID_Categorie = ".mysql_real_escape_string($CatParent).";";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
			}
			else
			{
				// Supression de tout les favoris dans toute les catégorie appartenant à l'utilisateur
				$requete = "DELETE FROM categorie_favoris WHERE URL_Favoris = '".mysql_real_escape_string($URL_Favoris)."' AND Email_Membre='".mysql_real_escape_string($_SESSION['email'])."';";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
			}
			$requete = "SELECT COUNT('URL_Favoris') as Nb FROM categorie_favoris WHERE URL_Favoris = '".mysql_real_escape_string($URL_Favoris)."' AND Email_Membre='".mysql_real_escape_string($_SESSION['email'])."';";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
			$resultat = mysql_fetch_array($resultat);
			if ($resultat['Nb'] == 0)
			{
				$requete = "DELETE FROM favoris_publie WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND URL_Favoris = '".mysql_real_escape_string($URL_Favoris)."';";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
			}
		}
		return true;
	}
	return false;
}
function isFavExist($url)
{
	/*
	//Verifie si le favoris en paramètre existe dans la table 'favoris' 
	// Si oui il retourne le favoris
	// Sinon retourne null
	*/
	$requete = "SELECT * from favoris where URL='".mysql_real_escape_string($url)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['URL']) && !isblank($resultat['URL']))
	{
		return $resultat;
	}
	else
	{
		return null;
	}	
}
function isFavPublie($url, $email)
{
	/*
	//Verifie si le membre à deja ajouté ce favoris (verifie dans la table 'favoris_publie')
	// Si oui retourne le favoris
	// Sinon retourne null
	*/
	$requete = "SELECT * from favoris_publie where URL_Favoris='".mysql_real_escape_string($url)."' AND Email_Membre = '".mysql_real_escape_string($email)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['URL_Favoris']) && isset($resultat['Email_Membre']) && ($resultat['URL_Favoris'] == $url) && ($resultat['Email_Membre'] == $email))
	{
		return $resultat;
	}
	else	
	{
		return null;
	}
}
function counterFavPublie($url)
{
	/*
	// Compte le nombre de favoris publié (compte dans la table "favoris_publie")
	// Renvoi le nombre de favoris publié
	*/
	$requete = "SELECT count('URL_Favoris') As compteur_ajout from favoris_publie where URL_Favoris='".mysql_real_escape_string($url)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	//var_dump($resultat);
	if (isset($resultat['compteur_ajout']) && !isblank($resultat['compteur_ajout']))
	{
		return $resultat['compteur_ajout'];		
	}
	else
	{
		return 0;
	}
}
function incrementCompteurAjout($URL)
{
	$requete = "UPDATE favoris SET Compteur_Ajout = Compteur_Ajout +1 WHERE URL ='".mysql_real_escape_string($URL)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
}
function addFav($URL,$Statut,$Nom,$Description,$Note)
{
	$afavoris = isFavExist($URL); // On verifie si le favoris existe
	switch ($afavoris['Statut'])
	{
		case null : // Le favoris n'existe pas	
			if (@fopen($URL, 'r'))
			{
				$Title = get_file_title($URL);
			}
			else
			{
				$Title = $Nom;
			}
			$Icone = "http://www.google.com/s2/favicons?domain=".$URL;
			//Ajout du favoris dans la table Favoris et Publie_favoris avec le statut demandé
			$requete = "INSERT INTO favoris (Url,Statut,Icone,Title) values ('".mysql_real_escape_string($URL)."','".mysql_real_escape_string($Statut)."','".mysql_real_escape_string($Icone)."','".mysql_real_escape_string($Title)."')";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
			break;
		case 'Private': //Le Favoris existe en privée
			if ($Statut == 'Public')// Le membre veut l'ajouter en "public"
			{
				if (counterFavPublie($URL) > 5)//Si compteur_ajout > 5 alors on passe le site en public
				{
					//Prevenir le posseur du passage en public
					// Passage en public du favoris
					$requete = "UPDATE favoris SET Statut='Public' WHERE URL ='".mysql_real_escape_string($URL)."'";
					$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				}
				else
				{
					echo "Exeption .... un membre dispose de ce favoris en privée, impossible de le mettre en public";
					//Prevenir le possesseur qu'un autre membre dispose de son favoris ...
					return false;
				}
			}							
			break;
		case 'Public': //Le favoris existe en public
			if ($Statut == 'Private') // Vérificatio si le membre veut privatiser un favoris deja public
			{
				echo "Impossible de privatiser le favoris car il est déjà en publique";	
				return false;
			}							
			break;
		default : break;
	}
	if (isFavPublie($URL,$_SESSION['email']) == null)// On verifie si le membre à deja ajouté le favoris
	{
		// Le membre n'a pas ajouté ce favoris
		// Ajout du favoris dans la bibliothèque privée
		$requete = "INSERT INTO favoris_publie (Email_Membre,URL_Favoris,Nom,Description,Note) 
		values (
		'".mysql_real_escape_string($_SESSION['email'])."',
		'".mysql_real_escape_string($URL)."',
		'".mysql_real_escape_string($Nom)."',
		'".mysql_real_escape_string($Description)."',
		".mysql_real_escape_string($Note)."
		)";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	}		
	
	return true;
}
function modifFav($URL,$Statut,$Nom,$Description,$Note)
{
	if (isFavPublie($URL,$_SESSION['email']))
	{
		// Mise à jour du favoris avec les paramètres saisies
		$requete = "UPDATE favoris_publie 
		SET 
		Nom='".mysql_real_escape_string($Nom)."', 
		Description='".mysql_real_escape_string($Description)."',
		Note = ".mysql_real_escape_string($Note)."	
		WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND URL_Favoris = '".mysql_real_escape_string($URL)."'";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		return true;
	}
	return false;
}
function extractImportFav($xhtml,$current,$ID_Parent,$DataImport) 
{
/*
	Fonction d'extraction de favoris et de catégorie
	Entrée : 
	- le document contenant les différents favoris et catégorie;
	- le dossier actuel a extraire
	- le dossier parent du dossier actuel;
	Sortie : 
	- Ecriture des favoris et catégorie dans la base de données
*/
	foreach($xhtml->find('li') as $favoris) // recherche de tout les champs contenant soit un lien soit un repertoire
	{
		$item = $favoris->children(0);// ajoute ce qui est contenu par 'li'
		if ($item->tag == 'a') 
		{	
			// c'est un lien
			addFav($item->href,'Private',$item->innertext,$item->innertext,'NULL');//Ajout du favoris et publication
			assocFavInCat($item->href,$ID_Parent);// association du favoris à une catégorie
			//$DataImport[$current]['Data'][$item->href]['Icone'] = $item->icon_uri;
			$item->tag = ''; // supression du (tag) lien analysé
		}
		else if ($item->tag == 'h3')
		{
			// c'est un répertoire
			if (isset($DataImport[$item->innertext])) // Verification si doublon
			{
				$DataImport[$item->innertext.$current] = $item->innertext.$current;// On modifie le nom du doublon
				$item->tag = '';// suppression du (tag) repertoire analysé
				$ID_New_Parent = publieCategorie($_SESSION['email'],$item->innertext.$current,null,$ID_Parent);
				if ($favoris->find('ul',0) != null) // vérification si le répertoire n'est pas vide
				{
					extractImportFav($favoris->find('ul',0),$item->innertext.$current,$ID_New_Parent);//lecture du repertoire
				}
			}
			else
			{
				$DataImport[$item->innertext] = $item->innertext;// On modifie le nom du doublon
				$item->tag = '';// suppression du (tag) repertoire analysé
				$ID_New_Parent = publieCategorie($_SESSION['email'],$item->innertext,null,$ID_Parent);
				if ($favoris->find('ul',0) != null) // vérification si le répertoire n'est pas vide
				{
					extractImportFav($favoris->find('ul',0),$item->innertext,$ID_New_Parent,$DataImport);//lecture du repertoire
				}
			}
		}
	}
}

?>