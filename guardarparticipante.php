<?php

	//echo "guardarparticipante.php";
	include('sqlconnector.php');

	$idusuario = filter_var($_POST["idusuario"], FILTER_VALIDATE_INT);
	
	// ═══════════════════════════════════════════════════════════════════════
	// VALIDACIONES DE SEGURIDAD - PHP 8+ Compatible
	// ═══════════════════════════════════════════════════════════════════════
	
	// Validar y sanitizar nombres (solo letras, espacios, acentos, ñ)
	$nombre = trim($_POST["nombre"]);
	$paterno = trim($_POST["paterno"]);
	$materno = trim($_POST["materno"]);
	
	// Función para validar solo letras y espacios
	function validarSoloLetras($texto, $campo) {
		if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+$/u', $texto)) {
			die(json_encode([
				'success' => false,
				'error' => "El campo '$campo' solo puede contener letras, espacios y guiones."
			]));
		} 
		return $texto;
	}
	
	// Validar campos de texto (nombres/apellidos)
	if (!empty($nombre)) {
		$nombre = validarSoloLetras($nombre, 'Nombre');
	}
	if (!empty($paterno)) {
		$paterno = validarSoloLetras($paterno, 'Apellido Paterno');
	}
	if (!empty($materno)) {
		$materno = validarSoloLetras($materno, 'Apellido Materno');
	}
	
	$area = trim($_POST["area"]);
	$genero = trim($_POST["genero"]);
	$fecha_nacimiento = $_POST["fecha_nacimiento"];
	
	$categoria = filter_var($_POST["categoria"], FILTER_VALIDATE_INT);
	
	// Validar correo electrónico
	$correo = filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL);
	if ($correo === false && !empty($_POST["correo"])) {
		die(json_encode([
			'success' => false,
			'error' => 'El correo electrónico no tiene un formato válido.'
		]));
	}
	
	$titulo = trim($_POST["titulo"]);
	$sobrenombre = trim($_POST["sobrenombre"]);
	$nombre_obra = trim($_POST["nombre_obra"]);
	$curp = strtoupper(trim($_POST["curp"] ?? ''));
	$edad = trim($_POST["edad"]);
	$clave_elector = trim($_POST["clave_elector"]);
	$paterno_tutor = trim($_POST["paterno_tutor"] ?? '');
	$materno_tutor = trim($_POST["materno_tutor"] ?? '');
	$nombre_tutor = trim($_POST["nombre_tutor"] ?? '');

	$tutor = trim($nombre_tutor) . "|" . trim($paterno_tutor) . "|" . trim($materno_tutor);

	$manifestacion1 = $_POST["manifestacion1"];
	$manifestacion2 = $_POST["manifestacion2"];
	$manifestacion3 = $_POST["manifestacion3"];
	$manifestacion4 = $_POST["manifestacion4"];
	$manifestacion5 = $_POST["manifestacion5"];
	$manifestacion6 = $_POST["manifestacion6"];
	$manifestacion7 = $_POST["manifestacion7"];

	
	// Validar teléfonos (solo números, máximo 12 dígitos)
	$tel1 = preg_replace('/[^0-9]/', '', trim($_POST["tel1"]));
	$tel2 = preg_replace('/[^0-9]/', '', trim($_POST["tel2"]));
	
	// Limitar a 12 dígitos
	$tel1 = substr($tel1, 0, 12);
	$tel2 = substr($tel2, 0, 12);
	
	// Validar longitud de teléfonos (mínimo 10, máximo 12)
	if (!empty($tel1) && (strlen($tel1) < 10 || strlen($tel1) > 12)) {
		die(json_encode([
			'success' => false,
			'error' => 'El teléfono de casa debe tener entre 10 y 12 dígitos.'
		]));
	}
	if (!empty($tel2) && (strlen($tel2) < 10 || strlen($tel2) > 12)) {
		die(json_encode([
			'success' => false,
			'error' => 'El teléfono celular debe tener entre 10 y 12 dígitos.'
		]));
	}
	
	$alcaldia = filter_var($_POST["alcaldia"], FILTER_VALIDATE_INT);
	$entidad = trim($_POST["entidad"]);
	$domicilio = trim($_POST["domicilio"]);
	$te_enteraste = filter_var($_POST["te_enteraste"], FILTER_VALIDATE_INT);
	$distrito = filter_var($_POST["distrito"], FILTER_VALIDATE_INT);
	
	// Validar formato CURP (si se proporcionó)
	// if (!empty($curp)) {
	// 	if (!preg_match('/^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/', $curp)) {
	// 		die(json_encode([
	// 			'success' => false,
	// 			'error' => 'CURP no válido. Debe tener el formato correcto alfanumérico (18 caracteres).'
	// 		]));
	// 	}
	// }



	$enlace = "main";
	
	$fecha_actual = date("Y-m-d H:i:s");
	
	$query = "INSERT INTO " . BD_PARTICIPANTES . " (idusuario, nombre, paterno, materno, sobrenombre, fecha_nacimiento, edad,
	    genero, correo, tel1, tel2, titulo, tutor, categoria, iddistrito, fecha_alta, fecha_modifica, alcaldia, entidad,
	    te_enteraste, curp, clave_elector, nombre_obra, manifesta1, manifesta2, manifesta3, manifesta4, manifesta5, manifesta6, manifesta7) 
	    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

		$params = array(
	    $idusuario, $nombre, $paterno, $materno, $sobrenombre, $fecha_nacimiento, $edad,
	    $genero, $correo, $tel1, $tel2, $titulo, $tutor, $categoria,
	    $distrito, $fecha_actual, $fecha_actual, $alcaldia, $entidad,
	    $te_enteraste,  $curp, $clave_elector, $nombre_obra, $manifestacion1, $manifestacion2, $manifestacion3, $manifestacion4, $manifestacion5, $manifestacion6, $manifestacion7);

//		echo $query;
	$row = sqlsrv_query($conn, $query, $params);
	if ($row === false) {
		echo 'ERROR: ' . print_r(sqlsrv_errors(), true);
		exit;
	}

	echo 'OK';
?>