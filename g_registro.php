<?php

include('sqlconnector.php');

session_start();
///////////////////////////////////////////////////////sesión
if (isset($_SESSION['idusuario'])) {
  $idusuario = $_SESSION["idusuario"];
  $my_user = $_SESSION["usr"];
  $my_pwd = $_SESSION["pwd"];
  $area = $_SESSION["area"];
  $concurso = $area;
  $distrito = $_SESSION["distrito"];

  //echo "*/".$distrito."/*";
  //$queryA="SELECT * FROM usuarios WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='".$area."'";
  $queryA = "SELECT * FROM " . BD_USUARIOS . " WHERE idusuario =" . $idusuario . " and usuario='" . $my_user . "' and area='ensayo' and (perfil=2 OR perfil=3)";

  //  echo $query;
  $resA = sqlsrv_query($conn, $queryA);
  if ($rowA = sqlsrv_fetch_array($resA)) {
  } else {
    session_destroy();
    header("location:index.php");
  }
} else {

  session_destroy();
  header("location:index.php");
}

$nombre = "";
$paterno = "";
$materno = "";
$area = "";
$genero = "";
$fecha_nacimiento = "";
$fecha_alta = "";
$correo = "";
$categoria = "";
$titulo = "";
$tutor = "";
$correo_tutor = "";
$alcaldia = "";
$entidad = "";
$escuela = "";
$tel_escuela = "";
$te_enteraste = "";
$sobrenombre = "";
$soyoriundo = "";
$soyoriginario = "";
$tel1 = "";
$tel2 = "";
$style_disabled = "";
$style_disabled2 = "";
$edad = "";
$concurso = "ensayo";



?>
<html>

<head>
  <meta charset="utf-8">
  <title>Concursos de divulgacion</title>

  <!-- Bootstrap core CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/mycss.css" rel="stylesheet">

  <!-- Bootstrap core JavaScript
    ================================================== -->
  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <!--<script src="js/holder.min.js"></script>-->
  <script src="js/funcionesajax_test.js"></script>
  <link rel="stylesheet" href="css/all.css">
</head>

<body>

  <div class="container" id="topmenu">
    <div class="row">
      <div class="col-sm-8"></div>
      <div class="col-sm-2">
        <button class="btn" style="background-color: #FFF;">
          <?php
          //$completo = $mynombre.''.$mypaterno.''.$mymaterno;
          //echo $_SESSION["idusuario"]." / ";
          echo "Usuario: " . $my_user;
          //echo $_SESSION["perfil"];
          ?>
        </button>

      </div>
      <div class="col-sm-1">
        <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
      </div>
    </div>
  </div>



  <?php include('header.php'); ?>
  <div class="row btn-portada2">
    <img src="img/ensayo.png">
  </div>

  <div class="container" id="main_container">
    <div class="row">
      <div class="col-sm-10">
        <h1><?php echo ucfirst($concurso); ?></h1>
      </div>
      <div class="col-sm-2">
        <?php
        if ($distrito == 40) {
          echo '<a href="maincentrales.php" class="btn btn-secondary">Regresar</a>';
        } else {
          echo '<a href="maindistrito.php" class="btn btn-secondary">Regresar</a>';
        }
        ?>
      </div>
    </div>

    <div id="div_errors"></div>

    <div id="div_registro">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a class="nav-link " data-toggle="tab" href="#menu1">Datos</a>
        </li>
        <li class="nav-item ">
          <a class="nav-link disabled" data-toggle="tab" href="#menu2" id="navmenu2">Documentación</a>
        </li>
      </ul>

      <div class="tab-content">
        <div class="tab-pane container active" id="menu1">
          <!------------------  DATOS ---->
          <?php
          include("datospersonalesdistrito.php");
          ?>
          <!------------------ DATOS ----->
        </div>

        <div class="tab-pane container" id="menu2">
          <?php
          //include("formatosdescarga.php");
          include("adjuntardocumentaciondistrito.php");
          ?>
        </div>
      </div>
    </div><!--- div_registro-->
  </div> <!--- cierra  conteiner  -->
  <?php include('footer.php'); ?>
</body>

</html>