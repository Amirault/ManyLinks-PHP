 <?php
 
 // Ajout d'une categorie
 //	Verififier si cette categorie n'existe pas deja
 // Si non alors ajouter cette catégorie en private => categorie
 // Vérififier si l'utilisateur deja ajouter cette catégorie
 // Si non alors ajouter cette catégorie => categorie_publie 
 
 // Vérifier si l'utilisateur dispose deja d'une catégorie racine
 // Si non la créer
  
  // verifier si la catégorie à été ajouté à partir d'une autre catégorie (parente)
  // Ajouter la catégorie au bonne endroi dans l'arbre
 
 //INSERT INTO arbre (id, gauche, droite, ref, id_parent) VALUES (1,1,2,'A',0); (insertion racine)
?>
<?php
$wd_was = getcwd();
chdir(dirname(__FILE__));
require_once("isBlank.php");
chdir("../");
require_once ('config.php');
chdir($wd_was);
?>
<?php
function publieCategorie($Email,$Name,$Description,$ID_Parent)
{
	if ($ID_Parent != null)
	{
		checkRootNode($Email);
		$ID = addNode($Email,$Name,$Description,$ID_Parent);
	}
	else
	{
		$ID_Parent = checkRootNode($Email);
		$ID = addNode($Email,$Name,$Description,$ID_Parent);
	}
	return $ID;
}
function isFavInCatExist($Url,$ID_Categorie,$Email)
{
/*A verif*/
	$requete = "SELECT * from categorie_favoris where URL_Favoris='".mysql_real_escape_string($Url)."' AND ID_Categorie = ".mysql_real_escape_string($ID_Categorie)." AND Email_Membre = '".mysql_real_escape_string($Email)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['URL_Favoris']) && isset($resultat['ID_Categorie']) && ($resultat['URL_Favoris'] == $Url) && ($resultat['ID_Categorie'] == $ID_Categorie) && ($resultat['Email_Membre'] == $Email))
	{
		return $resultat;
	}
	else	
	{
		return null;
	}
}
function delCat($Categories)
{
	if (isset($Categories))
	{
		// Suppression de la catégorie selectionné et des favoris associé
		foreach ($Categories as $ID_Categorie)
		{
			delNode($_SESSION['email'],$ID_Categorie);
		}	
		return true;
	}
	return false;
}
function assocFavInCat($URL,$Categorie)
{
	if ($Categorie != 'Default')
	{
		if (isFavInCatExist($URL,$Categorie,$_SESSION['email'] ) == null)
		{
			$requete = "INSERT INTO categorie_favoris (URL_Favoris,ID_Categorie,Email_Membre) 
			values (
			'".mysql_real_escape_string($URL)."',
			".mysql_real_escape_string($Categorie).",
			'".mysql_real_escape_string($_SESSION['email'])."'
			)";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		}	
	}
}
function assocMosaiqueInCat($ID_Mosaique,$Categorie)
{
	if ($Categorie != 'Default')
	{
		if (isMosaiqueInCatExist($ID_Mosaique,$Categorie,$_SESSION['email'] ) == null)
		{
			$requete = "INSERT INTO categorie_mosaique (ID_Mosaique,ID_Categorie,Email_Membre) 
			values (
			'".mysql_real_escape_string($ID_Mosaique)."',
			".mysql_real_escape_string($Categorie).",
			'".mysql_real_escape_string($_SESSION['email'])."'
			)";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		}	
	}
}
function isCatExist($ID)
{
	/*
	//Verifie si la catégorie en paramètre existe dans la table 'catégorie' 
	// Si oui il retourne la catégorie
	// Sinon retourne null
	*/
	$requete = "SELECT ID from categorie where ID=".mysql_real_escape_string($ID);
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['ID']) && !isblank($resultat['ID']))
	{
		return $resultat;
	}
	else
	{
		return null;
	}	
}
function checkRootNode($Email_Membre)
{
	$requete = "SELECT * from arbre_categorie where Email_Membre='".mysql_real_escape_string($Email_Membre)."' and Nom ='Root".mysql_real_escape_string($Email_Membre)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$result = mysql_fetch_array($resultat);
	if (!isset($result['Nom'])||($result['Nom'] != 'Root'.$Email_Membre))
	{
		$requete = "INSERT INTO arbre_categorie (Email_Membre,Gauche,Droite,Nom, ID_Noeud_Parent) VALUES ('".mysql_real_escape_string($Email_Membre)."',1,2,'Root".mysql_real_escape_string($Email_Membre)."',1)";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$ID = mysql_insert_id();
		$requete = "UPDATE arbre_categorie SET ID_Noeud_Parent =".$ID." WHERE ID_Noeud=".mysql_real_escape_string($ID)."";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	}
	else
	{
		$ID = $result['ID_Noeud'];
	}
	
	return $ID;
}
function addNode($Email_Membre,$Name,$Description,$id_parent_noeud)
{
	 /* A verif */
	$requete = "INSERT INTO arbre_categorie (Email_Membre,Nom, ID_Noeud_Parent) VALUES ('".mysql_real_escape_string($Email_Membre)."','".mysql_real_escape_string($Name)."','".mysql_real_escape_string($id_parent_noeud)."')";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$ID = mysql_insert_id();
	$requete = "SELECT Gauche,Droite FROM arbre_categorie WHERE ID_Noeud ='".mysql_real_escape_string($id_parent_noeud)."' AND Email_Membre='".mysql_real_escape_string($Email_Membre)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	$Gauche_Parent =$resultat['Gauche'];
	$Droite_Parent =$resultat['Droite'];
	$requete = "UPDATE arbre_categorie SET Gauche = Gauche + 2
	  WHERE Gauche > '".mysql_real_escape_string($Gauche_Parent)."' AND Email_Membre='".mysql_real_escape_string($Email_Membre)."'
	  ORDER BY Gauche DESC";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	
	$requete = "UPDATE arbre_categorie SET Droite = Droite + 2
	  WHERE (Droite >= '".mysql_real_escape_string($Droite_Parent)."' OR (Droite > '".mysql_real_escape_string($Gauche_Parent)."' + 1 AND Droite < '".mysql_real_escape_string($Droite_Parent)."')) AND Email_Membre='".$Email_Membre."'
	  ORDER BY Droite DESC";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	
	$requete = "UPDATE arbre_categorie SET Gauche = '".mysql_real_escape_string($Gauche_Parent)."' + 1, Droite = '".mysql_real_escape_string($Gauche_Parent)."' + 2 WHERE ID_Noeud = ".mysql_real_escape_string($ID)." ";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	
	return $ID;
}
function delNode($Email_Membre,$id_noeud)
{
	 /* A verif */
	$requete = "SELECT Gauche,Droite FROM arbre_categorie WHERE ID_Noeud =".mysql_real_escape_string($id_noeud)." AND Email_Membre='".mysql_real_escape_string($Email_Membre)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	$Gauche_Parent =$resultat['Gauche'];
	$Droite_Parent =$resultat['Droite'];
	unset($resultat);
	$requete = "SELECT ID_Noeud FROM arbre_categorie WHERE Gauche >= ".mysql_real_escape_string($Gauche_Parent)." AND Droite <= ".mysql_real_escape_string($Droite_Parent)." AND Email_Membre='".mysql_real_escape_string($Email_Membre)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete."\n\n Erreur : ".mysql_error());
	$Nb = 0;
	while ($enreg = mysql_fetch_assoc($resultat))
	{
		$requete = "DELETE FROM arbre_categorie WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Noeud = ".mysql_real_escape_string($enreg['ID_Noeud'])."";
		mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "DELETE FROM categorie_favoris WHERE ID_Categorie = ".mysql_real_escape_string($enreg['ID_Noeud'])." AND Email_Membre = '".mysql_real_escape_string($_SESSION['email'])."' ;";
		mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$Nb = $Nb +1;
	}
	$requete = "UPDATE arbre_categorie SET Gauche = Gauche - 2 * ".mysql_real_escape_string($Nb)." WHERE Gauche > ".mysql_real_escape_string($Gauche_Parent)." AND Email_Membre='".mysql_real_escape_string($Email_Membre)."' ORDER BY Gauche DESC";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	
	$requete = "UPDATE arbre_categorie SET Droite = Droite - 2 * ".mysql_real_escape_string($Nb)." WHERE Droite > ".mysql_real_escape_string($Droite_Parent)." AND Email_Membre='".mysql_real_escape_string($Email_Membre)."' ORDER BY Gauche DESC";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	
}
function isCatPublie($ID, $Email)
{
	/*
	//Verifie si le membre à deja ajouté cette catégorie (verifie dans la table 'catégorie_publie')
	// Si oui retourne la catégorie
	// Sinon retourne null
	*/
	$requete = "SELECT * from categorie_publie where ID_Categorie='".mysql_real_escape_string($ID)."' AND Email_Membre = '".mysql_real_escape_string($Email)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['ID_Categorie']) && isset($resultat['Email_Membre']) && ($resultat['ID'] == $ID) && ($resultat['Email_Membre'] == $Email))
	{
		return $resultat;
	}
	else	
	{
		return null;
	}
}
function verifParamAddCat($Parameter)
{
	/*
	// Vérifie les paramètres pour l'ajout d'une catégorie
	// Si il sont correct renvoie les paramètres
	// Sinon renvoie null
	*/
	// Test des paramètres
	if (!isset($Parameter))
	{
		return null;
	}
	// Test du Nom et du Statut
	if (!(isset($Parameter['NomCat'])) && (!isBlank($Parameter['NomCat'])))
	{
		return null;
	}
	//Test
	if (!(isset($Parameter['Description']) && !isBlank($Parameter['Description'])))
	{
		$Parameter['DescriptionFav'] = $Parameter['NomCat'];
	}
	if (!isset($Parameter['Categorie']) && !isBlank($Parameter['Categorie']))
	{
		$Parameter['Categorie'] = 'Default';
	}
	$Parameter['NomCat'] = mysql_real_escape_string(htmlspecialchars($Parameter['NomCat']));
	$Parameter['DescriptionFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['DescriptionFav']));
	$Parameter['Categorie'] = mysql_real_escape_string(htmlspecialchars($Parameter['Categorie']));
	
	return $Parameter;
}
?>