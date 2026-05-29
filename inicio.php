<?php

/**
 * Página de inicio / landing page del concurso de Ensayo.
 * Es la primera pantalla que ve el usuario al abrir el sistema.
 * Desde aquí se puede acceder al login o al registro.
 */
session_start();
error_reporting(0);

// Si ya tiene sesión activa, redirigir a su página correspondiente
if (isset($_SESSION['idusuario'])) {
    $perfil = $_SESSION['perfil'] ?? 0;
    if ($perfil == 2) {
        header('Location: maindistrito.php');
    } elseif ($perfil == 3) {
        header('Location: maincentrales.php');
    } else {
        header('Location: main.php');
    }
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=5">
    <meta name="description" content="Concurso Juvenil de Ensayo 2026 - Instituto Electoral de la Ciudad de México. Participa y comparte tu visión sobre la ciudad.">

    <title>Ensayo 2026 - IECM</title>
    <link rel="icon" href="img/IECM-1.png" type="image/png">


    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mycss.css" rel="stylesheet">
    <link rel="stylesheet" href="css/all.css">
    <link rel="stylesheet" href="css/inicio.css">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700;900&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</head>

<body>

    <!-- Canvas para el rastro del mouse (cubre toda la página) -->
    <canvas id="trailCanvas"></canvas>

    <!-- Sección combinada: header + hero fusionados -->
    <section class="hero-combined" style="background:none;">
            <!-- Logo arriba a la izquierda -->
            <div class="hero-combined__logo">
                <img src="img/IECM-1.png" alt="IECM">
            </div>

            <!-- Título principal fuera del card -->
            <div class="hero-combined__main-title-group">
                <h1 class="hero-combined__main-title">Concurso Juvenil de Ensayo 2026</h1>
                <div class="hero-combined__main-title-divider"></div>
                <h2 class="hero-combined__main-subtitle">&mdash; Conversando con los clásicos &mdash;</h2>
            </div>

            <!-- Contenido central -->
            <div class="hero-combined__center">
                <div class="hero-combined__graphic">
                    <img src="img/Grafico-min.jpg" alt="Gráfico del concurso">
                </div>
                <p class="hero-combined__subtitle">
                    <span>El Instituto Electoral de la Ciudad de México (IECM) te invita a participar en el certamen, con el propósito de divulgar la cultura democrática mediante el diálogo de ideas, reflexiones y aportaciones a partir de textos clásicos en materia social y política.</span>
                </p>
                <div class="hero-combined__actions">
                    <a href="index.php?login=1" class="btn-inicio">
                        <i class="fas fa-sign-in-alt"></i> Ingresar al sistema
                    </a>
                    <!-- <a href="convocatoria.pdf" target="_blank" class="btn-inicio-outline">
                        <i class="far fa-file-alt"></i> Ver convocatoria
                    </a> -->
                </div>
            </div>

        </section>

        <!-- Tarjetas informativas -->
        <div class="seccion-pasos section-panel section-panel--steps">
            <div class="container">
                <h2 class="section-title text-center" style="color:#fff;">¿Cómo participar?</h2>
                <div class="row">

                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card info-card info-card--step p-4 text-center">
                            <div class="info-card__media">
                                <img src="img/3d/mujer-escribiendo.png" alt="Registro" style="width: 120px; height: 120px; object-fit: contain;">
                            </div>
                            <h5 style="color:#fff;">1. Escribe tu ensayo</h5>
                            <p style="color:rgba(255,255,255,.82);">
                                Elige una obra; varias obras de un mismo tomo; todas las obras de un tomo, o dos o más obras de diferentes
                                tomos de la serie editorial Clásicos de Política y Democracia. Analiza,
                                interpela y dialoga con los clásicos para que aportes tu visión.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card info-card info-card--step p-4 text-center">
                            <div class="info-card__media">
                                <img src="img/3d/pluma-hoja3d.png" alt="Escribir" style="width: 120px; height: 120px; object-fit: contain;">
                            </div>
                            <h5 style="color:#fff;">2. Regístrate</h5>
                            <p style="color:rgba(255,255,255,.82);">
                                Crea tu cuenta de persona usuaria. Ingresa los datos solicitados en el formulario de registro para poder participar.
                            </p>
                        </div>
                    </div>

                    <div class="col-12 col-md-6 col-lg-4 mb-4">
                        <div class="card info-card info-card--step p-4 text-center">
                            <div class="info-card__media">
                                <img src="img/3d/libro-3d.png" alt="Subir documento" style="width: 120px; height: 120px; object-fit: contain;">
                            </div>
                            <h5 style="color:#fff;">3. Sube tu documento</h5>
                            <p style="color:rgba(255,255,255,.82);">
                                Ingresa al sistema con tu cuenta y sube tu ensayo. Recuerda seguir las especificaciones de formato indicadas en la convocatoria.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <div class="seccion-info section-panel section-panel--info">
            <div class="container">
                <div class="row">

                    <div class="col-12 col-lg-6 mb-4">
                        <div class="card info-card info-card--details p-4">
                            <div class="icon-circle mb-3">
                                <i class="fas fa-trophy"></i>
                            </div>
                            <h5 style="color:#fff;">Categorías del Concurso</h5>
                            <p style="color:rgba(255,255,255,.80); margin-bottom:16px;">
                                Consulta el rango de edad correspondiente antes de registrar tu participación.
                            </p>
                            <ul style="color:rgba(255,255,255,.82); line-height:1.9;">
                                <li>Categoría 1: de 15 a 17 años</li>
                                <li>Categoría 2: de 18 a 23 años</li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-12 col-lg-6 mb-4">
                        <div class="card info-card info-card--details p-4">
                            <div class="icon-circle mb-3">
                                <i class="fas fa-award"></i>
                            </div>
                            <h5 style="color:#fff;">Premios y reconocimientos</h5>
                            <p style="color:rgba(255,255,255,.82); margin-bottom:0;">
                                Se premiará con estímulo económico a los tres primeros lugares de ambas categorías:
                            </p>
                            <ul style="color:rgba(255,255,255,.82); line-height:1.8; margin-top:14px; margin-bottom:16px;">
                                <li>Primer lugar: $20,000.00 (veinte mil pesos 00/100, moneda nacional)</li>
                                <li>Segundo lugar: $16,000.00 (dieciséis mil pesos 00/100, moneda nacional)</li>
                                <li>Tercer lugar: $11,500.00 (once mil quinientos pesos 00/100, moneda nacional)</li>
                            </ul>
                            <p style="color:rgba(255,255,255,.82); margin-bottom:0;">
                                Se otorgará un reconocimiento (constancia) a los ensayos ganadores y a los trabajos que, en opinión del jurado, merezcan mención especial.
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Documentos / Recursos -->
        <div class="section-docs section-panel section-panel--docs">
            <div class="container">
                <h2 class="section-title text-center" style="color:#fff;">Recursos y Documentos</h2>
                <div class="row justify-content-center">
                    <div class="col-12 col-md-6 col-lg-5 mb-3">
                        <div class="card info-card info-card--resource p-4">
                            <i class="far fa-file-pdf fa-3x mb-3" style="color:#fff;"></i>
                            <a href="../ensayo2026/documentos_concursos/convo.pdf" target="_blank" style="color:#fff; font-weight:600; font-size:1.1rem; text-decoration:none;">
                                Descarga la Convocatoria Oficial
                            </a>
                            <small style="color:rgba(255,255,255,.65); margin-top:8px;">
                                Lee los requisitos completos y bases del concurso
                            </small>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-5 mb-3">
                        <div class="card info-card info-card--resource p-4">
                            <i class="fas fa-question-circle fa-3x mb-3" style="color:#fff;"></i>
                            <a href="mailto:concursos@iecm.mx" style="color:#fff; font-weight:600; font-size:1.1rem; text-decoration:none;">
                                ¿Tienes dudas? Contáctanos
                            </a>
                            <small style="color:rgba(255,255,255,.65); margin-top:8px;">
                                Escríbenos a concursos@iecm.mx
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php
	include('footer.php');
?>

        <script>
        (function() {
            var canvas = document.getElementById('trailCanvas');

            // Desactivar en dispositivos táctiles para mejor rendimiento
            if ('ontouchstart' in window || navigator.maxTouchPoints > 0) {
                canvas.style.display = 'none';
                return;
            }

            var ctx = canvas.getContext('2d');
            var points = [];

            // Paleta de colores del rastro (tonos azules: cyan, celeste y azul grisáceo)
            var palette = [
                'rgba(74,159,213,', // Azul claro
                'rgba(46,134,171,', // Azul medio
                'rgba(91,156,184,', // Azul grisáceo
                'rgba(255,255,255,', // Blanco
            ];

            function resize() {
                canvas.width = window.innerWidth;
                canvas.height = window.innerHeight;
            }

            resize();
            window.addEventListener('resize', resize);

            document.addEventListener('mousemove', function(e) {
                points.push({
                    x: e.clientX,
                    y: e.clientY,
                    alpha: 1,
                    radius: Math.random() * 40 + 30,
                    color: palette[Math.floor(Math.random() * palette.length)]
                });
            });

            function draw() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);

                for (var i = points.length - 1; i >= 0; i--) {
                    var p = points[i];

                    var grad = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.radius);
                    grad.addColorStop(0, p.color + p.alpha + ')');
                    grad.addColorStop(0.4, p.color + (p.alpha * 0.4) + ')');
                    grad.addColorStop(1, p.color + '0)');

                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.radius, 0, Math.PI * 2);
                    ctx.fillStyle = grad;
                    ctx.fill();

                    p.alpha -= 0.025;
                    p.radius += 1.5;
                    if (p.alpha <= 0) points.splice(i, 1);
                }

                requestAnimationFrame(draw);
            }

            draw();
        }());
    </script>

</body>

</html>