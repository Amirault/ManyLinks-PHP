<?php
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
	if (isset($_GET['IndexPage']))
	{
		$IndexPage = mysql_real_escape_string(htmlspecialchars($_GET['IndexPage']));
	}
	else
	{
		$IndexPage = 1;
	}
	if (isset($_GET['search']))
	{
		$disp = mysql_real_escape_string(htmlspecialchars($_GET['search']));
	}
	else
	{
		$disp = "";
	}
	$DebutAff = ($IndexPage-1)*10;
?>
		<div class="EntetePrincipal"><h1>Bibliothèque Publique</h1></div>
		<div class="row-fluid margin">
			<!-- tableau de gauche -->
			<div class="span3 BlockStyler padding">
				<table class="table table-bordered table-striped table-condensed">
					<caption>
						<h4>Top 10 sites les plus ajoutés</h4>
					</caption>
					<thead>
					  <tr>
						<th>N°</th>
						<th>URL</th>
						<th>Ajouts</th>
					  </tr>
					</thead>
					<tbody>
					 <?php
						aff_topAjoute();
					 ?>
					</tbody>
			  </table>
			</div>
			  <!-- tableau du milieu -->
			<div class="span6 BlockStyler padding">
				<h4><form id="searchbox" action="#" method="get">
					<input id="search" name="search" type="text" placeholder="Écrire ici" data-provide="typeahead" data-items="4" autocomplete="off" onkeyup="liveSearch(this.value)"
					<?php if(isset($_GET["search"]))
					{
						$search = htmlspecialchars(mysql_real_escape_string($_GET["search"]));
						echo " value=\"$search\"";
					}
					?>/>
					<input id="submit" type="submit" value="Rechercher">
				</form></h4>
				<div id="livesearch">
					<?php
						$nb=aff_search($DebutAff);
						aff_dernierAjout();
					?>
					<div class="pagination pagination-centered">
						<ul>
							<?php
									if(isset($_GET["search"]))
									{
										for ($i=1; $i <= round($nb/10);$i++)
										{
							?>
													<li><a href="BibPublique.php?search=<?php echo "$disp&IndexPage=$i"; ?>"><?php echo $i; ?></a></li>
							<?php
										}
									}
							?>
						</ul>
					</div>				
				</div>
			</div>
			<!-- tableau de droite -->
			<div class="span3 BlockStyler padding">
				<table class="table table-bordered table-striped table-condensed">
				<caption><h4>Top 10 sites les plus visités</h4></caption>
				<thead>
				  <tr>
					<th>N°</th>
					<th>URL</th>
					<th>Visites</th>
				  </tr>
				</thead>
				<tbody>
				<?php
					aff_topVisite();
				?>
				</tbody>
				</table>
			</div>
		</div>
		<script>
			function AddPublicLink(URL)
			{
				var ItemLi = document.forms['AddFavoris']['UrlFav'].value = URL;
				$('#myModal').modal('toggle');
			}
		</script>
	
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>