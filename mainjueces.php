<?php

include('sqlconnector.php');

session_start();

///////////////////////////////////////////////////////sesión
if (isset($_SESSION['idusuario'])) {
  $idusuario = $_SESSION['idusuario'];
  $my_user = $_SESSION['usr'];
  $my_pwd = $_SESSION['pwd'];
  $area = $_SESSION['area'];
  $concurso = $area;
  $distrito = $_SESSION['distrito'];

  //$queryA="SELECT * FROM usuarios WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='".$area."'";
  $queryA = 'SELECT * FROM ' . BD_USUARIOS . ' WHERE idusuario =' . $idusuario . ' and perfil=3';

  //  echo $query;
  $resA = sqlsrv_query($conn, $queryA);

  if ($resA && ($rowA = sqlsrv_fetch_array($resA))) {
  } else {
    session_destroy();
    header('location:index.php');
  }
} else {
  session_destroy();
  header('location:index.php');
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Concursos Juvenil de ensayo | Perfil Jueces</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/mycss.css" rel="stylesheet">
  <link rel="stylesheet" href="css/all.css">
  <link href="https://fonts.googleapis.com/css2?family=Bakbak+One&family=Funnel+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <!-- Bootstrap core JavaScript -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/funcionesajax_test.js"></script>

  <style>
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Funnel Sans', sans-serif;
      background: #f0f4f8;
    }

    .page-wrapper {
      min-height: 100vh;
      display: flex;
      flex-direction: column;
    }

    .page-content {
      flex: 1;
      padding: 40px 0 60px;
    }

    .page-title {
      font-family: 'Bakbak One', sans-serif;
      font-size: 2rem;
      color: #2E86AB;
      text-align: center;
      margin-bottom: 10px;
      letter-spacing: .03em;
    }

    /* Tarjeta de bienvenida */
    .welcome-card {
      background: #fff;
      border-radius: 24px;
      box-shadow: 0 8px 32px rgba(46,134,171,.13);
      max-width: 720px;
      margin: 32px auto 36px;
      padding: 48px 52px 40px;
      text-align: center;
      border-top: 5px solid #2E86AB;
      position: relative;
      overflow: hidden;
    }

    .welcome-card::before {
      content: '';
      position: absolute;
      top: -60px; right: -60px;
      width: 180px; height: 180px;
      border-radius: 50%;
      background: rgba(74,159,213,.07);
    }

    .welcome-card::after {
      content: '';
      position: absolute;
      bottom: -40px; left: -40px;
      width: 120px; height: 120px;
      border-radius: 50%;
      background: rgba(46,134,171,.05);
    }

    .welcome-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(46,134,171,.1);
      color: #2E86AB;
      border-radius: 30px;
      padding: 6px 18px;
      font-size: .82rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-transform: uppercase;
      margin-bottom: 22px;
    }

    .welcome-text {
      font-size: 1.05rem;
      color: #374151;
      line-height: 1.75;
      margin-bottom: 0;
    }

    .welcome-text p {
      margin: 0 0 8px;
    }

    .welcome-text p:last-child {
      margin-bottom: 0;
      color: #2E86AB;
      font-weight: 600;
    }

    .divider {
      width: 60px;
      height: 3px;
      background: linear-gradient(90deg, #2E86AB, #4A9FD5);
      border-radius: 10px;
      margin: 20px auto;
    }

    /* Botón Validación */
    .btn-validacion-wrap {
      text-align: center;
      margin-bottom: 20px;
    }

    .btn-validacion {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 12px;
      background: linear-gradient(135deg, #2E86AB, #4A9FD5);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 16px 52px;
      font-size: 1.08rem;
      font-weight: 700;
      letter-spacing: .06em;
      text-decoration: none;
      box-shadow: 0 6px 24px rgba(74,159,213,.4);
      transition: transform .18s, box-shadow .18s;
    }

    .btn-validacion:hover {
      transform: translateY(-3px);
      box-shadow: 0 12px 32px rgba(74,159,213,.5);
      color: #fff;
      text-decoration: none;
    }

    .btn-validacion i {
      font-size: 1.25rem;
    }

    @media (max-width: 768px) {
      .page-title { font-size: 1.45rem; }
      .welcome-card { padding: 36px 24px 32px; margin: 20px 16px; }
      .welcome-text { font-size: .97rem; }
      .btn-validacion { font-size: .95rem; padding: 14px 36px; }
    }
  </style>
</head>

<body>
<div class="page-wrapper">

  <?php include('header.php'); ?>

  <div class="page-content">
    <div class="container" id="main_container">

      <h1 class="page-title">
        <i class="fas fa-user-shield" style="margin-right:10px;color:#4A9FD5;"></i>
        <?php echo ucfirst($concurso); ?> &mdash; Perfil Jurado
      </h1>

      <!-- Tarjeta de bienvenida -->
      <div class="welcome-card">
        <div class="welcome-badge">
          <i class="fas fa-star"></i> Jurado
        </div>

        <div class="welcome-text">
          <p>Estimada persona integrante del jurado del Concurso Juvenil de Ensayo 2026 <strong>"Conversando con los clásicos"</strong>,</p>
          <p>el Instituto Electoral de la Ciudad de México le agradece su participación.</p>
          <strong>Este módulo tiene como objetivo calificar los ensayos con base en los criterios de evaluación publicados en la convocatoria.</strong>
        </div>

        <div class="divider"></div>

        <p class="welcome-text">
          <i class="fas fa-hand-point-down" style="margin-right:6px;"></i>
          Seleccione el botón para comenzar
        </p>
      </div>

      <!-- Botón Validación -->
      <div class="btn-validacion-wrap">
        <a href="g_validacion_juez.php" class="btn-validacion">
          <i class="fas fa-check-circle"></i> Ir a Validación
        </a>
      </div>

    </div>
  </div>

</div>
<!-- /page-wrapper -->
  <?php include('footer.php'); ?>
</body>

</html>