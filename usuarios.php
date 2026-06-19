<?php
ob_start();
session_start();

if (!isset($_POST['concurso'])) {
	header('Location: index.php');
}

include('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');
include('phpmailer/PHPMailerAutoload.php');
include('sqlconnector.php');
include('rutasitio.php');

$message = '';
$action = '';
$pass1 = '';
$count_usuario = '';

$concurso = $_POST['concurso'];

if (isset($_POST['action'])) {
	$action = $_POST['action'];

	$nombre = $_POST['nombre'];
	$paterno = $_POST['paterno'];
	$materno = $_POST['materno'];
	$curp = $_POST['curp'] ?? '';
	$user = $_POST['user'];
	$correo = $_POST['correo'];
	$genero = $_POST['genero'];
	$fecha_nacimiento = $_POST['fecha_nacimiento'];

	// ✅ PHP 8+ Compatible: Prepared statement para prevenir SQL injection
	$sql_usuario = 'SELECT usuario, correo FROM ' . BD_USUARIOS . ' WHERE usuario = ? OR correo = ?';
	$res_count = sqlsrv_query($conn, $sql_usuario, [$user, $correo]);

	while ($res_count && ($row = sqlsrv_fetch_array($res_count, SQLSRV_FETCH_ASSOC))) {
		if ($user === $row['usuario']) {
			ob_clean();
			echo '1';
			exit;
		}

		if ($correo === $row['correo']) {
			ob_clean();
			echo '2';
			exit;
		}
	}

	$area = $_POST['area'];
	$pass1 = $_POST['pass1'];

	// Validar captcha server-side
	$captcha_usuario = strtolower(trim($_POST['tmptxt'] ?? ''));
	$captcha_sesion = strtolower(trim($_SESSION['tmptxt'] ?? ''));

	// Limpiar el captcha de la sesión inmediatamente (para evitar reutilización)
	unset($_SESSION['tmptxt']);

	if (empty($captcha_usuario) || empty($captcha_sesion) || $captcha_usuario !== $captcha_sesion) {
		ob_clean();
		echo '3';
		exit;
	}

	if ($action === 'registrar') {
		// ✅ PHP 8+ Compatible: Prepared statement para prevenir SQL injection
		$query = 'INSERT INTO ' . BD_USUARIOS . ' (nombre, paterno, materno, curp, area, genero, fecha_nacimiento, correo, usuario, contrasena, perfil, estatus, fecha_alta) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)';
		$params = [
			$nombre,
			$paterno,
			$materno,
			$curp,
			$area,
			$genero,
			$fecha_nacimiento,
			$correo,
			$user,
			$pass1,
			'1',
			'1',
			date('Y-m-d H:i:s') // ✅ Formato 24hrs sin 'a' (am/pm)
		];
		$row = sqlsrv_query($conn, $query, $params);

		// ✅ PHP 8+ Compatible: Obtener último ID insertado
		$query2 = 'SELECT max(idusuario) AS ultimo FROM ' . BD_USUARIOS;
		$row2 = sqlsrv_query($conn, $query2);

		if ($row2 && ($res2 = sqlsrv_fetch_array($row2, SQLSRV_FETCH_ASSOC))) {
			$idusr = $res2['ultimo'];
		}

		// ✅ PHP 8+ Compatible: Prepared statement
		$query3 = 'SELECT * FROM ' . BD_USUARIOS . ' WHERE idusuario = ?';
		$row3 = sqlsrv_query($conn, $query3, [$idusr]);

		while ($row3 && ($res3 = sqlsrv_fetch_array($row3, SQLSRV_FETCH_ASSOC))) {
			$nombre_c = $res3['nombre'];
			$paterno_c = $res3['paterno'];
			$materno_c = $res3['materno'];
			$user_c = $res3['usuario'];
			$pass1 = $res3['contrasena'];
			$email = $res3['correo'];
		}

		$mail_error = '';
		if ($row) {
			$usuario_creado = true;
		} else {
			$usuario_creado = false;
		}

		if ($usuario_creado) {
			$mail = new PHPMailer(true);
			// ✅ PHP 8+ Compatible: Métodos actualizados (minúsculas)
			$mail->isSMTP();
			$mail->CharSet = 'UTF-8';
			$mail->SMTPAuth = false;
			$mail->Host = '145.0.40.63';
			$mail->Username = 'actividadesccycp@iecm.mx';
			$mail->Password = 's1s3c0m';
			$mail->Port = 25;
			$mail->setFrom('no-reply@iecm.mx', 'Instituto Electoral de la Ciudad de México');

			$id_64 = base64_encode($idusr);

			$html = '<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:Arial,Helvetica,sans-serif;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0f2f5;padding:30px 10px;">
  <tr>
    <td align="center">
      <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.13);">

        <!-- ── CABECERA ── -->
        <tr>
          <td bgcolor="#2c5364" style="background:#2c5364;background:linear-gradient(135deg,#0f2027 0%,#2c5364 55%,#4A9FD5 100%);padding:36px 40px 28px;text-align:center;">
            <img src="' . URL_CONCU . 'img/IECM-1.png" width="80" height="80" alt="IECM" style="display:block;margin:0 auto 14px;">
            <h1 style="margin:0 0 6px;color:#ffffff;font-size:26px;font-weight:700;letter-spacing:.02em;"><font color="#ffffff">¡Bienvenido/a!</font></h1>
            <p style="margin:0;color:#bfdbfe;font-size:14px;letter-spacing:.03em;"><font color="#bfdbfe">Instituto Electoral de la Ciudad de México</font></p>
            <div style="margin:18px auto 0;width:60px;height:3px;background:#4A9FD5;border-radius:2px;"></div>
          </td>
        </tr>

        <!-- ── CUERPO ── -->
        <tr>
          <td style="background:#ffffff;padding:32px 40px 10px;">

            <p style="margin:0 0 18px;font-size:16px;color:#1e293b;">
              Estimado/a <strong>' . htmlspecialchars($nombre_c . ' ' . $paterno_c . ' ' . $materno_c, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</strong>,

            <p style="margin:0 0 14px;font-size:14px;color:#374151;line-height:1.7;">
              Agradecemos tu registro como persona participante en el sistema del <strong>Concurso Juvenil de Ensayo 2026 “Conversando con los clásicos”</strong>. 
              Para continuar con tu inscripción deberás ingresar al sistema con tu nombre de persona usuaria y contraseña.
            </p>

            <p style="margin:0 0 24px;font-size:14px;color:#374151;line-height:1.7;">
              Es importante que, previo a tu solicitud de registro como participante, cuentes con toda la información
              y documentación señalada en la Convocatoria, ya que tu registro depende de ello.
            </p>

            <!-- Bloque de credenciales -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
              <tr>
                <td bgcolor="#0f2027" style="background:#0f2027;border-radius:10px;padding:20px 24px;">
                  <p style="margin:0 0 10px;color:#5B9CB8;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;"><font color="#5B9CB8">Tu nombre de persona usuaria y contraseña son:</font></p>
                  <p style="margin:0 0 8px;font-family:monospace;font-size:15px;color:#e2e8f0;">
                    <font color="#94a3b8">Persona Usuaria:&nbsp;&nbsp;&nbsp;</font>
                    <strong><font color="#4A9FD5">' . htmlspecialchars($user_c, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</font></strong>
                  </p>
                  <p style="margin:0;font-family:monospace;font-size:15px;color:#e2e8f0;">
                    <font color="#94a3b8">Contraseña: </font>
                    <strong><font color="#4A9FD5">' . htmlspecialchars($pass1, ENT_QUOTES | ENT_HTML5, 'UTF-8') . '</font></strong>
                  </p>
                </td>
              </tr>
            </table>

       
            <!-- Botón CTA -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
              <tr>
                <td align="center">
                  <table border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td bgcolor="#4A9FD5" style="background:#4A9FD5;background:linear-gradient(135deg,#4A9FD5,#2E86AB);border-radius:30px;">
                        <a href="' . URL_CONCU . 'index.php"
                           style="display:inline-block;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;letter-spacing:.03em;padding:13px 36px;border-radius:30px;">
                          <font color="#ffffff">Iniciar sesión &rarr;</font>
                        </a>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>

          </td>
        </tr>

        <!-- ── FOOTER ── -->
        <tr>
          <td style="background:#f8f9fb;border-top:1px solid #e2e8f0;padding:20px 40px;text-align:center;">
            <p style="margin:0 0 4px;font-size:12px;color:#6b7280;">
              <strong style="color:#2E86AB;">Instituto Electoral de la Ciudad de México</strong>
            </p>
            <p style="margin:0;font-size:11px;color:#9ca3af;line-height:1.7;">
              Huizaches 25 &bull; Rancho Los Colorines &bull; Tlalpan &bull; C.P. 14386 &bull; Ciudad de M&eacute;xico<br>
              Conmutador: (55) 5483 3800 &bull; <a href="https://www.iecm.mx" style="color:#4A9FD5;text-decoration:none;">www.iecm.mx</a>
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>

</body>
</html>';

			try {
				$destinatario1 = 'concursos@iecm.mx';
				// ✅ PHP 8+ Compatible: Métodos actualizados
				$mail->addAddress($email);
				$mail->addBCC('concursos@iecm.mx');
				$mail->isHTML(true);
				$mail->Subject = 'Concurso de Ensayo 2026';
				$mail->Body = $html;
				$exito = $mail->send();
			} catch (Exception $e) {
				$mail_error = $e->getMessage();
				// Log error en producción
				error_log('Error envío correo: ' . $e->getMessage());
			}
			$mail->clearAddresses();
		}

		ob_clean();
		if ($usuario_creado) {
			echo 'SUCCESS:' . (int)$idusr;
		} else {
			echo 'FAIL';
		}
		exit;
	}
} else {
?>

	<!DOCTYPE html>
	<html lang="es">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Concurso de Ensayo 2026</title>

		<!-- Bootstrap core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		<link href="css/mycss.css" rel="stylesheet">
		<link href="css/all.css" rel="stylesheet">
		<link href="css/header.css" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css2?family=Bakbak+One&family=Funnel+Sans:wght@400;600;700&display=swap" rel="stylesheet">

		<!-- Bootstrap core JavaScript -->
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/popper.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/funcionesajax_test.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<script src="js/validaciones.js"></script>

		<style>
			body {
				font-family: 'Funnel Sans', sans-serif;
				background: #f0f4f8;
			}

			/* ── form sections ── */
			.form-section {
				border-radius: 10px;
				overflow: hidden;
				box-shadow: 0 2px 10px rgba(0, 0, 0, .10);
				margin-bottom: 22px;
			}

			.form-section__header {
				display: flex;
				align-items: center;
				gap: 10px;
				padding: 12px 18px;
				color: #fff;
				font-weight: 700;
				font-size: 1rem;
			}

			.form-section__header i {
				font-size: 1.1rem;
				opacity: .9;
			}

			.form-section__body {
				padding: 20px 24px;
				background: #fff;
			}

			.form-section .form-control {
				border: 1.5px solid #d1d5db;
				border-radius: 8px;
				padding: 9px 13px;
				font-size: .93rem;
				transition: border-color .2s, box-shadow .2s;
			}

			.form-section .form-control:focus {
				border-color: #4A9FD5;
				box-shadow: 0 0 0 3px rgba(74, 159, 213, .12);
				outline: none;
			}

			.form-section .control-label {
				font-weight: 600;
				color: #374151;
				font-size: .88rem;
				padding-top: 9px;
			}

			.form-section .control-label i {
				margin-right: 5px;
				opacity: .75;
			}

			.badge-req {
				color: #dc2626;
				font-size: .85rem;
			}

			/* ── page wrapper card ── */
			.reg-wrapper {
				max-width: 100%;
				margin: 30px auto 50px;
				background: #fff;
				border-radius: 14px;
				box-shadow: 0 4px 24px rgba(74, 159, 213, .13);
				overflow: hidden;
			}

			.reg-wrapper__hero {
				background: linear-gradient(135deg, #0f2027 0%, #2c5364 60%, #4A9FD5 100%);
				padding: 28px 30px 22px;
				color: #fff;
				text-align: center;
			}

			.reg-wrapper__hero h2 {
				font-family: 'Bakbak One', sans-serif;
				font-size: 1.6rem;
				margin: 0 0 6px;
			}

			.reg-wrapper__hero p {
				font-size: .9rem;
				opacity: .85;
				margin: 0;
			}

			.reg-wrapper__body {
				padding: 28px 30px 10px;
			}

			/* ── buttons ── */
			.btn-crear {
				background: linear-gradient(135deg, #4A9FD5, #2E86AB);
				color: #fff;
				border: none;
				border-radius: 30px;
				padding: 11px 34px;
				font-size: 1rem;
				font-weight: 700;
				letter-spacing: .03em;
				transition: transform .18s, box-shadow .18s;
				box-shadow: 0 3px 12px rgba(74, 159, 213, .35);
			}

			.btn-crear:hover {
				transform: translateY(-2px);
				box-shadow: 0 6px 18px rgba(74, 159, 213, .45);
				color: #fff;
			}

			.btn-crear:disabled {
				opacity: .6;
				transform: none;
			}

			.btn-regresar {
				background: rgba(100, 116, 139, .12);
				color: #475569;
				border: 1.5px solid #cbd5e1;
				border-radius: 30px;
				padding: 11px 26px;
				font-size: .95rem;
				font-weight: 600;
				transition: background .18s;
			}

			.btn-regresar:hover {
				background: rgba(100, 116, 139, .22);
				color: #334155;
			}

			/* captcha holder */
			.captcha-box {
				display: flex;
				align-items: center;
				gap: 16px;
				background: #f8fafc;
				border: 1.5px dashed #cbd5e1;
				border-radius: 10px;
				padding: 14px 18px;
			}

			.captcha-box img {
				border: 2px solid #e2e8f0;
				border-radius: 6px;
			}

			#reloadCaptcha {
				font-size: .85rem;
				color: #4A9FD5;
				text-decoration: none;
				display: flex;
				align-items: center;
				gap: 5px;
			}

			#reloadCaptcha:hover {
				text-decoration: underline;
			}

			/* intro note */
			.reg-intro {
				background: linear-gradient(90deg, rgba(74, 159, 213, .07), rgba(74, 159, 213, .03));
				border-left: 4px solid #4A9FD5;
				border-radius: 0 8px 8px 0;
				padding: 13px 16px;
				margin-bottom: 24px;
				font-size: .88rem;
				color: #374151;
			}

			/* ── Responsivo móvil ── */
			@media (max-width: 575px) {

				.reg-wrapper__body {
					padding: 18px 14px 10px;
				}

				.reg-wrapper__hero {
					padding: 20px 16px 16px;
				}

				.reg-wrapper__hero h2 {
					font-size: 1.3rem;
				}

				.form-section__body {
					padding: 14px 12px;
				}

				.form-section__header {
					font-size: .9rem;
					padding: 10px 14px;
				}

				/* Labels ancho completo encima del input */
				.form-section .form-group.row>label.control-label,
				.form-section .form-group.row>.col-sm-3 {
					width: 100%;
					max-width: 100%;
					flex: 0 0 100%;
					padding-bottom: 3px;
				}

				/* Inputs ancho completo */
				.form-section .form-group.row>div[class*="col-"] {
					width: 100%;
					max-width: 100%;
					flex: 0 0 100%;
				}

				/* Captcha: apilar verticalmente */
				.captcha-box {
					flex-direction: column;
					align-items: flex-start;
					gap: 10px;
				}

				/* Botones: apilar y ancho completo */
				div[style*="justify-content:space-between"] {
					flex-direction: column-reverse !important;
				}

				.btn-crear,
				.btn-regresar {
					width: 100%;
					text-align: center;
				}

				/* Error edad */
				#erroredad {
					flex: 0 0 100%;
					max-width: 100%;
					padding-top: 4px;
				}
			}
		</style>
	</head>

	<body>

		<?php include('header.php'); ?>
		<div class="container" id="main_container">
			<div class="reg-wrapper">

				<!-- Hero header -->
				<div class="reg-wrapper__hero">
					<div style="font-size:2.4rem; margin-bottom:8px;"><i class="fas fa-user-plus"></i></div>
					<h2>Nuevo registro</h2>
				</div>

				<div class="reg-wrapper__body">

					<!-- Intro note -->
					<div class="reg-intro">
						<i class="fas fa-info-circle" style="color:#4A9FD5; margin-right:6px;"></i>
						La información que se capture deberá corresponder con los datos de quien vaya a participar.
						Si eres madre, padre, tutora o tutor de una persona menor de edad, captura los datos de la persona menor de edad.
						Llena los siguientes campos para generar tu persona usuaria y contraseña.
					</div>

					<p style="font-size:.88rem; color:#6b7280; margin-bottom:20px;">
						Los campos marcados con <span class="badge-req">*</span> son obligatorios.
					</p>

					<!-- ── SECCIÓN 1: Datos del participante ── -->
					<div class="form-section">
						<div class="form-section__header" style="background:#2E86AB;">
							<i class="fas fa-id-card"></i> <span>Datos de la persona participante</span>
						</div>
						<div class="form-section__body">
							<input type="hidden" value="<?php echo isset($idusr) ? htmlspecialchars($idusr) : ''; ?>" id="iduser" name="iduser">

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-user"></i> Nombre(s) <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="nombre" name="nombre"
										data-validar="letras"
										maxlength="50"
										placeholder="Nombre(s) del participante"
										pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s-]+"
										title="Solo se permiten letras y números máximo 50 caracteres">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-user-tag"></i> Primer apellido <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="paterno" name="paterno"
										data-validar="letras"
										maxlength="50"
										placeholder="Primer apellido"
										pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s-]+"
										title="Solo se permiten letras y espacios"> <small class="text-muted" style="font-size:.78rem;">En caso de NO tener primer o segundo apellido, escribe una <strong>X</strong>.</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-user-tag"></i> Segundo apellido <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="materno" name="materno"
										data-validar="letras"
										maxlength="50"
										placeholder="Segundo apellido"
										pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s-]+"
										title="Solo se permiten letras y espacios">
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-id-card"></i> CURP <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="curp" name="curp"
										maxlength="18"
										placeholder="Escribe tu CURP"
										oninput="this.value=this.value.toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,18)">
									<small class="text-muted" style="font-size:.78rem;">Captura tu CURP con mayúsculas y sin espacios.</small>
								</div>
							</div>
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-venus-mars"></i> Género <span class="badge-req">*</span></label>
								<div class="col-sm-5">
									<select class="form-control" name="genero" id="genero" required>
										<option value="0" selected disabled>Selecciona una opción</option>
										<option value="F">Femenino</option>
										<option value="M">Masculino</option>
										<option value="PND">Prefiero no decirlo</option>
										<option value="OTRO">Otro</option>
									</select>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-calendar-alt"></i> Fecha de nacimiento <span class="badge-req">*</span></label>
								<div class="col-sm-4">
									<input class="form-control" type="date" name="fecha_nacimiento" id="fecha_nacimiento" onchange="fnEdad();" step="1" min="2003-01-01" max="2026-12-31" required>
									<input type="hidden" name="edad_califica" id="edad_califica" value="0">
									<input type="hidden" name="edad" id="edad" value="0">
								</div>
								<div class="col-sm" id="erroredad" style="padding-top:7px; font-size:.88rem; color:#dc2626;"></div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-layer-group"></i> Categoría <span class="badge-req">*</span></label>
								<div class="col-sm-5">
									<select class="form-control" name="categoria" id="categoria" required disabled>
										<option value="0" selected disabled>Se asigna según la edad</option>
										<option value="1">Categoria 1 (15 a 17 años)</option>
										<option value="2">Categoria 2 (18 a 23 años)</option>
									</select>
								</div>
							</div>


							<input type='hidden' id="sel" name="sel" class="form-control" value='ensayo' disabled>


						</div>
					</div><!-- /seccion 1 -->

					<!-- SECCION 2: Credenciales -->
					<div class="form-section">
						<div class="form-section__header" style="background:#4A9FD5;">
							<i class="fas fa-key"></i> <span>Credenciales de acceso</span>
						</div>
						<div class="form-section__body">
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-at"></i> Nombre de usuario <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="text" class="form-control" id="user" name="user" placeholder="Ej. juanlopez2026" onkeypress="return validar2(event)" maxlength="20">
									<small class="text-muted" style="font-size:.78rem;">Solo se permiten letras y números máximo 20 caracteres.</small>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-lock"></i> Contraseña <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<div style="position:relative;">
										<input type="password" class="form-control" id="pass1" placeholder="Contraseña" onkeypress="return validar2(event)" maxlength="20" style="padding-right:42px;" >
									<small class="text-muted" style="font-size:.78rem;">No aceptan caracteres especiales</small>
										<span onclick="togglePass('pass1',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;"><i class="fas fa-eye"></i></span>
									</div>
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-lock"></i> Repetir contraseña <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<div style="position:relative;">
										<input type="password" class="form-control" id="pass2" placeholder="Repite la contraseña" onkeypress="return validar2(event)" onpaste="return false;" maxlength="20" style="padding-right:42px;">
										<span onclick="togglePass('pass2',this)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);cursor:pointer;color:#9ca3af;"><i class="fas fa-eye"></i></span>
									</div>
								</div>
							</div>
						</div>
					</div><!-- /seccion 2 -->

					<!-- SECCION 3: Correo -->
					<div class="form-section">
						<div class="form-section__header" style="background:#0e7490;">
							<i class="fas fa-envelope"></i> <span>Correo electrónico</span>
						</div>
						<div class="form-section__body">
							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-envelope-open-text"></i> Correo <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="email" class="form-control" id="correo" name="correo" placeholder="ejemplo@correo.com">
								</div>
							</div>

							<div class="form-group row">
								<label class="col-sm-3 control-label"><i class="fas fa-envelope-open-text"></i> Repetir correo <span class="badge-req">*</span></label>
								<div class="col-sm-7">
									<input type="email" class="form-control" id="correo2" name="correo2" placeholder="Repite tu correo electrónico" onpaste="return false;">
								</div>
							</div>
						</div>
					</div><!-- /seccion 3 -->

					<!-- SECCION 4: Captcha -->
					<div class="form-section">
						<div class="form-section__header" style="background:#065f46;">
							<i class="fas fa-shield-alt"></i> <span>Verificación de seguridad</span>
						</div>
						<div class="form-section__body">
							<div class="captcha-box">
								<img src="captcha.php?rand=<?php echo rand(); ?>" id="tmptxt2" alt="Captcha" height="50">
								<div>
									<input class="form-control" id="tmptxt" name="tmptxt" type="text" placeholder="Escribe los caracteres de la imagen" style="max-width:260px;">
									<a href="javascript:void(0)" id="reloadCaptcha" style="margin-top:7px; display:inline-flex; align-items:center; gap:5px;">
										<i class="fas fa-sync-alt"></i> Generar nuevos caracteres
									</a>
								</div>
							</div>
						</div>
					</div><!-- /seccion 4 -->

					<!-- Botones -->
					<div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; padding-bottom:24px;">
						<a href="index.php?concurso=ensayo" class="btn-regresar">
							<i class="fas fa-arrow-left" style="margin-right:6px;"></i> Regresar
						</a>
						<button type="button" class="btn-crear" id="btn_crearusuario" onclick="this.disabled=true;crearusuario();">
							<i class="fas fa-user-plus" style="margin-right:8px;"></i> Crear cuenta
						</button>
					</div>

					<div id="errormsg" style="margin-bottom:16px;"></div>

				</div><!-- /reg-wrapper__body -->
			</div><!-- /reg-wrapper -->
		</div><!-- /container -->

