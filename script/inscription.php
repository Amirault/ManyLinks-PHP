<?php
	//----------------26.03-01.04-----------------------------------------------------------------------------
    require_once "config.php";
	require_once "share.inc.php"; // Fichier share, contenant les fonctions appelées plus bas
	session_start();
	if (!isset($_SESSION['mysql']))
	{
		$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
	}
	
	if(!isset($_POST["Pseudo"]) || !isset($_POST["Email"]) || !isset($_POST["Mdp"]) || !isset($_POST["ConfMdp"]))
	{
		$msgErr= "";
		if(!isset($_GET["p"]) || !isset($_GET["c"]))
		{
			header ("location: ../");
			if (isset($_SESSION['mysql']))
			{
				mysql_close($_SESSION['mysql']);
				$_SESSION['mysql'] = null;
			}
			exit;
		}		
		$pseudo = mysql_real_escape_string(htmlspecialchars($_GET["p"]));
		$cle = mysql_real_escape_string(htmlspecialchars($_GET["c"]));
		
		$requete = "SELECT Actif, Clef FROM membres WHERE Pseudo = '$pseudo'";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête : $requete<br/>" . mysql_error($_SESSION['mysql']));
		
		if ($resultat)
		{
			if (mysql_num_rows($resultat)==0 )
			{
				header("Location: ../index.php?Err=C est Bizzarre, il semble que le lien de validation n est pas valide...");
			}
			else
			{
				while ($enreg = mysql_fetch_assoc($resultat)) 
				{
					if($enreg['Actif'] == "1") // Si le compte a été déjà activé
					{
						header("Location: ../index.php?Success= Il semble que votre compte est déjà activé ;), Essayez de vous connecter.");
					}
					else if($cle == $enreg['Clef']) // Comparer deux clés	
					{
						$requete = "UPDATE membres SET Actif = 1 WHERE Pseudo = '$pseudo'";	// Passer Actif de 0 à 1
						mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête !");
						header("Location: ../index.php?Success=Super ! Votre compte a été activé. Connectez-vous vite !");
					}
					else
					{
						header("Location: ../index.php?Err=C est Bizzarre, il semble que le lien de validation n est pas valide...");
					}
				}
			}
		}
		else
		{
			
			$msgErr.= "Erreur : ".mysql_error($_SESSION['mysql'])."<br/>";
		}
				
		// Fin de la connexion 
		mysql_close($_SESSION['mysql']);
	}
	else if (empty($_POST["Pseudo"]))
	{
		header("Location: ../inscription.php?Err=Veuillez choisir un pseudo.");
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
		exit;
	}
	else if (empty($_POST["Email"]) || !preg_match("/^.+@.+\.[a-zA-Z]{2,6}$$/",$_POST["Email"]))
	{
		header("Location: ../inscription.php?Err=Veuillez saisir un e-mail valable.");
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
		exit;
	}
	else if (empty($_POST["Mdp"]))
	{
		header("Location: ../inscription.php?Err=Veuillez choisir un mot de passe.");
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
		exit;
	}
	else if ($_POST["Mdp"] != $_POST["ConfMdp"])
	{
		header("Location: ../inscription.php?Err=La confirmation du mot de passe est incorrecte.");
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
		exit;
	}
	else
	{   
		// Il est préférable de faire un mysql_real_escape_string(htmlspecialchars($mavar)) car les caractère d'echapement que peut mettre un htmlspecialchar peuvent être dangeureux lors d'une requete sql
		$email = htmlspecialchars(mysql_real_escape_string($_POST["Email"]));
		$pseudo = htmlspecialchars(mysql_real_escape_string($_POST["Pseudo"]));
		$mdp =htmlspecialchars( mysql_real_escape_string($_POST["Mdp"]));
		
		// Vérifier Pseudo déjà existe
		$requete = "SELECT COUNT(*) AS nb FROM membres WHERE Pseudo='$pseudo'";
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete);
		if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$nb = $enreg['nb'];
			}
		}
		else
		{
			$msgErr= "Erreur : ".mysql_error($_SESSION['mysql']);
		}
		
		if ($nb!=0)
		{
			header("Location: ../inscription.php?Err=Ce pseudo est déjà choisi. Veuillez choisir un autre.");
			if (isset($_SESSION['mysql']))
			{
				mysql_close($_SESSION['mysql']);
				$_SESSION['mysql'] = null;
			}
			exit;
		}
		else
		{
			// Vérifier email déjà inscrit
			$requete = "SELECT COUNT(*) AS nb FROM membres WHERE Email='$email'";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
			
			if ($resultat)
			{
				while ($enreg = mysql_fetch_assoc($resultat)) 
				{
					$nb = $enreg['nb'];
				}
			}
			else
			{
				$msgErr= "Erreur : ".mysql_error($_SESSION['mysql']);
			}
			
			if ($nb!=0)
			{
				header("Location: ../MdpOublie.php?Err=Cet email est déjà inscrit.");
				if (isset($_SESSION['mysql']))
				{
					mysql_close($_SESSION['mysql']);
					$_SESSION['mysql'] = null;
				}
				exit;
			}
			
			else
			{
				// Génération aléatoire d'une clé
				$cle = md5(microtime(TRUE)*100000);
				
				// On crypte le mot de passe
				$mdp = md5($mdp.md5("GestionFav"));
				
				$requete = "INSERT INTO membres (Email, Pseudo, Mdp, Type, Actif, Clef) VALUES ('$email', '$pseudo', '$mdp', 'M', 0, '$cle')";
				mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete);
				
				//Envoyer mail
				$msgErr = mailActivation($email, $pseudo, $cle);
			}
		}	
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
		exit;
	}
	if (isset($_SESSION['mysql']))
	{
		mysql_close($_SESSION['mysql']);
		$_SESSION['mysql'] = null;
	}
	exit;
?>