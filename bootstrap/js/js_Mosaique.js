		var SelectedIndex = 0;
		var DefaultMosaique = 0;
		function GetUrl()
		{
			var Item = document.getElementById('URLToGet');
			var Value = Item.value;
			var ItemLi = document.getElementById('URL_'+SelectedIndex);
			ItemLi.innerHTML = "<i class=\"icon-remove\" onclick=\"removeUrl("+SelectedIndex+")\"></i><i class=\"icon-edit\" onclick=\"SelectItem('"+SelectedIndex+"','"+Value+"')\"></i><a id=\"Link_"+SelectedIndex+"\" href=\""+Value+"\"><img src=\"http://www.robothumb.com/src/"+Value+"@320x240.jpg\" /></a>";
		}
		function SelectItem(index,URL)
		{
			var ItemLi = document.getElementById('URL_'+SelectedIndex);
			SelectedIndex = index;
			if (URL)
			{
				document.getElementById('URLToGet').value = URL;
			}
			else
			{
				document.getElementById('URLToGet').value = "";
			}
			$('#myModalGetUrl').modal('toggle');
		}
		function removeUrl(index)
		{
			var ItemLi = document.getElementById('URL_'+index);
			ItemLi.innerHTML = "<span onclick=\"SelectItem("+index+")\" class=\"title\">Cliquer pour ajouter !</span>";
		}
		function LinkToHome()
		{
			DefaultMosaique = 1;
			document.getElementById("ButtonLinkToHome").outerHTML = "<button id=\"ButtonUnlinkToHome\" class=\"btn btn-info\" onclick=\"UnlinkToHome()\"><i class=\"icon-share icon-white\"></i> Détacher de l'accueil</button>";
			saveMosaique();
		}
		function UnlinkToHome()
		{
			DefaultMosaique = 0;
			document.getElementById("ButtonUnlinkToHome").outerHTML = "<button id=\"ButtonLinkToHome\" class=\"btn btn-info\" onclick=\"LinkToHome()\" ><i class=\"icon-share icon-white\"></i> Epingler à l'accueil</button>";
			saveMosaique();
		}
		function Linked(value)
		{			
			DefaultMosaique = value;
		}
		function DeleteMosaique()
		{
			var ID_Mosaique = document.getElementById('ID_Mosaique').value;
			var NomPage = document.getElementById('NomPage').value;
			NomForm = 'DeleteMosaique';
			$.post("script/edit.php", { ID_Mosaique: ID_Mosaique, Form : NomForm,Page : NomPage,Default : DefaultMosaique  })
			.done(function(data) 
			{
				var exp = "<ReturnAjax>(.*?)</ReturnAjax>";
				var texte = data.match(exp);
				if (texte != null)
				{
					texte = texte[1];
					document.location.href = 'BibPrivee.php?'+texte;
				}
				else
				{
					alert (data);
				}
			});
		}
		function saveMosaique()
		{
			var LinksMosaique = new Array();
			var ID_Mosaique = document.getElementById('ID_Mosaique').value;
			var NomMosaique = document.getElementById('NomMosaique').value;
			var NomForm = document.getElementById('NomForm').value;
			var NomPage = document.getElementById('NomPage').value;
			var MosaiqueCategorie = document.getElementById('MosaiqueCategorie').value;
			for (i=1; i <= 9; i++)
			{
				if (document.getElementById('Link_'+i))
				{
					LinksMosaique[i] = document.getElementById('Link_'+i).getAttribute("href");
				}
				else
				{
					LinksMosaique[i] = "";
				}
			}
			$.post("script/edit.php", { ID_Mosaique: ID_Mosaique, URL: LinksMosaique,Nom_Mosaique: NomMosaique,Form : NomForm,Page : NomPage,Categorie : MosaiqueCategorie,Default : DefaultMosaique  })
			.done(function(data) 
			{
				var exp = "<ReturnAjax>(.*?)</ReturnAjax>";
				var texte = data.match(exp);
				if (texte != null)
				{
					texte = texte[1];
					if (!isNaN(texte))
					{		
						if (texte != document.getElementById('ID_Mosaique'))
						{
							document.location.href = 'Mosaique.php?disp='+texte;
						}
					}
					else
					{
						alert (texte);
					}
				}
				else
				{
					alert (data);
				}
			});
		}