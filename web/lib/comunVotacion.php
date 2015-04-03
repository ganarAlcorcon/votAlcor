<?php

/**
 * Recupera todas las mesas
 */
function recuperaMesas() {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	conectarBD();
	$consulta = "SELECT * FROM " . $TABLE_PREFIX . "MESAS";
	
	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if ($resultado) {
		$mesas= array();
		while ($mesa= $resultado->fetch_assoc()) {
			$mesas[$mesa["ID"]]= $mesa;
		}
		return $mesas;
	}
	
	return false;
}


function votaEnMesa($nombre, $apellido1, $apellido2 ,$nif, $fchaNac, $mesa) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	conectarBD();
	
	$fchaNac= formatearFechaBD($fchaNac);
	$consulta = sprintf ("SELECT C.*, M.NOMBRE AS NOMBRE_MESA FROM " . $TABLE_PREFIX . "CENSO C LEFT OUTER JOIN " . $TABLE_PREFIX . "MESAS M ON M.ID = C.ID_MESA WHERE upper(C.NOMBRE)=%s AND upper(C.APELLIDO1)=%s AND C.FECHA_NACIMIENTO=%s AND C.HA_VOTADO IS NOT NULL",
			prepararCampo(mb_strtoupper($nombre,"UTF-8")),
			prepararCampo(mb_strtoupper($apellido1,"UTF-8")),
			prepararCampo($fchaNac)
	);
	if (isset($apellido2) && trim($apellido2) != "") {
		$consulta= $consulta . " AND upper(C.APELLIDO2)=" . prepararCampo(mb_strtoupper($apellido2,"UTF-8"));
	}
	if (isset($nif) && trim($nif) != "") {
		$consulta= $consulta . " AND upper(C.NIF)=" . prepararCampo(mb_strtoupper($nif,"UTF-8"));
	}
	
	$puedeVotar= false;
	$mensajeError= "";
	
	$resultado = $enlace->query($consulta);
	if ($resultado) {
		if ($yaVoto= $resultado->fetch_assoc()) {
			$mensajeError= $yaVoto["NOMBRE"] . " " . $yaVoto["APELLIDO1"] . " ya votó en la mesa " . $yaVoto["NOMBRE_MESA"] . " a las " . formatearTiempoFecha(leerFechaTiempoBD($yaVoto["HA_VOTADO"]));
		} else {
			
			$insert = "INSERT INTO " . $TABLE_PREFIX . "CENSO(NOMBRE,APELLIDO1,FECHA_NACIMIENTO,ID_VOTACION,ID_MESA,HA_VOTADO";
					
			$values = sprintf (") VALUES (%s,%s,%s,%d,%d,NOW()",
					prepararCampo($nombre),
					prepararCampo($apellido1),
					prepararCampo($fchaNac),
					$CONFIG["VOTACION"],
					$mesa
			);

			if (isset($apellido2) && trim($apellido2) != "") {
				$insert= $insert . ",APELLIDO2";
				$values= $values . "," . prepararCampo($apellido2);
			}
			if (isset($nif) && trim($nif) != "") {
				$insert= $insert . ",NIF";
				$values= $values . "," . prepararCampo($nif);
			}
			
			$resultado = $enlace->query($insert . $values . ")");
			if ($resultado) {
				$puedeVotar= true;
			} else {
				$mensajeError= "Ocurrió un error al inscribir en el censo";
				if ($DEBUG) {
					$mensajeError= $mensajeError . " " . $insert . $values . "). Devolvió " . $enlace->error;
				}
				trazar ("Error al inscribir en el censo " . $insert . $values . "). Devolvió " . $enlace->error);
			}
		}
	} else {
		$mensajeError= "Ocurrió un error al buscar en el censo";
		if ($DEBUG) {
			$mensajeError= $mensajeError . " " . $consulta . ". Devolvió " . $enlace->error;
		}
		trazar ("Error al consultar el censo " . $consulta . ". Devolvió " . $enlace->error);
	}
	return array("puedeVotar" => $puedeVotar, "mensajeError" => $mensajeError);
}