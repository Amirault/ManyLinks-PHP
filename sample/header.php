<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
	<head>
<!-- ##HEADER## -->
		<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen"/>
		<link href="bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet" media="screen"/>
		<link href="bootstrap/css/style.css" rel="stylesheet" type="text/css"/>
		<link href="bootstrap/css/menu.css" rel="stylesheet" media="screen"/>
		<script src="bootstrap/js/jquery-1.9.1.min.js" type="text/javascript"></script>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />	
		<title>ManyLinks</title>
<!-- ##HEADER## -->
	</head>
	<body>
<?php
	session_start(); // On demarre la session avant tout
	$page= substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], '/')+1,strrpos($_SERVER['PHP_SELF'],'.')-1);
	// Si connexion à la base est ouverte alors on la ferme
	if (isset($_SESSION['mysql']))
	{
		mysql_close($_SESSION['mysql']);
		unset($_SESSION['mysql']);
	}
	// Si l'ip du visiteur ne correspond pas à celle enregistré alors detruit la session et le renvoie à la connexion
	if (isset($_SESSION['IP']) && ($_SESSION['IP'] != $_SERVER['REMOTE_ADDR']))
	{
		//Destruction de la session
		session_destroy();
		unset($_SESSION);
		header('location: /Connexion.php'); //redirection vers la page de connexion
		exit;
	}
	else
	{
		// On enregistre l'ip du visiteur
		$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
	}
	// On vérifie si l'utilisateur est connecté
	if (isset($_SESSION['valid_user'])&&($_SESSION['valid_user'] == 1))
	{
		// L'utilisateur est connecté
		// On verifie qu'il se trouve bien sur une page autorisé
		if (!((($page != 'BibPrivee')XOR($page != 'BibPrivee.php'))
		||(($page != 'BibPublique')XOR($page != 'BibPublique.php'))
		||(($page != 'BibPrivee')XOR($page != 'BibPrivee.php'))
		||(($page != 'Accueil')XOR($page != 'Accueil.php'))
		||(($page != 'Contact')XOR($page != 'Contact.php'))
		||(($page != 'GestionCompte')XOR($page != 'GestionCompte.php'))
		||(($page != 'Galerie')XOR($page != 'Galerie.php'))
		||(($page != 'Mosaique')XOR($page != 'Mosaique.php')))
		)
		{
			// Page non autorisé
			header('location: /Accueil.php'); //redirection vers la page d'accueil
			exit;
		}
		else
		{
			$wd_was = getcwd();
			chdir(dirname(__FILE__));
			chdir("../script/");
			require_once ('config.php');
			chdir("Lib/");
			require_once("isBlank.php");
			require_once("method_categorie.php");
			require_once("method_mosaique.php");
			require_once("method_verif.php");
			require_once("method_aff.php");
			require_once("method_fav.php");
			chdir($wd_was);
			// On établie la connexion à la base de donnée
			$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
			mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
			
			$requete = "SELECT * from arbre_categorie WHERE Email_Membre='".$_SESSION['email']."'";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);	
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{	
				$Data[$enreg['ID_Noeud']] = $enreg;
			}
			// On inclu le menu en connexion
			chdir(dirname(__FILE__));
			require_once ('MenuCo.php');
			chdir($wd_was);
		}
	}
	else
	{	
		// L'utilisateur n'est pas connecté
		// On verifie qu'il se trouve bien sur une page autorisé
		if (!((($page != 'index')XOR($page != 'index.php'))
		||(($page != 'Connexion')XOR($page != 'Connexion.php'))
		||(($page != 'Inscription')XOR($page != 'Inscription.php'))
		||(($page != 'Contact')XOR($page != 'Contact.php'))
		||(($page != 'MdpOublie')XOR($page != 'MdpOublie.php'))
		||(($page != 'mdpReinilisation')XOR($page != 'mdpReinilisation.php')))
		)
		{
			// Page non autorisé
			//Destruction de la session (sécurité)
			session_destroy();
			unset($_SESSION);
			header('location: /Connexion.php'); //redirection vers la page de connexion
			exit;
		}
		else
		{
			$wd_was = getcwd();
			chdir(dirname(__FILE__));
			chdir("../script/");
			require_once ('config.php');
			chdir("Lib/");
			require_once("method_aff.php");
			chdir($wd_was);
			// On établie la connexion à la base de donnée
			$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
			mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
			chdir(dirname(__FILE__));
			// On inclu le menu hors connexion
			require_once ('MenuDeco.php');
			chdir($wd_was);
		}
	}
?>