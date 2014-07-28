<!-- ======================== FORMULAIRE D'AJOUT DE CATEGORIE ============================== -->
<div id="myModalCategorie" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelCategorie" aria-hidden="true">
	<!-- ================== ENTETE DU FORMULAIRE ====================== -->
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabelCategorie">Ajouter une catégorie</h3>
	</div>
	<!-- ============================================================== -->
	<form class="form" action="script/edit.php" method="POST">
		<!-- ================== CORPS DU FORMULAIRE ====================== -->
		<div class="modal-body">
		<!-- Champs de saisie du nom du favoris -->
		<label for="TextName">Nom de votre nouvelle catégorie <i>(obligatoire)</i> :</label>
		<input name="NomCat" id="TextName" type="text" class="span5" placeholder="Nom de de votre catégorie" required>
		<br />
		<br />
		<label for="Categorie">A placer commme sous-catégorie de :</label>
		<select Name="Categorie" class="span5">
			<option value="Default" >Aucune catégorie sélectionnée</option>
<?php
recurse_tree_select($_SESSION['ID_Root'],$Data,"",$_SESSION['ID_Root']);
?>
		</select>
		<input type="hidden" name="Form" value="AddCat" />
		<input type="hidden" name="Page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
		<br />
		<br />	
		</div>	
		<!-- ============================================================== -->			
		<!-- =================== BAS DU FORMULAIRE ======================== -->
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
			<button class="btn btn-success" type="submit">Ajouter Catégorie</button>
		</div>
		<!-- ============================================================== -->
	</form>
</div>
<!-- ======================== ========================  =========================== -->
<!-- ======================== DEBUT DU FORMULAIRE D'IMPORT DE FAVORIS =========================== -->
<div id="myModalImporter" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelCategorie" aria-hidden="true">
	<form enctype="multipart/form-data" action="script/edit.php" method="post">
		<input type="hidden" name="Form" value="ImportFav" />
		<input type="hidden" name="Page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
		<!-- ================== ENTETE DU FORMULAIRE ====================== -->
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabelCategorie">Importer des favoris</h3>
		</div>
		<!-- ============================================================== -->
		
		<!-- ================== CORPS DU FORMULAIRE ====================== -->
		<div class="modal-body">
				<!-- Fenetre de recherche de fichier -->
				<label for="bookmark">Veuillez selectionner votre fichier d'importation :</label><br />
				<input type="file" name="bookmark" title="Search for a file to add" required/><br /><br />
		</div>
		<!-- ============================================================== -->
		
		<!-- =================== BAS DU FORMULAIRE ======================== -->
		<div class="modal-footer">
			<!-- Bouton  d'annulation du formulaire -->
			<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
			<!-- Bouton  de soumission de formulaire -->
			<button class="btn btn-success" type="submit">Valider</button>
		</div>
		<!-- ============================================================== -->
	</form>
</div>
<!-- ======================== ========================  =========================== -->
<div id="myModalModifFav" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelModifFav" aria-hidden="true">	
	<!-- ================== ENTETE DU FORMULAIRE ====================== -->
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabelModifFav">Modifier votre favoris</h3>
	</div>
	<!-- ============================================================== -->
	<!-- ================== CORPS DU FORMULAIRE ====================== -->
	<form class="form" name="ModifFavoris" action="script/edit.php" method="POST">
		<input type="hidden" name="Form" value="ModifFav" />
		<input type="hidden" name="Page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
		<div class="modal-body">
			<!-- Champs de saisie de l'url : -->
			<br />
			<label for="URL">URL de votre favoris <i>(Obligatoire)</i> :</label>							
			<span Name="URL" class="contentPanel">
				<p id="UrlModif"></p>
				<input type="hidden" name="UrlFav" value="" />
			</span>
			<br />
			<!-- Champs de saisie du nom du favoris -->
			<label for="TextName">Nom de votre favoris :</label>
			<input name="NomFav" id="TextName" type="text" class="span5" placeholder="Nom de votre site">
			
			<!-- Bouton radio Public / Perso -->
			<label for="RadioButtons">Partager dans la bibliothèque publique :</label>
			<div Name="RadioButtons">
					<table>
						<tr><td><input type="radio" name="StatutFav" id="RdPublic" value="Public" checked /></td><td>Oui</td><td><input type="radio" name="StatutFav" id="RdPrive" value="Private" /></td><td>Non</td></tr>
					</table>
			</div>
			<br />
			<br />			
		</div>
		<!-- =================== BAS DU FORMULAIRE ======================== -->
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
			<input type="submit" value="Ajouter Favoris" class="btn btn-success" />
		</div>
		<!-- ============================================================== -->
	</form>
</div>