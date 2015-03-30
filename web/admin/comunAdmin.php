<?php
if (!(include_once dirname(__FILE__).'/../lib/comun.php')) {
	die ("Falta el archivo comun.php");
}


$TIMEOUT_SESION= $CONFIG["TIMEOUT_SESION"]* 60;
session_start();

if ((isset($_GET["salir"]) && $_GET["salir"]==="true") || (isset($_POST["salir"]) && $_POST["salir"]==="true")) {
	$_SESSION["autenticado"]=false;
	unset($_SESSION["nombre"]);
}

//Comprobar la expiración de la sesión
if (isset ($_SESSION["time"]) && $_SESSION["autenticado"]==true) {
	if ($_SESSION["time"] + $TIMEOUT_SESION >= time()) {
		$_SESSION["time"]=time();
	} else {
		if ($DEBUG) {
			echo "expiró en " . date('H:i:s',$_SESSION["time"] + $TIMEOUT_SESION);
		}
		session_destroy();
		die("<html><head><meta http-equiv='refresh' content='3; url=".$_SERVER['DOCUMENT_ROOT']."/admin'></head><body><h1 class='error'>La sesión ha expirado, vuelva a conectarse</h1></body></html>");
	}
} else {
	$_SESSION["time"]=time();
}

if (isset($_REQUEST['_SESSION'])) die("Inyección de sesión detectada");



/**
 * Indica si existe un usuario autenticado
 * @return boolean
 */
function autenticado() {
	return isset($_SESSION["nombre"]) && $_SESSION["nombre"] != null && $_SESSION["autenticado"]===true;
}

/**
 * Autentica a un usuario
 * @return boolean Autenticado o no
 */
function autenticar ($email, $passwd) {
	global $enlace;
	global $DEBUG;
	global $TABLE_PREFIX;
	conectarBD();

	$consulta = sprintf("SELECT PASSWORD,NOMBRE,ID FROM " . $TABLE_PREFIX . "SIMPATIZANTES
		    WHERE EMAIL='%s'", $enlace->real_escape_string($email));

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	// Comprobar el resultado
	$autenticado=false;
	if (!$resultado) {
		if ($DEBUG) {
			echo "'" . $consulta . "' Devolvió " . $enlace->error;
		}
	} else {
		while ($fila = $resultado->fetch_assoc()) {
			if (hash ("sha1",$passwd)===$fila['PASSWORD']) {
				$_SESSION["nombre"]=$fila['NOMBRE'];
				$_SESSION["ID"]=$fila['ID'];

				if (permitido('ADMIN')) {
					$autenticado=true;
				}
			}
		}
	}

	$_SESSION["autenticado"]=$autenticado;

	return $autenticado;
}

function permitido ($permiso) {
	global $enlace;
	global $DEBUG;
	global $TABLE_PREFIX;
	conectarBD();
	
	$consulta = sprintf("SELECT COUNT(*) AS PERMITIDO FROM " . $TABLE_PREFIX . "PERM_SIMP PS, " . $TABLE_PREFIX . "PERMISOS P
		WHERE P.NOMBRE='%s' AND P.ID = PS.ID_PERMISO AND PS.ID_SIMPATIZANTE=%d",
			$enlace->real_escape_string($permiso),
			$enlace->real_escape_string($_SESSION["ID"]));
	
	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if (!$resultado) {
		if ($DEBUG) {
			echo "'" . $consulta . "' Devolvió " . $enlace->error;
		}
	} else {
		while ($fila = $resultado->fetch_assoc()) {
			return $fila['PERMITIDO']==1;
		}
	}
	return false;
}



/**
 * Elimina un socio
 * @param int $idSocio
 * @return resource devuelve true si se ejecutó correctamente, false en caso contrario.
 */
function eliminarSocio ($idSocio) {
	global $DEBUG;
	global $enlace;
	global $TABLE_PREFIX;

	conectarBD();

	if ($DEBUG) {
		echo $consulta;
	}

	$consulta = sprintf("DELETE FROM " . $TABLE_PREFIX . "socio
			WHERE idSocio=%d",
			$enlace->real_escape_string($idSocio)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}

	return $resultado;
}

/**
 * Actualiza los datos de un socio
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
function actualizarSocio ($idSocio, $nombre, $apellido1, $apellido2, $dni, DateTime $fchaNacimiento, $email, $tipoVia, $direccion, $portal, $escalera, $piso, $puerta, $localidad, $cp) {
	global $DEBUG;
	global $enlace;
	global $TABLE_PREFIX;

	conectarBD();

	if ($DEBUG) {
		echo $consulta;
	}

	$consulta = sprintf("UPDATE " . $TABLE_PREFIX . "socio
			SET nombre='%s',
			apellido1='%s',
			apellido2='%s',
			dni='%s',
			fchaNacimiento='%s',
			email='%s',
			tipoVia='%s',
			direccion='%s',
			portal='%s',
			escalera='%s',
			piso='%s',
			puerta='%s',
			localidad='%s',
			cp='%s'
			WHERE idSocio=%d",
			$enlace->real_escape_string($nombre),
			$enlace->real_escape_string($apellido1),
			$enlace->real_escape_string($apellido2),
			$enlace->real_escape_string($dni),
			formatearFechaBD($fchaNacimiento),
			$enlace->real_escape_string($email),
			$enlace->real_escape_string($tipoVia),
			$enlace->real_escape_string($direccion),
			$enlace->real_escape_string($portal),
			$enlace->real_escape_string($escalera),
			$enlace->real_escape_string($piso),
			$enlace->real_escape_string($puerta),
			$enlace->real_escape_string($localidad),
			$enlace->real_escape_string($cp),
			$enlace->real_escape_string($idSocio)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}

	return $resultado;
}


/**
 * Recupera la lista de socios con la siguiente información:
 * idSocio,nombre,apellido1,apellido2,dni,email
 * @return resource devuelve false si fue incorrecto, un array con 0 elementos si no se encontró al usuario o si se encontró, contiene: nombre, apellido1, apellido2, dni, fchaNacimiento,  email, tipoVia, direccion, portal,  escalera, piso, puerta, localidad, cp
 *
 */
function listaSocios () {
	global $enlace;
	global $DEBUG;
	global $TABLE_PREFIX;

	conectarBD();

	$consulta = sprintf("SELECT idSocio,nombre,apellido1,apellido2,dni,email FROM " . $TABLE_PREFIX . "socio");

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}

	return $resultado;
}


/**
 * Busca un socio a partir del número de socio
 * @param decimal $idSocio
 * @return resource devuelte false si fue incorrecto, un array con 0 elementos si no se encontró al usuario o si se encontró, contiene: nombre, apellido1, apellido2, dni, fchaNacimiento,  email, tipoVia, direccion, portal,  escalera, piso, puerta, localidad, cp
 *
 */
function buscarSocio ($idSocio) {
	global $enlace;
	global $DEBUG;
	global $TABLE_PREFIX;

	conectarBD();

	$consulta = sprintf("SELECT * FROM " . $TABLE_PREFIX . "socio WHERE idSocio='%s'",
			$enlace->real_escape_string($idSocio)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado && $DEBUG) {
		echo "'" . $consulta . "' Devolvió " . $enlace->error;
	}

	return $resultado;
}