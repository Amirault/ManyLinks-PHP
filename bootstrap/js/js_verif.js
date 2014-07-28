function validEmail(email)
{
	var pattern =/^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/;
	if (pattern.test(email)) 
	{
		return true;
	}
	return false;
}
function validInscription()
{
	var pseudo = document.forms["inscription"]["Pseudo"].value;
	var email = document.forms["inscription"]["Email"].value
	var mdp = document.forms["inscription"]["Mdp"].value;
	var confMdp = document.forms["inscription"]["ConfMdp"].value;
	
	if (pseudo==null || pseudo=="")
	{
		alert("Veuillez chosir un pseudo.");
		return false;
	}
	if (!validEmail(email))
	{
		alert('L\'adresse email saisie est incorrect!\n\nVeuillez saisir une adresse email correct.');
		return false;
	}
	if (mdp==null || mdp=="")
	{
		alert("Veuillez choisir un mot de passe.");
		return false;
	}
	if (confMdp!=mdp)
	{
		alert("La confirmation du mot de passe est incorrecte.");
		return false;
	}
	if (!certification)
	{
		alert("Veuillez cocher la case de certification.");
		return false;
	}
}

function validModifCompte()
{
	var pseudo = document.getElementById("Pseudo").value;
	var ancienMdp = document.getElementById("ancienMdp").value;
	var mdp = document.getElementById("Mdp").value;
	var confMdp = document.getElementById("ConfMdp").value;
	
	if (pseudo==null || pseudo=="")
	{
		alert("Veuillez chosir un pseudo.");
		return false;
	}
	if ((ancienMdp==null || ancienMdp=="") && (mdp!=null && mdp!=""))
	{
		alert("Veuillez saisir votre ancien mot de passe.");
		return false;
	}
	if ((mdp==null || mdp=="") && (ancienMdp!=null && ancienMdp!=""))
	{
		alert("Veuillez saisir votre nouveau mot de passe.");
		return false;
	}
	if (confMdp!=mdp)
	{
		alert("La confirmation du mot de passe est incorrecte.");
		return false;
	}
}		
function validReinilisationMdp()
{
	var mdp = document.getElementById("Mdp").value;
	var confMdp = document.getElementById("ConfMdp").value;
	
	if (mdp==null || mdp=="")
	{
		alert("Veuillez choisir un mot de passe.");
		return false;
	}
	if (confMdp!=mdp)
	{
		alert("La confirmation du mot de passe est incorrecte.");
		return false;
	}
}
function validAddFavoris()
{
	var URL = document.forms["AddFavoris"]["UrlFav"].value;
	if (!validUrl(URL))
	{
		alert("L'URL saisie n'est pas valide !\n\nVeuillez saisir une adresse URL correct.");
		return false;
	}
}
function validUrl(url) 
{
	var pattern =/(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
	if (pattern.test(url)) 
	{
		return true;
	} 
	return false;
}
function validConnexion()
{
	if (!validEmail(document.forms["FormConnexion"]["email"].value))
	{
		alert('L\'adresse email saisie est incorrect!\n\nVeuillez saisir une adresse email correct.');
		return false;
	}
}
$(document).ready(function() {
	$("#search").typeahead({
		minLength: 1,
		source: function(query, process) {
			$.post('script/recherche.php', { search: query, limit: 4 }, function(data) {
				process(JSON.parse(data));
			});
		},
		updater: function (item) {
			document.location = "?search=" + encodeURIComponent(item);
			return item;
		}
	});
});
$(document).ready(function() {
	$("#searchURL").typeahead({
		minLength: 1,
		source: function(query, process) {
			$.post('script/recherche.php', { search: query, limit: 4 }, function(data) {
				process(JSON.parse(data));
			});
		},
		updater: function (item) {
			return item;
		}
	});
});	
function liveSearch(search)
{
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
		if (xmlhttp.readyState==4 && xmlhttp.status==200)
		{
			document.getElementById("livesearch").innerHTML=xmlhttp.responseText;
		}
	}
	xmlhttp.open("GET","script/Lib/liveSearch.php?search="+search,true);
	xmlhttp.send();
}
function  closeAlert(str)
{
	document.getElementById(str).outerHTML = "";
}