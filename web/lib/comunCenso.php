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
function altaSimpatizanteWeb ($nombre, $apellido1, $apellido2, $nif, DateTime $fchaNacimiento, $email, $telefono) {
	global $DEBUG;
	global $enlace;
	global $TABLE_PREFIX;

	conectarBD();

	if ($DEBUG) {
		echo $consulta;
	}

	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "SIMPATIZANTES (NOMBRE, APELLIDO1, NIF, FECHA_NACIMIENTO, EMAIL, TELEFONO)
		    VALUES ('%s','%s','%s','%s','%s','%s')",
			$enlace->real_escape_string($nombre),
			$enlace->real_escape_string($apellido1),
			$enlace->real_escape_string($apellido2),
			$enlace->real_escape_string($nif),
			formatearFechaBD($fchaNacimiento),
			$enlace->real_escape_string($email),
			$enlace->real_escape_string($telefono)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	} else {
		// Recuperar ID y guardar imagenes
	}

	return $resultado;
}


