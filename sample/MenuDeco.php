<?php 
$page= substr($_SERVER['PHP_SELF'],strrpos($_SERVER['PHP_SELF'], '/')+1,strrpos($_SERVER['PHP_SELF'],'.')-1);
?>
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<a class="title_site" href="index.php">ManyLinks</a>
		<ul class="nav pull-right">
			<li class="active"><a href="index.php"><i class="icon-white icon-home"></i>Accueil</a></li>
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown">Mon Compte<b class="caret"></b></a>
				<ul class="dropdown-menu">
					<li>
						<form name="FormConnexion" class="form margin-left-right" onsubmit="return validConnexion();" action="script/login.php" method="post">
							<input id="email" name="email" type="text" class="span2" placeholder="Adresse email" required>
							<input id="mdp" name="mdp" type="password" class="span2" placeholder="Mot de passe" required>
							<button type="submit" class="btn">Connexion</button>
						</form>
					</li>
					<li class="divider"></li>
					<!-- Pas de chemin relatif -->
					<li><a href="Inscription.php">Inscription</a></li>
					<li><a href="MdpOublie.php">Mot de passe oublié</a></li>
				</ul>
			</li>
			<li><a href="Contact.php">Contact</a></li>
		</ul>
	</div>
</div>
<div>
<?php
	if (isset($_GET['Err']))
	{
?>
	<div id="MsgAlert" class="error message">
			 <i class="icone-remove"></i><p><?php echo htmlspecialchars($_GET['Err']); ?><a onclick="closeAlert('MsgAlert')" href="#"> fermer le message</a></p>
	</div>
<?php
	}
	else if (isset($_GET['Success']))
	{
?>
	<div id="MsgAlert" class="success message">
			 <p><?php echo htmlspecialchars($_GET['Success']); ?><a onclick="closeAlert('MsgAlert')" href="#"> fermer le message</a></p>
	</div>
<?php
	}
?>
</div>