<?php
    $wd_was = getcwd();
	chdir(dirname(__FILE__));
	require_once("method_aff.php");
	chdir("../");
	require_once ('config.php');
	chdir($wd_was);
?>
<?php
	$connexion = mysql_pconnect($host, $user, $passwd) or die ("Connexion impossible !");
	mysql_select_db ($base, $connexion) or die ("$base n'existe pas !");
	
	if (isset($_GET['search']))
	{
		$disp = mysql_real_escape_string(htmlspecialchars($_GET['search']));
	}
	else
	{
		$disp = "";
	}
	
	if(isset($_GET["search"]))
	{	
		if(!empty($_GET["search"]))
		{
			$search = htmlspecialchars(mysql_real_escape_string($_GET["search"]));
			
			$requete = "SELECT URL,Icone,Title FROM favoris WHERE Statut = 'public' AND (URL LIKE '%$search%' OR Title LIKE '%$search%') LIMIT 0,10";
			$requeteCount = "SELECT Count(*) AS Nb FROM favoris WHERE Statut = 'public' AND (URL LIKE '%$search%' OR Title LIKE '%$search%')";
			$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete.mysql_error($connexion));
			$resultatCount = mysql_query($requeteCount, $connexion) or die ("Erreur de requête :".$resultatCount.mysql_error($connexion));
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
					$icone = $enreg['Icone'];
					echo "<tr class=\"legende\">";
					echo "<td><img src=\"$icone\" width=16 height=16/></td>";
					echo "<td style=\"border-left: none;\"><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">".$enreg['Title']."</a></td>";
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
				echo "Erreur : ".mysql_error($connexion)."<br />";
			}
			echo " </tbody>
				  </table>";
			$nb = $resultatCount['Nb'];
			
			echo"<div class=\"pagination pagination-centered\">
				<ul>";
				for ($i=1; $i <= round($nb/10);$i++)
				{
					echo "<li><a href=\"/GestionFav/BibPublique.php?search=".$disp."&IndexPage=".$i."\">".$i."</a></li>";
				}
				echo "</ul>";
			echo "</div>";
		}
		else
		{
			echo"<caption>
					<h4>Derniers sites ajout&eacute;s</h4>
				</caption>
				<table class=\"table table-bordered table-striped table-condensed\">
				<thead>
					<tr>
						<th>Date</th>
						<th colspan=2>URL</th>
						<th>Par</th>
					</tr>
				</thead>
				<tbody> ";
			$requete = "SELECT DISTINCT *, DATE_FORMAT(fp.Timestamp,'%T') as t, DATE_FORMAT(fp.Timestamp,'%d/%m/%Y') as d FROM favoris_publie fp, favoris f, membres m WHERE URL = URL_Favoris AND Email_Membre = Email AND Statut = 'public' ORDER BY fp.Timestamp DESC LIMIT 10";
						
			$resultat = mysql_query($requete, $connexion) or die ("Erreur de requête :".$requete. mysql_error($connexion));
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
					echo "<tr class=\"legende\">";
					echo "<td><div class=\"label label-info\">$time<br/>$date</div></td>";
					echo "<td><img src=\"$icone\" width=16 height=16/></td>";
					echo "<td style=\"border-left: none;\"><a onclick=\"UrlClick('$url')\" title=\"$url\" target=\"_blank\">".$nom."</a></td>";
					echo "<td><div class=\"badge badge-success\">$pseudo</div></td>";
					echo "</tr>";
				}
			}
			else
			{
				echo "Erreur : ".mysql_error($connexion)."<br />";
			}
			echo "	</tbody>
			</table>";
		}
	}
	// Fin de la connexion 
	mysql_close($connexion);
?>