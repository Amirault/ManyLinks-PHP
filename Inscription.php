<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
		<!-- ========= PAGE D'INSCRIPTION ================= -->
		<div class="EntetePrincipal"><h1>Inscription</h1></div>		
		<div class="container-fluid">
			<div class="row-fluid margin">		
				<div class="span4"></div>
				<div class="span4 BlockStyler padding"> 
					<!-- formulaire d'inscription : -->
					<form id ="inscription" name="inscription" action="script/inscription.php" onsubmit="return validInscription();" method="post">
						<h4 class="form-signin-heading">Veuillez saisir vos différentes informations :</h2>
						<span class="separator"></span>
						<label for="Pseudo">Entrez votre pseudo :</label>
						<input id="Pseudo" name="Pseudo" type="text" class="input-block-level" placeholder="Pseudonyme" required>
						<label for="Email">Veuillez entrer une adresse email valide :</label>
						<input id="Email" name="Email" type="text" class="input-block-level" placeholder="Adresse email" required>
						<label for="Mdp">Veuillez choisir un mot de passe :</label>
						<input id="Mdp" name="Mdp" type="password" class="input-block-level" placeholder="Mot de passe" required>
						<label for="ConfMdp">Confirmer votre mot de passe :</label>
						<input id="ConfMdp" name="ConfMdp" type="password" class="input-block-level" placeholder="Confirmation mot de passe" required>					
						<input class="btn btn-large btn-block  btn-primary" type="submit" value="Valider votre inscription"/>
					</form>
				</div>
				<div class="span4"></div>
			</div>
		</div>
		
		<!-- ============================================= -->
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>