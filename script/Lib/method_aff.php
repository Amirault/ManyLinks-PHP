<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once("isBlank.php");
	chdir("../");
	require_once ('config.php');
	chdir($wd_was);
?> 
<?php
// Fonction d'affichage
function aff_CatHistory($toDisp,$array) 
{
	$_SESSION['ID_Root'] = checkRootNode($_SESSION['email']);
	foreach ($array as $noeud) 
	{
		if ($toDisp == $noeud['ID_Noeud'])
		{
?>
			<div class="btn-group" data-toggle="buttons-checkbox">
<?php
			recurse_tree_CatHistory($noeud['ID_Noeud'], $array,$_SESSION['ID_Root']);
?>
			</div>
<?php
		}
	}
}
function recurse_tree_CatHistory($parent, $array,$ID_Source) 
{

	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
		echo recurse_tree_CatHistory($array[$parent]['ID_Noeud_Parent'], $array,$ID_Source);   
?>
		<a type="button" class="btn btn-primary" href="<?php echo "BibPrivee.php?disp=".$array[$parent]['ID_Noeud'];?>"><i class="icon-chevron-right icon-white"></i></a>
		<a type="button" class="btn btn-primary" href="<?php echo "BibPrivee.php?disp=".$array[$parent]['ID_Noeud'];?>"><?php echo $array[$parent]['Nom']; ?></a>
<?php 
	}
	else
	{
?>
		<a type="button" class="btn btn-primary"  href="<?php echo "BibPrivee.php?disp=".$array[$parent]['ID_Noeud'];?>"><i class="icon-home icon-white"></i></a>
<?php
	}
   return;
}
function recurse_tree($parent, $array,$ID_Source,$disp) 
{
	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
?>
					<li><i data-toggle="collapse" data-target="#UL<?php echo $array[$parent]['ID_Noeud']; ?>" class="icon-folder-close icon-white"></i><a href="<?php echo "BibPrivee.php?disp=".$array[$parent]['ID_Noeud']; ?>"><?php echo $array[$parent]['Nom']; ?></a></li>
					<ul class="collapse <?php if($disp < $parent)echo "in";?> id="UL<?php echo $array[$parent]['ID_Noeud'];?>" >
<?php
	}
	foreach ($array as $noeud) 
	{
	  if (($parent == $noeud['ID_Noeud_Parent'])&&($noeud['ID_Noeud'] != $ID_Source)) 
	  {
			echo recurse_tree($noeud['ID_Noeud'], $array,$ID_Source,$disp);   
	  }
	}
	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
?>
					</ul>
