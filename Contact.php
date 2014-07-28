<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("header.php");
	chdir($wd_was);
?>
<!-- ========= PAGE DE CONTACT ================= -->
		<div class="EntetePrincipal"><h1>Contact</h1></div>
		
		<div class="row-fluid margin">
			<div class="span2 transparent"></div>
			<div class="span8 BlockStyler padding">
				<form method="post" action="script/SendMsgContact.php" enctype="multipart/form-data">
					<table class="table table-bordered table-striped table-condensed table-contact">
					<thead>
						<tr>
							<th class="first">Tout les champs obligatoires</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td class="legende"><div align="left">Civilit&eacute;: </div></td>
							<td class="legende">
								<div align="left">
									<input type="hidden" name="page" value="<?php echo $_SERVER['PHP_SELF']; ?>" />
									<input type="hidden" name="form" value="Contact" />
									<label class="radio">
										<input type="radio" name="Civilite" value="Mr" checked="checked">
										M.
									</label>
									<label class="radio">
										<input type="radio" name="Civilite" value="Mme">
										Mme 
									</label>							
								</div>
							</td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Nom: </div></td>
						  <td><div align="left">
							<input name="Nom" type="text" class="" id="nom" size="30" required/>
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Pr&eacute;nom: </div></td>
						  <td><div align="left">
							<input name="Prenom" type="text" class="" id="prenom" size="30" required/>
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Email: </div></td>
						  <td><div align="left">
							<input name="Email" type="text" class="" id="email" size="30" required/>
						  </div></td>
						</tr>
						<tr>
						  <td class="legende"><div align="left">Sujet: </div></td>
						  <td><div align="left">
							<select name="Sujet" class="" id="Sujet">
								<option value="" selected="selected">A propos ...</option>
								<option value="Notre Groupe">- De notre équipe</option>
								<option value="Nos offres">- De l'application Gestion Favoris</option>
								<option value="Nos secteurs d'activité">- De bugs rencontr&eacute;s</option>
								<option value="Commentaires sur le site web">- D'un avis ou commentaire sur le site web</option>
								<option value="Commentaires sur le site web">- d'une demande d'assistance ou d'aide</option>
							</select>
						  </div></td>
						</tr>
						<tr>
							<td valign="top" class="legende"><div align="left">Votre question : </div></td>
							<td valign="top">
								<div align="left">
									<textarea style="width:80%; height:80%;" name="Question" cols="20" rows="" class="" id="question" required></textarea>
								</div>
							</td>
						</tr>
						<tr>
						  <td valign="top" class="legende"><div align="left">Merci de recopier en minuscule les caractères de l'image : <br /></div></td>
						  <td valign="top">
							<div align="left"><img id="Captcha" src="script/securitecode.php" alt="Code de sécurité" /><a onclick="return recharge()" href="<?php echo $_SERVER['PHP_SELF']; ?>"> Recharger</a></div>
								<input type="text" name="Captcha_code" size="10" maxlength="6" required/>				
							</td>
						</tr>
					</tbody>
					</table>
					<div id="DataImage"></div> <!-- Contient l'image captcha après rechargement -->
					<div class="bottom-right">
								<input class="btn btn-success" type="submit" value="Valider" />
								<a href="Contact.php"><button class="btn btn-info" >Effacer</Button></a>
					</div>
				</form>
			</div>
			<div class="span2 transparent"></div>
		</div>
		<script>
			function recharge()
			{
				$.post("script/securitecode.php").done(function(data) 
				{document.getElementById('DataImage').innerHTML = data;});
			   img = document.getElementById('Captcha'); 
			   img.src = 'script/securitecode.php?' + Math.random();;
				return false;
			}		
		</script>
<?php
//MANYLINK
	$wd_was = getcwd();
	chdir(dirname(__FILE__));
	chdir("sample/");
	require_once("footer.php");
	chdir($wd_was);
?>