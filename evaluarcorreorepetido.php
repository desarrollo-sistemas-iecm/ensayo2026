<?php

	include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];
    $correo=$_POST["mail"];
 


 

 

    $query="SELECT count(idcuento) as repetido FROM ".BD_PARTICIPANTES." WHERE correo ='".$correo."'";
    //echo $query;
    $res=sqlsrv_query($conn,$query);
    
    if($row= sqlsrv_fetch_array($res)){

    	$repetido=intval($row["repetido"]);


    }

    if($repetido>0){
        echo '<div class="alert alert-warning">Ya existe un usuario registrado con este correo</div>';

    }else{

         echo '';

    }

    


    //echo "xxxxxxxxxxxxx";


 ?>