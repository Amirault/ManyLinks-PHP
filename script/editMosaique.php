<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once ('config.php');
	chdir("Lib/");
	require_once ('isBlank.php');
	chdir($wd_was);
?>
<?php
//Ouverture de la session
session_start();
if (!isset($_SESSION['mysql']))
{
	$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $_SESSION['mysql']) or die ("La base :".$base." n'existe pas !");
}
?> 
<?php
	
?>