function checkAll()
{
	for(var i = 0; i < document.getElementsByName("SelectFav[]").length; i++)
	{
		document.getElementsByName("SelectFav[]")[i].checked = document.getElementsByName("AllCheck")[0].checked;
	}
	for(var i = 0; i < document.getElementsByName("SelectCat[]").length; i++)
	{
		document.getElementsByName("SelectCat[]")[i].checked = document.getElementsByName("AllCheck")[0].checked;
	}
	for(var i = 0; i < document.getElementsByName("SelectMosaique[]").length; i++)
	{
		document.getElementsByName("SelectMosaique[]")[i].checked = document.getElementsByName("AllCheck")[0].checked;
	}
}
function verifCheckDelete()
{
	var flag = false;
	if (document.getElementsByName("SelectFav[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectFav[]").length; i++)
		{
			if (document.getElementsByName("SelectFav[]")[i].checked)
			{
				flag=true;
			}
		}
	}
	
	if (document.getElementsByName("SelectCat[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectCat[]").length; i++)
		{
			if (document.getElementsByName("SelectCat[]")[i].checked)
			{
				flag=true;
			}
		}
	}
	if (document.getElementsByName("SelectMosaique[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectMosaique[]").length; i++)
		{
			if (document.getElementsByName("SelectMosaique[]")[i].checked)
			{
				flag=true;
			}
		}
	}
	
	if (flag == false)
	{
		alert("Aucun élement sélectionné");
	}
	else 
	{
		$("#myModalDeleteBibPrivee").modal('show');
	}
	
	return false;
	
}
function verifCheckAssoc()
{
	var flag = false;
	
	if (document.getElementsByName("SelectFav[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectFav[]").length; i++)
		{
			if (document.getElementsByName("SelectFav[]")[i].checked)
			{
				flag=true;
			}
		}
	}
		
	if (flag == false)
	{
		alert("Aucun élement sélectionné");
	}
	else 
	{
		$("#myModalValidAssoc").modal('show');
	}
	
	return false;
}
function verifCheckEdit()
{
	var flag = false;
	var comptFav = 0;
	var comptCat = 0;
	var comptMosaique = 0;
	var index = 0;
	
	if (document.getElementsByName("SelectFav[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectFav[]").length; i++)
		{
			if (document.getElementsByName("SelectFav[]")[i].checked)
			{
				index = i;
				comptFav++;
			}
		}
	}
	
	if (document.getElementsByName("SelectCat[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectCat[]").length; i++)
		{
			if (document.getElementsByName("SelectCat[]")[i].checked)
			{
				index = i;
				comptCat++;
			}
		}
	}
	if (document.getElementsByName("SelectMosaique[]").length >= 1)
	{
		for(var i = 0; i < document.getElementsByName("SelectMosaique[]").length; i++)
		{
			if (document.getElementsByName("SelectMosaique[]")[i].checked)
			{
				index = i;
				comptMosaique++;
			}
		}
	}
	if (comptFav+comptCat+comptMosaique > 1) 
	{
		alert("Vous ne pouvez éditer qu'un seul élement.");		
	}
	else if (comptFav+comptCat+comptMosaique == 0)
	{
		alert("Aucun élement sélectionné");
	}
	else
	{
		if (comptFav == 1)
		{
			document.getElementById('UrlModif').innerHTML = "<strong>"+document.getElementsByName("SelectFav[]")[index].value+"</strong>";
			document.forms["ModifFavoris"]["NomFav"].value = document.getElementById('Edit'+document.getElementsByName("SelectFav[]")[index].value).innerHTML;	
			document.forms["ModifFavoris"]["UrlFav"].value = document.getElementsByName("SelectFav[]")[index].value;	
			$("#myModalModifFav").modal('show');
			return false;
		}
		else if (comptCat == 1)
		{
			alert('Une catégorie ne peut être modifié.');
			return false;
		}
		else if (comptMosaique == 1)
		{
			document.location.href = 'Mosaique.php?disp='+document.getElementsByName("SelectMosaique[]")[index].value;
			return false;
		}
	}
	
	return false;
}