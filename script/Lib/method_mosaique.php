<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once("isBlank.php");
	chdir("../");
	require_once ('config.php');
	chdir($wd_was);
?>
<?php
function addMosaique($Nom_Mosaique,$Links)
{
	$requete = "INSERT INTO mosaique_publie (Email_Membre,Nom) values ('".mysql_real_escape_string($_SESSION['email'])."','".mysql_real_escape_string($Nom_Mosaique)."')";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$ID_Mosaique = mysql_insert_id();
	for ($i=1; $i <= 9; $i++)
	{	
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'".mysql_real_escape_string($Links[$i])."','".mysql_real_escape_string($i)."','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	}
	return $ID_Mosaique;
}
function modifMosaique($Links,$ID_Mosaique)
{
	for ($i=1; $i <= 9; $i++)
	{	
		$Link[$i] = mysql_real_escape_string(htmlspecialchars(urldecode($Links[$i])));
		$requete = "UPDATE mosaique_favoris SET URL_Favoris = '".mysql_real_escape_string($Links[$i])."' WHERE Email_Membre = '".mysql_real_escape_string($_SESSION['email'])."' AND ID_Emplacement = ".mysql_real_escape_string($i)." AND ID_Mosaique = '".mysql_real_escape_string($ID_Mosaique)."'";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	}
}
function defaultMosaique($Default,$ID_Mosaique)
{
	$requete = "UPDATE mosaique_publie SET mosaique_publie.Default = ".mysql_real_escape_string($Default)." WHERE Email_Membre = '".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($ID_Mosaique)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
}	
function LoadExternalMosaique($Default,$ID_Mosaique)
{
	$requete = "SELECT * from mosaique_favoris LEFT JOIN mosaique_publie on mosaique_favoris.ID_Mosaique=mosaique_publie.ID_Mosaique AND mosaique_favoris.Email_Membre=mosaique_publie.Email_membre WHERE mosaique_favoris.Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND mosaique_publie.ID_Mosaique = ".mysql_real_escape_string($ID_Mosaique)."";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);

	$IndexEmplacement =0;
	$xstart = true;
	$html = "";
	while ($enreg = mysql_fetch_assoc($resultat)) 
	{
		if ($IndexEmplacement == 0)
		{
			$html = "<ul id=\"MenuMosaique\" class=\"menu\">";
		}
		$IndexEmplacement ++;
		if ($enreg['ID_Emplacement'] == $IndexEmplacement && ($enreg['URL_Favoris'] != 'null') && (!empty($enreg['URL_Favoris'])))
		{
			$html = $html."\n"."
			<li id=\"URL_".$enreg['ID_Emplacement']."\" >
			  <a id=\"Link_".$enreg['ID_Emplacement']."\" href=\"".$enreg['URL_Favoris']."\"><img src=\"http://www.robothumb.com/src/".$enreg['URL_Favoris']."@320x240.jpg\" /></a>
			</li>	
			";
		}
		else
		{
			$html = $html."\n"."
			<li>
			</li>
			";
		}
		if ($IndexEmplacement == 9)
		{
			$html = $html."\n"."
			</ul>
			";
		}
	}
	$html = $html."<body>\n
		<html>\n";
	$html = "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\" >\n
			<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"fr\">\n
				<head>\n
					<link href=\"http://manylinks.fr/bootstrap/css/style.css\" rel=\"stylesheet\" type=\"text/css\">\n
					<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />\n
				</head>\n
				<body>\n".$html;
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("../../Mosaique/");
	$pageDefault = file_put_contents(md5($_SESSION['email'].'Mosaique'.$ID_Mosaique).'.html',$html);
	chdir($wd_was);
}
function LoadDefaultMosaique()
{
	$requete = "SELECT * from mosaique_publie where Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' and Nom ='DefaultMosaique".mysql_real_escape_string($_SESSION['email'])."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$result = mysql_fetch_array($resultat);
	if (!isset($result['Nom']))
	{
		$requete = "INSERT INTO mosaique_publie (Email_Membre,Nom,mosaique_publie.Default) VALUES ('".mysql_real_escape_string($_SESSION['email'])."','DefaultMosaique".mysql_real_escape_string($_SESSION['email'])."',1)";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$ID_Mosaique = mysql_insert_id();
		$requete = "DELETE FROM mosaique_favoris WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($ID_Mosaique)."';";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://google.com','1','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://facebook.com','2','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://youtube.com','3','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://yahoo.com','4','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://live.com','5','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://msn.com','6','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://wikipedia.org','7','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://blogspot.com','8','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$requete = "INSERT INTO mosaique_favoris (ID_Mosaique,URL_Favoris,ID_Emplacement,Email_Membre) values (".mysql_real_escape_string($ID_Mosaique).",'http://baidu.com','9','".mysql_real_escape_string($_SESSION['email'])."')";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		return $ID_Mosaique;
	}
	else
	{
		if ($result['Default'] != 1)
		{
			$requete = "UPDATE mosaique_publie SET mosaique_publie.Default = 1 WHERE mosaique_publie.ID_Mosaique=".mysql_real_escape_string($result['ID_Mosaique'])."";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		}
		return $result['ID_Mosaique'];
	}
}
function isMosaiquePublie($ID_Mosaique, $email)
{
	/*
	//Verifie si le membre à deja ajouté cette mosaïque (verifie dans la table 'mosaique_publie')
	// Si oui retourne le mosaïque
	// Sinon retourne null
	*/
	$requete = "SELECT * from mosaique_publie where ID_Mosaique='".mysql_real_escape_string($ID_Mosaique)."' AND Email_Membre = '".mysql_real_escape_string($email)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['ID_Mosaique']) && isset($resultat['Email_Membre']) && ($resultat['ID_Mosaique'] == $ID_Mosaique) && ($resultat['Email_Membre'] == $email))
	{
		return $resultat;
	}
	else	
	{
		return null;
	}
}
function isMosaiqueInCatExist($ID_Mosaique,$ID_Categorie,$Email)
{
/*A verif*/
	$requete = "SELECT * from categorie_mosaique where ID_Mosaique='".mysql_real_escape_string($ID_Mosaique)."' AND ID_Categorie = ".mysql_real_escape_string($ID_Categorie)." AND Email_Membre = '".mysql_real_escape_string($Email)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$resultat = mysql_fetch_array($resultat);
	if (isset($resultat['ID_Mosaique']) && isset($resultat['ID_Categorie']) && ($resultat['ID_Mosaique'] == $ID_Mosaique) && ($resultat['ID_Categorie'] == $ID_Categorie) && ($resultat['Email_Membre'] == $Email))
	{
		return $resultat;
	}
	else	
	{
		return null;
	}
}
function counterMosaiquePublie($ID_Mosaique)
{
	/*
	// Compte le nombre de Mosaique publié (compte dans la table "mosaique_publie")
	// Renvoi le nombre de mosaique publié
	*/
	$requete = "SELECT count('ID_Mosaique') As compteur_ajout from mosaique_publie where ID_Mosaique='".mysql_real_escape_string($ID_Mosaique)."'";
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
function incrementCompteurAjoutMosaique($ID_Mosaique)
{
	$requete = "UPDATE favoris SET Compteur_Ajout = Compteur_Ajout +1 WHERE URL ='".mysql_real_escape_string($URL)."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
}
function delMosaique($Mosaiques,$CatParent)
{
	if (isset($Mosaiques))
	{
		if (is_array($Mosaiques))
		{
			// Suppression du ou des mosaiques selectionné
			foreach ($Mosaiques as $aMosaique)
			{
				if ($CatParent != null)
				{
					// Suppression de la mosaique dans la catégorie appartenant à l'utilisateur
					$requete = "DELETE FROM categorie_mosaique WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($aMosaique)."' AND ID_Categorie = ".mysql_real_escape_string($CatParent)."";
					$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				}
				else
				{
					// Supression de la mosaique dans les catégorie appartenant à l'utilisteur
					$requete = "DELETE FROM categorie_mosaique WHERE ID_Mosaique = '".mysql_real_escape_string($aMosaique)."' AND Email_Membre='".mysql_real_escape_string($_SESSION['email'])."'";
					$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				}
				$requete = "SELECT COUNT('ID_Mosaique') as Nb FROM categorie_mosaique WHERE ID_Mosaique = '".mysql_real_escape_string($aMosaique)."' AND Email_Membre='".mysql_real_escape_string($_SESSION['email'])."'";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				$resultat = mysql_fetch_array($resultat);
				if ($resultat['Nb'] == 0)
				{
					$requete = "DELETE FROM mosaique_publie WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($aMosaique)."'";
					$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
					$requete = "DELETE FROM mosaique_favoris WHERE Email_Membre='".$_SESSION['email']."' AND ID_Mosaique = '".mysql_real_escape_string($aMosaique)."'";
					$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				}
			}
		}
		else
		{
			// Supression de tout les favoris dans toute les catégorie appartenant à l'utilisateur
				$requete = "DELETE FROM categorie_mosaique WHERE ID_Mosaique = '".mysql_real_escape_string($Mosaiques)."' AND Email_Membre='".mysql_real_escape_string($_SESSION['email'])."'";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				$requete = "DELETE FROM mosaique_publie WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($Mosaiques)."'";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
				$requete = "DELETE FROM mosaique_favoris WHERE Email_Membre='".mysql_real_escape_string($_SESSION['email'])."' AND ID_Mosaique = '".mysql_real_escape_string($Mosaiques)."'";
				$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		}
		return true;
	}
	return false;
}
?>