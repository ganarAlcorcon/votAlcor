<?php


session_start();
if (isset($_REQUEST['_SESSION'])) die("Inyección de sesión detectada");

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


function votaEnMesaNif($nif, $mesa) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	conectarBD();
	
	$fchaNac= formatearFechaBD($fchaNac);
	$consulta = sprintf ("SELECT C.*, M.NOMBRE AS NOMBRE_MESA FROM " . $TABLE_PREFIX . "CENSO C LEFT OUTER JOIN " . $TABLE_PREFIX . "MESAS M ON M.ID = C.ID_MESA WHERE upper(C.NIF)=%s AND C.HA_VOTADO IS NOT NULL",
			prepararCampo($nif)
	);
	
	$puedeVotar= false;
	$mensajeError= "";
	
	$resultado = $enlace->query($consulta);
	if ($resultado) {
		if ($yaVoto= $resultado->fetch_assoc()) {
			$mensajeError= $yaVoto["NIF"] . " ya votó en la mesa " . $yaVoto["NOMBRE_MESA"] . " a las " . formatearTiempoFecha(leerFechaTiempoBD($yaVoto["HA_VOTADO"]));
		} else {
			
			$insert = "INSERT INTO " . $TABLE_PREFIX . "CENSO(NIF,ID_VOTACION,ID_MESA,HA_VOTADO";
					
			$values = sprintf (") VALUES (%s,%d,%d,NOW()",
					prepararCampo($nif),
					$CONFIG["VOTACION"],
					$mesa
			);

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


function borrarCensoError($nombre, $apellido1, $apellido2 ,$nif, $fchaNac, $mesa) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	$fchaNac= formatearFechaBD($fchaNac);
	$consulta = sprintf ("DELETE FROM " . $TABLE_PREFIX . "CENSO WHERE NOMBRE=%s AND APELLIDO1=%s AND FECHA_NACIMIENTO=%s AND ID_VOTACION=%d AND ID_MESA=%d AND HA_VOTADO IS NOT NULL",
			prepararCampo($nombre),
			prepararCampo($apellido1),
			prepararCampo($fchaNac),
			$CONFIG["VOTACION"],
			$mesa
	);
	
	if (isset($apellido2) && trim($apellido2) != "") {
		$consulta= $consulta . " AND APELLIDO2=" . prepararCampo($apellido2);
	}
	if (isset($nif) && trim($nif) != "") {
		$consulta= $consulta . " AND NIF=" . prepararCampo($nif);
	}
		
	$resultado = $enlace->query($consulta);
	if (!$resultado) {
		trazar ("Error grave al borrar un votante del censo por error: " . $consulta . "). Devolvió " . $enlace->error);
	}
}

function votaEnWeb($simpatizante, $cabezaLista, $listaGeneral) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;
	
	$votoCorrecto= false;
	$mensajeError= "";
	
	//Comprobar la entrada
	$entradaCorrecta=true;
	$candidatos= listaCandidatos();
	
	if (!isset($candidatos[$cabezaLista])) {
		$entradaCorrecta=false;
		$mensajeError= "Error en el cabeza de lista";
		if ($DEBUG) {
			$mensajeError= $mensajeError . ": " . $cabezaLista;
		}
		trazar("Error con el valor del cabeza de lista: " . $cabezaLista);
	}
	
	foreach ($listaGeneral as $cand) {
		if (!isset($candidatos[$cand])) {
			$entradaCorrecta=false;
			$mensajeError= "Error en un elemento de la lista general";
			if ($DEBUG) {
				$mensajeError= $mensajeError . ": " . $cand;
			}
			trazar("Error en un elemento de la lista general: " . $cand);
		}
	}
	
	if ($entradaCorrecta) {
		$resultado= votaEnMesa($simpatizante["NOMBRE"], $simpatizante["APELLIDO1"], $simpatizante["APELLIDO2"] ,$simpatizante["NIF"], leerFechaBD($simpatizante["FECHA_NACIMIENTO"]), $CONFIG["ID_MESA_WEB"]);
		
		if ($resultado["puedeVotar"]) {
			$resultado= insertarVotoWeb($simpatizante["ID"], $cabezaLista, $listaGeneral);
			if ($resultado["correcto"]) {
				$votoCorrecto= true;
			} else {
				$mensajeError= $resultado["mensajeError"];
				borrarCensoError($simpatizante["NOMBRE"], $simpatizante["APELLIDO1"], $simpatizante["APELLIDO2"] ,$simpatizante["NIF"], leerFechaBD($simpatizante["FECHA_NACIMIENTO"]), $CONFIG["ID_MESA_WEB"]);
			}
		} else {
			$mensajeError= "Error en el censo de votantes: " . $resultado["mensajeError"];
		}
	}

	return array("votoCorrecto" => $votoCorrecto, "mensajeError" => $mensajeError);
}


