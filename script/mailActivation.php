<?php
	require_once "config.php";
	require_once "share.inc.php"; // Fichier share, contenant les fonctions appelées plus bas
	
	 if(!isset($_GET["m"]))
	{
		header ("location: ..");
	}
		$email = htmlspecialchars(mysql_real_escape_string($_GET["m"]));
		
		$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
		
		$requete = "SELECT Pseudo, Clef FROM membres WHERE Email='$email'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete. mysql_error($connexion));
		
		if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$pseudo = $enreg['Pseudo'];
				$cle = $enreg['Clef'];
			}
		}
		else
		{
			$msgErr= "Erreur : ".mysql_error($connexion);
		}
		
		//Envoyer mail
		$msgErr = mailActivation($email, $pseudo, $cle);
		
		echo $msgErr."<br/><a href='..'>Accueil</a>";
?>