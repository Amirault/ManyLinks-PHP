<?php
	//----------------26.03-01.04-----------------------------------------------------------------------------
    require_once 'config.php';
	
	session_start();
	if ($_SESSION['valid_user'] != 1)
	{
		header ("location: ../index.php");
	}
	$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");

	$requete = "DELETE FROM favoris_publie WHERE Email_Membre = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM arbre_categorie WHERE Email_Membre = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM categorie_favoris WHERE Email_Membre = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM message_envoye WHERE Email_expediteur = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM message_recu WHERE Email_destinataire = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM mosaique_publie WHERE Email_Membre = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	$requete = "DELETE FROM membres WHERE Email = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requete !");
	
	// Fin de la connexion 
	mysql_close($connexion);
	
	//destruction de la session
	session_destroy();
	unset($_SESSION);
	
	header ("location: ../index.php?Success=Votre compte a été supprimé ! A bientôt ?");

?>