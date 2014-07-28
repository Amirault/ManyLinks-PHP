<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
<?php
	include('sample/Modal_Mosaique.php');
?>
		<div class="EntetePrincipalSeul"><h1>Mosaïque</h1></div>
		<!-- Entete de la page : -->
			
<?php
	if (isset($_GET['disp'])&&(is_numeric($_GET['disp'])))
	{
		$ID_Mosaique = mysql_real_escape_string(htmlspecialchars($_GET['disp']));
?>
				<input type="hidden" name="ID_Mosaique" id="ID_Mosaique" value="<?php echo $ID_Mosaique; ?>" />
				<input type="hidden" name="Form" id="NomForm" value="ModifMosaique" />
				<input type="hidden" name="Page" id="NomPage" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
				
<?php
		// Affichage d'une mosaïque existante (à modifier)
		aff_mosaiqueModif($ID_Mosaique);
	}
	else
	{
		// Affichage d'une nouvelle mosaïque (vide)
		include("sample/MosaiqueVide.php");
	}
?>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>