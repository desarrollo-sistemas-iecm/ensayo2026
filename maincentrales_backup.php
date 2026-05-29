<?php

 include('sqlconnector.php');

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
      $queryA="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario." and perfil=3";

    //  echo $query;
      $resA=sqlsrv_query($conn,$queryA);
      if($rowA= sqlsrv_fetch_array($resA)){
       

      }else{
        session_destroy();
        header("location:index.php");
        
      }

 
    
  } else{
    
        session_destroy();
        header("location:index.php");
  }

 
?>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
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
                   <div class="col-sm-5">
                     <h2><?php echo ucfirst($concurso);?> - Perfil central</h2>
                   </div>
                   <div class="col-sm-5">
                    
                     <!-- <a href="index.php" class="btn btn-secondary">Regresar</a>-->
                   </div>
                </div>
              
                 <div id="div_errors"></div>

                <div class="row">
                 
                  <div class="col-sm-1">
                  </div>
                  
                  <div class="col-sm-6">
                   <!-- <p><a href="g_registro.php" class="btn btn-primary btn-block">Registro</a> <a href="g_listadodistrito.php" class="btn btn-secondary btn-block">Listado (Registros de usuarios en perfil central)</a></p>-->
                    <p><a href="g_validacion.php" class="btn btn-primary btn-block">Validación</a></p>
                    
                    <p></p>

                    <p><a href="g_reporte1.php" class="btn btn-primary btn-block">Reporte de validados</a></p>
                    <p><a href="g_reporte2.php" class="btn btn-primary btn-block">Reporte de registrados</a></p>
                  </div>
                  
                  
                </div>
	
	              

              
			
	       
			
			    
			
	   </div> <!--- cierra  conteiner  -->
   <?php include('footer.php');?>
</body>
</html>
