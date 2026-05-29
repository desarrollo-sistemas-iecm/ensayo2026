<?php

include('sqlconnector.php');
error_reporting(E_ALL ^ E_NOTICE);

session_start();
  ///////////////////////////////////////////////////////sesión
if(isset($_SESSION['idusuario'])){
  $idusuario=$_SESSION["idusuario"];
  $my_user=$_SESSION["usr"];
  $my_pwd=$_SESSION["pwd"];
  $area=$_SESSION["area"];
  $concurso=$area;
  $distrito=$_SESSION["distrito"];


      //$queryA="SELECT * FROM usuarios WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='".$area."'";
  $queryA="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='ensayo' and perfil=3";

// echo $queryA;
  $resA=sqlsrv_query($conn,$queryA);
  if($rowA= sqlsrv_fetch_array($resA)){


  }else{
    session_destroy();
    header("location:index.php");

  }



}else{

  session_destroy();
  header("location:index.php");
}

      $nombre="";
      $paterno="";
      $materno="";
      $area="";
      $genero="";
      $fecha_nacimiento="";

      $fecha_alta="";
      $correo="";

      $categoria="";
      $titulo="";
      $tutor="";
      $correo_tutor="";
      $alcaldia="";
      $entidad="";

      $escuela="";
      $tel_escuela="";
      $te_enteraste="";
      $domicilio="";
      

      $sobrenombre="";
     
      $soyoriundo="";
      $soyoriginario="";
      $tel1="";
      $tel2="";
      $concurso="ensayo";



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
            echo "Usuario: ".$my_user	;
            //echo $_SESSION["perfil"];
            ?>
          </button>

        </div>
        <div class="col-sm-1">
          <a href="logout.php" class="btn btn-secondary">Cerrar sesión</a>
        </div>

      </div>

    </div>



    <?php include('header.php');?>
    <div class="row btn-portada2">

      <img src="img/ensayo.png">

    </div>
    <div class="container" id="main_container">

      <div class="row" style="margin-bottom: 10px;">
                   <div class="col-sm-10">
                     <h2><?php echo ucfirst($concurso);?> - Perfil central</h2>
                   </div>
                   <div class="col-sm-2">
                    
                     <a href="maincentrales.php" class="btn btn-secondary">Regresar</a>
                   </div>
                </div>
      
      <?php


      include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];
      $idusuario=$_SESSION["idusuario"];
// define("UPLOAD_DIR", "uploads/");

      ?>

     

      <div class="row">

        <div class="col-sm-3">
          <input type="radio" name="radio_busqueda" value="1" checked="checked" onchange="fnRadioBusqueda();" />
          <label> Por sobrenombre</label>
        </div>

        <div class="col-sm-8">
          <div class="input-group mb-3"> 
          <div class="input-group-prepend">
            <label class="input-group-text" for="select_nombre">Nombre</label>
          </div>
          <input type="text" class="form-control" id="select_nombre" name="select_nombre" placeholder="Nombre" onchange="fnBusquedaNombre()">
          <button class="form-control btn btn-primary" id="btn_nombre" name="btn_nombre" onclick="fnBusquedaNombre()">Buscar</button>
        </div>
      </div>

    </div>

    <!-- por folio-->
    <div class="row">

      <div class="col-sm-3">
        <input type="radio" name="radio_busqueda" value="2" onchange="fnRadioBusqueda();" />
        <label> Por folio</label>
      </div>

      <div class="col-sm-8">

       <div class="input-group mb-3"> 

        <div class="input-group-prepend">
          <label class="input-group-text" for="select_folio">Folio</label>
        </div>
        <input type="text" class="form-control" id="select_folio" name="select_folio" placeholder="Folio" onchange="fnBusquedaFolio()" disabled>
         <button class="form-control btn btn-primary" id="btn_folio" name="btn_folio" onclick="fnBusquedaFolio()" disabled>Buscar</button>

      </div>

    </div>


  </div>
        
        
  <div class="row" >

    <div class="col-sm-3">
     <input type="radio" name="radio_busqueda" value="3" onchange="fnRadioBusqueda();"/>
     <label>  Todos los registros</label>
   </div>

   <div class="col-sm-8">

     <div class="input-group mb-3"> 

      <button class="form-control btn btn-primary" id="btn_todos" name="btn_todos" onclick="fnBusquedaNombre()" disabled>Buscar</button>
     

    </div>

  </div>


</div>

  <!----  termina folio -->       


  <div class="row" style="display: none;">

    <div class="col-sm-3">
     <input type="radio" name="radio_busqueda" value="3" onchange="fnRadioBusqueda();"/>
     <label>  Por distrito</label>
   </div>

   <div class="col-sm-8">

     <div class="input-group mb-3"> 

      <div class="input-group-prepend">
        <label class="input-group-text" for="select_distrito">Distrito</label>
      </div>

      <select class="custom-select" id="select_distrito" onchange="fnBusquedaDistrito()" disabled>
        <option selected disabled value="0">Todos</option>
        <?php
        for($i=1;$i<=33;$i++){
          echo "<option value='".$i."'>".$i."</option>";

        }

        ?>


      </select>

    </div>

  </div>


</div>


<div class="tab-content">
  <div class="tab-pane container active" id="menu_">

    <div class="row card">
      <div class="card-body">
        Selecciona un parámetro para búsqueda
      </div>
    </div>

  </div>


</div>

<div id="div_errors"></div> 







</div> <!--- cierra  conteiner  -->
<?php include('footer.php');?>
</body>
</html>