function insertarVotoWeb($idSimpatizante, $cabezaLista, $listaGeneral) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;
	
	$correcto= false;
	$mensajeError= "";
	
	$general=join(";",$listaGeneral);
	
	conectarBD();
	
	$consulta = sprintf ("INSERT INTO " . $TABLE_PREFIX . "VOTOS (ID_SIMPATIZANTE, CABEZA_LISTA, RESTO_LISTA) VALUES(%d,%s,%s);",
			$idSimpatizante,
			prepararCampo($cabezaLista),
			prepararcampo($general)
	);
	
	$resultado = $enlace->query($consulta);
	if ($resultado) {
		$correcto= true;
	} else {
		$mensajeError= "Ocurrió un error al insertar el voto";
		if ($DEBUG) {
			$mensajeError= $mensajeError . " " . $consulta . ". Devolvió " . $enlace->error;
		}
		trazar ("Error al insertar el voto " . $consulta . ". Devolvió " . $enlace->error);
	}
	
	return array("correcto" => $correcto, "mensajeError" => $mensajeError);
}

function recuperaVotacion($idVotacion) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;
	
	conectarBD();
	
	$consulta = sprintf ("SELECT * FROM " . $TABLE_PREFIX . "VOTACIONES WHERE ID=%d",
			$idVotacion
	);
	
	$resultado = $enlace->query($consulta);
	if ($resultado) {
		if ($votacion= $resultado->fetch_assoc()) {
			return $votacion;
		}
	} else {
		error_log ("Error al recuperar la votacion " . $consulta . "). Devolvió " . $enlace->error);
	}
	
	return false;
}

function comprobarFechasVotacion($votacion) {
	$ahora= new DateTime();
	$inicio=leerFechaTiempoBD($votacion["INICIO"]);
	$fin=leerFechaTiempoBD($votacion["FIN"]);
	
	return $ahora >= $inicio && $ahora <= $fin;	
}

function codigoEmail ($email) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;
	
	
	$puedeVotar= false;
	$mensajeError="";
	
	conectarBD();
	
	$consulta = sprintf ("SELECT * FROM " . $TABLE_PREFIX . "SIMPATIZANTES WHERE upper(EMAIL)=upper(%s) AND FECHA_BAJA IS NULL",
			prepararCampo($email)
	);
	
	$resultado = $enlace->query($consulta);
	if ($resultado) {
		if ($simpatizante= $resultado->fetch_assoc()) {
			if ($simpatizante["EMAIL_V"] != 'S') {
				$mensajeError= "El email no fue validado";
			} else if ($simpatizante["DOCUMENTO_V"] != 'S') {
				$mensajeError= "El documento enviado no fue validado";
			} else if (generarVerificacionMailVotar($simpatizante["ID"],$email)) {
				$_SESSION["simpatizante"]= $simpatizante;
				$puedeVotar= true;
			} else {
				$mensajeError= "Ocurrió un error al enviar el email";
			}
		} else {
			$mensajeError= "El email no corresponde a ningún simpatizante registrado.";
			if ($DEBUG) {
				$mensajeError= $mensajeError . " " . $consulta . ". Sin resultados";
			}
		}
	} else {
		$mensajeError= "Ocurrió un error al buscar el email";
		if ($DEBUG) {
			$mensajeError= $mensajeError . " " . $consulta . ". Devolvió " . $enlace->error;
		}
	}
	
	return array("puedeVotar" => $puedeVotar, "mensajeError" => $mensajeError);
}


