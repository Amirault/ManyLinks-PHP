<?php
$wd_was = getcwd();
chdir(dirname(__FILE__));
require_once('sample/header.php');
chdir($wd_was);
?>
<!-- ##CORPS DE LA PAGE## -->
		<div class="EntetePrincipal"><h1>ManyLinks</h1></div>
		<!--  Emporter vos favoris avec vous! -->
		<div class="row-fluid margin">
		
			<!-- Premiere colonne -->
			<div class="span3 BlockStyler padding">
					<?php
						$_SESSION['mysql'] = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
						mysql_select_db ($base, $_SESSION['mysql'] ) or die ("$base n'existe pas !");
						aff_dernierAjout();
					?>
			</div>
			
			<!--Deuxieme colonne -->
			<div class="span6 BlockStyler padding">
				<h4>ManyLinks</h4>
				<h4>Demo</h4>
				<div id="myCarousel" class="carousel slide">
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
						<li data-target="#myCarousel" data-slide-to="3"></li>
						<li data-target="#myCarousel" data-slide-to="4"></li>
						<li data-target="#myCarousel" data-slide-to="5"></li>
					</ol>
					<div class="carousel-inner">
					  <div class="active item">
						<img src="image/import.jpg" alt="Importer vos favoris" />
						<div class="carousel-caption">
					  <h4>Import</h4>
					  <p>Importer vos favoris avec votre navigateur</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/mosaique.jpg" alt="Créer des mosaïques" />
						<div class="carousel-caption">
					  <h4>Mosaique</h4>
					  <p>Afficher sur des mosaiques vos favoris</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/bibliprivee.jpg" alt="Gérer vos favoris" />
						<div class="carousel-caption">
					  <h4>G&eacute;rer ses favoris</h4>
					  <p>Avec les bibliotheques</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/stat.jpg" alt="Consulter les statistiques" />
						<div class="carousel-caption">
					  <h4>Statistiques</h4>
					  <p>Obtenir des statistiques sur les favoris</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/mosa.jpg" alt="Composer votre galerie" />
						<div class="carousel-caption">
					  <h4>Galerie</h4>
					  <p>Composer des galeries de mosaiques</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/staff.jpg" alt="Equipe de ManyLinks" />
						<div class="carousel-caption">
					  <h4>Staff</h4>
					  <p>Les talents</p>
					  </div>
					  </div>
					</div>
					<!-- Carousel nav -->
					<a class="carousel-control left" href="#myCarousel" data-slide="prev">&lsaquo;</a>
					<a class="carousel-control right" href="#myCarousel" data-slide="next">&rsaquo;</a>
				</div>
			</div>
			
			<!-- Troisième colonne : -->
			<div class="span3 BlockStyler padding">
				<form id ="inscription" name="inscription" action="script/inscription.php" onsubmit="return validInscription();" method="post">
						<h4>Inscription rapide !</h4>
						<fieldset>
						<label for="Pseudo">Entrez votre pseudo :</label>
						<input id="Pseudo" name="Pseudo" type="text" class="input-block-level" placeholder="Pseudonyme" required />
						<label for="Email">Entrer une adresse email valide :</label>
						<input id="Email" name="Email" type="text" class="input-block-level" placeholder="Adresse email" required />
						<label for="Mdp">Choisir un mot de passe :</label>
						<input id="Mdp" name="Mdp" type="password" class="input-block-level" placeholder="Mot de passe" required />
						<label for="ConfMdp">Confirmer votre mot de passe:</label>
						<input id="ConfMdp" name="ConfMdp" type="password" class="input-block-level" placeholder="Confirmation mot de passe" required />
						<input class="btn btn-large btn-block btn-primary" type="submit" value="Valider" />
						</fieldset>						
				</form>
			</div>
		</div>
<!-- ##FIN DU CORPS DE LA PAGE## -->
<?php
$wd_was = getcwd();
chdir(dirname(__FILE__));
require_once('sample/footer.php');
chdir($wd_was);
?>