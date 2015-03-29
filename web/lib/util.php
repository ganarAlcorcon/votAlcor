<?php

// Variables globales
global $DEBUG;

// Parámetro DEBUG
$DEBUG = true;

global $FORMATO_FECHA;
global $FORMATO_TIEMPO;
global $FORMATO_FECHATIEMPO;
global $FORMATO_FECHABD;

$FORMATO_FECHA="d/m/Y";
$FORMATO_TIEMPO="H:i:s";
$FORMATO_FECHATIEMPO=$FORMATO_FECHA . " " . $FORMATO_TIEMPO;
$FORMATO_FECHATIEMPOBD="Y-m-d H:i:s";
$FORMATO_FECHABD="Y-m-d";

/**
 * Lee una fecha y la parsea. El tiempo se pone a 0.
 * @param string $cadenaFecha La cadena con la fecha
 * @return DateTime|NULL La fecha o NULL si ocurrió un error
 */
function leerFecha ($cadenaFecha) {
	global $FORMATO_FECHATIEMPO;
	global $DEBUG;

	/*if ($DEBUG) {
		echo "leerFecha " . $cadenaFecha;
	}*/
	
	$fecha= DateTime::createFromFormat ($FORMATO_FECHATIEMPO, $cadenaFecha . " 00:00:00") ;
	
	/*if ($DEBUG) {
		echo "fecha leida: " . var_dump($fecha);
	}*/
	
	if ($fecha && checkdate($fecha->format("m"), $fecha->format("d"), $fecha->format("Y")) && _comprobarHora($fecha)) {
		return $fecha;
	} else {
		return NULL;
	}
}

/**
 * Lee un tiempo y lo parsea
 * @param string $cadenaFecha La cadena con el tiempo
 * @return DateTime|NULL El tiempo o NULL si ocurrió un error (La fecha será la de EPOCH)
 */
function leerFechaTiempo ($cadenaTiempo) {
	global $FORMATO_FECHATIEMPO;
	
	$fecha= DateTime::createFromFormat ($FORMATO_FECHATIEMPO, $cadenaTiempo);
	if ($fecha && checkdate($fecha->format("m"), $fecha->format("d"), $fecha->format("Y")) && _comprobarHora($fecha)) {
		return $fecha;
	} else {
		return NULL;
	}
}

/**
 * Lee una fecha con tiempo y la parsea
 * @param string $cadenaFecha La cadena con la fecha y tiempo
 * @return DateTime|NULL La fecha y tiempo o NULL si ocurrió un error
 */
function leerTiempo ($cadenaTiempo) {
	global $FORMATO_TIEMPO;
	
	$fecha= DateTime::createFromFormat ($FORMATO_TIEMPO, $cadenaTiempo);
	if ($fecha && _comprobarHora($fecha)) {
		return $fecha;
	} else {
		return NULL;
	}
}

/**
 * Lee una fecha de base de datos y la parsea
 * @param string $cadenaFecha La cadena con la fecha y tiempo
 * @return DateTime|NULL La fecha y tiempo o NULL si ocurrió un error
 */
function leerFechaBD ($cadenaFecha) {
	global $FORMATO_FECHABD;
	
	$fecha= DateTime::createFromFormat ($FORMATO_FECHABD, $cadenaFecha);
	if ($fecha && _comprobarHora($fecha)) {
		return $fecha;
	} else {
		return NULL;
	}
}

/**
 * Lee una fecha de base de datos y la parsea
 * @param string $cadenaFecha La cadena con la fecha y tiempo
 * @return DateTime|NULL La fecha y tiempo o NULL si ocurrió un error
 */
function leerFechaTiempoBD ($cadenaFechaTiempo) {
	global $FORMATO_FECHATIEMPOBD;
	
	$fecha= DateTime::createFromFormat ($FORMATO_FECHATIEMPOBD, $cadenaFechaTiempo);
	if ($fecha && _comprobarHora($fecha)) {
		return $fecha;
	} else {
		return NULL;
	}
}

/**
 * Formatea una fecha
 * @param int|DateTime $fecha Fecha a convertir (UNIX o DateTime)
 * @return string|NULL Devuelve la fecha formateada o NULL si falló
 */
function formatearFecha ($fecha) {
	global $FORMATO_FECHA;
	
	return _formatearFechas ($fecha,$FORMATO_FECHA);
}

/**
 * Formatea un tiempo
 * @param int|DateTime $tiempo Tiempo a convertir (UNIX o DateTime)
 * @return string|NULL Devuelve el tiempo formateado o NULL si falló
 */
function formatearTiempo ($tiempo) {
	global $FORMATO_TIEMPO;
	
	return _formatearFechas ($fecha, $FORMATO_TIEMPO);
}

