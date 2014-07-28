<?php
	require_once 'config.php';
	
	if(!isset($_POST["Mdp"]) || !isset($_POST["ConfMdp"]))
	{
		header ("location: ../index.php");
	}

	if (empty($_POST["Mdp"]))
	{
		header("Location: ../index.php?Err=Veuillez choisir un mot de passe.");
	}
	else if ($_POST["Mdp"] != $_POST["ConfMdp"])
	{
		header("Location: ../index.php?Err=La confirmation du mot de passe est incorrecte.");
	}
	else
	{
		$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
		
		$pseudo = htmlspecialchars(mysql_real_escape_string($_POST["Pseudo"]));
		$mdp =htmlspecialchars( mysql_real_escape_string($_POST["Mdp"]));
		// On crypte le mot de passe
		$mdp = md5($mdp.md5("GestionFav"));
		
		$requete = "UPDATE membres SET Mdp='$mdp' WHERE Pseudo='$pseudo'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete);
		
		// Fin de la connexion 
		mysql_close($connexion);
		
		header("Location: ../index.php?Success=Votre nouveau mot de passe à été enregistré, essayez de vous connecter !");
	}
?>