<!-- ══ BOTÓN FLOTANTE: AVISO DE PRIVACIDAD ══ -->
<button type="button" id="btnAvisoPrivacidad" onclick="document.getElementById('modalAvisoPrivacidad').style.display='flex'"
  title="Aviso de Privacidad"
  style="position:fixed;bottom:28px;right:28px;z-index:9999;
         width:60px;height:60px;border-radius:50%;border:none;cursor:pointer;
         background:#0f2027;box-shadow:0 4px 18px rgba(0,0,0,.4);
         display:flex;align-items:center;justify-content:center;
         transition:transform .2s,box-shadow .2s;"
  onmouseover="this.style.transform='scale(1.12)';this.style.boxShadow='0 6px 24px rgba(0,0,0,.5)'"
  onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 18px rgba(0,0,0,.4)'">
  <i class="fas fa-shield-alt" style="font-size:1.5rem;color:#4A9FD5;"></i>
</button>

<!-- ══ MODAL AVISO DE PRIVACIDAD ══ -->
<div id="modalAvisoPrivacidad"
  style="display:none;position:fixed;inset:0;z-index:10000;
         background:rgba(0,0,0,.65);align-items:center;justify-content:center;padding:16px;">
  <div style="background:#fff;border-radius:14px;max-width:700px;width:100%;
              max-height:90vh;display:flex;flex-direction:column;
              box-shadow:0 20px 60px rgba(0,0,0,.45);overflow:hidden;">

    <!-- Cabecera del modal -->
    <div style="background:#0f2027;padding:18px 24px;display:flex;align-items:center;gap:12px;">
      <i class="fas fa-shield-alt" style="font-size:1.4rem;color:#4A9FD5;"></i>
      <strong style="color:#fff;font-family:'Funnel Sans',sans-serif;font-size:1.05rem;letter-spacing:.04em;">
        AVISO DE PRIVACIDAD SIMPLIFICADO
      </strong>
    </div>

    <!-- Cuerpo del modal (scroll) -->
    <div style="padding:24px;overflow-y:auto;flex:1;font-size:.9rem;color:#374151;line-height:1.75;text-align:justify;">
      <p>
        El <strong>Instituto Electoral de la Ciudad de México (IECM)</strong>, a través de la Dirección Ejecutiva de Género, Derechos Humanos, 
        Educación Cívica y Construcción Ciudadana, es el responsable del tratamiento de los datos personales que nos proporcione, 
        los cuales serán protegidos en el Sistema de registro de participantes en los concursos para la promoción de la participación ciudadana 
        y divulgación de la cultura democrática.
        <br>
        Los datos personales que recabemos serán utilizados con la finalidad siguiente: llevar a cabo el registro de datos personales 
        de las personas interesadas en participar en los concursos que organice, promueva o difunda el Instituto Electoral. 
        <br>
        Los datos personales podrán ser transferidos a la Comisión de Derechos Humanos de la Ciudad de México, para la investigación de quejas y denuncias 
        por presuntas violaciones a los derechos humanos; al Instituto de Transparencia, Acceso a la Información Pública, Protección de Datos Personales y 
        Rendición de Cuentas de la Ciudad de México, para la sustanciación de recursos de revisión, recurso de inconformidad, denuncias y procedimientos de 
        verificación para determinar el presunto incumplimiento a la Ley de Protección de Datos Personales en posesión de Sujetos Obligados de la Ciudad de México; 
        a la Auditoría Superior de la Ciudad de México, para el ejercicio de sus funciones de fiscalización; a Órganos de Control Interno, 
        para la realización de auditorías o desarrollo de investigaciones por presuntas faltas administrativas; a Órganos Jurisdiccionales Locales y Federales, 
        para la sustanciación de los procesos jurisdiccionales tramitados ante ellos y al Instituto Nacional Electoral, para la sustanciación de procedimientos 
        administrativos sancionadores. Este Sistema de Datos Personales no cuenta con Encargados; ni con Despacho de Auditores Externos encargados del ejercicio 
        de las funciones de fiscalización.
        <br>
        Usted podrá manifestar la negativa al tratamiento de sus datos personales directamente ante la Unidad de Transparencia del IECM, 
        ubicada en la Calle de Huizaches No. 25, Rancho los Colorines, Planta Baja, Tlalpan, C. P. 14386, Ciudad de México, 
        con número telefónico 55 5483 3800 ext. 4725 y 4727 y al correo electrónico <b>unidad.transparencia@iecm.mx</b>.
        <br>
        Para conocer el Aviso de privacidad Integral, puede acudir directamente a la Unidad de Transparencia o ingresar al 
        Sitio de Internet:<b> https://www.iecm.mx/proteccion-de-datos-personales/.  
        <br>
        Fecha de última actualización: 30 de enero de 2026

    </div>

    <!-- Pie: checkbox de aceptación -->
    <div style="padding:16px 24px;border-top:1px solid #e5e7eb;background:#f9fafb;
                display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:.9rem;color:#374151;font-weight:600;">
        <input type="checkbox" id="checkAvisoPrivacidad" style="width:18px;height:18px;cursor:pointer;"
          onchange="onCheckAviso(this)">
        He leído y acepto el Aviso de Privacidad
      </label>
      <div id="mensajeAvisoAceptado" style="display:none;align-items:center;gap:6px;
           background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:6px 14px;">
        <i class="fas fa-check-circle" style="color:#16a34a;"></i>
        <span style="color:#15803d;font-size:.88rem;font-weight:600;">Aviso aceptado</span>
      </div>
      <button type="button" onclick="cerrarModalAviso()"
        style="background:none;border:1px solid #d1d5db;border-radius:8px;padding:6px 18px;
               cursor:pointer;font-size:.88rem;color:#6b7280;">
        Cerrar
      </button>
    </div>

  </div>
