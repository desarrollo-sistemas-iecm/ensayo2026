<?php
ob_start();
session_start();

if (!isset($_POST['concurso'])) {
	header('Location: index.php');
}

include('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');
include('phpmailer/PHPMailerAutoload.php');
include('sqlconnector.php');
include('rutasitio.php');

$message = '';
$action = '';
$pass1 = '';
$count_usuario = '';

$concurso = $_POST['concurso'];

if (isset($_POST['action'])) {
	$action = $_POST['action'];

	$nombre = $_POST['nombre'];

	$paterno = $_POST['paterno'];

	$materno = $_POST['materno'];

	$user = $_POST['user'];

	$correo = $_POST['correo'];

	$genero = $_POST['genero'];

	$fecha_nacimiento = $_POST['fecha_nacimiento'];

	$sql_usuario = 'SELECT * FROM ' . BD_USUARIOS . " WHERE usuario = '$user' or correo = '$correo';";
	$res_count = sqlsrv_query($conn, $sql_usuario);

	while ($res_count && ($row = sqlsrv_fetch_array($res_count))) {
		//Validadion usuario y correo

		if ($user == $row['usuario']) {
			$action = '';
			echo '1';
		}

		if ($correo == $row['correo']) {
			$action = '';
			echo '2';
		}
	}

	$area = $_POST['area'];
	$pass1 = $_POST['pass1'];

	// Validar captcha server-side
	if (!isset($_SESSION['captcha']) || strtolower($_POST['tmptxt'] ?? '') !== strtolower($_SESSION['captcha'])) {
		echo '3'; // código de error captcha
		exit;
	}


					
//				
  	$area=$_POST['area'];
  	$pass1=$_POST['pass1'];
	  
  	
	  
	  
  if($action=="insert"){

	$fecha_actual = date("Y-m-d H:i:s");
	$query = "INSERT INTO ".BD_USUARIOS." (nombre, paterno, materno, area, genero, fecha_nacimiento, correo, usuario, contrasena, perfil, estatus, fecha_alta) " .
	         "VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, '1', '1', ?)";
	$params_insert = array($nombre, $paterno, $materno, $area, $genero, $fecha_nacimiento, $correo, $user, $pass1, $fecha_actual);
  	$row = sqlsrv_query($conn, $query, $params_insert);
		
	

	$query2 = "SELECT SCOPE_IDENTITY() AS ultimo from ".BD_USUARIOS."";
	$row2 = sqlsrv_query($conn, $query2);
	
	if($res2 = sqlsrv_fetch_array($row2)){
		$idusr = $res2["ultimo"];
	}

	$query3 = "SELECT * from ".BD_USUARIOS." where idusuario = ?";
	$row3 = sqlsrv_query($conn, $query3, array($idusr));
	
	while($res3=sqlsrv_fetch_array($row3)){
		$nombre_c = $res3['nombre'];
		$paterno_c= $res3['paterno'];
		$materno_c = $res3['materno'];
		$user_c= $res3['usuario'];
		$pass1=$res3['contrasena'];

		$email= $res3['correo'];
	}
		

	///$row=false;
		
		//////crear correooooo/////
		
		

	if($row){

		$usuario_creado=true;
		

		echo '<div class="row">
					<div class="col-sm-8"></div>
					<div class="col-sm-4">
				      <a href="index.php" class="btn btn-secondary">Regresar</a>
				    </div>
			    </div>';

		echo "<div class='alert alert-success' align='center'> <p>Usuario creado correctamente.</p><p>Se envió correo electrónico confirmando tu registro.</p><p>Favor de verificar en las bandejas de: entrada, spam, correo no deseado u otras, si ahí se encuentra el correo referido.</p></div>";
		

		echo "<div class='row'>";
		echo "<div class='col-sm-6' align='center' id='divcorreo'><p>¿No has recibido ningún correo?</p> <button class='btn btn-primary' onclick='fnReenviarCorreo(".$idusr."); this.disabled=true;'>Haz clic aquí para enviarlo otra vez</button></div>";
		echo "</div>";

			
	
			
	}else{

		$usuario_creado=false;

		echo "<div class='alert alert-danger' align='center'>Error al crear el usuario</div>";
		echo '<div class="col-sm-4">
			      <a href="index.php" class="btn btn-secondary">Regresar</a>
			    </div>';
		

	}


	if($usuario_creado){

		
		
	
		$mail = new PHPMailer(true);
					//Luego tenemos que iniciar la validación por SMTP:
					$mail->IsSMTP();
					$mail->CharSet = 'utf-8';
					$mail->SMTPAuth = false;
					$mail->Host = "145.0.40.63"; // SMTP a utilizar. Por ej. smtp.elserver.com
		//			$mail->Username = "inscripciones@iedf.org.mx"; // Correo completo a utilizar
		//			$mail->Password = "1nscr1pc10nes"; // Contraseña
		//			$mail->Port = 25; // Puerto a utilizar
					$mail->Username = "actividadesccycp@iecm.mx"; // Correo completo a utilizar
					$mail->Password = "s1s3c0m"; // Contraseña
					$mail->Port = 25; // Puerto a utilizar
					$mail->From = "no-reply@iecm.mx"; // Desde donde enviamos (Para mostrar)
					$mail->FromName = "Instituto Electoral de la Ciudad de México";
					
					//Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: “From: Nombre <correo@dominio.com>”) de //correo.
					
			
					$id_64=base64_encode($idusr);
				

	
					$html=' 
					<html>
					<body style="width:85%; display: block; margin: 0 auto;">
					<table width="650" border="0">
			  			<tbody>
				    		<tr>
				      			<td width="218"><img src="'.URL_CONCU.'/img/ban-iecm.jpg" width="146" height="151"></td>
						      	<td width="805"><h1>Concurso de Ensayo 2022 “Retos y perspectivas a 11 años del Presupuesto Participativo en la Ciudad de México”.</h1> </td>
				    		</tr>
			  			</tbody>
					</table>
					<hr>

					<div style="align:center">
							
							
							<p align="justify"><h3>Estimada(o) '.$nombre_c.' '.$paterno_c.' '.$materno_c.'.</h3></p>
							
							<p align="justify"> El Instituto Electoral de la Ciudad de México agradece tu interés por
                            participar en el Concurso de Ensayo 2022 “Retos y perspectivas a 11 años del Presupuesto Participativo en la Ciudad de México”, para continuar con el registro deberás ingresar al sistema con tu usuario y contraseña.
	                       <br>
                            Es importante que previo a tu solicitud de registro como participante, cuentes con toda la información y documentación necesaria señalada en la Convocatoria.</p>
							<br>
							<br>

							
								<p> Usuario: <strong>' .$user_c.'</strong></p>
                                
								<p> Contraseña: <strong>' .$pass1.'<strong></p>
                                
							</div>
                            
							<br>
							<br>
							
							
								<p>Inicia sesión <a href="'.URL_CONCU.'index.php"> haciendo clic aquí</a></p>
							<hr>
							<div>
					            <strong><p align="center"><font size="3pt">Instituto Electoral de la Ciudad de M&eacute;xico<br /> Huizaches 25 &bull; Rancho Los Colorines &bull; Tlalpan &bull; C.P.   14386 &bull; Ciudad de M&eacute;xico  &bull; Conmutador: (55) 5483 3800</font></p>
					                          </strong>
					        </div>
					</body>
					</html>';

		                   try{
							
				

							//$mail->AddAddress($destinatario1); // Esta es la dirección a donde enviamos
							//$mail->AddAddress($destinatario2); // Esta es la dirección a donde enviamos
							$mail->AddAddress($email);
							//$mail->AddAddress($destinatario2);// Esta es la dirección a donde enviamos
							//$mail->AddAddress($destinatario3);// Esta es la dirección a donde enviamos

							///////////////------>>>>>
							//$mail->AddCC("inscripciones@iedf.org.mx"); // Copia
							//$mail->AddBCC("inscripciones@iedf.org.mx"); // Copia oculta

							//$mail->AddBCC("nancy.hernandez@iecm.mx"); // Copia oculta
							$mail->AddBCC("concursos@iecm.mx"); // Copia oculta



							$mail->IsHTML(true); // El correo se envía como HTML
							$mail->Subject = "Sistema de Registro de Concurso de Divulgacion"; // Este es el titulo del email.
							$mail->Body = $html; // Mensaje a enviar
							
							$exito = $mail->Send(); // Envía el correo.
							

							//$exito ="1";//
							//$exito =0;//
				
								if($exito):


								else:
	

								 


								endif;
							}//try
							catch (Exception $e) {
					
							      echo "Excepción: ".$e->getMessage();
							};//catch

                    

                    $mail->ClearAddresses();
                    

	}






  } /// cierre accion insert



}else{

  	

?>
 

 
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Concursos 2019</title>

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
    

</head>
<body>

<?php
		include('header.php');

?>

<div class="container" id="main_container">

	<div class="form-group row">
		    	
			    <div class="col-sm-10">
			      
			    </div>
	            
			    <div class="col-sm-2">
			      <a href="index.php" class="btn btn-secondary">Regresar</a>
			    </div>

		   </div>
	<div style="margin: 0 auto; width:80%;">
		<p>Llena los siguientes campos para generar usuario y contraseña para el registro.</p>


	</div>
    <div class="card">
      <div class="card-header"><b>Nuevo registro</b></div>
      <div class="card-body">
          <!-- ////////////////////////////////////////////////////////////// -->

          <div class="form-group row">
		    <label class="col-sm-3 control-label">Tipo de concurso</label>
		    <div class="col-sm-9">
		      <select id="sel" name="sel" class="form-control" disabled>
		      	<!-- <option value="0" disabled>Seleccione una opción</option>-->
		      	<option value="cuento" <?php if($concurso=="cuento") echo "selected"; ?> >Cuento</option>
				<option value="ensayo" <?php if($concurso=="ensayo") echo "selected"; ?> >Ensayo</option>
				 <option value="grafiti" <?php if($concurso=="grafiti") echo "selected"; ?>>Grafiti</option> 
				  <option value="debate" <?php if($concurso=="debate") echo "selected"; ?> >Debate</option>
	   	
		      </select>
		    </div>
		     
		   </div>
		   <hr>

		


			<div class="form-group row">
				<input type="hidden" value="<?php echo $idusr?>" id="iduser" name="iduser">
				
				
			    <label class="col-sm-3 control-label">Nombre(s)</label>
			    <div class="col-sm-4">
			      <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Nombre" >
			    </div>
			</div>
		
			<div class="form-group row">	
			    <label class="col-sm-3 control-label">Primer apellido</label>
			    <div class="col-sm-4">
			      <input type="text" class="form-control" id="paterno" name="paterno" placeholder="Primer apellido" >
			   <b style="font-size:9pt;">“En caso de NO tener primer apellido o segundo, favor de registrar una X en el campo correspondiente”.</b>                </div>
			</div>
			
			<div class="form-group row">
				    <label class="col-sm-3 control-label">Segundo apellido</label>
				    <div class="col-sm-4">
				      <input type="text" class="form-control" id="materno" name="materno" placeholder="Segundo apellido " >
				    </div>
			     
		         
			</div>
			<div class="form-group row">
				
		        <label class="col-sm-3 control-label">Sexo</label>
		        <div class="col-sm-6">
		            <select  class="form-control input-medium" name="genero" id="genero" required>                               
			            <option value="0" selected disabled>Selecciona una opción</option>
			            <option value="H" >Hombre</option>
			            <option value="M" >Mujer</option>
			            <option value="PND" >Prefiero no decirlo</option>
					</select>
		        </div>
			</div>

			<div class="form-group row">
				
		        <label class="col-sm-3 control-label">Fecha de nacimiento</label>
		        <div class="col-sm-3">
		            <input class="form-control input-small" type="date" name="fecha_nacimiento" id="fecha_nacimiento" onchange="fnEdad();" step='1' min='1900-01-01' max='2003-12-31' value="2000-00-00" required>

		            <input type="hidden" name="edad_califica" id="edad_califica" value="0">
		            <input type="hidden" name="edad" id="edad" value="0">



		        </div>
		        <div class="col-sm" id="erroredad"></div>
			</div>

			<div class="form-group row">
				
		        <label class="col-sm-3 control-label">Categoría</label>
			       <div class="col-sm-6">
			            <select  class="form-control input-medium" name="categoria" id="categoria" required disabled>                               
				            <option value="0" selected disabled>Selecciona una opción</option>
				            <option value="1" >18 a 59 años</option>
				            <option value="2" >60 años en adelante</option>
				            
						</select>
			        </div>
		       
			</div>

							

                          
                           
                            

	<hr>
	
		  <div class="form-group row">
		    <label class="col-sm-3 control-label">Nombre de usuario</label>
		    <div class="col-sm-9">
		      <input type="text" class="form-control" id="user" name="user" placeholder="Nombre de usuario sin espacios" onkeypress="return validar2(event)" maxlength="20">
		    </div>
		     
		   </div>
       
		   <div class="form-group row">
		    <label class="col-sm-3 control-label">Contraseña</label>
		    <div class="col-sm-9">
		      <input type="password" class="form-control" id="pass1" placeholder="Contraseña" onkeypress="return validar2(event)" maxlength="20">
		    </div>
		     
		   </div>

		   <div class="form-group row">
		    <label class="col-sm-3 control-label">Repetir contraseña</label>
		    <div class="col-sm-9">
		      <input type="password" class="form-control" id="pass2" placeholder="Repetir la contraseña" onkeypress="return validar2(event)" onpaste="return false;">
		    </div>
		     
		   </div> 
       


		   

	   <hr>

		   <div class="form-group row">
		    <label class="col-sm-3 control-label">Correo electrónico</label>
		    <div class="col-sm-9">
		      <input type="text" class="form-control" id="correo" name="correo" placeholder="Correo electrónico" >
		    </div>
		     
		   </div>

		   <div class="form-group row">
		    <label class="col-sm-3 control-label">Repetir correo electrónico</label>
		    <div class="col-sm-9">
		      <input type="text" class="form-control" id="correo2" name="correo2" placeholder="Repetir correo electrónico" onpaste="return false;">
	          
		    </div>
		   </div>
	   <hr>

		<div class="form-group row">
		    <div class="col-sm-4">
			    <input class="form-control" id="tmptxt" name="tmptxt" type="text" placeholder="Escribe los caracteres de la imagen">
				<input class="form-control" id="tmptxt2" name="tmptxt2" type="hidden" value= "<?php $_SESSION['tmptxt2']?>">
				</br>
		          	  <img src="captcha.php" width="137" height="50">
			</div>
			<div class="col-sm-4">
					   <input class="btn btn-secondary"  name="button" type="button" onClick="history.go(0)" value="Nuevos caracteres">   
			</div>
		</div>
			
          <div class="card" style="width:100%;">
			  <div class="card-header">
          <button class="btn-primary btn btn-block" type="button" data-toggle="collapse" data-target="#collapse1">
              AVISO DE PRIVACIDAD SIMPLIFICADO 
          </button>
        </div>
		 <div class="card-body">
   
        <div class="d-flex justify-content-center">
          <p align="justify">El Instituto Electoral de la Ciudad de México (Instituto Electoral), a través de la Dirección Ejecutiva de Educación Cívica y Construcción de Ciudadanía, es el Responsable del tratamiento de los datos personales que nos proporcione, los cuales serán protegidos en el Sistema de registro de participantes en los concursos para la promoción de la participación ciudadana y divulgación de la cultura democrática.
          <br>
            <br>  
            Los datos personales recabados serán utilizados con la finalidad de llevar a cabo el registro de datos personales de las personas interesadas en participar en los concursos que organice, promueva o difunda el Instituto Electoral; y podrán ser transferidos a la Comisión de Derechos Humanos de la Ciudad de México, para la investigación de quejas y denuncias por presuntas violaciones a los derechos humanos; Instituto de Transparencia, Acceso a la Información Pública, Protección de Datos Personales y Rendición de Cuentas de la Ciudad de México, para la sustanciación de recursos de revisión, recurso de inconformidad, denuncias y el
            procedimiento para determinar el presunto incumplimiento a la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados de la Ciudad de México; la Auditoría Superior de la Ciudad de México, para la realización de auditorías o realización de investigaciones por presuntas faltas administrativas; Órganos de Control Interno, para la realización de auditorías o desarrollo de investigaciones por presuntas faltas
            administrativas; Órganos Jurisdiccionales Locales y Federales, para la sustanciación de los procedimientos Jurisdiccionales tramitados por ellos y el Instituto Nacional Electoral, para la sustanciación de procedimientos administrativos sancionadores.
            <br><br>
            Este Sistema de Datos Personales no cuenta con Encargados; ni con Despacho de Auditores Externos encargados del ejercicio de las funcionesde fiscalización.
            <br><br>
            Usted podrá manifestar la negativa al tratamiento de sus datos personales directamente ante la Unidad de Transparencia del Instituto Electoral, ubicada en la Calle de Huizaches No. 25, Colonia Rancho los Colorines, Planta Baja, Alcaldía Tlalpan, C. P. 14386, Ciudad de México, con número telefónico
            54833800 a la extensión 4725, o bien a través de la Plataforma Nacional de Transparencia http://www.plataformadetransparencia.org.mx/ o en el correo electrónico unidad.transparencia@iecm.mx.</p>
        </div>
        
    
    </div><!--card body-->
  </div><!---card-->          
    	

		    

       <hr>
		   <div class="form-group row">
		    	
			    <div class="col-sm-6">
			      <button class="btn btn-primary btn-block" id="btn_crearusuario" onclick="this.disabled=true;crearusuario();">Crear cuenta de usuario</button>
			    </div>
	            
			    <div class="col-sm-4">
			      <a href="index.php" class="btn btn-secondary">Regresar</a>
			    </div>

		   </div>
		   <div class="row">
		   		<div id="errormsg"></div>
		   </div>
	
  

	

    

  		</div> <!-- panel body -->
	</div>
</div> <!--- maincontainer -->


<?php
	include('footer.php');
?>


</body>
</html>

<?php

	
	} ////fin del else
  

  ?>
