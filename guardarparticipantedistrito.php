<?php

	//echo "guardarparticipante.php";
	include('sqlconnector.php');

	$idusuario=$_POST["idusuario"];


	        $nombre=$_POST["nombre"];
	      $paterno=$_POST["paterno"];
	      $materno=$_POST["materno"];
	      $area=$_POST["area"];
	      $genero=$_POST["genero"];
	      $fecha_nacimiento=$_POST["fecha_nacimiento"];

	      $categoria=$_POST["categoria"];
	      $correo=$_POST["correo"];
	      
	      $titulo=$_POST["titulo"];
	      $sobrenombre=$_POST["sobrenombre"];
	      $tutor=$_POST["tutor"];
	      

	    	      
	      $tel1=$_POST["tel1"];
	      $tel2=$_POST["tel2"];

		$alcaldia=$_POST["alcaldia"];
		$entidad=$_POST["entidad"];
		$domicilio=$_POST["domicilio"];
		$resido_cdmx=$_POST["resido_cdmx"];
		$soyoriundo=$_POST["soyoriundo"];
		$soyoriginario=$_POST["soyoriginario"];
		$te_enteraste=$_POST["te_enteraste"];
	    $distrito=$_POST["distrito"];
	      
	    //$enlace="maindistrito";



	    /* $queryCorreo="SELECT COUNT(idgrafiti) as repetido FROM participantes_grafiti where correo ='".$correo."'";

		  $row = sqlsrv_query($conn,$queryCorreo);
          if($res=sqlsrv_fetch_array($row))
          {
              
          	$repetido=intval($res['repetido']);
          	//echo "  **".$num."**  ";
          	if($repetido>=1){
          		echo " Correo ya registrado";
          		return;
          	}
          }else{

          	//echo "1"	;
            die( print_r( sqlsrv_errors(), true));
            ///echo '<a href="maindistrito.php" class="btn btn-primary">Haz clic aquí para continuar</a>';

          }
			*/

	    $query1="SELECT COUNT(idensayo) as numero FROM ".BD_PARTICIPANTES." where folio is not null AND categoria=".$categoria."";

		  $row = sqlsrv_query($conn,$query1);
          if($res=sqlsrv_fetch_array($row))
          {
              
          	$num=intval($res['numero'])+1;
          	//echo "  **".$num."**  ";
          }else{

          	//echo "1"	;
            die( print_r( sqlsrv_errors(), true));
            ///echo '<a href="maindistrito.php" class="btn btn-primary">Haz clic aquí para continuar</a>';

          }

          $folio="CE".$categoria."-".$num;
          $checkfolio=true;

          while($checkfolio){

          	$query2="SELECT count(idensayo) as existe FROM ".BD_PARTICIPANTES." where folio='".$folio."'";

			  $row = sqlsrv_query($conn,$query2);
	          while($res=sqlsrv_fetch_array($row))
	          {
	          	$existe=intval($res["existe"]);
	          	///echo " --".$existe."--  //".$num."//  ";
	          	if($existe>=1){
	          		$num++;
	          		$folio="CE".$categoria."-".$num;
	          		$checkfolio=true;
	          		//echo "   if:true   ";

	          	}else{
	          		$checkfolio=false;
	          		//echo "   if:false   ";


	          	}
	            
	          	
	          }

          }


         

	       


	   $query="INSERT INTO ".BD_PARTICIPANTES." (idusuario, nombre, paterno, materno, sobrenombre, fecha_nacimiento,folio,
	    genero, correo, tel1, tel2,
	    titulo,tutor,categoria,
	    iddistrito, fecha_alta, fecha_modifica, alcaldia, entidad,
	    domicilio,te_enteraste) 

	    values(".$idusuario.",'".$nombre."', '".$paterno."', '".$materno."', '".$sobrenombre."','".$fecha_nacimiento."','".$folio."',
	    '".$genero."', '".$correo."', '".$tel1."', '".$tel2."', 
	    '".$titulo."','".$tutor."',".$categoria.",
	    ".$distrito.",'".date("Y-m-d h:i:sa")."','".date("Y-m-d h:i:sa")."',".$alcaldia.",'".$entidad."',
	    '".$domicilio."',".$te_enteraste." );";

		  $row = sqlsrv_query($conn,$query);
          if($row)
          {
              
          	echo '<div class="alert alert-success" ><p>Datos guardados con éxito</p>
          		<p>Número de folio: <b>'.$folio.'</b></p>
          	</div>';
          	echo '<form method="post" action="descargaracuse.php" target="_blank">
          				<button type="submit" class="btn btn-primary">Descargar acuse</button>
          				<input type="hidden" name="folio"  value="'.$folio.'">

          	</form>';

          	//echo $query."  ////   ";
          	if($distrito==40){
          		echo '<a href="maincentrales.php" class="btn btn-secondary">Regresar</a>';

          	}else{
          		echo '<a href="maindistrito.php" class="btn btn-secondary">Regresar</a>';

          	}

          	
          }else{

          			//echo "2"	;
          			//echo $query."  ////   ";
          	if($distrito==40){
          		echo '<a href="maincentrales.php" class="btn btn-secondary">Regresar</a>';

          	}else{
          		echo '<a href="maindistrito.php" class="btn btn-secondary">Regresar</a>';

          	}
          	echo $query;
            die( print_r( sqlsrv_errors(), true));
           

          }

		//echo $query."  ////   ";

		//echo $query1."  ////   ";

		//echo $query2;

		//echo "     /".$correo_tutor."/    ";

		//echo "  //".$_POST["correo_tutor"]."//   ";



    

?>