function UrlClick(url)
{
	var url1 =  encodeURIComponent(url);
	$.get("Clic.php", { URL: url1 })
	.done(function(data) {
	window.open(url);
	});
}	