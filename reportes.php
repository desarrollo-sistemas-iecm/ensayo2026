<?php
	
include 'sqlconnector.php';
session_start();
$idusuario=$_SESSION["idusuario"];
	
error_reporting(0);

	
$accion=$_POST['accion'];

 $val= $_POST['val'];
	

	//echo "accion en la que entroooo  ". $accion;
	
	
  if($accion=="folio"){


		$query= "SELECT * FROM participantes_grafiti as A INNER JOIN usuarios as B ON A.idusuario= B.idusuario and folio like '%$val%' and A.iddistrito=0";
     	 
	/* $query ="SELECT * FROM  participantes_grafiti where folio like '%$val%' ";*/

  }


  if($accion=="nombre"){

    
	  
	 	$query= "SELECT A.*,B.fecha_nacimiento, B.usuario, B.iddistrito FROM participantes_grafiti as A INNER JOIN usuarios as B ON A.idusuario= B.idusuario and sobrenombre like '%$val%' and A.iddistrito=0"; 
 		//$query ="SELECT * FROM  participantes_grafiti where sobrenombre like '%$val%' ";
	  	

  }

  if($accion=="distrito"){


     $query =" SELECT A.*,B.fecha_nacimiento, B.usuario, B.iddistrito FROM participantes_grafiti as A INNER JOIN usuarios as B ON A.idusuario= B.idusuario and B.iddistrito = $val ";


  }

   

    //echo " * ".$query." * ";
echo '<hr>';

 echo '<div class="grouprowconsultas">';
    	echo '<div class="row headerconsultas">
        			<div class="col-sm-4">
        				Nombre 
        			</div>
        			<div class="col-sm-2">
        				Folio
        			</div>
        			<div class="col-sm-2">
        				Documento
        			</div>
      				<div class="col-sm-4">
          				Usuario que realizó la validación
          		</div>
		
    		  </div>';
	 
   
   	 

 // $query="SELECT A.*,B.fecha_nacimiento, B.usuario FROM participantes_grafiti as A LEFT JOIN usuarios as B ON A.idusuario= B.idusuario WHERE A.idusuario =".$idusuario."";
 // $query="SELECT A.*,B.fecha_nacimiento, B.usuario FROM participantes_grafiti as A LEFT JOIN usuarios as B ON A.idusuario= B.idusuario ";

//  echo $query;

    $res=sqlsrv_query($conn,$query);
    
 //if($row= sqlsrv_fetch_array($res)){
	 while($row= sqlsrv_fetch_array($res))
	 {

    	
		$nombre_completo=$row["nombre"].' '.$row["paterno"].' '.$row["materno"];
		$folio= $row["folio"];
		$usuario= $row["usuario"];
		$idgrafiti=$row["idgrafiti"];
		 
    	$boceto=$row["boceto"];
    	$status_boceto=$row["status_boceto"];
    	$observa_boceto=$row["observa_boceto"];

    	$identificacion=$row["identificacion"];
    	$status_identifica=$row["status_identifica"];
    	$observa_identifica=$row["observa_identifica"];

    	$declaracion=$row["declaracion"];
    	$status_declara=$row["status_declara"];
    	$observa_declara=$row["observa_declara"];

    	$manifestacion=$row["manifestacion"];
    	$status_manifest=$row["status_manifest"];
    	$observa_manifest=$row["observa_manifest"];

    	$cartacesion=$row["cartacesion"];
    	$status_carta=$row["status_carta"];
    	$observa_carta=$row["observa_carta"];

    	$formato=$row["formato"];
    	$status_formato=$row["status_formato"];
    	$observa_formato=$row["observa_formato"];


   
   	
 
    	echo '<div class="row rowconsultas">
        			<div class="col-sm-4">
        				'.$nombre_completo.'
        			</div>


          			<div class="col-sm-2">';
          			//if($boceto)
          				//echo '<a href="'.UPLOAD_DIR.$boceto.'"><i class="fas fa-cube"></i></a>';
      					echo $folio;
          		//	else
          				//echo '-';

          	     echo   '</div>
		
          <div class="col-sm-2">';
      				if ($status_boceto == 1 && $status_carta ==1 && $status_declara == 1 && $status_formato ==1 && $status_manifest==1 && $status_identifica==1 ){
                echo 'VALIDADO';

              }
				
		 
		 
        	 		else{
                echo '<a href="#" class="btn btn-primary" onclick="fnValidar('.$val.','.$grafiti.')">POR VALIDAR</a>';

              }
				
    				
    		echo'</div>
    			
    			<div class="col-sm-4">
    				'.$usuario.'
    			</div>
				
				
    		  </div>';///row
	 
   


  	

 }// cierra el do while 
  echo '</div>';  ///grouprowconsultas
	 
	 
   /////// aqui quite cosas////

   	echo '<div id="errormsg"></div>';




  
	
?>