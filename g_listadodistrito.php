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
      $queryA="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='ensayo' and (perfil=2 OR perfil=3) ";

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


      //$concurso="grafiti";


  
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
     
        <img src="img/grafiti.png">
     
     </div>

        <div class="container" id="main_container">
	
                 <div class="row">
                   <div class="col-sm-10">
                     <h1><?php echo ucfirst($concurso);?></h1>
                   </div>
                   
                   <?php
                   if($distrito=='40'){
                    echo '<div class="col-sm-2">
                             <a href="maincentrales.php" class="btn btn-secondary">Regresar</a>
                          </div>';

                   }else{
                     echo '<div class="col-sm-2">
                             <a href="maindistrito.php" class="btn btn-secondary">Regresar</a>
                          </div>';

                   }


                   ?>
                  
                </div>
                  
                
              
                 <div id="div_errors"></div>
                  <?php
                   echo '<hr>';
                  echo '<div class="row">';
                      echo '<div class="col-sm">';
                        echo 'Folio';
                      echo '</div>';
                      echo '<div class="col-sm">';
                         echo 'Nombre';
                      echo '</div>';

                      echo '<div class="col-sm">';
                         echo 'Correo';
                      echo '</div>';

                      echo '<div class="col-sm">';
                         echo 'Correo tutor';
                      echo '</div>';

                      echo '<div class="col-sm">';
                         echo 'Edad';
                      echo '</div>';
                      echo '<div class="col-sm">';
                         echo 'Concurso';
                      echo '</div>';
                      echo '<div class="col-sm">';
                         echo 'Acuse';
                      echo '</div>';



                    echo '</div>';
                    echo '<hr>';

                 

                 $query="SELECT * FROM ".BD_PARTICIPANTES." WHERE iddistrito =".$distrito." order by CAST(SUBSTRING(folio,4,3) as int) DESC";

               
                  $res=sqlsrv_query($conn,$query);
                  while($row= sqlsrv_fetch_array($res)){
                    echo '<div class="row">';
                      echo '<div class="col-sm">';
                        echo $row['folio'];
                      echo '</div>';
                      echo '<div class="col-sm">';
                        echo $row['nombre']." ".$row['paterno']." ".$row['materno'];
                      echo '</div>';

                      echo '<div class="col-sm">';
                        echo $row['correo'];
                      echo '</div>';

                      echo '<div class="col-sm">';
                        echo $row['correo_tutor'];
                      echo '</div>';

                      echo '<div class="col-sm">';
                        echo date_diff(date_create($row['fecha_nacimiento']),date_create($row['fecha_alta']))->y;
                      echo '</div>';
                       echo '<div class="col-sm">';
                        echo 'Ensayo';
                      echo '</div>';

                       echo '<div class="col-sm">';
                         echo '<form method="post" action="descargaracuse.php" target="_blank">
                                  <button type="submit" class="btn btn-primary">Descargar acuse</button>
                                  <input type="hidden" name="folio"  value="'.$row['folio'].'">

                            </form>';
                      
                      echo '</div>';



                    echo '</div>';
                  }
                    
                   

                 



                 ?>
	
	             

              
			
	       
			
			    
			
	</div> <!--- cierra  conteiner  -->
   <?php include('footer.php');?>
</body>
</html>
