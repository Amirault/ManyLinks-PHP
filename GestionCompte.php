<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
		<div class="EntetePrincipal"><h1>Mon Compte</h1></div>
			
<?php 
	include('sample/Modal_GestionCompte.php');
?>
<?php 
	
	$requete = "SELECT * FROM membres WHERE Email = '".$_SESSION['email']."'";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
	if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$pseudo = $enreg['Pseudo'];
			}
		}
		else
		{
			echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
		}
?>
		
		<form id="compte_info" name="envoi" method="post" action="./script/compte_modification.php" onsubmit="return validModifCompte();" enctype="multipart/form-data">
			<div class="row-fluid margin">
				<div class="span2 transparent"></div>
				<div class="span4 BlockStyler padding">
					<label for="Pseudo"> Pseudo : </label>
					<input name="Pseudo" type="text" class="" id="Pseudo" size="30" required="" value="<?php echo $pseudo; ?>" />
					<br />
					<br />
					<label for="email">&nbsp;Email : </label>
					<div id="DivEmail">
						<label name="email" id="email"><?php echo $_SESSION['email'];?></label>
					</div>
				</div>
				<div class="span4 BlockStyler padding">
							<label for="ancienMdp">Ancien Mot de passe : </label>
							<input name="ancienMdp" type="password" class="" id="ancienMdp" size="30" />
							<br />
							<br />
							<label for="Mdp">Nouveau Mot de passe : </label>
							<input name="Mdp" type="password" class="" id="Mdp" size="30" />
							<br />
							<br />
							<label for="ConfMdp">Confirmer le mot de passe : </label>
							<input name="ConfMdp" type="password" class="" id="ConfMdp" size="30" />
				</div>
				<div class="span2 transparent"></div>
			</div>
			
			<div class="row-fluid margin">
				<div class="span2 transparent"></div>
				<div class="span8 BlockStyler padding">
					<div id="BottomButton">
						<div id="ModifCompte">
							<label for="Modif">Modifier le compte : </label>
							<input name="Modif" class="btn btn-info dropdown-toggle pull-center" role="button" type="submit" value="Modifier"/>
						</div>
						
						<div id="SuppCompte">
							<label for="BtnSupp">Supprimer le compte : </label>
							<a name="BtnSupp" class="btn btn-danger dropdown-toggle pull-center" role="button" data-toggle="modal" href="#myModalSupression"> Suppression </a>
						</div>
					</div>
				</div>
				<div class="span2 transparent"></div>
			</div>
		</form>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>	