/**
 * Formatea una fecha con tiempo
 * @param int|DateTime $fecha Fecha y tiempo a convertir (UNIX o DateTime)
 * @return string|NULL Devuelve la fecha y tiempo formateada o NULL si falló
 */
function formatearTiempoFecha ($fecha) {
	global $FORMATO_FECHATIEMPO;
	
	return _formatearFechas ($fecha, $FORMATO_FECHATIEMPO);
}

/**
 * Formatea fechas al formato de la base de datos
 * @param int|DateTime $fecha Fecha a convertir (UNIX o DateTime)
 * @return string|NULL Devuelve la fecha formateada o NULL si falló
 */
function formatearFechaBD ($fecha) {
	global $FORMATO_FECHABD;
	
	return _formatearFechas ($fecha, $FORMATO_FECHABD);
}

/**
 * Formatea fechas al formato de la base de datos
 * @param int|DateTime $fecha Fecha a convertir (UNIX o DateTime)
 * @return string|NULL Devuelve la fecha formateada o NULL si falló
 */
function formatearFechaTiempoBD ($fecha) {
	global $FORMATO_FECHATIEMPOBD;
	
	return _formatearFechas ($fecha, $FORMATO_FECHATIEMPOBD);
}

/**
 * Parsea fechas en el formato indicado
 * @param int|DateTime $fecha Fecha a convertir (UNIX o DateTime)
 * @param string $formato cadena de formato igual que las usadas para date
 * @return string|NULL
 */
function _formatearFechas ($fecha, $formato) {
	global $DEBUG;
	
	if (is_object($fecha) && get_class($fecha)==="DateTime") {
		return $fecha->format($formato);
	//TODO Faltaría el array
	} else if (is_int($fecha)) {
		return date ($cadenaFecha, $formato);
	} else {
		return NULL;
	}
}

/**
 * Comprueba que las horas son correctas
 * @param DateTime $fecha
 * @return boolean Devuelve true si es correcta y false si no lo es
 */
function _comprobarHora(DateTime $fecha) {
	return $fecha->format("H") >=0 && $fecha->format("H") <=23 && $fecha->format("i") >=0 && $fecha->format("i") <=59 && $fecha->format("s") >=0 && $fecha->format("s") <=59;
}

$PROVINCIA = array("DESCONOCIDA", "Áraba/Álava","Albacete","Alacant/Alicante","Almería","Ávila","Badajoz","Illes Balears/Islas Baleares","Barcelona","Burgos","Cáceres","Cádiz","Castelló/Castellón","Ciudad Real","Córdoba","A Coruña","Cuenca","Girona","Granada","Guadalajara","Gipuzkoa","Huelva","Huesca","Jaén","León","Lleida","La Rioja","Lugo","Madrid","Málaga","Murcia","Navarra","Ourense","Asturias","Palencia","Las Palmas","Pontevedra","Salamanca","S. C. Tenerife","Cantabria","Segovia","Sevilla","Soria","Tarragona","Teruel","Toledo","València/Valencia","Valladolid","Bikaia/Vizcaya","Zamora","Zaragoza","Ceuta","Melilla");

/**
 * Devuelve el nombre de una provincia a partir de un código postal
 * @param unknown $codPostal
 * @return unknown
 */
function provincia ($codPostal) {
	global $PROVINCIA;
	
	$codProvincia=  substr($codPostal,0,2) + 0;
	return $PROVINCIA[$codProvincia];
}


/**
 * Genera un texto aleatorio del tamaño $tam. El texto estará separado en grupos de $grupos caracteres si se especifica.
 * @param number $tam Tamaño total de la cadena (incluidos separadores)
 * @param number $grupos e.g.: si $grupos es 4 -> abcd-efgh-ijkl
 * @param string $separador caracter separador ¡Debe ser un solo caracter!
 * @return la cadena de texto aleatoria generada
 */
function textoAleatorio ($tam, $grupos = 0, $separador = "c") {
	if ($grupos < 1) {
		$grupos = 0;
	} else {
		//Sumamos uno para que mod funcione correctamente
		$grupos += 1;
	}
	$cadena="";
	for ($i=0; $i<$tam;$i++) {
		if ($grupos != 0 && (($i+1) % $grupos == 0)) {
			$cadena = $cadena . $separador;
		} else {
			$cadena = $cadena . caracterAleatorio();
		}
	}
	
	return $cadena;
}

/**
 * @return Devuelve un caracter aleatorio [0-9a-zA-Z]
 */
function caracterAleatorio() {
	$num= mt_rand(48, 109);
	if ($num > 57) {
		$num += 7;
	}
	if ($num > 90) {
		$num += 6;
	}
	return chr($num);
}