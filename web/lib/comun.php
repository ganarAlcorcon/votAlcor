<?php
if (!(include_once 'util.php')) {
	die ("Falta el archivo util.php");
}

// Variables globales
global $enlace;
global $DEBUG;
global $TABLE_PREFIX;
global $CONFIG;

//Leemos el fichero de configuración

//Constantes
$CONFIG=parse_ini_file("config.ini");
$TABLE_PREFIX = $CONFIG["TABLE_PREFIX"];




/**
 * Conectarse a la base de datos, setea $bd
 */
function conectarBD () {
	global $enlace;
	global $DEBUG;
	global $CONFIG;
	
	if (!$enlace) {
		$enlace= new mysqli($CONFIG["DB_HOST"],$CONFIG["DB_USER"],$CONFIG["DB_PASSWD"],$CONFIG["DB_NAME"]);

		if (!$enlace) {
			echo <<<EOT
<h1 class="error">Ocurrió un error al acceder a la base de datos (A002)</h1>
EOT;
			if ($DEBUG) {
				echo $enlace->error;
			}
		} else {
			if ($enlace->connect_errno) {
				echo "Error de conexión: " . $enlace->connect_error;
			} else {
				$enlace->set_charset("utf8");
			}
		}
	}
}

