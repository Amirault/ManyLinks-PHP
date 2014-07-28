<?php
	session_start();
	
	//----------------26.03-01.04-----------------------------------------------------------------------------
    require_once "config.php";
	require_once "share.inc.php"; // Fichier share, contenant les fonctions appelées plus bas
	require_once "Lib/method_categorie.php";
	
	//ouverture de la base de donnée
    $_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
    mysql_select_db ($base, $_SESSION['mysql'] ) or die ("$base n'existe pas !");
    
	$email = htmlspecialchars(mysql_real_escape_string($_POST["email"]));
	$mdp =htmlspecialchars( mysql_real_escape_string($_POST["mdp"]));
	
	// On crypte le mot de passe
	$mdp = md5($mdp.md5("GestionFav"));
	
	// Vérification authentification
	$msg = VerifSession($email , $mdp, $_SESSION['mysql'] );
	if ($msg=="ok") 
	{
		// Envoi du cookie, puis redirection
		$_SESSION['email'] = $email;
		// Et pour eviter certaine faille il faudrat sans doute créer une table navigation avec l'id de session $PHPSESSID + le pseudo.
		// Lorsque la connexion est validé il faudrat regenerer un ID de session via ->session_regenerate_id()
		$_SESSION['valid_user'] = 1;
		$_SESSION['IP'] = $_SERVER['REMOTE_ADDR'];
		$_SESSION['ID_Root'] = checkRootNode($email);
		// Fin de la connexion 
		mysql_close($_SESSION['mysql']);
		session_regenerate_id();
		header('location: ../BibPrivee.php'); //redirection vers la page "Ma Bibliothèque"
		exit;
	}
	else 
	{
		$_SESSION['valid_user'] = 0;
		echo $msg;
	}
	
	// Fin de la connexion 
	mysql_close($_SESSION['mysql']);

?>