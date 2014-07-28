<?php
	require_once 'config.php';
	
	session_start();
	if ($_SESSION['valid_user'] != 1 || !isset($_POST["Pseudo"]) || !isset($_POST["ancienMdp"]) || !isset($_POST["Mdp"]) || !isset($_POST["ConfMdp"]))
	{
		header ("location: ..");
	}
	
	if (empty($_POST["Pseudo"]))
	{
		$msgErr="Veuillez chosir un pseudo.";
	}
	else if (!empty($_POST["Mdp"]) && empty($_POST["ancienMdp"]))
	{
		$msgErr="Veuillez saisir votre ancien mot de passe.";
	}
	else if (!empty($_POST["ancienMdp"]) && empty($_POST["Mdp"]))
	{
		$msgErr="Veuillez saisir votre nouveau mot de passe.";
	}
	else if ($_POST["Mdp"] != $_POST["ConfMdp"])
	{
		$msgErr="La confirmation du mot de passe est incorrecte.";
	}
	else
	{
		$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
		
		$pseudo = htmlspecialchars(mysql_real_escape_string($_POST["Pseudo"]));
		$ancienMdp = htmlspecialchars( mysql_real_escape_string($_POST["ancienMdp"]));
		$mdp = htmlspecialchars( mysql_real_escape_string($_POST["Mdp"]));
		
		// Vérifier Pseudo déjà existe
		$requete = "SELECT COUNT(*) AS nb FROM membres WHERE Pseudo='$pseudo' AND Email <> '".$_SESSION['email']."'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete);
		if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$nb = $enreg['nb'];
			}
		}
		else
		{
			$msgErr= "Erreur : ".mysql_error($connexion);
		}
		
		if ($nb!=0)
		{
			$msgErr="Ce pseudo est d&eacute;j&agrave; choisi. Veuillez en choisir un autre.";
		}
		else
		{
			if (!empty($_POST["Mdp"]))
			{
				$requete = "SELECT Mdp FROM membres WHERE Email = '".$_SESSION['email']."'";
				$resultat = mysql_query($requete, $connexion) or die ("Erreur de requ&ecirc;te : $requete<br/>" . mysql_error($connexion));
				if ($resultat)
				{
					while ($enreg = mysql_fetch_array($resultat)) 
					{
						$ancienMdp = md5($ancienMdp.md5("GestionFav"));
						if ($enreg['Mdp']!= $ancienMdp)
						{
							$msgErr= "Mot de passe incorrect.";
						}
						else
						{
							// On crypte le mot de passe
							$mdp = md5($mdp.md5("GestionFav"));
							$requete = "UPDATE membres SET Pseudo='$pseudo', Mdp='$mdp' WHERE Email = '".$_SESSION['email']."'";
							mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete);
							$msgErr="Votre modification a &eacute;t&eacute; enregistr&eacute;.";
						}
					}
				}
				else
				{
					echo "Erreur : ".mysql_error($connexion)."<br />";
				}	
			}
			else
			{
				$requete = "UPDATE membres SET Pseudo='$pseudo' WHERE Email = '".$_SESSION['email']."'";
				$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete);
				$msgErr="Votre modification a &eacute;t&eacute; enregistr&eacute;.";
			}

			// Fin de la connexion 
			mysql_close($connexion);		
		}
	}
	echo $msgErr."<br/><a href='../GestionCompte.php'>Retour</a>";
?>