<?php
    $host = "localhost";
    $user = "root";
    $passwd = "";
	//App3GestionFavSchool
    $base = "gestionfav";
	function CloseMysql()
	{
		if (isset($_SESSION['mysql']))
		{
			mysql_close($_SESSION['mysql']);
			$_SESSION['mysql'] = null;
		}
	}
?>
