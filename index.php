<?php
session_start();
error_reporting(0);

// Redirigir a la página de inicio si el usuario llega sin login activo ni intento de POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST' && !isset($_SESSION['idusuario']) && !isset($_GET['login'])) {
    header('Location: inicio.php');
    exit;
}

$concurso = 'ensayo';
$show_back_button = true; // Mostrar botón de volver en el header

// Ajuste del periodo de registro de usuario
$fecha_inicio_registro = '2026-03-01 09:00:00';
$fecha_fin_registro = '2026-05-06 08:58:00';

$boton_habilitado = false; //FALSO CIERRE DE REGISTRO || TRUE ABIERTO REGISTRO

if (date('Y-m-d H:i:s') >= $fecha_inicio_registro && date('Y-m-d H:i:s') <= $fecha_fin_registro) {
    $boton_habilitado = true; // Habilitar el botón
}

if (isset($_POST['usr']) && isset($_POST['pwd'])) {
    include('sqlconnector.php');

    $usr = $_POST['usr'];
    $pwd = $_POST['pwd'];
    $concurso = $_POST['concurso'] ?? 'ensayo';

    // Consulta con prepared statements para evitar SQL injection
    $query = 'SELECT * FROM ' . BD_USUARIOS . ' WHERE usuario = ? AND contrasena = ? AND area = ? AND estatus = 1';
    $params = array($usr, $pwd, $concurso);
    $row0 = sqlsrv_query($conn, $query, $params);

    if ($row0 && ($row = sqlsrv_fetch_array($row0))) {
        $id = $row['idusuario'];
        $perfil = $row['perfil'];
        $status = $row['estatus'];

        $usr = $row['usuario'];
        $pwd = $row['contrasena'];

        $area = $row['area'];
        $distrito = $row['iddistrito'];

        $nombre = $row['nombre'] ?? '';
        $paterno = $row['paterno'] ?? '';
        $materno = $row['materno'] ?? '';
        

       // $paterno_tutor=$row['tutor'] ?? '';
        //$materno_tutor= $row['tutor'] ?? '';
        $nombre_tutor= $row['nombre'] ?? '';
        $clave_elector=$row['clave_elector'] ?? '';

        // Datos de sesión comunes a todos los perfiles
        $_SESSION['idusuario'] = $id;
        $_SESSION['usr'] = $usr;
        $_SESSION['pwd'] = $pwd;
        $_SESSION['nombre'] = $nombre;
        $_SESSION['paterno'] = $paterno;
        $_SESSION['materno'] = $materno;
        $_SESSION['perfil'] = $perfil;
        $_SESSION['area'] = $area;
        $_SESSION['distrito'] = $distrito;

        if ($status == '1' && $perfil == '1') {
            header('Location: main.php');
            exit;
        } elseif ($perfil == '2') {
            header('Location: maincentrales.php');
            exit;
        } elseif ($perfil == '3') {
            header('Location: mainjueces.php');
            exit;
        } else {
            session_destroy();
            header('Location: index.php?login=1&activado=0&concurso=' . $concurso);
            exit;
        }
    } else {
        session_destroy();
        header('Location: index.php?login=1&act=0&concurso=' . $concurso);
    }
} else {
?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Concurso de Ensayo - Login</title>

        <!-- Bootstrap core CSS -->
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/mycss.css" rel="stylesheet">
        <link rel="stylesheet" href="css/all.css">
        <link rel="stylesheet" href="css/inicio.css">
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
        <style>
            @keyframes fadeInUp {
                from {
                    opacity: 0;
                    transform: translateY(30px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes shimmer {
                0% {
                    background-position: -200% 0;
                }

                100% {
                    background-position: 200% 0;
                }
            }

            @keyframes pulse-glow {

                0%,
                100% {
                    box-shadow: 0 0 15px rgba(74, 159, 213, .3);
                }

                50% {
                    box-shadow: 0 0 25px rgba(74, 159, 213, .6);
                }
            }

            .card-animate {
                animation: fadeInUp 0.6s ease-out forwards;
                opacity: 0;
            }

            .card-animate:nth-child(1) {
                animation-delay: 0.1s;
            }

            .card-animate:nth-child(3) {
                animation-delay: 0.2s;
            }

            .login-input {
                background: rgba(255, 255, 255, .08) !important;
                border: 1px solid rgba(255, 255, 255, .2) !important;
                color: #fff !important;
                padding: 0.75rem 1rem !important;
                border-radius: 8px !important;
                transition: all 0.3s ease !important;
            }

            .login-input:focus {
                background: rgba(255, 255, 255, .12) !important;
                border-color: rgba(74, 159, 213, .6) !important;
                box-shadow: 0 0 0 3px rgba(74, 159, 213, .15) !important;
                outline: none !important;
            }

            .login-input::placeholder {
                color: rgba(255, 255, 255, .4) !important;
            }

            .icon-circle {
                transition: all 0.3s ease;
            }

            .card-animate:hover .icon-circle {
                transform: scale(1.1) rotate(5deg);
            }

            .btn-inicio {
                position: relative;
                overflow: hidden;
                transition: all 0.3s ease;
            }

            .btn-inicio::before {
                content: '';
                position: absolute;
                top: 50%;
                left: 50%;
                width: 0;
                height: 0;
                border-radius: 50%;
                background: rgba(255, 255, 255, .2);
                transform: translate(-50%, -50%);
                transition: width 0.6s, height 0.6s;
            }

            .btn-inicio:hover::before {
                width: 300px;
                height: 300px;
            }

            .btn-inicio:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(74, 159, 213, .3);
            }

            .card-info-hover {
                transition: all 0.3s ease;
            }

            .card-info-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 30px rgba(74, 159, 213, .25);
            }

            .status-badge {
                animation: pulse-glow 2s ease-in-out infinite;
            }

            .divider-line {
                position: relative;
                overflow: hidden;
            }

            .divider-line::after {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 50%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(74, 159, 213, .5), transparent);
                animation: shimmer 3s infinite;
            }
        </style> <!-- Bootstrap core JavaScript -->
        <script src="js/jquery-3.3.1.min.js"></script>
        <script src="js/popper.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/funcionesajax_test.js"></script>

        <!-- SweetAlert2 -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            function checkRegistrationPeriod() {
                let buttonEnabled = <?php echo json_encode($boton_habilitado); ?>;
                document.getElementById('registroForm').submit();
            }
        </script>
        <script>
            window.addEventListener('DOMContentLoaded', function() {
                // Mostrar alerta solo una vez por sesión
                if (!sessionStorage.getItem('alertaSistemaEnsayoMostrada')) {
                    <?php if ($boton_habilitado): ?>
                        Swal.fire({
                            title: '🟢 Sistema Abierto',
                            html: 'El periodo de registro está <strong>vigente</strong>.<br>Puedes registrarte hasta la fecha límite indicada en la convocatoria.',
                            icon: 'success',
                            confirmButtonText: '¡Entendido!',
                            confirmButtonColor: '#16a34a',
                            background: '#0f3a3a',
                            color: '#ffffff',
                            timer: 8000,
                            timerProgressBar: true
                        });
                    <?php else: ?>
                        Swal.fire({
                            title: '🔴 Sistema Cerrado',
                            html: 'El periodo de registro ha <strong>concluido</strong>.<br>Ya no es posible registrar nuevas personas usuarias.',
                            icon: 'warning',
                            confirmButtonText: 'Entendido',
                            confirmButtonColor: '#2E86AB',
                            background: '#0f2027',
                            color: '#ffffff',
                            timer: 8000,
                            timerProgressBar: true
                        });
                    <?php endif; ?>

                    // Marcar como mostrada en esta sesión
                    sessionStorage.setItem('alertaSistemaEnsayoMostrada', 'true');
                }
            });
        </script>
    </head>

    <body>
        <?php $show_back_button = true;
        include('header.php'); ?>

        <div style="background: linear-gradient(135deg, #1a0d2e 0%, #2d1b4e 50%, #1f1035 100%); min-height:100vh; position:relative;">
            <div class="login-hero">
                <div class="container">
                    <div class="row align-items-stretch justify-content-center" style="gap:0;">

                        <!-- ── Tarjeta LOGIN ── -->
                        <div class="col-11 col-md-5 mb-4 mb-md-0 card-animate">
                            <div class="card info-card h-100" style="padding:2rem 1.8rem;display:flex;flex-direction:column;background:linear-gradient(160deg, rgba(45,27,78,0.95) 0%, rgba(63,40,100,0.92) 100%);border:1px solid rgba(147,51,234,0.25);box-shadow: 0 16px 40px rgba(0,0,0,0.35);">
                                <!-- Ícono + Encabezado -->
                                <div style="text-align:center;">
                                    <div class="icon-circle" style="width:72px;height:72px;border-radius:50%;background:rgba(74,159,213,.25);border:2px solid rgba(74,159,213,.6);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;">
                                        <i class="fas fa-sign-in-alt" style="font-size:1.8rem;color:#4A9FD5;"></i>
                                    </div>
                                    <h3 style="color:#fff;font-family:'Inter',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem;">Ingresar</h3>
                                    <p style="color:rgba(255,255,255,.85);font-size:.9rem;margin-bottom:1.6rem;">Ingresa tus credenciales para continuar</p>
                                </div>

                                <div class="divider-line" style="height:1px;background:rgba(255,255,255,.15);margin-bottom:1.5rem;"></div>
                                <!-- Formulario -->
                                <form id="loginform" action="index.php" method="post" style="flex:1;display:flex;flex-direction:column;">
                                    <input type="hidden" name="concurso" value="<?php echo $concurso; ?>">

                                    <div class="form-group mb-3">
                                        <label style="color:#fff;font-size:.85rem;text-align:left;display:block;margin-bottom:6px;font-weight:600;">
                                            <i class="fas fa-user fa-sm" style="color:#4A9FD5;"></i> Nombre de persona usuaria
                                        </label>
                                        <input type="text" class="form-control login-input" id="usr" name="usr" placeholder="Usuario" required>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label style="color:#fff;font-size:.85rem;text-align:left;display:block;margin-bottom:6px;font-weight:600;">
                                            <i class="fas fa-lock fa-sm" style="color:#4A9FD5;"></i> Contraseña
                                        </label>
                                        <input type="password" class="form-control login-input" id="pwd" name="pwd" placeholder="••••••••" required>
                                    </div>

                                    <div class="text-center mb-4">
                                        <a href="recuperarcontrasena.php" style="color:#4A9FD5;font-size:1rem;text-decoration:none;transition:all 0.3s;font-weight:600;" onmouseover="this.style.color='#fff';this.style.transform='scale(1.05)'" onmouseout="this.style.color='#4A9FD5';this.style.transform='scale(1)'">
                                            <i class="fas fa-key"></i> ¿Olvidaste tu contraseña?
                                        </a>
                                    </div>
                                    <div style="margin-top:auto;">
                                        <button type="submit" name="submit" class="btn-inicio" style="display:block;width:100%;font-size:1.05rem;padding:.85rem;margin:0;position:relative;z-index:1;">
                                            <span style="position:relative;z-index:2;"><i class="fas fa-sign-in-alt"></i> &nbsp;Ingresar</span>
                                        </button>
                                    </div>
                                    <?php if (isset($_GET['act'])): ?>
                                        <div class="alert alert-danger mt-3 mb-0" style="font-size:.9rem;background:rgba(220,38,38,.35);border:1px solid rgba(220,38,38,.8);color:#fff;">
                                            <i class="fas fa-exclamation-circle"></i> Usuario o contraseña incorrectos
                                        </div>
                                    <?php endif; ?>
                                    <?php if (isset($_GET['activado'])): ?>
                                        <div class="alert alert-warning mt-3 mb-0" style="font-size:.9rem;background:rgba(245,158,11,.35);border:1px solid rgba(245,158,11,.8);color:#fff;">
                                            <i class="fas fa-ban"></i> Usuario no activo
                                        </div>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                        <!-- Separador vertical (solo desktop) -->
                        <div class="d-none d-md-flex align-items-center px-3">
                            <div style="width:1px;height:70%;background:rgba(255,255,255,.15);"></div>
                        </div>

                        <!-- ── Tarjeta REGISTRO ── -->
                        <div class="col-11 col-md-5 card-animate">
                            <div class="card info-card h-100" style="padding:2rem 1.8rem;display:flex;flex-direction:column;background:linear-gradient(160deg, rgba(45,27,78,0.95) 0%, rgba(63,40,100,0.92) 100%);border:1px solid rgba(147,51,234,0.25);box-shadow: 0 16px 40px rgba(0,0,0,0.35);">
                                <!-- Ícono + Encabezado -->
                                <div style="text-align:center;">
                                    <div class="icon-circle" style="width:72px;height:72px;border-radius:50%;background:rgba(91,156,184,.25);border:2px solid rgba(91,156,184,.6);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;">
                                        <i class="fas fa-user-plus" style="font-size:1.8rem;color:#5B9CB8;"></i>
                                    </div>
                                    <h3 style="color:#fff;font-family:'Inter',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem;">Nueva persona usuaria</h3>
                                    <p style="color:rgba(255,255,255,.85);font-size:.9rem;margin-bottom:1.6rem;">¿Primera vez? Crea tu cuenta aquí</p>
                                </div>

                                <div class="divider-line" style="height:1px;background:rgba(255,255,255,.15);margin-bottom:1.5rem;"></div>
                                <!-- Estado del sistema -->
                                <div style="margin-bottom:1.4rem;">
                                    <?php if ($boton_habilitado): ?>
                                        <div class="status-badge" style="background:rgba(22,163,74,.35);border:1px solid rgba(22,163,74,.8);border-radius:12px;padding:.9rem 1.1rem;display:flex;align-items:center;gap:.7rem;">
                                            <span style="font-size:1.4rem;">🟢</span>
                                            <div style="text-align:left;">
                                                <strong style="color:#4ade80;font-size:.98rem;font-weight:700;">SISTEMA ABIERTO</strong><br>
                                                <span style="color:#fff;font-size:.8rem;">Periodo vigente · Registro disponible</span>
                                            </div>
                                        </div>
                                    <?php else: ?>
                                        <div class="status-badge" style="background:rgba(220,38,38,.35);border:1px solid rgba(220,38,38,.8);border-radius:12px;padding:.9rem 1.1rem;display:flex;align-items:center;gap:.7rem;">
                                            <span style="font-size:1.4rem;">🔴</span>
                                            <div style="text-align:left;">
                                                <strong style="color:#f87171;font-size:.98rem;font-weight:700;">SISTEMA CERRADO</strong><br>
                                                <span style="color:#fff;font-size:.8rem;">El periodo de registro ha concluido</span>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <!-- Info adicional -->
                                <p style="color:rgba(255,255,255,.85);font-size:.88rem;margin-bottom:1.6rem;line-height:1.6;">
                                    <i class="fas fa-info-circle" style="color:#4A9FD5;margin-right:.4rem;"></i>
                                    <span style="color:#fff;">Ingresa la información solicitada en el formulario de registro para participar en el concurso.</span>
                                </p>

                                <!-- Botón de registro -->
                                <form action="usuarios.php" method="post" id="registroForm" style="margin-top:auto;">
                                    <input type="hidden" name="concurso" value="<?php echo $concurso; ?>">
                                    <?php if ($boton_habilitado): ?>
                                        <button type="button" class="btn-inicio" style="display:block;width:100%;font-size:1.05rem;padding:.85rem;margin:0;position:relative;z-index:1;" onclick="checkRegistrationPeriod()">
                                            <span style="position:relative;z-index:2;"><i class="fas fa-pen"></i> &nbsp;Registrarme</span>
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn-inicio" style="display:block;width:100%;font-size:1.05rem;padding:.85rem;margin:0;opacity:.4;cursor:not-allowed;position:relative;" disabled>
                                            <span style="position:relative;z-index:2;"><i class="fas fa-lock"></i> &nbsp;Registro no disponible</span>
                                        </button>
                                    <?php endif; ?>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Recursos -->
            <div class="section-docs" style="background: linear-gradient(135deg, #1a0d2e 0%, #2d1b4e 50%, #1f1035 100%);border-top:3px solid rgba(147,51,234,0.4);padding:40px 20px;margin-top:60px;">
                <div class="container">
                    <h2 class="section-title text-center" style="color:#fff;margin-bottom:2rem;">Recursos</h2>
                    <div class="row justify-content-center">
                        <div class="col-md-5 mb-3">
                            <div class="card info-card card-info-hover p-4" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;min-height:120px;background:linear-gradient(160deg, rgba(45,27,78,0.95) 0%, rgba(63,40,100,0.92) 100%);border:1px solid rgba(147,51,234,0.25);box-shadow: 0 16px 40px rgba(0,0,0,0.35);">
                                <i class="far fa-file-pdf fa-2x mb-2" style="color:#4A9FD5;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.2) rotate(5deg)';this.style.color='#fff'" onmouseout="this.style.transform='scale(1) rotate(0deg)';this.style.color='#4A9FD5'"></i>
                                <a href="../ensayo2026/documentos_concursos/convo.pdf" target="_blank" style="color:#fff;font-weight:600;text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='#4A9FD5'" onmouseout="this.style.color='#fff'">Descarga la Convocatoria oficial</a>
                                <small style="color:rgba(255,255,255,.75);margin-top:8px;display:block;">Lee los requisitos completos del concurso</small>
                            </div>
                        </div>
                        <div class="col-md-5 mb-3">
                            <div class="card info-card card-info-hover p-4" style="display:flex;flex-direction:column;align-items:center;justify-content:center;text-align:center;min-height:120px;background:linear-gradient(160deg, rgba(45,27,78,0.95) 0%, rgba(63,40,100,0.92) 100%);border:1px solid rgba(147,51,234,0.25);box-shadow: 0 16px 40px rgba(0,0,0,0.35);">
                                <i class="fas fa-question-circle fa-2x mb-2" style="color:#4A9FD5;transition:transform 0.3s;" onmouseover="this.style.transform='scale(1.2) rotate(-5deg)';this.style.color='#fff'" onmouseout="this.style.transform='scale(1) rotate(0deg)';this.style.color='#4A9FD5'"></i>
                                <a href="mailto:concursos@iecm.mx" style="color:#fff;font-weight:600;text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='#4A9FD5'" onmouseout="this.style.color='#fff'">¿Dudas? Contáctanos</a>
                                <small style="color:rgba(255,255,255,.75);margin-top:8px;display:block;">Escríbenos a concursos@iecm.mx</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div><!-- /wrapper gradient -->

        <?php include('footer.php'); ?>

    </body>

    </html>

<?php } ?>