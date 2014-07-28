<div id="myModalSupression" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabelSupression" aria-hidden="true">
	<!-- ================== ENTETE DU FORMULAIRE ====================== -->
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabelSupression">Supression de votre compte</h3>
	</div>
	<!-- ============================================================== -->
	<!-- ================== CORPS DU FORMULAIRE ====================== -->
	<div class="modal-body">
		<form id="compte_info" name="envoi" method="get" action="script/suppression.php" enctype="multipart/form-data">
				<table class="table table-bordered table-striped table-condensed">
					<caption>
					<h4>Suppression du compte</h4>
					</caption>
					<thead>
					  <tr>
						<th>Confirmez-vous la suppression de votre compte?</th>
					  </tr>
					</thead>
					<tr>
					  <td class="legende"><div align="left">Toutes vos données personnelles seront supprimées</div></td>
					</tr>
					<tr>
					  <td class="legende"><div align="left">Supprimer le compte : </div></td>
					  <td><div align="left">
						<input type="submit" accesskey="s" value="Valider" name="submit" />
					  </div></td>
					</tr>
				</table>
			</form>
	</div>
</div>