<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("script/");
	require_once ('config.php');
	chdir($wd_was);
?> 
<?php
	if (isset($_GET['URL']))
	{
		$URL = mysql_real_escape_string(htmlspecialchars(urldecode($_GET['URL'])));
		$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
		mysql_select_db ($base,$connexion) or die ("La base :".$base." n'existe pas !");
		$requete = "UPDATE favoris
		SET Compteur_clic=Compteur_clic + 1
		WHERE URL='".$URL."'";
		$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête  : \n".$requete);
	}
?>
<?php
if (isset($connexion))
{
	mysql_close($connexion);
	unset ($connexion);
	$connexion = null;
}
exit;
?>