<?php
	}
   return;
}
function recurse_tree_DisplayCat($parent, $array,$sSub,$ID_Source) 
{
	$img = "<i class=\"icon-folder-close\"></i>";
	
	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
?>
		<tr>
			<td><input type="checkbox" name="SelectCat[]" value="<?php echo $array[$parent]['ID_Noeud']; ?>" /></td>
			<td><?php echo $img; ?></td>
			<td class="CelluleCenter">
				<a href="<?php echo "BibPrivee?disp=".$array[$parent]['ID_Noeud'];?>">
					<?php echo $sSub.$array[$parent]['Nom']; ?>
				</a>
			</td>
		</tr>
<?php
	}
	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
		$sSub = $sSub."- > ";
		
	}
	foreach ($array as $noeud) 
	{
		if (($parent == $noeud['ID_Noeud_Parent'])&&($noeud['ID_Noeud'] != $ID_Source)) 
		{			
			echo recurse_tree_DisplayCat($noeud['ID_Noeud'], $array,$sSub,$ID_Source);   	
		}
	}
   return;
}
function strmaxwordlen($input,$len) 
{
 $l = 0;
 $output = "";
 for ($i = 0; $i < $len; $i++) {
  $char = substr($input,$i,1);
  $output .= $char;
 }
 return($output."<strong>...</strong>");
}
function aff_fav_bibprivee($disp,$IndexPage)
{
?>
			<tr>
				<td class="pull-left" style="float: none;" colspan="5">
					<div class="alert alert-info" style="margin-bottom:-0.15%;"><strong><i class="icon-star"></i> Favoris :</strong></div>
				</td>
			</tr>
<?php

	$DebutAff = ($IndexPage-1)*10;
	$requete = "SELECT * from categorie_favoris LEFT JOIN favoris_publie on categorie_favoris.URL_Favoris=favoris_publie.URL_Favoris AND categorie_favoris.Email_Membre = favoris_publie.Email_Membre LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE categorie_favoris.Email_Membre='".$_SESSION['email']."' AND ID_Categorie=".$_SESSION['ID_Root']." LIMIT $DebutAff,10";
	$requeteCount = "SELECT Count(*) as Nb from categorie_favoris LEFT JOIN favoris_publie on categorie_favoris.URL_Favoris=favoris_publie.URL_Favoris AND categorie_favoris.Email_Membre = favoris_publie.Email_Membre LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE categorie_favoris.Email_Membre='".$_SESSION['email']."' AND ID_Categorie=".$_SESSION['ID_Root']."";

	if (is_numeric($disp))
	{
		$requete = "SELECT * from categorie_favoris LEFT JOIN favoris_publie on categorie_favoris.URL_Favoris=favoris_publie.URL_Favoris AND categorie_favoris.Email_Membre = favoris_publie.Email_Membre LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE categorie_favoris.Email_Membre='".$_SESSION['email']."' AND ID_Categorie=".$disp." LIMIT $DebutAff,10";
		$requeteCount = "SELECT Count(*) as Nb from categorie_favoris LEFT JOIN favoris_publie on categorie_favoris.URL_Favoris=favoris_publie.URL_Favoris AND categorie_favoris.Email_Membre = favoris_publie.Email_Membre LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE categorie_favoris.Email_Membre='".$_SESSION['email']."' AND ID_Categorie=".$disp." ";
	}
	else if ($disp == 'AllFav')
	{
		$requete = "SELECT * from favoris_publie LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE Email_Membre='".$_SESSION['email']."' LIMIT $DebutAff,10";
		$requeteCount = "SELECT Count(*) as Nb from favoris_publie LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL WHERE Email_Membre='".$_SESSION['email']."'";
	}
	else if ($disp == 'FavNotAssoc')
	{
		$requete = "SELECT * FROM favoris_publie LEFT JOIN favoris on favoris_publie.URL_Favoris=favoris.URL where favoris_publie.URL_Favoris NOT IN (SELECT URL_Favoris FROM categorie_favoris where categorie_favoris.Email_Membre = '".$_SESSION['email']."')  AND favoris_publie.Email_Membre ='".$_SESSION['email']."' LIMIT $DebutAff,10";
		$requeteCount = "SELECT Count(*) as Nb FROM favoris_publie where URL_Favoris NOT IN (SELECT URL_Favoris FROM categorie_favoris where categorie_favoris.Email_Membre = '".$_SESSION['email']."')  AND favoris_publie.Email_Membre ='".$_SESSION['email']."'";
	}
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$xIsData = false;
	while ($enreg = mysql_fetch_assoc($resultat)) 
	{
		if (!$xIsData)
		{
			$xIsData = true;
		}
		if ($enreg['Note'] == null)
		{
			$enreg['Note'] = 'non noté';
		}
		$img = "<img style=\"max-width: 24px; max-height: 24px; min-width: 24px; min-height: 24px;\" src=\"".$enreg['Icone']."\" />"; // a voir pour le style dans le html...
?>
						<tr>
							<td class="CelluleCenter"><input type="checkbox" name="SelectFav[]" value="<?php echo $enreg['URL_Favoris'];?>"/></td>
							<td style="max-width:10%;"><?php echo $img; ?></td>
							<td class="CelluleCenter">
								<a id="Edit<?php echo "".$enreg['URL_Favoris'].""; ?>" onclick="UrlClick(<?php echo "'".$enreg['URL_Favoris']."'"; ?>)" title="<?php echo $enreg['URL_Favoris'];?>">
									<?php echo $enreg['Nom']; ?>
								</a>
							</td>
							<td class="CelluleCenter"><?php echo  $enreg['Note']; ?></td>
							<td class="CelluleCenter">
								<div class="pull-right" style="margin-right:2%;">
									<?php echo $enreg['TimeStamp']; ?>
								</div>	
							</td>
						</tr>
<?php
	}
	if (!$xIsData)
	{
?>
						<tr>
							<td class="pull-left" style="float: none;" colspan="5">
								<div class="alert alert-error" style="margin-top:2%;"><strong>Pas de favoris renseigné !</strong></div>
							</td>
						</tr>
<?php
	}
	else
	{
		$resultat = mysql_query($requeteCount, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
		$resultat = mysql_fetch_array($resultat);
		if ($resultat['Nb'] > 10)
		{
?>
					<tr>
						<td class="pull-left" style="float: none;" colspan="5">
							<div class="pagination pagination-centered">
								<ul>
<?php
			for ($i=1; $i <= round($resultat['Nb']/10);$i++)
			{
	?>
									<li><a href="BibPrivee.php?disp=<?php echo "$disp&IndexPage=$i"; ?>"><?php echo $i; ?></a></li>
	<?php
			}
?>
								</ul>
							</div>
						</td>
					</tr>
<?php
		}
	}
}
function aff_cat_bibprivee($Data,$CategorieToDisplay)
{
?>
	<tr>
		<td class="pull-left" style="float: none;" colspan="5">
			<div class="alert alert-info" style="margin-bottom:-0.15%;"><strong><i class="icon-folder-open"></i> Catégories :</strong></div>
		</td>
	</tr>
<?php
	if (!is_numeric($CategorieToDisplay))
	{
		$CategorieToDisplay = $_SESSION['ID_Root'];
	}
	$xIsData = false;
	foreach ($Data as $noeud) 
	{
		if (($CategorieToDisplay== $noeud['ID_Noeud_Parent'])&&($noeud['ID_Noeud'] != $_SESSION['ID_Root'])) 
		{
			if (!$xIsData)
			{
				$xIsData = true;
			}
?>
			<tr>
				<td class="CelluleCenter">
					<input type="checkbox" name="SelectCat[]" value="<?php echo $noeud['ID_Noeud']; ?>" />
				</td>
				<td>
					<?php echo "<i class=\"icon-folder-close\"></i>" ?>
				</td>
				<td>
					<a href="<?php echo "BibPrivee.php?disp=".$noeud['ID_Noeud'];?>"> <?php echo  $noeud['Nom']; ?> </a>
				</td>
			</tr>
<?php
		}
	}
	if (!$xIsData)
	{
?>
						<tr>
							<td class="pull-left" style="float: none;" colspan="5">
								<div class="alert alert-error" style="margin-top:2%;"><strong>Pas de catégorie renseigné !</strong></div>
							</td>
						</tr>
<?php
	}
}
function aff_mosaique_bibprivee($disp,$IndexPage)
{
?>
			<tr>
				<td class="pull-left" style="float: none;" colspan="5">
					<div class="alert alert-info" style="margin-bottom:-0.15%;"><strong><i class="icon-th-large"></i> Mosaïque :</strong></div>
				</td>
			</tr>
<?php
	$DebutAff = ($IndexPage-1)*10;
	$requete = "SELECT * from categorie_mosaique LEFT JOIN mosaique_publie on categorie_mosaique.ID_Mosaique=mosaique_publie.ID_Mosaique AND categorie_mosaique.Email_Membre = mosaique_publie.Email_Membre WHERE categorie_mosaique.Email_Membre='".$_SESSION['email']."' AND categorie_mosaique.ID_Categorie=".$_SESSION['ID_Root']." LIMIT $DebutAff,10";
	$requeteCount = "SELECT Count(*) as Nb from categorie_mosaique LEFT JOIN mosaique_publie on categorie_mosaique.ID_Mosaique=mosaique_publie.ID_Mosaique AND categorie_mosaique.Email_Membre = mosaique_publie.Email_Membre WHERE categorie_mosaique.Email_Membre='".$_SESSION['email']."' AND categorie_mosaique.ID_Categorie=".$_SESSION['ID_Root']."";

	if (is_numeric($disp))
	{
		$requete = "SELECT * from categorie_mosaique LEFT JOIN mosaique_publie on categorie_mosaique.ID_Mosaique=mosaique_publie.ID_Mosaique AND categorie_mosaique.Email_Membre = mosaique_publie.Email_Membre WHERE categorie_mosaique.Email_Membre='".$_SESSION['email']."' AND categorie_mosaique.ID_Categorie=".$disp." LIMIT $DebutAff,10";
		$requeteCount = "SELECT Count(*) as Nb from categorie_mosaique LEFT JOIN mosaique_publie on categorie_mosaique.ID_Mosaique=categorie_mosaique.ID_Mosaique AND categorie_mosaique.Email_Membre = mosaique_publie.Email_Membre WHERE categorie_mosaique.Email_Membre='".$_SESSION['email']."' AND categorie_mosaique.ID_Categorie=".$disp."";
	}
	else if ($disp == 'AllMosaique')
	{
		$requete = "SELECT * from mosaique_publie WHERE Email_Membre='".$_SESSION['email']."' LIMIT $DebutAff,10";
		$requeteCount = "SELECT Count(*) as Nb from favoris_publie from mosaique_publie WHERE Email_Membre='".$_SESSION['email']."'";
	}
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$xIsData = false;
	while ($enreg = mysql_fetch_assoc($resultat)) 
	{
		if ($enreg['Nom'] != 'DefaultMosaique'.$_SESSION['email'])
		{
			if (!$xIsData)
			{
				$xIsData = true;
			}
			if ($enreg['Note'] == null)
			{
				$enreg['Note'] = 'non noté';
			}
			$img = "<i class=\"icon-th\"></i>"; // a voir pour le style dans le html...
?>
						<tr>
							<td class="CelluleCenter"><input type="checkbox" name="SelectMosaique[]" value="<?php echo $enreg['ID_Mosaique'];?>"/></td>
							<td style="max-width:10%;"><?php echo $img; ?></td>
							<td class="CelluleCenter">
								<a href="Mosaique.php?disp=<?php echo $enreg['ID_Mosaique']; ?>" title="<?php echo $enreg['Nom'];?>">
									<?php echo $enreg['Nom']; ?>
								</a>
							</td>
							<td class="CelluleCenter"><?php echo  $enreg['Note']; ?></td>
							<td class="CelluleCenter">
								<div class="pull-right" style="margin-right:2%;">
									<?php echo $enreg['TimeStamp']; ?>
								</div>	
							</td>
						</tr>
<?php
		}
	}
	if (!$xIsData)
	{
?>
						<tr>
							<td class="pull-left" style="float: none;" colspan="5">
								<div class="alert alert-error" style="margin-top:2%;"><strong>Pas de Mosaique renseigné !</strong></div>
							</td>
						</tr>
<?php
	}
}
function aff_topVisite()
{
	$requete = "SELECT * FROM favoris WHERE Statut = 'public' ORDER BY Compteur_clic DESC LIMIT 10";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
	$i=0;
	if ($resultat)
	{
		while ($enreg = mysql_fetch_assoc($resultat)) 
		{
			$i++;
			$url = $enreg['URL'];
			$nom = substr($enreg['Title'],0,30);
			if (strlen($url)> 30)
			{
				$nom=$nom."...";
			}
			//$miniature = $enreg['Miniature'];
			//$logo = $enreg['Logo'];
			$cpt_clic = $enreg['Compteur_clic'];
			$icone = $enreg['Icone'];
			if (!isFavPublie($enreg['URL'],$_SESSION['email']))
			{
				$AddFav = "<i class=\"icon-plus pull-right\" onclick=\"AddPublicLink('$url')\"></i>";
			}
			else
			{
				$AddFav = "";
			}
			echo "<tr class=\"legende\">";
			echo "<td><span class=\"label label-info\">$i</span></td>";
			echo "<td><img src=\"$icone\" width=16 height=16/></td>";
			echo "<td style=\"border-left: none;\"><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">$nom</a>$AddFav</td>";
			echo "<td><span class=\"badge badge-success\">$cpt_clic</span></td>";
			echo "</tr>";
		}
	}
	else
	{
		echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
	}
}
function aff_topAjoute()
{
	$requete = "SELECT * FROM favoris WHERE Statut = 'public' ORDER BY Compteur_Ajout DESC LIMIT 10";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
	$i=0;
	if ($resultat)
	{
		while ($enreg = mysql_fetch_assoc($resultat)) 
		{
			$i++;
			$url = $enreg['URL'];
			$nom = substr($enreg['Title'],0,30);
			if (strlen($url)> 30)
			{
				$nom=$nom."...";
			}
			$cpt_ajout = $enreg['Compteur_Ajout'];
			$icone = $enreg['Icone'];
			if (!isFavPublie($enreg['URL'],$_SESSION['email']))
			{
				$AddFav = "<i class=\"icon-plus pull-right\" onclick=\"AddPublicLink('$url')\"></i>";
			}
			else
			{
				$AddFav = "";
			}
			echo "<tr>";
			echo "<td><span class=\"label label-info\">$i</span></td>";
			echo "<td><img src=\"$icone\" width=16 height=16 alt=\"$url\"/><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">$nom</a>$AddFav</td>";
			echo "<td><span class=\"badge badge-success\">$cpt_ajout</span></td>";
			echo "</tr>\n";
		}
	}
	else
	{
		echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
	}
}
function aff_dernierAjout()
{
	echo"<h4>Derniers sites ajoutés</h4>\n
		<table style=\"table-layout:fixed\" class=\"table table-bordered table-striped table-condensed\">
		<thead>
			<tr>
				<th>Date</th>
				<th>URL</th>
				<th>Par</th>
			</tr>
		</thead>
		<tbody> ";
	if(!isset($_GET["search"]) || empty($_GET["search"]))
	{
		$requete = "SELECT DISTINCT *, DATE_FORMAT(fp.Timestamp,'%T') as t, DATE_FORMAT(fp.Timestamp,'%d/%m/%Y') as d FROM favoris_publie fp, favoris f, membres m WHERE URL = URL_Favoris AND Email_Membre = Email AND Statut = 'public' ORDER BY fp.Timestamp DESC LIMIT 10";				
		$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
		$i=0;
		if ($resultat)
		{
			while ($enreg = mysql_fetch_assoc($resultat)) 
			{
				$i++;
				$url = $enreg['URL'];
				$nom = substr($enreg['Title'],0,20);
				if (strlen($url)> 20)
				{
					$nom=$nom."...";
				}
				$time = $enreg['t'];
				$date = $enreg['d'];
				$icone = $enreg['Icone'];
				$pseudo = $enreg['Pseudo'];
				
				$AddFav = "";
				if (isset ($_SESSION['email']))
				{
					if (!isFavPublie($enreg['URL'],$_SESSION['email']))
					{
						$AddFav = "<i class=\"icon-plus pull-right\" onclick=\"AddPublicLink('$url')\"></i>";
					}
				}
				
				echo "<tr>\n";
				echo "<td>$time<br/>$date</td>";
				echo "<td><img src=\"$icone\" width=16 height=16 alt=\"$url\"/><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">".$nom."</a>$AddFav</td>";
				echo "<td><div style=\"overflow:auto;\"><span class=\"badge badge-success\">$pseudo</span></div></td>";
				echo "\n</tr>\n";
			}
		}
		else
		{
			echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
		}
	}
	echo "	</tbody>
	</table>";
}
function aff_Membres()
{
	echo"<table style=\"table-layout:fixed\" class=\"table table-bordered table-striped table-condensed\">
		<caption>
			<h4>Bienvenue aux nouveaux membres</h4>
		</caption>
		<thead>
			<tr>
				<th>Pseudo</th>
				<th>Inscrit le</th>
			</tr>
		</thead>
		<tbody> ";
	$requete = "SELECT Pseudo, DATE_FORMAT(Timestamp,'%T') as t, DATE_FORMAT(Timestamp,'%d/%m/%Y') as d FROM membres WHERE Actif=1 ORDER BY Timestamp DESC LIMIT 10";
				
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete. mysql_error($_SESSION['mysql']));
	$i=0;
	if ($resultat)
	{
		while ($enreg = mysql_fetch_assoc($resultat)) 
		{
			$pseudo = $enreg['Pseudo'];
			$time = $enreg['t'];
			$date = $enreg['d'];
			
			echo "<tr>";
			echo "<td><div style=\"overflow:auto;\"><div  class=\"badge badge-success\">$pseudo</div><div></td>";
			echo "<td><div>$time<br/>$date</div></td>";
			echo "</tr>";
		}
	}
	else
	{
		echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
	}
	echo "	</tbody>
	</table>";
}
function aff_search($DebutAff)
{
	if(isset($_GET["search"]) && !empty($_GET["search"]))
		{
			$search = htmlspecialchars(mysql_real_escape_string($_GET["search"]));
			
			$requete = "SELECT URL,Icone,Title FROM favoris WHERE Statut = 'public' AND (URL LIKE '%$search%' OR Title LIKE '%$search%') LIMIT $DebutAff,10";
			$requeteCount = "SELECT Count(*) AS Nb FROM favoris WHERE Statut = 'public' AND (URL LIKE '%$search%' OR Title LIKE '%$search%')";
			$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête :".$requete.mysql_error($_SESSION['mysql']));
			$resultatCount = mysql_query($requeteCount, $_SESSION['mysql']) or die ("Erreur de requête :".$requeteCount.mysql_error($_SESSION['mysql']));
			$resultatCount = mysql_fetch_array($resultatCount);
			
			echo "<table class=\"table table-bordered table-striped table-condensed\">
					<thead>
					  <tr>";
					  
			if ($resultat && $resultatCount['Nb'] != 0)
			{
					echo "<th colspan=2>URL</th>
					  </tr>
					</thead>
					<tbody> ";
				while ($enreg = mysql_fetch_assoc($resultat)) 
				{
					$url = $enreg['URL'];
					$urlAffiche = substr($url,0,70);
					if (strlen($url)> 70)
					{
						$urlAffiche=$urlAffiche."...";
					}
					if (!isFavPublie($enreg['URL'],$_SESSION['email']))
					{
						$AddFav = "<i class=\"icon-plus pull-right\" onclick=\"AddPublicLink('$url')\"></i>";
					}
					else
					{
						$AddFav = "";
					}
					$icone = $enreg['Icone'];
					echo "<tr>";
					echo "<td><img src=\"$icone\" width=16 height=16/></td>";
					echo "<td style=\"border-left: none;\"><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">".$enreg['Title']."</a>$AddFav</td>";
					echo "</tr>";
				}
			}
			else if ($resultatCount['Nb'] == 0)
			{
				echo "<th>Pas de résultat.</th>
					  </tr>
					</thead>";
			}
			else
			{
				echo "Erreur : ".mysql_error($_SESSION['mysql'])."<br />";
			}
			echo " </tbody>
				  </table>";
			return $resultatCount['Nb'];
		}
}
function recurse_tree_select($parent, $array,$sSub,$ID_Source) 
{
	if ($array[$parent]['ID_Noeud'] != $ID_Source)
	{
?>
													<option value="<?php echo $array[$parent]['ID_Noeud']; ?>"><?php echo $sSub."/".$array[$parent]['Nom']; ?></option>
<?php
	}
	foreach ($array as $noeud) 
	{
	  if (($parent == $noeud['ID_Noeud_Parent'])&&($noeud['ID_Noeud'] != $ID_Source)) 
	  {
		if ($array[$parent]['ID_Noeud'] != $ID_Source)
		{
			$sSub = $sSub."/".$array[$parent]['Nom'];
		}
		echo recurse_tree_select($noeud['ID_Noeud'], $array,$sSub,$ID_Source);   
	  }
	}
   return;
}
function aff_mosaiqueModif($ID_Mosaique)
{
	$requete = "SELECT * from mosaique_favoris LEFT JOIN mosaique_publie on mosaique_favoris.ID_Mosaique=mosaique_publie.ID_Mosaique AND mosaique_favoris.Email_Membre=mosaique_publie.Email_membre WHERE mosaique_favoris.Email_Membre='".$_SESSION['email']."' AND mosaique_favoris.ID_Mosaique = $ID_Mosaique";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
?>
						<div class="margin-left">
							<h4>Vos options :</h4>
							<button class="btn btn-success" data-toggle="modal" href="#myModalSaveMosaique"><i class="icon-ok icon-white"></i> Enregistrer</button></li>
							<button class="btn btn-danger" data-toggle="modal" href="#myModalDeleteMosaique"><i class="icon-remove icon-white"></i> Supprimer</button></li>
							<a href="Mosaique/<?php echo md5($_SESSION['email'].'Mosaique'.$ID_Mosaique); ?>.html">
								<button id="ButtonLinkOut" class="btn btn-info"  >
									<i class="icon-ban-circle icon-white"></i> Consulter hors connexion
								</button>
							</a>
<?php
					$IndexEmplacement =0;
					$xstart = true;
					while ($enreg = mysql_fetch_assoc($resultat)) 
					{
						if ($xstart)
						{
							$xstart=false;
?>
									<input type="hidden" name="NomMosaique" id="NomMosaique" value="<?php echo $enreg['Nom']; ?>" />
									<input type="hidden" name="DefaultMosaique" id="DefaultMosaique" value="<?php echo $enreg['Default']; ?>" />
<?php
							if ($enreg['Default'] == 0)
							{
?>
									<button id="ButtonLinkToHome" class="btn btn-info" onclick="LinkToHome()"><i class="icon-ok-circle icon-white"></i> Ajouter à la galerie</button>
<?php
							}
							else
							{
?>
									<button id="ButtonUnlinkToHome" class="btn btn-info" onclick="UnlinkToHome()" ><i class="icon-ban-circle icon-white"></i> Enlever de la galerie</button>
<?php
							}
?>
						</div>
						
						<div class="row-fluid margin">
								<ul id="MenuMosaique" class="menu">
			<?php
						}
						$IndexEmplacement ++;
						if (($enreg['ID_Emplacement'] == $IndexEmplacement) && ($enreg['URL_Favoris'] != 'null') && (!empty($enreg['URL_Favoris'])))
						{
			?>
								<li id="URL_<?php echo $enreg['ID_Emplacement']; ?>" >
								  <i class="icon-remove" onclick="removeUrl('<?php echo $enreg['ID_Emplacement']; ?>')"></i><i class="icon-edit" onclick="SelectItem('<?php echo $enreg['ID_Emplacement']; ?>','<?php echo $enreg['URL_Favoris']; ?>')"></i><a id="Link_<?php echo $enreg['ID_Emplacement']; ?>" href="<?php echo $enreg['URL_Favoris']; ?>"><img src="http://www.robothumb.com/src/<?php echo $enreg['URL_Favoris']; ?>@320x240.jpg" /></a>
								</li>	
			<?php
						}
						else
						{
			?>
								<li id="URL_<?php echo $IndexEmplacement; ?>" >
								  <span onclick="SelectItem(<?php echo $IndexEmplacement; ?>)" class="title">Cliquer pour ajouter !</span> 
								</li>	
			<?php
						}
					}
					if ($IndexEmplacement != 9)
					{
						for ($i=$IndexEmplacement; $i <= 9; $i++)
						{
			?>
								<li id="URL_<?php echo $IndexEmplacement; ?>" >
								  <span onclick="SelectItem(<?php echo $IndexEmplacement; ?>)" class="title">Cliquer pour ajouter !</span> 
								</li>
			<?php
						}
					}
			?>
								</ul>
						</div>
					
<?php
}
function aff_mosaique_galerie()
{
	$requete = "SELECT * from mosaique_favoris LEFT JOIN mosaique_publie on mosaique_favoris.ID_Mosaique=mosaique_publie.ID_Mosaique AND mosaique_favoris.Email_Membre=mosaique_publie.Email_membre WHERE mosaique_favoris.Email_Membre='".$_SESSION['email']."' AND mosaique_publie.Default = 1 ORDER BY mosaique_publie.TimeStamp DESC";
	$resultat = mysql_query($requete, $_SESSION['mysql']) or die ("Erreur de requête  : \n".$requete);
	$IndexEmplacement =0;
	$xstart = true;
	while ($enreg = mysql_fetch_assoc($resultat)) 
	{
		if ($enreg['ID_Emplacement'] == 1)
		{
?>
		<div class="<?php if ($IndexEmplacement == 0){ echo 'active';} ?> item">
			<ul id="MenuMosaique" class="menu">
<?php

		}
		$IndexEmplacement ++;
		if (!empty($enreg['URL_Favoris']))
		{
?>
			<li id="URL_<?php echo $enreg['ID_Emplacement']; ?>" >
			  <a id="Link_<?php echo $enreg['ID_Emplacement']; ?>" href="<?php echo $enreg['URL_Favoris']; ?>"><img src="http://www.robothumb.com/src/<?php echo $enreg['URL_Favoris']; ?>@320x240.jpg" /></a>
			</li>	
<?php
		}
		else
		{
?>
			<li>
			</li>
<?php
		}
		if ($enreg['ID_Emplacement'] == 9)
		{
?>
			</ul>
		</div>
<?php
		
		}
	}
	if ($IndexEmplacement == 0)
	{
		defaultMosaique(1,LoadDefaultMosaique());
		aff_mosaique_galerie();
	}
}
?>