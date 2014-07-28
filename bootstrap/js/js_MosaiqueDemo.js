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