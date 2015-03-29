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
		}
	} else {
		if ($enlace->errno==1062) {
			$devolver["mensajeError"]= "El simpatizante ya ha sido dado de alta, revise los datos introducidos y no haga trampas";
			
			//TODO: Insertar en una tabla de "posibles tramposos" por duplicado que cuente el número de intentos por IP, puerto y cookie
		}
		if ($enlace->errno==1048) {
			$devolver["mensajeError"]= "Por favor, rellene todos los campos y no haga trampas";
			
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