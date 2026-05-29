<?php
	session_start();
	error_reporting(0);

  $concurso= $_GET['concurso'];
  if(!isset($_GET['concurso'])){
    header("Location: index.php");

  }


  if($concurso=="ensayo"){
    //$fecha1="2019-04-22";
    $fecha1="2026-04-08";
    $fecha2="2026-12-31";

       
  }else{
    $fecha1="2026-12-12";
    $fecha2="2026-12-12";

  }
   //echo " / ".date("Y-m-d")." / ".date($fecha1)." / ".date($fecha2);



    if(date("Y-m-d")>=date($fecha1)&&date("Y-m-d")<=date($fecha2)){
    //  echo "dentro de rango";
      $fecha_registro="";
        
    }else{
    //  echo "fuera de rango";
           
      $fecha_registro="disabled";

    }



  if( isset($_POST['usr']) && isset($_POST['pwd'])){

    include('sqlconnector.php');

    $nomcompleto = "";
    $usr = trim($_POST['usr']);
    $pwd = $_POST['pwd'];
    $concurso = $_POST['concurso'];
    
    $query = "SELECT * FROM " . BD_USUARIOS . " WHERE usuario = ? and contrasena = ? and area = ? and estatus = 1";
    $params = array($usr, $pwd, $concurso);
    $row0 = sqlsrv_query($conn, $query, $params);

    if($row = sqlsrv_fetch_array($row0)){

      $id = $row['idusuario'];
      $perfil = $row['perfil'];
      $status = $row['estatus'];
      $usr = $row['usuario'];
      $pwd = $row['contrasena'];
      $area = $row['area'];
      $distrito = $row['iddistrito'];

      if($status=='1' && $area==$concurso && $perfil=='1'){

        $_SESSION["idusuario"] = $id;
        $_SESSION["usr"] = $usr;
        $_SESSION["pwd"] = $pwd;
        $_SESSION["nombre"]=$nombre;
  	    $_SESSION["paterno"]=$paterno;
  	    $_SESSION["materno"]=$materno;
        $_SESSION["perfil"] =$perfil;
        $_SESSION["area"]=$area;

  		    header("Location: main.php");
          //header("Location: registroterminado.php");



        //echo "true";  
      }else{

        session_destroy();
		 header("Location: login.php?activado=0&concurso=".$concurso."");	 
      //echo " / ".$area." // ".$concurso." / ";
		  
	    }

      if($perfil=='2'){

          session_start();

        $_SESSION["idusuario"] = $id;
        $_SESSION["usr"] = $usr;
        $_SESSION["pwd"] = $pwd;
        $_SESSION["nombre"]=$nombre;
        $_SESSION["paterno"]=$paterno;
        $_SESSION["materno"]=$materno;
        $_SESSION["perfil"] =$perfil;
        $_SESSION["area"]=$area;
        $_SESSION["distrito"]=$distrito;

       


          header("Location: maincentrales.php");
        echo "true";  
      }

      if($perfil=='3'){

          session_start();

        $_SESSION["idusuario"] = $id;
        $_SESSION["usr"] = $usr;
        $_SESSION["pwd"] = $pwd;
        $_SESSION["nombre"]=$nombre;
        $_SESSION["paterno"]=$paterno;
        $_SESSION["materno"]=$materno;
        $_SESSION["perfil"] =$perfil;
        $_SESSION["area"]=$area;
        $_SESSION["distrito"]=$distrito;

       


          header("Location: mainjueces.php");
        echo "true";  
      }


    }else{
      session_destroy();
		  header("Location: login.php?act=0&concurso=".$concurso.""); ////no existe
        //echo "false";
    }

	}else{


?>

 <!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>

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
<body >



<?php
	include('header.php');
?>

<div class="row btn-portada2">
     
      <img src="img/ensayo.png">
     
  </div>

<!-- //////////////////////////////////////////////////////////// -->
<div class="container" id="main_container"> <!-- container 2 -->
   
<div class="container">
  
  
  

  <div class="row">
     <div class="col-sm-10">
       <h1><?php echo ucfirst($concurso);?></h1>
     </div>
     <div class="col-sm-2">
        <a href="index.php" class="btn btn-secondary">Regresar</a>
     </div>
  </div>


  <div class="row">
    <div class="col-md-5">
      <div class="card" >
        <div class="card-header">Ingreso</div>
        <div class="card-body">
            <!-- ////////////////////////////////////////////////////////////// -->


          <form class="form-horizontal" id="loginform"  action="login.php" method="post">
            <input type="hidden" name="concurso" value="<?php echo $concurso;?>">
            <div class="form-group">

             <!-- <label class="control-label" for="usr">Usuario:</label>-->
              <div class="col-sm-12">
                <input type="text" class="form-control" id="usr" name="usr" placeholder="Nombre de usuario" required>
              </div>
            </div>
            <div class="form-group">
              <!-- <label class="control-label" for="pwd">Password:</label>-->
              <div class="col-sm-12">
                <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Contraseña" required>
              </div>
            </div>
              <div class="form-group">
              <div class="col-sm-12">
              <button type="submit" name="submit" class="btn btn-primary btn-block">Ingresar</button>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col">
                  <button type="button" class="btn btn-light btn-block" onclick="location.href='recuperarcontrasena.php'">Recuperar contraseña</button>
              </div>
            </div>
            
  <?php 
            if( isset($_GET['act'])){
               echo '<div class="alert alert-success" align="center">Usuario o contraseña no existe</div>';
            }
  	   if( isset($_GET['activado'])){
               echo '<div class="alert alert-success" align="center">Usuario NO Activo</div>';
            }
  ?>
          </form>

        </div>
    </div>
  </div>
  
  <div class="col-md-2">
  </div>
  
  <div class="col-md-5">
    <div class="row" id="anim">
      <div class="bg-primary text-white anim1">
        ¿Eres nuevo aquí? Primero regístrate  <i class="fas fa-long-arrow-alt-down"></i>
      </div>
      
    </div>
    <div class="card">
      <div class="card-header">Nuevo usuario</div>
      <div class="card-body">

        <div class="form-group">
          <form action="usuarios.php" method="post" >
            <div class="col">
              <input type="hidden" name="concurso" value="<?php echo $concurso;?>">

              <button  type="submit" class="btn btn-primary btn-block" <?php //echo $fecha_registro;?> >Registro de usuario</button>
            </div>
            <hr>
            <div class="col">
              
              <p> Periodo de registro:</p> <p>De las 9:00 horas del 8 de julio hasta las 17:00 horas del 08 de agosto de 2022. Para todos los efectos se considerará la zona horaria del centro de la república
                mexicana.</p>
            </div>
          </form>
            
          </div>


        
      </div>
    </div>
  </div>

  </div> <!-- row -->

  <div class="row"><!-- row 2 -->
   
      
      <div class="card" style="width:100%;">
        <div class="card-header">
          <button class="btn btn-secondary btn-block" type="button" data-toggle="collapse" data-target="#collapse1">
            Formatos para descarga
          </button>
        </div>
      <div class="card-body">
      <div class="collapse" id="collapse1">
        <div class="d-flex justify-content-center"><p>Descarga los siguientes formatos y llénalos con tinta azul.</p></div>
        
          <div class="row">
             
            <div class="col-sm-1">
            </div>
            <div class="col-sm-5">
              <div class="d-flex justify-content-center"><p>Formatos para participante.</p></div>
                        
              
              <div class="row">
                <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="documentos_concursos/carta_cesion.docx" target="_blank">Carta de cesión de derechos.</a></div>
              </div>

        

               <div class="row">
                <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="documentos_concursos/manifestacion_bajo_protesta.docx" target="_blank">Manifestación bajo protesta de decir verdad que cumple con la edad requerida para registrarse en la convocatoria.</a></div>
              </div>

            </div>

            <div class="col-sm-1">
            </div>
            
                            
        </div>
        

    </div><!---collapse -->
    </div><!--card body-->
  </div><!---card-->
  </div><!--- row2 -->






</div><!-- otro container -->





</div> <!--- /maincontainer -->

<!-- //////////////////////////////////////////////////////////// -->


<?php
	include('footer.php');
?>


</body>
</html> 

<?php

}//////else default

?>