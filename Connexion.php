<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
		<!-- ========= PAGE DE CONNEXION ================= -->
		<div class="first_content">
		
			<h1 class="entete">Connexion</h1>
			
			<span class="separator"></span>
			
			<!-- formulaire de connnexion : -->
			<div>
				<form class="form-signin" action="script/login.php" method="post">
					<h4 class="form-signin-heading">Entrer vos identifiant :</h2>
					<input type="text" class="input-block-level" id="email" name="email" placeholder="Adresse email">
					<input type="password" class="input-block-level" id="mdp" name="mdp" placeholder="Mot de passe">
					<label class="checkbox">
						<input type="checkbox" value="remember-me"> Se souvenir de moi
					</label>
					<button class="btn btn-large btn-primary" type="submit">Se connecter</button>
				</form>
			</div> 
			
			<!-- =================== ESPACE AIDE ========================= -->
				<div class="conteneur_message">
					<div class="no_cmpt">
						<div class="alert alert-info message">
							<p><i class="icon-black icon-exclamation-sign"></i><strong> Pas de compte ?</strong></p>
						</div>
						<p>Creez-en un <a href="#">gratuitement en moins de 2 min</a></p>
					</div>
					
					<div class="forgot_mdp">
						<div class="alert alert-error message">
							<p><i class="icon-black icon-exclamation-sign"></i><strong> Mot de passe oublie ?</strong></p>
						</div>
						<p>Consultez notre <a href="#">lien de recuperation de mot de passe</a>.</p>
					</div>
				</div>
			<!-- =========================================================== -->
			
		</div>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>