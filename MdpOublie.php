<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
	
	$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
	
	$msgErr="";
	if (!isset($_GET["p"]) || !isset($_GET["c"]))
	{
?>
<!-- ========= PAGE DE Mot de passe oublié ================= -->
	<div class="EntetePrincipal"><h1>Mot de passe oublié</h1></div>
	<div class="row-fluid margin">
		<div class="span3"></div>
		<div class="span6 BlockStyler">
			<form method="POST" action="#">
				<table class="table_position">
					<tr>
						<td>
							<h4>Vous avez oubliez votre mot de passe ? Pas d'inquiétude ! 
							saisissez l'email associé à votre compte et un email pour réinitialiser votre mot de passe vous sera envoyé.</h4>
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" name="email" id="email"/>
							<button class="btn btn-success" type="submit">Envoyer l'email</button>
						</td>
					</tr>
				</table>
				<input type="hidden" name="page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				<input type="hidden" name="form" value="MdpOublie" />
			</form>
		</div>
		<div class="span3"></div>
	</div>
<?php
	if (isset($_POST["email"]))
	{
		$email = htmlspecialchars(mysql_real_escape_string($_POST["email"]));
		
		// Vérifier email déjà inscrit
		$requete = "SELECT COUNT(*) AS nb FROM membres WHERE Email='$email'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete. mysql_error($connexion));
		
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
		
		if ($nb==0)
		{
			header("Location: MdpOublie.php?Err=Désolé, on ne connais pas cette email...");
		}
		else
		{
			$requete = "SELECT Pseudo, Mdp, TimeStamp FROM membres WHERE Email = '$email'";
			$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete. mysql_error($connexion));
			if ($resultat)
			{
				while ($enreg = mysql_fetch_assoc($resultat)) 
				{
					$pseudo = $enreg['Pseudo'];
					$mdp = $enreg['Mdp'];
					$t = $enreg['TimeStamp'];
				}
			}
			else
			{
				echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
			}
			
			$cle = md5($mdp.$t);
			//Envoyer mail
			// Préparation du mail contenant le lien de réinitialisation
			$sujet = "Réinitialisation du mot de passe" ;
			// Le lien est composé de la clé(c)
			$message = "Bonjour,
			
			Vous avez oublié votre mot de passe du compte : ".$email."

			Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien ci dessous
			ou le copier/coller dans votre navigateur internet (en haut dans la barre d'adresse).

			http://manylinks.fr/MdpOublie.php?p=".urlencode($pseudo)."&c=".urlencode($cle)."

			Petit conseil : Noter votre mot de passe quelque part afin de vous aider à vous en souvenir :).
			
			Ceci est un mail automatique, Merci de ne pas y répondre.";
			
			if(mail($email, $sujet, $message))
			{
				header("Location: MdpOublie.php?Success= Le mail vous a été envoyé, allez voir votre compte mail : $email");
				exit;
			}
			else
			{
				header("Location: MdpOublie.php?Err=Oups, votre message n a pu être envoyé... réessayer.");
				exit;
			}
		}
	}
	}
	else
	{
		$pseudo = htmlspecialchars(mysql_real_escape_string($_GET["p"]));
		$cle = htmlspecialchars(mysql_real_escape_string($_GET["c"]));
		
		$requete = "SELECT Mdp, TimeStamp FROM membres WHERE Pseudo='$pseudo'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete);
		
		if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$mdp = $enreg['Mdp'];
				$t = $enreg['TimeStamp'];
			}
		}
		else
		{
			$msgErr= "Erreur : ".mysql_error($connexion);
		}
		
		if (strcmp($cle, md5($mdp.$t))==0)
		{
?>
<!-- ========= PAGE DE Réanilisation du mot de passe ================= -->
	<div class="first_content">
		<h1 class="entete">Réinitialisation du mot de passe</h1>
		<span class="separator"></span>
		<form method="POST" action="script/mdpReinilisation.php" onsubmit="return validReinilisationMdp();">
			<table class="table table-bordered table-striped table-condensed">
				<caption>
				<h4>Saisissez votre nouveau mot de passe</h4>
				</caption>
						<tr>
						  <td class="legende"><div align="left">Pseudo : </div></td>
						  <td><div align="left">
							<label class=""> <?php echo $pseudo; ?></label>
							<input type="hidden" name="Pseudo" id="Pseudo" value="<?php echo $pseudo; ?>" />
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Nouveau Mot de passe : </div></td>
						  <td><div align="left">
							<input name="Mdp" type="password" class="" id="Mdp" size="30" />
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Confirmer le mot de passe : </div></td>
						  <td><div align="left">
							<input name="ConfMdp" type="password" class="" id="ConfMdp" size="30" />
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Modifier le compte : </div></td>
						  <td><div align="left">
							<input class="btn dropdown-toggle pull-center" role="button" type="submit" value="Modifier"/>
						  </div></td>
						</tr>
				  </table>
		</form>
<?php
		}
		else
		{
			header("Location: ../index.php?Err=C est Bizzarre, il semble que le lien de réinitialisation n est pas valide...");
		}
	}
	mysql_close($connexion);
?>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>