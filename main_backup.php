<?php

 include('sqlconnector.php');
 error_reporting(E_ALL ^ E_NOTICE);
 $cat_genero=$arrayName = array('M' =>'Masculino' ,'F' =>'Femenino','X' =>'Otro' );
 

  session_start();
  ///////////////////////////////////////////////////////sesión
  if(isset($_SESSION['idusuario'])){
    $idusuario=$_SESSION["idusuario"];
    $my_user=$_SESSION["usr"];
    $my_pwd=$_SESSION["pwd"];
    $area=$_SESSION["area"];
    $concurso=$area;
    $distrito=0;

      //$queryA="SELECT * FROM usuarios WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='".$area."'";
      $queryA="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='ensayo'";

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

  $query1="SELECT * FROM ".BD_PARTICIPANTES." WHERE idusuario =".$idusuario."";
  //  echo $query;
    $res1=sqlsrv_query($conn,$query1);
    if($row1= sqlsrv_fetch_array($res1)){
      $registrado=true;
      $folio=$row1['folio'];
       $style_disabled2=" disabled style='background-color:#f7f7f7;'";

    }else{
      $registrado=false;
      $folio=" - ";
      $style_disabled2="";
    }
     $style_disabled=" disabled style='background-color:#f7f7f7;'";
      $idusuario=$_SESSION["idusuario"];


 

 if($registrado){

    $query = "SELECT A.*,B.correo, B.area, B.fecha_nacimiento FROM ".BD_PARTICIPANTES." as A LEFT JOIN ".BD_USUARIOS." as B ON A.idusuario = B.idusuario WHERE A.idusuario = ?";
    $res = sqlsrv_query($conn, $query, array($idusuario));
    
    if($row= sqlsrv_fetch_array($res)){
      $nombre=$row["nombre"];
      $paterno=$row["paterno"];
      $materno=$row["materno"];
      $area=$row["area"];
      $genero=$row["genero"];
      $fecha_nacimiento=$row["fecha_nacimiento"];
      $fecha_alta=$row["fecha_alta"];
      $correo=$row["correo"];

      $categoria=$row["categoria"];
      $titulo=$row["titulo"];
      $tutor=$row["tutor"];
      $correo_tutor=$row["correo_tutor"];
      $alcaldia=$row["alcaldia"];
      $entidad=$row["entidad"];
      $domicilio=$row["domicilio"];

      $escuela = $row["escuela"] ?? '';
      $tel_escuela = $row["tel_escuela"] ?? '';
      $te_enteraste=$row["te_enteraste"];
      

      $sobrenombre=$row["sobrenombre"];
      $tel1=$row["tel1"];
      $tel2=$row["tel2"];
     
    }


    //$style_disabled2=" disabled style='background-color:#f7f7f7;'";

 }
 if(!$registrado){


 

    $query="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario."";
  //  echo $query;
    $res=sqlsrv_query($conn,$query);
    if($row= sqlsrv_fetch_array($res)){
      $nombre=$row["nombre"];
      $paterno=$row["paterno"];
      $materno=$row["materno"];
      $area=$row["area"];
      $genero=$row["genero"];
      $fecha_nacimiento=$row["fecha_nacimiento"];

      $fecha_alta=$row["fecha_alta"];
      $correo=$row["correo"];

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
      

    }
    //$style_disabled2="";//" disabled style='background-color:#FFF;'";

  }

    //$style_disabled=" disabled style='background-color:#f7f7f7;'";

    $edad=date_diff(date_create($fecha_nacimiento), date_create($fecha_alta))->y;
    if($edad>=8&&$edad<=11){$categoria="1";} 
    if($edad>=12&&$edad<=14){$categoria="2";}
    if($edad>=14&&$edad<=18){$categoria="3";}
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
     
        <img src="img/<?php echo $concurso;?>.png">
     
     </div>
        <div class="container" id="main_container">
	
                 <div class="row" style="margin-bottom: 10px;">
                   <div class="col-sm-5">
                     <h2><?php echo ucfirst($concurso);?></h2>
                   </div>
                   <div class="col-sm-5">
                    <div class="card">
                      <span>No. de folio: <b><?php echo $folio; ?></b></span>
                    </div>
                     <!-- <a href="index.php" class="btn btn-secondary">Regresar</a>-->
                   </div>
                </div>
              
                 <div id="div_errors"></div>
	
	              <ul class="nav nav-tabs">
                  <li class="nav-item">
                    <a class="nav-link " data-toggle="tab" href="#menu1">Datos</a>
                  </li>
                  <li class="nav-item ">
                    <a class="nav-link <?php if(!$registrado) echo "disabled"?>" data-toggle="tab" href="#menu2"  >Formatos para descarga</a>
                  </li>
                  
                
              </ul>

              
			
	       
			
			    <div class="tab-content">
                <div class="tab-pane container <?php if(!$registrado) echo "active"?>" id="menu1">

                  <?php

                  if(!$registrado){
                    echo'
                          <div class="row" id="anim">
                            <div class="bg-primary text-white anim1">
                              Primero, completa tus datos personales. Luego deberás adjuntar tus archivos<i class="fas fa-long-arrow-alt-down"></i>
                            </div>
                            
                          </div>';
                        }
                  ?>

                  <!------------------  DATOS ---->
                  <?php

                    include("datospersonales.php");
                  

                  ?>
                  <!------------------ DATOS ----->
              

                </div>
				  
                <div class="tab-pane container <?php if($registrado) echo "active"?>" id="menu2">

                  
                  <?php

                    //include("formatosdescarga.php");
                    include("adjuntardocumentacion.php");
                  

                  ?>
                  


              
                </div>

                
                
              </div>
			
	</div> <!--- cierra  conteiner  -->
   <?php include('footer.php');?>
</body>
</html>
