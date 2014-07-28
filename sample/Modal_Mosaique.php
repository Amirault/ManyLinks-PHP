<!-- ============================================= -->
	<div id="myModalGetUrl" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelGetUrl" aria-hidden="true">
		<!-- ================== ENTETE DU FORMULAIRE ====================== -->
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabelGetUtrl">Ajouter votre lien</h3>
		</div>
		<!-- ============================================================== -->
		<!-- ================== CORPS DU FORMULAIRE ====================== -->
		<div class="modal-body">
				<!-- Champs de saisie du nom du favoris -->
				<label for="TextName">Saisisser votre URL :</label>
				<input name="URL" id="URLToGet" type="text" class="span5" placeholder="Liens de votre favoris">														
				<!-- =================== BAS DU FORMULAIRE ======================== -->
					<div class="modal-footer">
						<button class="btn btn-success" data-dismiss="modal" aria-hidden="true" onclick="GetUrl()">Ajouter</button>
					</div>
				<!-- ============================================================== -->
		</div>
		<!-- ============================================================== -->
	</div>
	<div id="myModalLinkToHome" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelLinkToHome" aria-hidden="true">
		<!-- ================== ENTETE DU FORMULAIRE ====================== -->
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabelLinkToHome">Epingler la mosaïque à l'accueil ?</h3>
		</div>
		<!-- ============================================================== -->													
		<!-- =================== BAS DU FORMULAIRE ======================== -->
			<div class="modal-footer">
				<button class="btn btn-success" data-dismiss="modal" aria-hidden="true" onclick="LinkToHome()">Valider</button>
			</div>
		<!-- ============================================================== -->
		<!-- ============================================================== -->
	</div>
	<div id="myModalDeleteMosaique" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelDeleteMosaique" aria-hidden="true">
		<!-- ================== ENTETE DU FORMULAIRE ====================== -->
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabelDeleteMosaique">Supprimer la mosaïque ?</h3>
		</div>
		<!-- ============================================================== -->											
		<!-- =================== BAS DU FORMULAIRE ======================== -->
			<div class="modal-footer">
				<button class="btn btn-success" data-dismiss="modal" aria-hidden="true" onclick="DeleteMosaique()">Valider</button>
			</div>
		<!-- ============================================================== -->
	</div>
	<div id="myModalSaveMosaique" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelSaveMosaique" aria-hidden="true">
<?php
if (!isset($_GET['disp'])||(!is_numeric($_GET['disp'])))
{
?>
		<!-- ================== ENTETE DU FORMULAIRE ====================== -->
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabelSaveMosaique">Enregistrement de votre Mosaique</h3>
		</div>
		<!-- ============================================================== -->
				<!-- Champs de saisie du nom du favoris -->
		<!-- ================== CORPS DU FORMULAIRE ====================== -->
		<div class="modal-body">
			<label for="TextName">Saisisser son Nom :</label>
			<input name="NomMosaique" id="NomMosaique" type="text" class="span5" placeholder="Nom de votre mosaique" required>
			<label for="Categorie">Ajouter dans :</label>
			<select id="MosaiqueCategorie" Name="MosaiqueCategorie" class="span5">
				<option value="Default" >Hors catégorie</option>
<?php
recurse_tree_select($_SESSION['ID_Root'],$Data,"",$_SESSION['ID_Root']);
?>
			</select>
		</div>
		<!-- =================== BAS DU FORMULAIRE ======================== -->
		<div class="modal-footer">
			<button  onclick="return saveMosaique()" type="submit" class="btn btn-success" >Enregistrer</button>
		</div>
		<!-- ============================================================== -->
<?php
}
else
{
?>
			<!-- ================== ENTETE DU FORMULAIRE ====================== -->
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabelSaveMosaique">Enregistrer les modifications  ?</h3>
			</div>
			<!-- ============================================================== -->
			<input type="hidden" id="MosaiqueCategorie" name="MosaiqueCategorie" value="<?php echo $_SESSION['ID_Root'] ?>" />
			<div class="modal-footer">
				<button  onclick="saveMosaique()" class="btn btn-success" data-dismiss="modal" aria-hidden="true">Valider</button>
			</div>
<?php
}
?>
		<!-- ============================================================== -->
	</div>