<!-- ======================== FORMULAIRE D'AJOUT DE FAVORIS ============================== -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">	
	<!-- ================== ENTETE DU FORMULAIRE ====================== -->
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Ajouter votre favoris</h3>
	</div>
	<!-- ============================================================== -->
	<!-- ================== CORPS DU FORMULAIRE ====================== -->
	<form class="form" name="AddFavoris" onsubmit="return validAddFavoris();" action="script/edit.php" method="POST">
		<div class="modal-body">
			<!-- Champs de saisie de l'url : -->
			<br />
			<label for="URL">URL de votre favoris <i>(Obligatoire)</i> :</label>							
			<span Name="URL" class="contentPanel">
				<input id="searchURL" name="UrlFav" type="text" class="span5" placeholder="URL de votre site" data-provide="typeahead" data-items="4" autocomplete="off" required />
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
			<p> Paramètres supplémentaires : </p>
			<div class="tabbable tabs-below"> <!-- Only required for left/right tabs -->
				<div class="tab-content">
					<div class="tab-pane" id="tab1">
							<!-- Combobox de choix pour la catégorie -->
							<div class="input-prepend">
								<label for="Categorie">Ajouter dans :</label>
								<select Name="Categorie" class="span5">
									<option value="Default" >Hors catégorie</option>
<?php
recurse_tree_select($_SESSION['ID_Root'],$Data,"",$_SESSION['ID_Root']);
?>
								</select>
								<input type="hidden" name="Form" value="AddFav" />
								<input type="hidden" name="Page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
							</div>											
							<!-- Notation -->
							<label for="Note">Notation :</label>
							<ul Name="Note" class="inline notes-echelle">
								<li>
									<label for="note01" class="test">1</label>
									<input type="radio" name="NoteFav" id="note01" value="1" />
								</li>
								<li>
									<label for="note02" class="test">2</label>
									<input type="radio" name="NoteFav" id="note02" value="2" />
								</li>
								<li>
									<label for="note03" class="test">3</label>
									<input type="radio" name="NoteFav" id="note03" value="3" />
								</li>
								<li>
									<label for="note04" class="test">4</label>
									<input type="radio" name="NoteFav" id="note04" value="4" />
								</li>
								<li>
									<label for="note05" class="test">5</label>
									<input type="radio" name="NoteFav" id="note05" value="5" />
								</li>
							</ul>
							<br />
							<br />											
							<!-- Champs de saisie de la description du favoris -->
							<label for="BlockDescr">Description :</label>
							<textarea name="DescriptionFav" id="BlockDescr" rows="2" class="span5" placeholder="Description"></textarea>
					</div>
					<div class="tab-pane active" id="tab2">
					</div>
					<br />
					<ul class="nav nav-tabs span5">										
						<li><a href="#tab1" data-toggle="tab">Montrer</a></li>
						<li class="active"><a href="#tab2" data-toggle="tab">Cacher</a></li>
					</ul>
				</div>
			</div>	
		</div>
		<!-- =================== BAS DU FORMULAIRE ======================== -->
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Fermer</button>
			<input type="submit" value="Ajouter Favoris" class="btn btn-success" />
		</div>
		<!-- ============================================================== -->
	</form>
</div>