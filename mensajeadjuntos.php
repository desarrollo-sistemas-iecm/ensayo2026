<?php

	include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];
	define("UPLOAD_DIR", "uploads/");
 $idusuario=$_POST["idusuario"];
 //$index=$_POST["index"];


 



    $query="SELECT * FROM ".BD_PARTICIPANTES." WHERE idusuario =".$idusuario."";
  //  echo $query;
    $res=sqlsrv_query($conn,$query);
    
    if($row= sqlsrv_fetch_array($res)){

    	

    	$ensayo=$row["ensayo"];
    	

    	$identificacion=$row["identificacion"];
    	

    	
    	

    	$manifestacion=$row["manifestacion"];
    	

    	$cartacesion=$row["cartacesion"];
    	
    	$formato=$row["formato"];
    	




    }

    if($ensayo&&$identificacion&&$manifestacion&&$cartacesion&&$formato){
        $completado=true;
        echo '<div class="alert alert-success">
                <p>Tu registro fue exitoso. Tienes que esperar la validación de documentos para poder continuar con el concurso.</p>
            </div>

        ';
    }
    else{
        $completado=false;

        echo '<div class="alert alert-warning">
                <p>Para poder continuar con el concurso deberás adjuntar todos los documentos.</p>
            </div>

        ';
    }

   
   	

 ?>