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
	