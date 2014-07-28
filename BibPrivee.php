<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
<?php 
if (isset($_GET['disp']) && (!empty($_GET['disp'])))
{
	$disp = mysql_real_escape_string(htmlspecialchars($_GET['disp']));
}
else
{
	$disp = $_SESSION['ID_Root'];
}
if (isset($_GET['IndexPage']) && (is_numeric($_GET['IndexPage'])))
{
	$IndexPage = mysql_real_escape_string(htmlspecialchars($_GET['IndexPage']));
}
else
{
	$IndexPage = 1;
}
?>
		<div class="EntetePrincipal"><h1>Ma Bibliothèque</h1></div>
		
		<div class="row-fluid margin">
			<!-- Mise en place d'un tableau pour gerer au mieux le RESPONSIVE -->
			<div class="span3 transparent">
				<div class="row-fluid">
					<div class="btn-group">
						<button class="btn btn-success dropdown-toggle" data-toggle="dropdown"><i class="icon-white icon-plus"></i> Ajouter <span class="caret"></span></button>
						<ul class="dropdown-menu">
						  <li><a data-toggle="modal" href="#myModal"><i class="icon-black icon-plus"></i> Favoris</a></li>
						  <li><a data-toggle="modal" href="#myModalCategorie" ><i class="icon-black icon-plus"></i> Catégorie</a></li>
						  <li><a href="Mosaique.php?New" ><i class="icon-black icon-plus"></i> Mosaïque</a></li>
						  <li class="divider"></li>
						  <li><a data-toggle="modal" href="#myModalImporter" ><i class="icon-circle-arrow-down"></i><i>Importer</i></a></li>
						</ul>
					</div>
				</div>
				<div class="row-fluid">
					<?php include('sample/Modal_BibPriveeAjout.php'); ?>
					<div id="ListMenu" class="BlockStyler">
						<!-- ======== BIBLIOTHEQUE PRIVE ================= -->
						<!-- ============================================= -->	
						<ul class="nav nav-list">
							<li>
								<a href="<?php echo "BibPrivee.php?disp=".$_SESSION['ID_Root']; ?>">Mes Catégories :</a>
							</li>
							<div>
								<?php
									recurse_tree($ID_Root,$Data,$ID_Root,$disp);
								?>
							</div>
							<li class="divider"></li>
							<li <?php if ($disp == 'FavNotAssoc'){ echo "class=\"active\"";}?>><a href="<?php echo "BibPrivee.php?disp=FavNotAssoc"; ?>">Favoris non Classée</a></li>
							<li <?php if ($disp == 'AllFav'){ echo "class=\"active\"";}?>><a href="<?php echo "BibPrivee.php?disp=AllFav"; ?>">Mes favoris</a></li>
							<li <?php if ($disp == 'AllMosaique'){ echo "class=\"active\"";}?>><a href="<?php echo "BibPrivee.php?disp=AllMosaique"; ?>">Mes mosaïques</a></li>
							<li class="divider"></li>
						</ul>
					</div>
				</div>
			</div>
			<div class="span9 BlockStyler padding">
				<form action="script/edit.php" method="POST">
					<input type="hidden" name="Page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
					<input type="hidden" name="AffCat" value="<?php echo $disp; ?>" />
					<div id="ContenerTable">
						<div id="HeaderOptions">
							<?php
								if (is_numeric($disp))
								{
									aff_CatHistory($disp,$Data);
								}
								else if ($disp == 'AllFav')
								{
							?>
							<div>
								<h3>Mes favoris</h3>
							</div>
							<?php
								}
								else if ($disp == 'AllMosaique')
								{
							?>
							<div>
								<h3>Mes mosaïques</h3>
							</div>	
							<?php
								}
								else if ($disp == 'FavNotAssoc')
								{
							?>
							<div>
								<h3>Mes favoris non classés</h3>
							</div>
							<?php
								}
							?>
						</div>
						<table class="table">
							<!-- Header contenant les différentes options possibles : -->
							<tr class="alert alert-success">	
								<!-- Checkbox : (tout selectionner) -->
								<th>
									<input type="checkbox" name="AllCheck" onclick="checkAll()"/>
								</th>
								
								<!-- Boutons d'edition et de suppression -->
								<th colspan="2">
									<button class="btn btn-inverse"  onclick="return verifCheckEdit()" ><i class="icon-edit icon-white"></i> Editer</button>
									<button class="btn btn-inverse " onclick="return verifCheckDelete()"><i class="icon-remove icon-white"></i> Supprimer</button>
								</th>
								
								<th></th>
								<!-- Combobox choix : -->
								<th id="EnteteAssociation" >
									<div class="pull-right">
										<select name="AssocCategorie" class="input-medium">
											<option value="Default">Associer à</option>
											<?php recurse_tree_select($_SESSION['ID_Root'],$Data,"",$_SESSION['ID_Root']); ?>
										</select>
										<button class="btn btn-inverse" onclick="return verifCheckAssoc()" >Ok</button>
									</div>
								</th>
							</tr>
							
							<!-- Affichage du contenu : -->
							<?php
							if (($disp != 'AllMosaique') && ($disp != 'AllFav') && ($disp != 'FavNotAssoc'))
							{
								aff_cat_bibprivee($Data,$disp);
							}
							if (($disp != 'AllMosaique')&&($disp != $_SESSION['ID_Root']))
							{
								aff_fav_bibprivee($disp,$IndexPage);
							}
							if (($disp != 'AllFav')&&($disp != 'FavNotAssoc')&&($disp!= $_SESSION['ID_Root'] ))
							{
								aff_mosaique_bibprivee($disp,$IndexPage);
							}
							?>
						</table>
					</div>
					<?php include('sample/Modal_BibPrivee.php'); ?>
				</form>
			</div>
		</div>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>