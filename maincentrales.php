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
  $queryA = 'SELECT * FROM ' . BD_USUARIOS . ' WHERE idusuario =' . $idusuario . ' and perfil=2';

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
  <title>Concursos de divulgación | Perfil Central</title>

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
      margin-bottom: 36px;
      letter-spacing: .03em;
    }

    /* Botón Validación */
    .btn-validacion {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      background: linear-gradient(135deg, #2E86AB, #4A9FD5);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 13px 40px;
      font-size: 1rem;
      font-weight: 700;
      letter-spacing: .04em;
      text-decoration: none;
      box-shadow: 0 4px 18px rgba(74, 159, 213, .35);
      transition: transform .18s, box-shadow .18s;
      margin: 0 auto 40px;
      width: fit-content;
    }

    .btn-validacion:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 26px rgba(74, 159, 213, .45);
      color: #fff;
      text-decoration: none;
    }

    .btn-validacion i {
      font-size: 1.2rem;
    }

    /* Cards de reporte */
    .report-card {
      background: rgba(255, 255, 255, .72);
      backdrop-filter: blur(10px);
      -webkit-backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, .6);
      border-radius: 20px;
      box-shadow: 0 6px 24px rgba(46, 134, 171, .13);
      padding: 52px 36px;
      text-align: center;
      text-decoration: none;
      color: #374151;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      transition: transform .2s, box-shadow .2s;
      cursor: pointer;
      height: 100%;
    }

    .report-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 16px 40px rgba(46, 134, 171, .22);
      text-decoration: none;
      color: #2E86AB;
    }

    .report-card .excel-icon {
      width: 86px;
      height: 86px;
      border-radius: 20px;
      background: linear-gradient(135deg, #16a34a, #15803d);
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 6px 20px rgba(21, 128, 61, .38);
    }

    .report-card .excel-icon i {
      font-size: 2.8rem;
      color: #fff;
    }

    .report-card .card-label {
      font-family: 'Funnel Sans', sans-serif;
      font-weight: 700;
      font-size: 1.25rem;
      color: #2E86AB;
    }

    .report-card .card-sub {
      font-size: .92rem;
      color: #6b7280;
      line-height: 1.5;
    }

    @media (max-width: 768px) {
      .page-title {
        font-size: 1.5rem;
      }

      .btn-validacion {
        font-size: .9rem;
        padding: 11px 30px;
      }

      .report-card {
        padding: 40px 24px;
      }

      .report-card .excel-icon {
        width: 70px;
        height: 70px;
      }

      .report-card .excel-icon i {
        font-size: 2.2rem;
      }

      .report-card .card-label {
        font-size: 1.1rem;
      }
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
          <?php echo ucfirst($concurso); ?> &mdash; Perfil Central
        </h1>

        <!-- Botón Validación -->
        <div class="text-center">
          <a href="g_validacion.php" class="btn-validacion">
            <i class="fas fa-check-circle"></i> Validación
          </a>
        </div>

        <!-- Cards de reportes -->
        <div class="row justify-content-center" style="max-width:900px;margin:0 auto;">

          <div class="col-md-5 mb-4">
            <a href="g_reporte1.php" class="report-card">
              <div class="excel-icon"><i class="fas fa-file-excel"></i></div>
              <div class="card-label">Reporte de validados</div>
              <div class="card-sub">Descarga el listado de participantes validados</div>
            </a>
          </div>

          <div class="col-md-5 mb-4">
            <a href="g_reporte2.php" class="report-card">
              <div class="excel-icon"><i class="fas fa-file-excel"></i></div>
              <div class="card-label">Reporte de registrados</div>
              <div class="card-sub">Descarga el listado completo de registros</div>
            </a>
          </div>
          
              <div class="col-md-5 mb-4">
            <a href="g_reporte3.php" class="report-card">
              <div class="excel-icon"><i class="fas fa-file-excel"></i></div>
              <div class="card-label">Reporte de ensayos calificados</div>
              <div class="card-sub">Descarga el listado completo de ensayos calificados</div>
            </a>
          </div>

        </div>

      </div>
    </div><!-- /page-content -->

    <?php include('footer.php'); ?>

  </div><!-- /page-wrapper -->
</body>

</html>
