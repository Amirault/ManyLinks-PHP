<?php 
	//----------------26.03-01.04--------------------------------------------------
	session_start();
	if ($_SESSION['valid_user'] == 1)
	{
		//destruction de la session
		session_destroy();
		unset($_SESSION);
	}
	
	//retour à l'acceuil public
	header('location: ../index.php');	//redirection vers la page d'accueil
?>


