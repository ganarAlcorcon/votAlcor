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


function guardarImagenSubida($nombre,$nombreFichero) {
	try {
			
		// Undefined | Multiple Files | $_FILES Corruption Attack
		// If this request falls under any of them, treat it invalid.
		if (
		!isset($_FILES[$nombre]['error']) ||
		is_array($_FILES[$nombre]['error'])
		) {
			throw new RuntimeException('Invalid parameters.');
		}

		// Check $_FILES['upfile']['error'] value.
		switch ($_FILES[$nombre]['error']) {
			case UPLOAD_ERR_OK:
				break;
			case UPLOAD_ERR_NO_FILE:
				throw new RuntimeException('No file sent.');
			case UPLOAD_ERR_INI_SIZE:
			case UPLOAD_ERR_FORM_SIZE:
				throw new RuntimeException('Exceeded filesize limit.');
			default:
				throw new RuntimeException('Unknown errors.');
		}

		// You should also check filesize here.
		if ($_FILES[$nombre]['size'] > $CONFIG["MAX_IMG_SIZE"]) {
			throw new RuntimeException('Exceeded filesize limit.');
		}

		// DO NOT TRUST $_FILES['upfile']['mime'] VALUE !!
		// Check MIME Type by yourself.
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		if (false === $ext = array_search(
				$finfo->file($_FILES[$nombre]['tmp_name']),
				array(
						'jpg' => 'image/jpeg',
						'png' => 'image/png',
						'gif' => 'image/gif',
				),
				true
		)) {
			throw new RuntimeException('Invalid file format.');
		}

		// You should name it uniquely.
		// DO NOT USE $_FILES['upfile']['name'] WITHOUT ANY VALIDATION !!
		// On this example, obtain safe unique name from its binary data.
		if (!move_uploaded_file(
				$_FILES[$nombre]['tmp_name'],$CONFIG["RUTA_SUBIDAS"] . $nombreFichero . $ext)
		) {
			throw new RuntimeException('Failed to move uploaded file.');
		}

		echo 'File is uploaded successfully.';

	} catch (RuntimeException $e) {

		echo $e->getMessage();

	}
}