function generarVerificacionMailVotar ($idSimpatizante, $email) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	conectarBD();

	$clave= textoAleatorio($CONFIG["V_TAMANO"],$CONFIG["V_GRUPOS"],$CONFIG["V_SEPARADOR"]);
	
	if ($DEBUG) {
		echo "clave email: ". $clave;
	}

	//TODO: Comprobar que no existe la clave

	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "VERIFICACIONES (ID_SIMP,CLAVE,TIPO) VALUES (%d, %s, 'V')",
			$idSimpatizante,
			prepararCampo($clave)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if (!$resultado) {
		trazar("Error al guardar verificación al votar de: " . $idSimpatizante. " ".$consulta. " -> ".$enlace->error);
	} else {
		enviarMail($email,'Código para votar en las primarias de Ganar Alcorcón','
<html>
<head>
  <title>Código de la votación</title>
</head>
<body>
  <p>A continuación le presentamos el código para votar en las primarias de Ganar Alcorcón</p>
  <p>Código:</p>
  <p><span style="background-color:#bbb;margin:10px 50%; padding:20px">' . $clave . '</span></p>
  <p>No responda a este correo, la dirección no admite correo entrante. Si usted no se ha intentado votar, puede ignorar este email.</p>
  <p></p>
  <p>Ganar Alcorcón</p>
</body>
</html>
');
	}
	
	return $resultado;
}


function codigoSMS ($telefono) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;


	$puedeVotar= false;
	$mensajeError="";

	conectarBD();

	$consulta = sprintf ("SELECT * FROM " . $TABLE_PREFIX . "SIMPATIZANTES WHERE TELEFONO=%s AND FECHA_BAJA IS NULL",
			prepararCampo($telefono)
	);

	$resultado = $enlace->query($consulta);
	if ($resultado) {
		if ($simpatizante= $resultado->fetch_assoc()) {
			if (generarVerificacionSMSVotar($_SESSION["simpatizante"]["ID"],$telefono)) {
				$puedeVotar= true;
			} else {
				$mensajeError= "Ocurrió un error al enviar el SMS";
			}
		} else {
			$mensajeError= "El teléfono no corresponde a ningún simpatizante registrado.";
			if ($DEBUG) {
				$mensajeError= $mensajeError . " " . $consulta . ". Devolvió resultado vacío";
			}
		}
	} else {
		$mensajeError= "Ocurrió un error al buscar el teléfono";
		if ($DEBUG) {
			$mensajeError= $mensajeError . " " . $consulta . ". Devolvió " . $enlace->error;
		}
	}

	return array("puedeVotar" => $puedeVotar, "mensajeError" => $mensajeError);
}


function generarVerificacionSMSVotar ($idSimpatizante, $telefono) {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	global $DEBUG;

	conectarBD();

	$clave= textoAleatorio($CONFIG["T_TAMANO"],$CONFIG["T_GRUPOS"],$CONFIG["T_SEPARADOR"]);

	if ($DEBUG) {
		echo "clave SMS: ". $clave;
	}

	//TODO: Comprobar que no existe la clave

	$consulta = sprintf("INSERT INTO " . $TABLE_PREFIX . "VERIFICACIONES (ID_SIMP,CLAVE,TIPO) VALUES (%d, %s, 'T')",
			$idSimpatizante,
			prepararCampo($clave)
	);

	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);

	if (!$resultado) {
		trazar("Error al guardar verificación al votar de: " . $idSimpatizante. " ".$consulta. " -> ".$enlace->error);
	} else {
		$resultado= enviarSMS($telefono,'Su código para votar en primarias Ganar Alcorcón es: ' . $clave);
		if ($resultado) {
			return true;
		}
	}

	return false;
}


function listaCandidatos() {
	global $CONFIG;
	global $enlace;
	global $TABLE_PREFIX;
	
	conectarBD();
	
	$consulta ="SELECT * FROM " . $TABLE_PREFIX . "CANDIDATOS";
		
	// Ejecutar la consulta
	$resultado = $enlace->query($consulta);
	
	if ($resultado) {
		$candidatos= array();
		while ($candidato= $resultado->fetch_assoc()) {
			$candidatos[$candidato["ID"]]= $candidato;
		}
		shuffle_assoc($candidatos);
		return $candidatos;
	}
	
	return false;
}
