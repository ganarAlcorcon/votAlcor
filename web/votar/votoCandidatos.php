<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunVotacion.php')) {
	die ("Falta el archivo comunVotar.php");
}
if (!(include_once dirname(__FILE__).'/../lib/comunCenso.php')) {
	die ("Falta el archivo comunCenso.php");
}

?>


<!DOCTYPE html>
<html lang="es">
	<head>
		<title>Votación primarias | Validar SMS</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link rel="stylesheet" type="text/css" href="../css/jQueryUI/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../css/bootstrap-theme.min.css">
		<link rel="stylesheet" type="text/css" href="../css/estilo.css">
		
		<script type="text/javascript" src="../js/ext/jQuery/jquery.min.js" ></script>
		<script type="text/javascript" src="../js/ext/jQuery/jquery-ui.min.js" ></script>
		<script type="text/javascript" src="../js/ext/jQuery/datepicker-es.js" ></script>
		<script type="text/javascript" src="../js/ext/bootstrap.min.js" ></script>
		<script type="text/javascript" src="../js/censoWeb.js" ></script>
		
	</head>
	<body class="container" onload="inicio()">
		<?php 
		if (isset($_SESSION["VOTACION"]) && $_SESSION["VOTACION"]) {

			if (isset($_POST["cod_telefono"])) {
				if (isset($_SESSION["telefono_v"]) && $_SESSION["telefono_v"] && $_SESSION["email_v"] ||
						verificarEmail($_POST["cod_telefono"],'T')) {
					$_SESSION["telefono_v"]= true;
					$candidatos= listaCandidatos();
		?>
		<div id="divError" class="alert alert-danger"></div>
		<div id="divOk" class="alert alert-success"></div>
		<form action="votar.php" accept-charset="utf-8" method="post" enctype="multipart/form-data" role="form" >
			<h2>CANDIDATOS</h2>
			
			<h3 class="importante">Cabeza de lista <small>(1)</small></h3>
			
			<div id="bloque_cabeza_lista">
			<?php
			foreach ($candidatos as $candidato) {
				if ($candidato["CABEZA_LISTA"]=='S') {
			?>
				<div class="row candidatos">
					<div class="col-sm-2">
						<input id="cabeza_<?php echo $candidato["ID"];?>" value="<?php echo $candidato["ID"];?>" name="cabeza_lista" type="radio" class="form-control" autocomplete="off" />
					</div>
					<label for="cabeza_<?php echo $candidato["ID"];?>" class="control-label col-sm-9">
						<div class="row cabecera_nombre">
							<div class="col-sm-4 foto">
								<img class="img-circle img-responsive" alt="Foto <?php echo $candidato["NOMBRE_COMPLETO"];?>" src="<?php echo $CONFIG["RUTA_IMG_CANDIDATOS"] . '/' . $candidato["ID"] . '.png';?>">
							</div>
							<div class="col-sm-8 nombre">
								<?php echo $candidato["NOMBRE_COMPLETO"];?>
							</div>
						</div>
						<?php if ($CONFIG["DESCRIPCION"] == 'S') {?>
						<h4>Biografía</h4>
						<div class="row biografia">
							<?php echo $candidato["BIOGRAFIA"];?>
						</div>
						<h4>Motivaciones</h4>
						<div class="row motivaciones">
							<?php echo $candidato["MOTIVACIONES"];?>
						</div>
						<?php }?>
					</label>
				</div>
			<?php
				}
			}
			?>
			</div>
			
			<h3 class="importante">Lista general <small>(13)</small></h3>
			
			
			<div id="bloque_lista_general">
			<?php
			foreach ($candidatos as $candidato) {
				if ($candidato["LISTA_GENERAL"]=='S') {
			?>
				<div class="row candidatos">
					<div class="col-sm-2">
						<input id="general_<?php echo $candidato["ID"];?>" value="<?php echo $candidato["ID"];?>" name="lista_general[]" type="checkbox" class="form-control" autocomplete="off" />
					</div>
					<label for="general_<?php echo $candidato["ID"];?>" class="control-label col-sm-9">
						<div class="row cabecera_nombre">
							<div class="col-sm-4 foto">
								<img class="img-circle img-responsive" alt="Foto <?php echo $candidato["NOMBRE_COMPLETO"];?>" src="<?php echo $CONFIG["RUTA_IMG_CANDIDATOS"] . '/' . $candidato["ID"] . '.png';?>">
							</div>
							<div class="col-sm-8 nombre">
								<?php echo $candidato["NOMBRE_COMPLETO"];?>
							</div>
						</div>
						<?php if ($CONFIG["DESCRIPCION"] == 'S') {?>
						<h4>Biografía</h4>
						<div class="row biografia">
							<?php echo $candidato["BIOGRAFIA"];?>
						</div>
						<h4>Motivaciones</h4>
						<div class="row motivaciones">
							<?php echo $candidato["MOTIVACIONES"];?>
						</div>
						<?php }?>
					</label>
				</div>
			<?php
				}
			}
			?>
			</div>
			
			<div class="form-group">
				<div class="col-sm-offset-2 col-sm-10">
					<input type="submit" value="Votar" class="form-control" />
				</div>
			</div>
		</form>
		<?php
						} else {
		?>
			<h1 class="error">El código del teléfono no es válido</h1>
		<?php 
						}
				} else {
		?>
			<h1 class="error">No se ha introducido el código del teléfono</h1>
		<?php 
				}
			} else {
			?>
			<h1 class="error">Debe comenzar de nuevo</h1>
		<?php 
			}
		?>
		
	</body>
</html>