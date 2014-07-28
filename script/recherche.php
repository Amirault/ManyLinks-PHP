<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once ('config.php');
	chdir($wd_was);
?>
<?php
	$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
	
	$search = htmlspecialchars(mysql_real_escape_string($_POST["search"]));
	$limit = htmlspecialchars(mysql_real_escape_string($_POST["limit"]));	
	
	$requete = "SELECT Title FROM favoris WHERE Statut = 'public' AND (Title LIKE '%$search%') UNION SELECT URL as Title FROM favoris WHERE Statut = 'public' AND (URL LIKE '%$search%') LIMIT $limit";
	$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête : $requete<br/>" . mysql_error($connexion));
	$array = array();
	if ($resultat)
	{
		while ($enreg = mysql_fetch_assoc($resultat)) 
		{
			$array[] = $enreg['Title'];
		}
		echo json_encode($array);
	}
	else
	{
		echo "Erreur : ".mysql_error($connexion)."<br />";
	}
	// Fin de la connexion 
	mysql_close($connexion);
?>