<?php



/**
 * Da de alta un nuevo socio
 * @param int $idSocio
 * @param string $nombre
 * @param string $apellido1
 * @param string $apellido2
 * @param string $dni
 * @param DateTime $fchaNacimiento
 * @param string $email
 * @param string $tipoVia
 * @param string $direccion
 * @param string $portal
 * @param string $escalera
 * @param string $piso
 * @param string $puerta
 * @param string $localidad
 * @param string $cp
 * @return resource devuelve true si se ejecutó correctamente, false en caso contrario.
 */
function altaSimpatizanteWeb ($nombre = NULL, $apellido1 = NULL, $apellido2 = NULL, $nif = NULL, DateTime $fchaNacimiento, $email = NULL, $telefono = NULL, $ip, $puerto, $cookie) {
	global $DEBUG;
	global $enlace;
	global $TABLE_PREFIX;

	conectarBD();

	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "SIMPATIZANTES (NOMBRE, APELLIDO1, APELLIDO2, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, IP_REGISTRO, PUERTO_REGISTRO, COOKIE)
		    VALUES (%s,%s,%s,%s,'%s',%s,%s,%s,%d,%s)",
			prepararCampo($nombre),
			prepararCampo($apellido1),
			prepararCampo($apellido2),
			prepararCampo($nif),
			formatearFechaBD($fchaNacimiento),
			prepararCampo($email),
			prepararCampo($telefono),
			prepararCampo($ip),
			$puerto,
			prepararCampo($cookie)
	);

	/*if ($DEBUG) {
		echo $consulta;
	}*/

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if ($resultado) {
		$idInsertado= $enlace->insert_id;
		if ($_POST["identificacion"]=="padron") {
			$msgIncorrecto= guardarImagenSubida("padron",$idInsertado . "_padron");
		} else if ($_POST["identificacion"]=="dni" || $_POST["identificacion"]=="nie") {
			$msgIncorrecto= guardarImagenSubida("nif_anv",$idInsertado . "_" . $_POST["identificacion"] . "_anverso");
			if (!$msgIncorrecto) {
				$msgIncorrecto= guardarImagenSubida("nif_rev",$idInsertado . "_" . $_POST["identificacion"] . "_reverso");
			}
		}
	
		//Comprobamos que se hayan guardado todas las imágenes, si no es así, se elimina la entrada.
		if ($msgIncorrecto) {
			$devolver["mensajeError"]= $msgIncorrecto;
			borrarSimpatizante ($idInsertado, null);
		} else {
			generarVerificacionMail($idInsertado,prepararCampo($email));
		}
	} else {
		if ($enlace->errno==1062) {
			$devolver["mensajeError"]= "El simpatizante ya ha sido dado de alta, revise los datos introducidos y no haga trampas";
			
			error_log("Intento de introducir simpatizante duplicado: ".$nombre." ".$apellido1." ".$apellido2." ".$nif." ".formatearFecha($fchaNacimiento)." ".$email." ".$telefono." Desde " . $ip.":".$puerto." ".$cookie.": ".$enlace->error);
			//TODO: Insertar en una tabla de "posibles tramposos" por duplicado que cuente el número de intentos por IP, puerto y cookie
		}
		if ($enlace->errno==1048) {
			$devolver["mensajeError"]= "Por favor, rellene todos los campos y no haga trampas";

			error_log("Intento de introducir campos nulos: ".$nombre." ".$apellido1." ".$apellido2." ".$nif." ".formatearFecha($fchaNacimiento)." ".$email." ".$telefono." Desde " . $ip.":".$puerto." ".$cookie.": ".$enlace->error);
			//TODO: Insertar en una tabla de "posibles tramposos" por nulo que cuente el número de intentos por IP, puerto y cookie
		}
		if ($DEBUG) {
			$devolver["mensajeError"]= $devolver["mensajeError"] . ". Consulta: " . $consulta . ". Error: " . $enlace->error . " (" . $enlace->errno . ")";
		}
	}
	
	$devolver["correcto"] = $resultado && !$msgIncorrecto;

	return $devolver;
}

function borrarSimpatizante ($id, $erroneo) {
	global $enlace;
	global $TABLE_PREFIX;
	
	if ($erroneo) {
		$consulta = sprintf("DELETE FROM " . $TABLE_PREFIX . "SIMPATIZANTES WHERE ID=%d",
				$enlace->real_escape_string($id)
		);
	} else {
		$consulta = sprintf("UPDATE " . $TABLE_PREFIX . "SIMPATIZANTES SET FECHA_BAJA=NOW() WHERE ID=%d",
				$enlace->real_escape_string($id)
		);
	}

	/*if ($DEBUG) {
		echo $consulta;
	}*/

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if (!$resultado) {
		error_log("Error al borrar usuario: " . $id. " ".$consulta. " -> ".$enlace->error);
	}
	
	/*if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}*/
}

function prepararCampo ($valor) {
	global $enlace;
	
	if (!isset($valor) || empty($valor) || $valor == "" || trim($valor) == "") {
		$valor='NULL';
	} else {
		$valor= "'" . $enlace->real_escape_string($valor) . "'";
	}
	
	return $valor;
}

function generarVerificacionMail ($idSimpatizante, $email) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	
	$clave= textoAleatorio($CONFIG["EM_TAMANO"],$CONFIG["EM_GRUPOS"],$CONFIG["EM_SEPARADOR"]);
	
	//TODO: Comprobar que no existe la clave
	
	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "VERIFICACIONES (ID_SIMP,CLAVE,TIPO) VALUES (%d, %s, 'E')",
			$idSimpatizante,
			prepararCampo($clave)
	);
	
	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if (!$resultado) {
		error_log("Error al guardar verificación de: " . $idSimpatizante. " ".$consulta. " -> ".$enlace->error);
	} else {

		$mensaje = '
<html>
<head>
  <title>Verificación del correo</title>
</head>
<body>
  <p>¡Enhorabuena! Se ha registrado correctamente como simpatizante de Ganar Alcorcón</p>
  <p>Para completar el registro necesitamos que verifique su dirección de correo pinchando en <a href="' . $CONFIG["RUTA_VERIF_MAIL"] . '?verificar=' . $clave .'">este enlace</a>.</p>
  <p>No responda a este correo, la dirección no admite correo entrante. Si usted no se ha registrado, puede ignorar este email.</p>
  <p></p>
  <p>Ganar Alcorcón</p>
</body>
</html>
';
	
		enviarMail ($email,'Verificación de correo', $mensaje);
	}
}

function enviarMail($email, $titulo, $mensaje) {
	
	// Para enviar un correo HTML, debe establecerse la cabecera Content-type
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";
	
	// Cabeceras adicionales
	//$cabeceras .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
	$cabeceras .= 'From: No-reply Ganar Alcorcón <no-reply@ganaralcorcon.com>' . "\r\n";
	
	// Enviarlo
	mail($email, $titulo, $mensaje, $cabeceras);
}