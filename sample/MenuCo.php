<?php 
$page= substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], '/')+1,strrpos($_SERVER['PHP_SELF'],'.')-1);
?>
<?php 
require_once('Modal_Ajout.php'); 
?>
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<a class="title_site" href="Accueil.php">ManyLinks</a>
		<ul class="nav">
			<li <?php if ($page == 'BibPrivee.php'){ echo 'class=active';} ?>>
				<a href="BibPrivee.php">Ma bibliothèque </a>
			</li>
			<li <?php if ($page == 'Galerie.php'){ echo 'class=active';} ?>>
				<a href="Galerie.php"> Ma Galerie</a>
			</li>
			<li <?php if ($page == 'BibPublique.php'){ echo 'class=active';} ?>>
				<a href="BibPublique.php">Bibliothèque publique</a>
			</li>
		</ul>				
		<ul class="nav pull-right">
			<li <?php if ($page == 'Accueil.php'){ echo 'class=active';} ?>>
				<a href="Accueil.php"><i class="icon-white icon-home"></i>  Accueil</a>
			</li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mon Compte<b class="caret"></b></a>
				<ul class="dropdown-menu" role="menu">
					<li role="menuitem"><a href="GestionCompte.php"><span class="icon-user"></span><?php echo $_SESSION['email'] ?></a></li>
					<li role="menuitem"><a href="script/deconnexion.php">Deconnexion</a></li>
				</ul>
			</li>
			<li><a href="Contact.php">Contact</a></li>
		</ul>
		<a class="btn btn-success btn_ajout dropdown-toggle pull-right" role="button" data-toggle="modal" href="#myModal">
			<i class="icon-white icon-plus"></i>
		</a>				
	</div>
</div>	
<div>
<?php
	if (isset($_GET['Err']))
	{
?>
	<div id="MsgAlert" class="error message">
			 <i class="icone-remove"></i><p><?php echo mysql_real_escape_string(htmlspecialchars($_GET['Err'])); ?><a onclick="closeAlert('MsgAlert')" href="#"> -> fermer</a></p>
	</div>
<?php
	}
	else if (isset($_GET['Success']))
	{
?>
	<div id="MsgAlert" class="success message">
			 <p><?php echo mysql_real_escape_string(htmlspecialchars($_GET['Success'])); ?><a onclick="closeAlert('MsgAlert')" href="#"> fermer le message</a></p>
	</div>
<?php
	}
?>
</div>