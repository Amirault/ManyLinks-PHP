<?php
$wd_was = getcwd();
chdir(dirname(__FILE__));
require_once("isBlank.php");
chdir("../");
require_once ('config.php');
chdir($wd_was);
?> 
<?php
function cleanhtml($dirtyhtml) 
{
	$dirtyhtml = htmlspecialchars($dirtyhtml,ENT_QUOTES,'UTF-8');
	$dirtyhtml = mysql_real_escape_string($dirtyhtml);
	return $dirtyhtml;
}
// TODO Vérifier la taille de l'url (inférieur à 767)
function verifUrl($url)
{
	/*
	// Verifie si l'url passé en paramètre est valid ou non
	// Si valid retourne true
	// Sinon retourne false
	*/
	//Permet d'afficher un message si le lien d'une URL est valide ou non.
	// L'URL du site web
	$url = mysql_real_escape_string(htmlspecialchars($url)); //Sécurisation
	$v = "/^(http|https|ftp):\/\/([A-Z0-9][A-Z0-9_-]*(?:\.[A-Z0-9][A-Z0-9_-]*)+):?(\d+)?\/?/i"; // Regex de validation URL
	if (preg_match($v, $url))//Verification si l'url est valide
	{
		$timeout = 7;
		//L'url saisie est valide
		$old = ini_set('default_socket_timeout', $timeout);		
		$file = @fopen($url, 'r'); //essai de recupération de la page
		ini_set('default_socket_timeout', $old);
		stream_set_timeout($file, $timeout);
		stream_set_blocking($file, 0);
		
		if ($file) 
		{
			//L'url pointe bien sur quelque chose
			return true;
		} 
		else 
		{
			
			//L'url ne pointe pas sur quelque chose d'accèssible
			return true;
		}
	}
	else
	{
		//L'url saisie n'est pas valide
		return false;			
	}			
}
?>
<?php
function verifParameterContact($Parameter)
{
	if ((!isset($Parameter['Civilite']))||isBlank($Parameter['Civilite']))
	{
		$ParameterReturn['Err']= "Veuillez renseigner la civilité est incorrect";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Nom'])||isBlank($Parameter['Nom']))
	{
		$ParameterReturn['Err']= "Veuillez renseigner le nom";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Prenom'])||isBlank($Parameter['Prenom']))
	{
		$ParameterReturn['Err']= "Renseigner le Prénom";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Email'])||isBlank($Parameter['Email']))
	{
		$ParameterReturn['Err']= "Renseigner votre adresse email";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Sujet'])||isBlank($Parameter['Sujet']))
	{
		$ParameterReturn['Err']= "Renseigner le sujet de votre message";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Question'])||isBlank($Parameter['Question']))
	{
		$ParameterReturn['Err']= "Renseigner votre question s'il vous plait.";
		return $ParameterReturn;
	}
	if (!isset($Parameter['Captcha_code'])||isBlank($Parameter['Captcha_code']))
	{
		$ParameterReturn['Err']= "Renseigner le code présent dans l'image s'il vous plait.";
		return $ParameterReturn;
	}
	if ($Parameter['Captcha_code'] != $_SESSION['securecode']) 
	{
		$ParameterReturn['Err']= "Le code saisie ne correspond pas à celui dans l'image.";
		return $ParameterReturn;
	}
	$ParameterReturn['form'] = mysql_real_escape_string(htmlspecialchars($Parameter['form']));
	$ParameterReturn['Civilite'] = mysql_real_escape_string(htmlspecialchars($Parameter['Civilite']));
	$ParameterReturn['Nom'] = mysql_real_escape_string(htmlspecialchars($Parameter['Nom']));
	$ParameterReturn['Prenom'] = mysql_real_escape_string(htmlspecialchars($Parameter['Prenom']));
	$ParameterReturn['Email'] = mysql_real_escape_string(htmlspecialchars($Parameter['Email']));
	$ParameterReturn['Sujet'] = mysql_real_escape_string(htmlspecialchars($Parameter['Sujet']));
	$ParameterReturn['Question'] = mysql_real_escape_string(htmlspecialchars($Parameter['Question']));
	$ParameterReturn['Captcha_code'] = mysql_real_escape_string(htmlspecialchars($Parameter['Captcha_code']));
	$ParameterReturn['Err'] = null;
	
	return $ParameterReturn;
}
function verifParameterDel($Parameter)
{
	if (isset($Parameter['AffCat']) )
	{
		if(is_numeric($Parameter['AffCat']))
		{
			$ParameterReturn['AffCat'] = mysql_real_escape_string(htmlspecialchars($Parameter['AffCat']));
			$ParameterReturn['Disp'] = mysql_real_escape_string(htmlspecialchars($Parameter['AffCat']));
		}
		else if (($Parameter['AffCat'] == 'AllFav')||($Parameter['AffCat'] == 'FavNotAssoc')||($Parameter['AffCat'] == 'AllCat')||($Parameter['AffCat'] == 'AllMosaique'))
		{
			$ParameterReturn['Disp'] = $Parameter['AffCat'];
		}
	}
	else
	{
		$ParameterReturn['AffCat'] = null;
		$ParameterReturn['Disp'] = null;
	}
	if ((!isset($Parameter['SelectFav']))&&(!isset($Parameter['SelectCat'])&&(!isset($Parameter['SelectMosaique']))))
	{
		$ParameterReturn['Err']= "Veuillez selectionnez un favoris ou une catégorie à supprimer";
		return $ParameterReturn;
	}
	if (isset($Parameter['SelectFav']))
	{
		$ParameterReturn['SelectFav'] = array_map("cleanhtml", $Parameter['SelectFav']);
	}
	else
	{
		$ParameterReturn['SelectFav'] = null;
	}
	if (isset($Parameter['SelectMosaique']))
	{
		$ParameterReturn['SelectMosaique'] = array_map("cleanhtml", $Parameter['SelectMosaique']);
	}
	else
	{
		$ParameterReturn['SelectMosaique'] = null;
	}
	if (isset($Parameter['SelectCat']))
	{
		$ParameterReturn['SelectCat'] = array_map("cleanhtml", $Parameter['SelectCat']);
	}
	else
	{
		$ParameterReturn['SelectCat'] = null;
	}
	
	$ParameterReturn['Err'] = null;
	
	return $ParameterReturn;
}
function verifParameterAddFav($Parameter)
{
	/*
	// Vérifie les paramètres pour l'ajout d'un favorie
	// Si il sont correct renvoie les paramètres
	// Sinon renvoie null
	*/
	//Verification du contenu des paramètres
	if ((!isset($Parameter['UrlFav']) || (!verifUrl($Parameter['UrlFav']))))
	{
		$ParameterReturn['Err']= "Veuillez saisir une URL correcte";
		return $ParameterReturn;
	}
	//Url correct				
	if (isset($Parameter['NomFav']) && !isBlank($Parameter['NomFav']))
	{
		$ParameterReturn['NomFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['NomFav']));
	}
	else
	{
		if (@fopen($Parameter['UrlFav'], 'r'))
		{
			$ParameterReturn['NomFav'] = mysql_real_escape_string(htmlspecialchars(get_file_title($Parameter['UrlFav'])));
		}
		else
		{
			$ParameterReturn['NomFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['UrlFav']));;
		}
	}
	if (isset($Parameter['DescriptionFav']) && !isBlank($Parameter['DescriptionFav']))
	{
		$ParameterReturn['DescriptionFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['DescriptionFav']));
	}
	else
	{
		$ParameterReturn['DescriptionFav'] = $Parameter['UrlFav'];
	}
	if (isset($Parameter['CommentaireFav']) && !isBlank($Parameter['CommentaireFav']))
	{
		$ParameterReturn['CommentaireFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['CommentaireFav']));
	}
	else
	{
		$ParameterReturn['CommentaireFav'] = 'NULL';
	}
	if (isset($Parameter['NoteFav']) && !isblank($Parameter['NoteFav']) && is_numeric($Parameter['NoteFav']) && ($Parameter['NoteFav'] <= 5)  && ($Parameter['NoteFav'] >= 0))
	{
		$ParameterReturn['NoteFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['NoteFav']));
	}
	else
	{
		$ParameterReturn['NoteFav'] =  'NULL';
	}
	if (isset($Parameter['StatutFav']) && ($Parameter['StatutFav'] == 'Private'))
	{
		$ParameterReturn['StatutFav'] = 'Private';
	}
	else
	{
		$ParameterReturn['StatutFav'] = 'Public';
	}
	if (isset($Parameter['Categorie']) && !isBlank($Parameter['Categorie']))
	{
		$ParameterReturn['Categorie'] = mysql_real_escape_string(htmlspecialchars($Parameter['Categorie']));
	}
	else
	{
		$ParameterReturn['Categorie'] = 'Default';
	}
	$ParameterReturn['UrlFav'] = mysql_real_escape_string(htmlspecialchars($Parameter['UrlFav']));
	$ParameterReturn['Err'] = null;
		
	return $ParameterReturn;
}
function verifParameterAddMosaique($Parameter)
{
	if ((!isset($Parameter['ID_Mosaique'])||($Parameter['ID_Mosaique'] != 'New')))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter une mosaique à partir de la bibliothèque privée';
		return $ParameterReturn;
	}
	if (!isset($Parameter['Nom_Mosaique'])||(isBlank($Parameter['Nom_Mosaique'])))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter un nom à votre mosaïque';
		return $ParameterReturn;
	}
	if (!isset($Parameter['URL']))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter correctement les URLs';
		return $ParameterReturn;
	}
	if (isset($Parameter['Categorie']) && (!isBlank($Parameter['Categorie'])))
	{
		$ParameterReturn['Categorie'] = mysql_real_escape_string(htmlspecialchars($Parameter['Categorie']));
	}
	else
	{
		$ParameterReturn['Categorie'] = 'Default';
	}
	if (!isset($Parameter['Default']) || (!is_numeric($Parameter['Default'])))
	{
		$ParameterReturn['Default'] = 0;
	}
	else
	{
		$ParameterReturn['Default'] = mysql_real_escape_string(htmlspecialchars($Parameter['Default']));
	}
	$ParameterReturn['Links'] = array_map("cleanhtml", $Parameter['URL']);			
	$ParameterReturn['ID_Mosaique'] = mysql_real_escape_string(htmlspecialchars($Parameter['ID_Mosaique']));
	$ParameterReturn['Nom_Mosaique'] = mysql_real_escape_string(htmlspecialchars($Parameter['Nom_Mosaique']));
	$ParameterReturn['Err'] = null;

	return $ParameterReturn;
}
function verifParameterModifMosaique($Parameter)
{
	if (!isset($Parameter['ID_Mosaique'])||(!is_numeric($Parameter['ID_Mosaique'])))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter une mosaique à partir de la bibliothèque privée';
		return $ParameterReturn;
	}
	if (!isset($Parameter['Nom_Mosaique']))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter un nom à votre mosaïque';
		return $ParameterReturn;
	}
	if (!isset($Parameter['URL']))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter correctement les URLs';
		return $ParameterReturn;
	}
	if (!isset($Parameter['Default']) || (!is_numeric($Parameter['Default'])))
	{
		$ParameterReturn['Default'] = 0;
	}
	else
	{
		$ParameterReturn['Default'] = mysql_real_escape_string(htmlspecialchars($Parameter['Default']));
	}
	$ParameterReturn['Links'] = array_map("cleanhtml", $Parameter['URL']);			
	$ParameterReturn['ID_Mosaique'] = mysql_real_escape_string(htmlspecialchars($Parameter['ID_Mosaique']));
	$ParameterReturn['Nom_Mosaique'] = mysql_real_escape_string(htmlspecialchars($Parameter['Nom_Mosaique']));
	$ParameterReturn['Err'] = null;

	return $ParameterReturn;
}
function verifParameterAssoc($Parameter)
{
	if (!isset($Parameter['SelectFav']))
	{
		$ParameterReturn['Err']= "Veuillez selectionnez un favoris ou une catégorie à supprimer";
		return $ParameterReturn;
	}
	if (isset($Parameter['SelectFav']))
	{
		$ParameterReturn['SelectFav'] = array_map("cleanhtml", $Parameter['SelectFav']);
	}
	else
	{
		$ParameterReturn['SelectFav'] = null;
	}
	if (isset($Parameter['AssocCategorie']) && (!isBlank($Parameter['AssocCategorie']))&&(is_numeric($Parameter['AssocCategorie'])))
	{
		$ParameterReturn['AssocCategorie'] = mysql_real_escape_string(htmlspecialchars($Parameter['AssocCategorie']));
	}
	else
	{
		$ParameterReturn['AssocCategorie'] = 'Default';
	}
	$ParameterReturn['Err'] = null;
	
	return $ParameterReturn;
}
function verifParameterDelMosaique($Parameter)
{
	if ((!isset($Parameter['ID_Mosaique']))||(!is_numeric($Parameter['ID_Mosaique'])))
	{
		$ParameterReturn['Err'] = 'Veuillez ajouter une mosaique à partir de la bibliothèque privée';
		return $ParameterReturn;
	}
	$ParameterReturn['ID_Mosaique'] = mysql_real_escape_string(htmlspecialchars($Parameter['ID_Mosaique']));
	$ParameterReturn['Err'] = null;
	
	return $ParameterReturn;	
}

?>