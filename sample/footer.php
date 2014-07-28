		<!-- ============ JAVASCRIPT ===================== -->
<?php
		if (isset($_SESSION['valid_user']))
		{
?>
			<script type="text/javascript" src="bootstrap/js/bootstrap.js" ></script>
			<script src="bootstrap/js/js_BibPrivee.js" type="text/javascript"></script> 
			<script src="bootstrap/js/ajax_clic.js" type="text/javascript"></script>
			<script src="bootstrap/js/js_Mosaique.js" type="text/javascript"></script>
			<script src="bootstrap/js/js_verif.js" type="text/javascript"></script>
			<!-- ============================================= -->	
<?php
		}
		else
		{
?>
			<script type="text/javascript" src="bootstrap/js/bootstrap.js" ></script>
			<script src="bootstrap/js/js_MosaiqueDemo.js" type="text/javascript"></script>
			<script src="bootstrap/js/js_verif.js" type="text/javascript"></script>
<?php
		}
?>
	</body>
</html>
<?php
if (isset($_SESSION['mysql']))
{
	mysql_close($_SESSION['mysql']);
	$_SESSION['mysql'] = null;
}
?>