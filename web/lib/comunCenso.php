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
function altaSimpatizanteWeb ($nombre, $apellido1, $apellido2, $nif, DateTime $fchaNacimiento, $email, $telefono, $ip) {
	global $DEBUG;
	global $enlace;
	global $TABLE_PREFIX;

	conectarBD();
	
	//Valores que pueden ser nulos
	if ($apellido2) {
		$apellido2= $enlace->real_escape_string($apellido2);
	}
	if ($nif) {
		$nif= $enlace->real_escape_string($nif);
	}

	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "SIMPATIZANTES (NOMBRE, APELLIDO1, APELLIDO2, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO, IP_REGISTRO)
		    VALUES ('%s','%s','%s','%s','%s','%s','%s','%s')",
			$enlace->real_escape_string($nombre),
			$enlace->real_escape_string($apellido1),
			$apellido2,
			$nif,
			formatearFechaBD($fchaNacimiento),
			$enlace->real_escape_string($email),
			$enlace->real_escape_string($telefono),
			$enlace->real_escape_string($ip)
	);

	if ($DEBUG) {
		echo $consulta;
	}

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	} else {
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
			echo <<<EOT
<h1 class="error">
EOT;
			echo $msgIncorrecto;
			echo <<<EOT
</h1>
EOT;
			borrarSimpatizante ($idInsertado, null);
		}

	}

	return $resultado;
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

	if ($DEBUG) {
		echo $consulta;
	}

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}
}
