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
	chdir($wd_was);
	//Ouverture de la session
	session_start();
if((isset($_POST['form']))&&($_POST['form'] == 'Contact')&& (isset($_SESSION['securecode'])))
{
	$Parameter = verifParameterContact($_POST);
	if ($Parameter['Err'] == null)
	{	
		//Envoyer mail
		// Préparation du mail contenant le lien d'activation
		$sujet = htmlspecialchars($_POST['Sujet']) ;
		// Le lien d'activation est composé du pseudo(p) et de la clé(c)
		$message = " Nom :".htmlspecialchars($_POST['Nom'])."\n
		Prenom :".htmlspecialchars($_POST['Prenom'])."\n
		Email :".htmlspecialchars($_POST['Email'])."\n
		Message :".htmlspecialchars($_POST['Question']);

		if(mail('amirault.tony@gmail.com', $sujet, $message))
		{
			header("Location: ../index.php?Success= Votre message a été envoyé ! Attendez un peu avant d'avoir une réponse.");
			exit;
		}
		else
		{
			header("Location: ../index.php?Err= Oups, votre message n a pu être envoyé... réessayer.");
			exit;
		}			
		unset($_SESSION['securecode']);
	}
	else
	{
		header("Location: ../Contact.php?Err=".$Parameter['Err']);
	}
}
else
{
	header("Location: ../Contact.php");
}
?>
<?php
if (isset($_SESSION['mysql']))
{
	mysql_close($_SESSION['mysql']);
	$_SESSION['mysql'] = null;
}
?>