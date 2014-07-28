<?php
	//----------------26.03-01.04-----------------------------------------------------------------------------

	function mailActivation($email, $pseudo, $cle)
	{			
		// Préparation du mail contenant le lien d'activation
		$sujet = "Activer votre compte" ;
		// Le lien d'activation est composé du pseudo(p) et de la clé(c)
		$message = "Bienvenue sur ManyLinks,

		Pour activer votre compte, veuillez cliquer sur le lien ci dessous
		ou le copier/coller dans votre navigateur internet.

		http://manylinks.fr/script/inscription.php?p=".urlencode($pseudo)."&c=".urlencode($cle)."

		Ceci est un mail automatique, Merci de ne pas y répondre.";

		if(mail($email, $sujet, $message))
		{
			header("Location: ../index.php?Success= C'est presque fini. Un email d'activation a été envoyé à votre compte mail  : $email , allez voir !");
			exit;
		}
		else
		{
			header("Location: ../index.php?Err= Oups, un problème c est produit l'ors de votre inscription, réessayer. ");
			exit;
		}
		return $msgErr;
	}
	function verifSession ($email , $mdp, $connexion)
	{	
		if (!isset($_SESSION['mysql']))
		{
			$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
			mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
		}
		//si le email existe
		$requete = "SELECT Email, Mdp, Actif FROM membres  WHERE Email = '$email'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requ&ecirc;te : $requete<br/>" . mysql_error($connexion));

		
		if ($resultat)
		{
			if (mysql_num_rows($resultat)==0 )
			{
				header("Location: ../index.php?Err=L'utilisateur $email n'existe pas.");
				exit;
			}
			else
			{
				while ($enreg = mysql_fetch_assoc($resultat)) 
				{
					if ($enreg['Mdp']!= $mdp)
					{
						header("Location: ../index.php?Err=Mot de passe incorrect.");
						if (isset($_SESSION['mysql']))
						{
							mysql_close($_SESSION['mysql']);
							$_SESSION['mysql'] = null;
						}
						exit;
					}
					else if ($enreg['Actif']==0)
					{
						return "Votre compte n'est pas encore activé. <br/> <a href=\"./mailActivation.php?m=$email\">Renvoyer le mail d'activation</a>";
					}
					else
					{
						return "ok";
					}
				}
			}
		}
		else
		{
			echo "Erreur : ".mysql_error($connexion)."<br />";
			return false;
		}
	}
?>