</div>

<script>
  // ═══════════════════════════════════════════════════════════════════════
  // AVISO DE PRIVACIDAD
  // ═══════════════════════════════════════════════════════════════════════
  // La validación de la clave de elector se encuentra en js/funcionesajax_test.js


  (function() {
    var KEY = 'avisoPrivacidadAceptado_<?php echo $_SESSION["idusuario"] ?? "0"; ?>';

    function marcarAceptado() {
      var chk = document.getElementById('checkAvisoPrivacidad');
      var msg = document.getElementById('mensajeAvisoAceptado');
      chk.checked = true;
      chk.disabled = true;
      chk.style.cursor = 'default';
      chk.parentElement.style.cursor = 'default';
      msg.style.display = 'flex';
    }

    // Restaurar estado si ya fue aceptado en esta sesión
    if (sessionStorage.getItem(KEY) === '1') {
      window.addEventListener('DOMContentLoaded', marcarAceptado);
    } else {
      // Mostrar modal automáticamente la primera vez
      setTimeout(function() {
        document.getElementById('modalAvisoPrivacidad').style.display = 'flex';
      }, 800);
    }

    window.onCheckAviso = function(checkbox) {
      if (checkbox.checked) {
        sessionStorage.setItem(KEY, '1');
        marcarAceptado();
      }
    };

    // Evita cerrar el modal si el aviso no fue aceptado
    window.cerrarModalAviso = function() {
      var chk = document.getElementById('checkAvisoPrivacidad');
      var modal = document.getElementById('modalAvisoPrivacidad');
      if (chk && chk.checked) {
        if (modal) modal.style.display = 'none';
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Aviso aceptado',
            text: 'Has aceptado el Aviso de Privacidad.',
            confirmButtonColor: '#4fd54a'
          }).then(function() {
            if (chk) chk.focus();
          });
        } else {
          alert('Has aceptado el Aviso de Privacidad.');
          if (chk) chk.focus();
        }
      }
    };
  })();
  
    // Mascara y validación en vivo para CURP
    function formatCurpInput(el){
      if (typeof window.aplicarMascaraCurpInput === 'function') {
        window.aplicarMascaraCurpInput(el);
        return;
      }
      if(!el) return;
      var v = String(el.value || '').toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,18);
      el.value = v;
      var re = /^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/;
      if(v.length === 18){
        if(re.test(v)){
          el.classList.remove('is-invalid'); el.classList.add('is-valid');
        } else {
          el.classList.remove('is-valid'); el.classList.add('is-invalid');
        }
      } else {
        el.classList.remove('is-valid'); el.classList.remove('is-invalid');
      }
    }
