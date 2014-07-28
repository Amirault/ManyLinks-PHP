<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once ('config.php');
	chdir($wd_was);
?> 
<?php
	if (isset($_GET['URL']))
	{
		$URL = mysql_real_escape_string(htmlspecialchars($_GET['URL']);
		mysql_close($_SESSION['mysql']);
		$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
		$requete = "UPDATE favoris
		SET Compteur_clic=Compteur_clic + 1
		WHERE URL=".$_GET['URL'];
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	}
?>
<?php
if (isset($_SESSION['mysql']))
{
	mysql_close($_SESSION['mysql']);
	$_SESSION['mysql'] = null;
}
?>