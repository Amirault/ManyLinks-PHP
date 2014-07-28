<?php
	//Déclaration des functions à utiliser
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once ('config.php');
	chdir("Lib/");
	require_once("isBlank.php");
	require_once('method_categorie.php');
	require_once('method_mosaique.php');
	require_once('method_verif.php');
	require_once('method_fav.php');
	require_once('simple_html_dom.php');// Utilisé pour l'analyse du fichier d'import
	chdir('../');
//Ouverture de la session
session_start();
if (!isset($_SESSION['mysql']))
{
	$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
}
// Programme Principal
if((isset($_POST['Form']) && isset($_POST['Page'])) 
//&& (stristr($_POST['Page'],'/GestionFav/'))	
&& (($_POST['Form'] == 'Del')|| ($_POST['Form'] == 'AddFav')|| ($_POST['Form'] == 'ModifFav')
	||($_POST['Form'] == 'AddMosaique')|| ($_POST['Form'] == 'ModifMosaique')
	||($_POST['Form'] == 'AddCat')|| ($_POST['Form'] == 'ModifCat')
	||($_POST['Form'] == 'DeleteMosaique')|| ($_POST['Form'] == 'Assoc')
	||($_POST['Form'] == 'ImportFav')
	))
{
	switch ($_POST['Form'])
	{
		case 'Del':
			$Parameter = verifParameterDel($_POST);// Verification que les paramètres sont correct
			if ($Parameter['Err'] == null)
			{
				delFav($Parameter['SelectFav'],$Parameter['AffCat']);
				delMosaique($Parameter['SelectMosaique'],$Parameter['AffCat']);
				delCat($Parameter['SelectCat']);
				header("Location: ../BibPrivee.php?disp=".$Parameter['Disp']);
			}
			else
			{
				// Verifier si la page qui effectue la requête est une page connu, ainsi que le formulaire
				//Paramètre invalide
				header('Location: ../BibPrivee.php?Form=\'Del\'&Etat=1');
				exit;
			}
			break;
		case 'Assoc':
			$Parameter = verifParameterAssoc($_POST);
			if ($Parameter['Err'] == null)
			{
				foreach ($Parameter['SelectFav'] as $URL)
				{
					assocFavInCat($URL,$Parameter['AssocCategorie']);
				}
				CloseMysql();
				header("Location: ../BibPrivee.php?&Success=Association réussie !");
			}
			else
			{
				header('Location: ../BibPrivee.php?Form=\'Assoc\'&Etat=1');
				exit;
			}			
			break;
		case 'ImportFav':
			// Upload du fichier avec vérification
			$dossier = 'import/';
			$fichier = date("H:i:s")."-".basename($_FILES['bookmark']['name']);
			$taille_maxi = 500000;
			$taille = filesize($_FILES['bookmark']['tmp_name']);
			$extensions = array('.html','.htm');
			$extension = strrchr($_FILES['bookmark']['name'], '.'); 
			//Début des vérifications de sécurité...
			if(!in_array($extension, $extensions)) //Si l'extension n'est pas dans le tableau
			{
				 $erreur = 'Vous devez uploader un fichier de type html ou htm';
			}
			if($taille>$taille_maxi)
			{
				 $erreur = 'Le fichier est trop gros...';
			}
			if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
			{
				 //On formate le nom du fichier ici...
				 $fichier = strtr($fichier, 
					  'ÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïðòóôõöùúûüýÿ', 
					  'AAAAAACEEEEIIIIOOOOOUUUUYaaaaaaceeeeiiiioooooouuuuyy');
				 $fichier = preg_replace('/([^.a-z0-9]+)/i', '-', $fichier);
				 if(!move_uploaded_file($_FILES['bookmark']['tmp_name'], $dossier . $fichier)) //Si la fonction renvoie TRUE, c'est que ça a fonctionné...
				 {					
					if (isset($_SESSION['mysql']))
					{
						mysql_close($_SESSION['mysql']);
						$_SESSION['mysql'] = null;
					}
				   header('Location: ../BibPrivee.php?Err=Un problème est survenu lors durant l importation des vos favoris... réessayer ?');
				   exit;
				 }
			}
			else
			{
				if (isset($_SESSION['mysql']))
				{
					mysql_close($_SESSION['mysql']);
					$_SESSION['mysql'] = null;
				}
				header('Location: ../BibPrivee.php?Err='.$erreur);
				exit;
			}
			// Fin upload
			// Edition du fichier pour une exploitation de son contenu
			$html = file_get_contents('import/'.$fichier);
			unlink('import/'.$fichier);
			$html = preg_replace('#\<DL\>\<p\>#','<ul>',$html);
			$html = preg_replace('#\</DL\>\<p\>#','</ul>',$html);
			$html = preg_replace('#\<DT\>#','<li>',$html);
			$html = preg_replace('#\<DT\>#','<li>',$html);
			$html = file_put_contents('import/bookmarksTest.html',$html);
			$html = file_get_html('import/bookmarksTest.html');
			unlink("import/bookmarksTest.html");
			// Fin d'édition
			//Creation du dossier d'import des favoris (ou les favoris seront contenu)
			$DataImport = null;
			$NodeRootImport = 'import_'.date("H:i:s"); 
			$DataImport[$NodeRootImport] = $NodeRootImport;
			$ID_NodeRootImport = publieCategorie($_SESSION['email'],$NodeRootImport,null,null);//Stockage du dossier à la racine
			//Extraction des favoris et catégorie contenu dans le fichier bookmark
			extractImportFav($html,$NodeRootImport,$ID_NodeRootImport,$DataImport);

			header('Location: ../BibPrivee.php?Success=Import effectué avec succès');
			//incrementCompteurAjout($Parameter['UrlFav']);
			CloseMysql();
			header('Location: ../BibPrivee.php?&Success=Import effectué avec succès');
			break;
		case 'AddFav':
			$Parameter = verifParameterAddFav($_POST);// Verification que les paramètres sont correct
			if ($Parameter['Err'] == null)
			{
				//Les paramètre reçus sont correct
				addFav($Parameter['UrlFav'],$Parameter['StatutFav'],$Parameter['NomFav'],$Parameter['DescriptionFav'],$Parameter['NoteFav']); // Ajout du favoris dans la base (table favoris, favoris_publie)
				assocFavInCat($Parameter['UrlFav'],$Parameter['Categorie']);// association du favoris à une catégorie
				incrementCompteurAjout($Parameter['UrlFav']);
				CloseMysql();
				header('Location: '.$_POST['Page'].'?&Success=Ajout du favoris effectué avec succès');
			}
			else
			{
				// Verifier si la page qui effectue la requête est une page connu, ainsi que le formulaire
				//Paramètre invalide
				header('Location: '.htmlspecialchars($_POST['Page']).'?Form='.htmlspecialchars($_POST['Form']).'&Etat=1&Err='.$Parameter['Err']);
			}
			break;
		case 'ModifFav':
			$Parameter = verifParameterAddFav($_POST);// Verification que les paramètres sont correct
			if ($Parameter['Err'] == null)
			{
				modifFav($Parameter['UrlFav'],$Parameter['StatutFav'],$Parameter['NomFav'],$Parameter['DescriptionFav'],$Parameter['NoteFav']); // Modification du favoris publie
				assocFavInCat($Parameter['UrlFav'],$Parameter['Categorie']);// association du favoris à une catégorie
				CloseMysql();
				header('Location: ../BibPrivee.php?&Success=Modification du favoris effectué avec succès');
			}
			else
			{
				// Verifier si la page qui effectue la requête est une page connu, ainsi que le formulaire
				//Paramètre invalide
				header('Location: ../BibPrivee.php?Form='.htmlspecialchars($_POST['Form']).'&Etat=1&Err='.$Parameter['Err']);
			}
			break;
		case 'AddCat':
			$Parameter = verifParamAddCat($_POST);// Verification que les paramètres sont correct
			if ($Parameter != null)
			{
				if ($Parameter['Categorie'] == 'Default')
				{
					$ID_Root = checkRootNode($_SESSION['email']);
					publieCategorie($_SESSION['email'],$Parameter['NomCat'],null,$ID_Root);
				}
				else
				{
					publieCategorie($_SESSION['email'],$Parameter['NomCat'],null,$Parameter['Categorie']);
				}
				header('Location: ../BibPrivee.php?&Success=Ajout de la catégorie réussi');
			}
			else
			{
				header('Location: ../BibPrivee.php?Form='.$Parameter['Form'].'&Etat=1');
			}
			break;
		case 'AddMosaique':
			$Parameter = verifParameterAddMosaique($_POST);
			if ($Parameter['Err'] == null)
			{
				$ID_Mosaique = addMosaique($Parameter['Nom_Mosaique'],$Parameter['Links']);
				assocMosaiqueInCat($ID_Mosaique,$Parameter['Categorie']);// association du favoris à une catégorie
				LoadExternalMosaique($Parameter['Default'],$ID_Mosaique);
				defaultMosaique($Parameter['Default'],$Parameter['ID_Mosaique']);
				CloseMysql();
				echo '<ReturnAjax>'.$ID_Mosaique.'</ReturnAjax>';
			}
			else
			{
				echo '<ReturnAjax>'.$Parameter['Err'].'</ReturnAjax>';
			}
			break;
		case 'ModifMosaique':
			$Parameter = verifParameterModifMosaique($_POST);
			if ($Parameter['Err'] == null)
			{
				modifMosaique($Parameter['Links'],$Parameter['ID_Mosaique']);
				defaultMosaique($Parameter['Default'],$Parameter['ID_Mosaique']);
				LoadExternalMosaique($Parameter['Default'],$Parameter['ID_Mosaique']);
				CloseMysql();
				echo '<ReturnAjax>Modification réussi !</ReturnAjax>';
			}
			else
			{
				echo '<ReturnAjax>'.$Parameter['Err'].'</ReturnAjax>';
				CloseMysql();
			}			
			break;
		case 'DeleteMosaique':
			$Parameter = verifParameterDelMosaique($_POST);
			if ($Parameter['Err'] == null)
			{
				delMosaique($Parameter['ID_Mosaique'],null);
				CloseMysql();
				echo '<ReturnAjax>Success=Suppression de la mosaïque réussie !</ReturnAjax>';
			}
			else
			{
				echo '<ReturnAjax>'.$Parameter['Err'].'</ReturnAjax>';
				CloseMysql();
			}			
			break;
		default:
			break;
	}
}
else
{	
	//Redirection  et affichage erreur!
	header('Location: Accueil.php');  
}
?>
<?php
if (isset($_SESSION['mysql']))
{
	mysql_close($_SESSION['mysql']);
	$_SESSION['mysql'] = null;
}
?>