</script>



		<?php
		include('footer.php');
		?>
		<script>
			function togglePass(id, el) {
				var inp = document.getElementById(id);
				var ico = el.querySelector('i');
				if (inp.type === 'password') {
					inp.type = 'text';
					ico.classList.replace('fa-eye', 'fa-eye-slash');
				} else {
					inp.type = 'password';
					ico.classList.replace('fa-eye-slash', 'fa-eye');
				}
			}

			window.onRegistroExitoso = function(idusr) {
				Swal.fire({
					icon: 'success',
					title: '<span style="font-family:\'Bakbak One\',sans-serif;color:#4A9FD5">¡Registro exitoso!</span>',
					html: '<p style="margin-bottom:6px;">Tu cuenta de persona usuaria fue creada correctamente.</p>' +
						'<p style="margin-bottom:16px;">Se envió un correo electrónico confirmando tu registro.<br>' +
						'<small style="color:#6b7280">(Si no aparece en recibidos, revisa la bandeja de <strong>No deseados / Spam</strong>)</small></p>' +
						'<p style="margin:0 0 8px;">¿No recibiste ningún correo?</p>' +
						'<button onclick="fnReenviarCorreo(' + idusr + '); this.disabled=true; this.textContent=\'✓ Correo reenviado\';" ' +
						'style="background:linear-gradient(135deg,#4A9FD5,#2E86AB);color:#fff;border:none;border-radius:30px;padding:9px 22px;font-size:.9rem;cursor:pointer;">' +
						'<i class="fas fa-paper-plane" style="margin-right:7px;"></i>Enviar correo otra vez</button>',
					showConfirmButton: true,
					confirmButtonText: '<i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>Ir al inicio de sesión',
					confirmButtonColor: '#4A9FD5',
					allowOutsideClick: false,
					width: '560px'
				}).then(function(result) {
					if (result.isConfirmed) {
						window.location.href = 'index.php?concurso=ensayo';
					}
				});
			};

			$(document).ready(function() {
				$("#reloadCaptcha").click(function() {
					var captchaImage = $('#tmptxt2').attr('src');
					captchaImage = captchaImage.substring(0, captchaImage.lastIndexOf("?"));
					captchaImage = captchaImage + "?rand=" + Math.random() * 1000;
					$('#tmptxt2').attr('src', captchaImage);
				});
			});
		</script>



	</body>

	</html>

<?php

}

?>