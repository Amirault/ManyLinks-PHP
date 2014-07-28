
var itemNameSave = "";
var itemDD ;
var disp = getQuerystring('disp');

if (isNumber(disp))
{
	if ('UL'+disp != '')
	{
		disp++;
		while (document.getElementById('UL'+disp) != null)
		{
			document.getElementById('UL'+disp).style.display = 'none';
			disp ++;
		}
	}
}
else
{
	itemDD = document.getElementsByClassName('ArbreBib');
	initSubItem();
}
function displayHide(itemName)
{
	if (itemName != '')
	{
		var subitem = document.getElementById(itemName);
		if (subitem.style.display == 'none')
		{
			subitem.style.display = 'list-item';
		}
		else
		{
			subitem.style.display = 'none';
		}
	}
}
//Fonction d'initialisation des elements du menu
function initSubItem()
{
	for (var i = 0; i < itemDD.length; i++) 
	{
		itemDD[i].style.display = 'none';
	}
}
// Fonction get Elements by class
document.getElementsByClassName = function(cl) {
var retnode = [];
var myclass = new RegExp('\\b'+cl+'\\b');
var elem = this.getElementsByTagName('*');
for (var i = 0; i < elem.length; i++) {
var classes = elem[i].className;
if (myclass.test(classes)) retnode.push(elem[i]);
}
return retnode;
}; 
function getQuerystring(key, default_) // recupération du paramètre (GET) contenu dans l'URL
{
  if (default_==null) default_="";
  key = key.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regex = new RegExp("[\\?&]"+key+"=([^&#]*)");
  var qs = regex.exec(window.location.href);
  if(qs == null)
    return default_;
  else
    return qs[1];
}
// Fonction permettant de savoir si la variable est un nombre
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}