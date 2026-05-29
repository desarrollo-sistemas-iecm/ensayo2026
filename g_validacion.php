<?php
include('sqlconnector.php');
error_reporting(E_ALL ^ E_NOTICE);

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
  $queryA = 'SELECT * FROM ' . BD_USUARIOS . ' WHERE idusuario =' . $idusuario . " and usuario='" . $my_user . "' and area='ensayo' and perfil=2";

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

$nombre = '';
$paterno = '';
$materno = '';
$area = '';
$genero = '';
$fecha_nacimiento = '';
$fecha_alta = '';
$correo = '';

$categoria = '';
$titulo = '';
$tutor = '';
$correo_tutor = '';
$alcaldia = '';
$entidad = '';

$escuela = '';
$tel_escuela = '';
$te_enteraste = '';

$sobrenombre = '';

$soyoriundo = '';
$soyoriginario = '';
$tel1 = '';
$tel2 = '';

$style_disabled = '';
$style_disabled2 = '';
$edad = '';

$concurso = 'ensayo';

?>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Validación – Concursos de Ensayo</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/mycss.css" rel="stylesheet">
  <link rel="stylesheet" href="css/all.css">
  <link rel="stylesheet" href="css/all.css">
  <link href="https://fonts.googleapis.com/css2?family=Bakbak+One&family=Funnel+Sans:wght@400;600;700&display=swap" rel="stylesheet">

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="js/funcionesajax_test.js"></script>

  <style>
    html, body { height: 100%; margin: 0; font-family: 'Funnel Sans', sans-serif; background: #f0f4f8; }
    .page-wrapper { min-height: 100vh; display: flex; flex-direction: column; }
    .page-content  { flex: 1; padding: 36px 0 60px; }

    /* ── Barra de título ── */
    .val-hero {
      background: linear-gradient(135deg, #2E86AB 0%, #4A9FD5 100%);
      border-radius: 14px;
      padding: 22px 28px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 16px;
      margin-bottom: 28px;
      flex-wrap: wrap;
    }
    .val-hero h1 {
      font-family: 'Bakbak One', sans-serif;
      font-size: 1.55rem;
      color: #fff;
      margin: 0;
      letter-spacing: .04em;
    }
    .btn-regresar {
      background: rgba(255,255,255,.15);
      color: #fff;
      border: 1.5px solid rgba(255,255,255,.45);
      border-radius: 30px;
      padding: 8px 22px;
      font-size: .9rem;
      font-weight: 600;
      text-decoration: none;
      transition: background .18s;
      white-space: nowrap;
    }
    .btn-regresar:hover { background: rgba(255,255,255,.28); color: #fff; text-decoration: none; }

    /* ── Sección de búsqueda ── */
    .search-card {
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 3px 16px rgba(46,134,171,.10);
      padding: 26px 28px;
      margin-bottom: 24px;
    }
    .search-card__title {
      font-size: .78rem;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: .08em;
      color: #9ca3af;
      margin-bottom: 16px;
    }

    /* radio custom */
    .radio-opt {
      display: flex;
      align-items: center;
      gap: 8px;
      font-weight: 600;
      color: #374151;
      font-size: .9rem;
      cursor: pointer;
      padding: 6px 0;
    }
    .radio-opt input[type=radio] { accent-color: #4A9FD5; width: 16px; height: 16px; cursor: pointer; }

    /* input groups */
    .search-row { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; }
    .search-row .form-control {
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      padding: 9px 13px;
      font-size: .92rem;
      transition: border-color .2s, box-shadow .2s;
    }
    .search-row .form-control:focus {
      border-color: #4A9FD5;
      box-shadow: 0 0 0 3px rgba(74,159,213,.12);
      outline: none;
    }
    .btn-buscar {
      background: linear-gradient(135deg, #2E86AB, #4A9FD5);
      color: #fff;
      border: none;
      border-radius: 8px;
      padding: 9px 22px;
      font-size: .92rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform .15s, box-shadow .15s;
      white-space: nowrap;
    }
    .btn-buscar:hover { transform: translateY(-1px); box-shadow: 0 4px 14px rgba(74,159,213,.38); }
    .btn-buscar:disabled { opacity: .5; cursor: not-allowed; transform: none; }
    .btn-todos {
      background: #f3f4f6;
      color: #2E86AB;
      border: 1.5px solid #d1d5db;
      border-radius: 8px;
      padding: 9px 26px;
      font-size: .92rem;
      font-weight: 700;
      cursor: pointer;
      transition: background .18s;
    }
    .btn-todos:hover { background: #e5e7eb; }

    /* ── Tabla de resultados ── */
    .vl-table-wrap { margin-top: 8px; }
    .vl-count {
      font-size: .83rem;
      color: #6b7280;
      margin-bottom: 10px;
      padding: 0 2px;
    }
    .vl-count strong { color: #2E86AB; }
    .vl-table-scroll { overflow-x: auto; border-radius: 12px; box-shadow: 0 3px 18px rgba(46,134,171,.10); }
    .vl-table {
      width: 100%;
      border-collapse: separate;
      border-spacing: 0;
      background: #fff;
      border-radius: 12px;
      overflow: hidden;
      font-size: .875rem;
    }
    .vl-table thead tr {
      background: linear-gradient(135deg, #2E86AB 0%, #4A9FD5 100%);
    }
    .vl-table thead th {
      color: rgba(255,255,255,.92);
      font-family: 'Funnel Sans', sans-serif;
      font-weight: 700;
      font-size: .78rem;
      text-transform: uppercase;
      letter-spacing: .07em;
      padding: 13px 16px;
      white-space: nowrap;
      border: none;
    }
    .th-num { width: 42px; text-align: center; }
    .vl-table tbody tr { transition: background .12s; }
    .vl-table tbody tr:nth-child(even) { background: #f8fafc; }
    .vl-table tbody tr:hover { background: #eff6ff; }
    .vl-table tbody td {
      padding: 12px 16px;
      vertical-align: middle;
      border-bottom: 1px solid #e5e7eb;
      color: #374151;
    }
    .vl-table tbody tr:last-child td { border-bottom: none; }
    .td-num { text-align: center; color: #9ca3af; font-size: .78rem; }
    .td-name .name-main { font-weight: 600; color: #1f2937; }
    .td-pseudo { color: #6b7280; font-style: italic; }
    .td-folio code {
      background: #eff6ff;
      color: #2563eb;
      padding: 3px 8px;
      border-radius: 6px;
      font-size: .82rem;
      font-weight: 700;
      letter-spacing: .04em;
    }
    /* badges de estado */
    .badge-val {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      padding: 4px 11px;
      border-radius: 20px;
      font-size: .78rem;
      font-weight: 700;
      white-space: nowrap;
    }
    .badge-val--ok   { background: #d1fae5; color: #065f46; }
    .badge-val--none { background: #f3f4f6; color: #6b7280; }
    .btn-val-action {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      background: linear-gradient(135deg, #2E86AB, #4A9FD5);
      color: #fff;
      border: none;
      border-radius: 20px;
      padding: 5px 14px;
      font-size: .78rem;
      font-weight: 700;
      cursor: pointer;
      transition: transform .12s, box-shadow .12s;
      white-space: nowrap;
    }
    .btn-val-action:hover { transform: translateY(-1px); box-shadow: 0 3px 10px rgba(74,159,213,.38); }
    .validator-chip {
      display: inline-block;
      background: #eff6ff;
      color: #2E86AB;
      padding: 3px 10px;
      border-radius: 12px;
      font-size: .78rem;
      font-weight: 700;
      letter-spacing: .03em;
    }
    /* estado vacío */
    .vl-empty {
      text-align: center;
      padding: 48px 20px;
      color: #9ca3af;
      font-size: .95rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 10px rgba(0,0,0,.05);
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* resultado */
    #menu_ .card { border: none; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,.06); }
    #menu_ .card .card-body { color: #9ca3af; font-style: italic; padding: 20px; }

    /* ── Tarjeta de validación de documentos ── */
    .vd-card { background:#fff; border-radius:14px; box-shadow:0 3px 18px rgba(46,134,171,.12); overflow:hidden; }
    .vd-header {
      background: linear-gradient(135deg, #2E86AB 0%, #4A9FD5 100%);
      color:#fff; font-family:'Bakbak One',sans-serif;
      font-size:1.1rem; padding:18px 24px; letter-spacing:.03em;
    }
    .vd-body { padding:28px 24px; }
    .vd-section { margin-bottom:26px; }
    .vd-section__label {
      font-size:.75rem; font-weight:700; text-transform:uppercase;
      letter-spacing:.08em; color:#9ca3af; margin-bottom:12px;
    }
    .vd-field-row { display:flex; align-items:flex-end; gap:16px; flex-wrap:wrap; }
    .vd-field-col--file { flex:0 0 auto; }
    .vd-field-col--status { flex:0 0 145px; }
    .vd-field-col--obs { flex:1; min-width:200px; }
    .vd-label { font-size:.78rem; font-weight:700; color:#6b7280; margin-bottom:5px; display:block; }
    .btn-view-file {
      display:inline-flex; align-items:center; gap:8px;
      background:#eff6ff; color:#2563eb; border:1.5px solid #93c5fd;
      border-radius:22px; padding:9px 18px; font-size:.88rem; font-weight:700;
      text-decoration:none; transition:background .15s;
    }
    .btn-view-file:hover { background:#dbeafe; color:#1e40af; text-decoration:none; }
    .vd-nofile { color:#ef4444; font-size:.88rem; font-weight:600; }
    .vd-select {
      width:100%; border:1.5px solid #d1d5db; border-radius:8px;
      padding:9px 12px; font-size:.88rem; background:#fff;
      cursor:pointer; transition:border-color .2s;
    }
    .vd-select:focus { border-color:#4A9FD5; outline:none; box-shadow:0 0 0 3px rgba(74,159,213,.12); }
    .vd-input {
      width:100%; border:1.5px solid #d1d5db; border-radius:8px;
      padding:9px 12px; font-size:.88rem; transition:border-color .2s;
    }
    .vd-input:focus { border-color:#4A9FD5; outline:none; box-shadow:0 0 0 3px rgba(74,159,213,.12); }
    .vd-checks { display:flex; gap:28px; flex-wrap:wrap; }
    .vd-check { display:flex; align-items:center; gap:8px; font-size:.88rem; color:#6b7280; cursor:not-allowed; }
    .vd-check input[type=checkbox] { width:16px; height:16px; accent-color:#4A9FD5; cursor:not-allowed; }
    .vd-textarea {
      width:100%; border:1.5px solid #d1d5db; border-radius:8px;
      padding:10px 12px; font-size:.88rem; min-height:88px; resize:vertical;
      transition:border-color .2s; font-family:'Funnel Sans',sans-serif;
    }
    .vd-textarea:focus { border-color:#4A9FD5; outline:none; box-shadow:0 0 0 3px rgba(74,159,213,.12); }
    .vd-actions { display:flex; flex-direction:column; align-items:flex-start; gap:10px; margin-top:4px; }
    .btn-vd-save {
      background:linear-gradient(135deg,#2E86AB,#4A9FD5); color:#fff;
      border:none; border-radius:8px; padding:11px 28px; font-size:.95rem;
      font-weight:700; cursor:pointer; display:inline-flex; align-items:center; gap:8px;
      transition:transform .12s, box-shadow .12s;
    }
    .btn-vd-save:hover { transform:translateY(-1px); box-shadow:0 4px 14px rgba(74,159,213,.38); }
    .btn-vd-save:disabled { opacity:.55; cursor:not-allowed; transform:none; }

    @media (max-width: 768px) {
      .val-hero h1 { font-size: 1.2rem; }
      .search-card { padding: 18px 16px; }
      .vd-field-row { flex-direction: column; align-items: stretch; }
      .vd-field-col--status, .vd-field-col--obs { flex: 1 1 auto; }
    }
  </style>
</head>

<body>
<div class="page-wrapper">

  <?php include('header.php'); ?>

  <div class="page-content">
    <div class="container" id="main_container">

      <!-- Hero / título -->
      <div class="val-hero">
        <h1><i class="fas fa-check-double" style="margin-right:10px;opacity:.85;"></i><?php echo ucfirst($concurso); ?> &mdash; Validación</h1>
        <a href="maincentrales.php" class="btn-regresar"><i class="fas fa-arrow-left" style="margin-right:6px;"></i>Regresar</a>
      </div>

      <?php
      include 'sqlconnector.php';
      $idusuario = $_SESSION['idusuario'];
      ?>

      <!-- Tarjeta de búsqueda -->
      <div class="search-card">
        <div class="search-card__title"><i class="fas fa-search" style="margin-right:6px;"></i>Parámetros de búsqueda</div>

        <!-- Por sobrenombre -->
        <div class="row align-items-center mb-3">
          <div class="col-sm-3">
            <label class="radio-opt">
              <input type="radio" name="radio_busqueda" value="1" checked="checked" onchange="fnRadioBusqueda();">
              Por sobrenombre
            </label>
          </div>
          <div class="col-sm-9">
            <div class="search-row">
              <input type="text" class="form-control flex-grow-1" id="select_nombre" name="select_nombre"
                     placeholder="Escribe el sobrenombre o nombre" onchange="fnBusquedaNombre()">
              <button class="btn-buscar" id="btn_nombre" onclick="fnBusquedaNombre()">
                <i class="fas fa-search" style="margin-right:5px;"></i>Buscar
              </button>
            </div>
          </div>
        </div>

        <!-- Por folio -->
        <div class="row align-items-center mb-3">
          <div class="col-sm-3">
            <label class="radio-opt">
              <input type="radio" name="radio_busqueda" value="2" onchange="fnRadioBusqueda();">
              Por folio
            </label>
          </div>
          <div class="col-sm-9">
            <div class="search-row">
              <input type="text" class="form-control flex-grow-1" id="select_folio" name="select_folio"
                     placeholder="Número de folio" onchange="fnBusquedaFolio()" disabled>
              <button class="btn-buscar" id="btn_folio" onclick="fnBusquedaFolio()" disabled>
                <i class="fas fa-search" style="margin-right:5px;"></i>Buscar
              </button>
            </div>
          </div>
        </div>

        <!-- Todos -->
        <div class="row align-items-center">
          <div class="col-12 text-center">
            <button class="btn-todos" id="btn_todos" onclick="fnBusquedaTodos()">
              <i class="fas fa-list" style="margin-right:7px;"></i>Mostrar todos los registros
            </button>
          </div>
        </div>

        <!-- Por distrito (oculto) -->
        <div class="row" style="display:none;">
          <div class="col-sm-3">
            <label class="radio-opt">
              <input type="radio" name="radio_busqueda" value="3" onchange="fnRadioBusqueda();">
              Por distrito
            </label>
          </div>
          <div class="col-sm-9">
            <div class="search-row">
              <select class="form-control" id="select_distrito" onchange="fnBusquedaDistrito()" disabled>
                <option selected disabled value="0">Todos</option>
                <?php for ($i = 1; $i <= 33; $i++) { echo "<option value='" . $i . "'>" . $i . "</option>"; } ?>
              </select>
            </div>
          </div>
        </div>

      </div><!-- /search-card -->

      <!-- Resultados -->
      <div class="tab-content">
        <div class="tab-pane container active" id="menu_">
          <div class="row card">
            <div class="card-body">
              <i class="fas fa-info-circle" style="margin-right:6px;color:#93c5fd;"></i>
              Selecciona un parámetro de búsqueda para ver los registros.
            </div>
          </div>
        </div>
      </div>

      <div id="div_errors"></div>

    </div>
  </div><!-- /page-content -->

  <?php include('footer.php'); ?>

</div><!-- /page-wrapper -->
</body>
</html>
