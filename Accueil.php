<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
<!-- ##CORPS DE LA PAGE## -->
		<div class="EntetePrincipal">
			<h1>ManyLinks</h1>
		</div>
		<!--  Emporter vos favoris avec vous! -->
		<div class="row-fluid margin">
			<!-- Premiere colonne -->
			<div class="span3 BlockStyler padding">
					<?php
						aff_dernierAjout();
					?>
			</div>		
			<!--Deuxieme colonne -->
			<div class="span6 BlockStyler padding">
				<h4>Démonstration ManyLinks</h4>
				<div id="myCarousel" class="carousel slide">
					<ol class="carousel-indicators">
						<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
						<li data-target="#myCarousel" data-slide-to="1"></li>
						<li data-target="#myCarousel" data-slide-to="2"></li>
						<li data-target="#myCarousel" data-slide-to="3"></li>
						<li data-target="#myCarousel" data-slide-to="4"></li>
						<li data-target="#myCarousel" data-slide-to="5"></li>
					</ol>
					<!-- Carousel items -->
					<div class="carousel-inner">
					  <div class="active item">
						<img src="image/import.jpg" alt="">
						<div class="carousel-caption">
					  <h4>Import</h4>
					  <p>Importer vos favoris avec votre navigateur</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/mosaique.jpg" alt="">
						<div class="carousel-caption">
					  <h4>Mosaique</h4>
					  <p>Afficher sur des mosaiques vos favoris</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/bibliprivee.jpg" alt="">
						<div class="carousel-caption">
					  <h4>G&eacute;rer ses favoris</h4>
					  <p>Avec les bibliotheques</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/stat.jpg" alt="">
						<div class="carousel-caption">
					  <h4>Statistiques</h4>
					  <p>Obtenir des statistiques sur les favoris</p>
					  </div>
					  </div>
					  <div class="item">
						<img src="image/mosa.jpg" alt="">
						<div class="carousel-caption">
					  <h4>Galerie</h4>
					  <p>Composer des galeries de mosaiques</p>
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
				<?php
					aff_Membres();
				?>
			</div>
		</div>
<!-- ##FIN DU CORPS DE LA PAGE## -->
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>