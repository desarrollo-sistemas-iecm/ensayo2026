<?php

	include('phpmailer/class.phpmailer.php');
	include('phpmailer/class.smtp.php');
	include('phpmailer/PHPMailerAutoload.php');
  	include('sqlconnector.php');
	//define("URL_CONCU", "http://145.0.40.76/concursos2019/");
	include('rutasitio.php');
	
	  
	

  	$idusr = filter_var($_POST['id'], FILTER_VALIDATE_INT);
  	$correo = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);

	if($idusr == 0){
		$query3 = "SELECT * from ".BD_USUARIOS." where correo = ?";
		$row3 = sqlsrv_query($conn, $query3, array($correo));
		
		while($res3 = sqlsrv_fetch_array($row3)){
			$idusr = $res3['idusuario'];
			$nombre_c = $res3['nombre'];
			$paterno_c = $res3['paterno'];
			$materno_c = $res3['materno'];
			$user_c = $res3['usuario'];
			$pass1 = $res3['contrasena'];
			$correo = $res3['correo'];
		}
	}else{
		$query3 = "SELECT * from ".BD_USUARIOS." where idusuario = ?";
		$row3 = sqlsrv_query($conn, $query3, array($idusr));
		
		while($res3 = sqlsrv_fetch_array($row3)){
			$nombre_c = $res3['nombre'];
			$paterno_c = $res3['paterno'];
			$materno_c = $res3['materno'];
			$user_c = $res3['usuario'];
			$correo = $res3['correo'];
			$pass1 = $res3['contrasena'];
		}
	}

	
		

	///$row=false;
		
		//////crear correooooo/////
		
		

	

		
		
	if($row3){
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
					
					//Estas dos líneas, cumplirían la función de encabezado (En mail() usado de esta forma: "From: Nombre <correo@dominio.com>") de //correo.
					
				
			
					$id_64=base64_encode($idusr);
				

$html = '<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0f2f5;padding:30px 10px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.13);">
      <tr>
        <td bgcolor="#2c5364" style="background:#2c5364;background:linear-gradient(135deg,#0f2027 0%,#2c5364 55%,#4A9FD5 100%);padding:36px 40px 28px;text-align:center;">
          <img src="'.URL_CONCU.'img/IECM-1.png" width="80" height="80" alt="IECM" style="display:block;margin:0 auto 14px;">
          <h1 style="margin:0 0 6px;color:#ffffff;font-size:24px;font-weight:700;"><font color="#ffffff">Concurso de Ensayo 2026</font></h1>
          <p style="margin:0;color:#bfdbfe;font-size:14px;"><font color="#bfdbfe">Instituto Electoral de la Ciudad de M&eacute;xico</font></p>
          <div style="margin:16px auto 0;width:60px;height:3px;background:#4A9FD5;border-radius:2px;"></div>
        </td>
      </tr>
      <tr>
        <td style="background:#ffffff;padding:32px 40px 10px;">
          <p style="margin:0 0 18px;font-size:16px;color:#0f2027;">
            Estimado/a <strong>'.htmlspecialchars($nombre_c.' '.$paterno_c.' '.$materno_c).'</strong>,
          </p>
          <p style="margin:0 0 14px;font-size:14px;color:#374151;line-height:1.7;">
            Agradecemos tu registro en el sistema del <strong>Concurso Juvenil de Ensayo 2026 "Conversando con los clasicos"</strong>.
            Para continuar con tu inscripci&oacute;n deber&aacute;s ingresar al sistema con tu usuario y contrase&ntilde;a.
          </p>
          <p style="margin:0 0 24px;font-size:14px;color:#374151;line-height:1.7;">
            Es importante que, previo a tu solicitud de registro, cuentes con toda la
            informaci&oacute;n y documentaci&oacute;n se&ntilde;alada en la Convocatoria.
          </p>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td bgcolor="#0f2027" style="background:#0f2027;border-radius:10px;padding:20px 24px;">
                <p style="margin:0 0 10px;color:#5B9CB8;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;"><font color="#5B9CB8">Tu nombre de persona usuaria y contraseña son:</font></p>
                <p style="margin:0 0 8px;font-family:monospace;font-size:15px;color:#e2e8f0;">
                  <font color="#94a3b8">Usuario:&nbsp;&nbsp;&nbsp;</font>
                  <strong><font color="#4A9FD5">'.htmlspecialchars($user_c).'</font></strong>
                </p>
                <p style="margin:0;font-family:monospace;font-size:15px;color:#e2e8f0;">
                  <font color="#94a3b8">Contrase&ntilde;a: </font>
                  <strong><font color="#4A9FD5">'.htmlspecialchars($pass1).'</font></strong>
                </p>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td bgcolor="#fefce8" style="background:#fefce8;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:14px 16px;">
                <p style="margin:0;font-size:13px;color:#78350f;line-height:1.65;"><font color="#78350f">
                  <strong>Aviso importante:</strong> Guarda tus credenciales en un lugar seguro. Te recomendamos cambiar tu contrase&ntilde;a al acceder por primera vez.
                </font></p>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
            <tr>
              <td align="center">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#4A9FD5" style="background:#4A9FD5;background:linear-gradient(135deg,#4A9FD5,#2E86AB);border-radius:30px;">
                      <a href="'.URL_CONCU.'index.php"
                         style="display:inline-block;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:13px 36px;border-radius:30px;">
                        <font color="#ffffff">Iniciar sesi&oacute;n &rarr;</font>
                      </a>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="background:#f8f9fb;border-top:1px solid #e2e8f0;padding:20px 40px;text-align:center;">
          <p style="margin:0 0 4px;font-size:12px;color:#6b7280;">
            <strong style="color:#2E86AB;">Instituto Electoral de la Ciudad de M&eacute;xico</strong>
          </p>
          <p style="margin:0;font-size:11px;color:#9ca3af;line-height:1.7;">
            Huizaches 25 &bull; Rancho Los Colorines &bull; Tlalpan &bull; C.P. 14386 &bull; Ciudad de M&eacute;xico<br>
            Conmutador: (55) 5483 3800 &bull; <a href="https://www.iecm.mx" style="color:#4A9FD5;text-decoration:none;">www.iecm.mx</a>
          </p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>';


		                   try{
							
                            $mail->AddAddress($correo); // aqui se pone la varible del correo donde se envia
                               
							///////////////------>>>>>
							//$mail->AddCC("inscripciones@iedf.org.mx"); // Copia
							//$mail->AddBCC("inscripciones@iedf.org.mx"); // Copia oculta
							//$mail->AddBCC("nancy.hernandez@iecm.mx"); // Copia oculta
							$mail->AddBCC("concursos@iecm.mx"); // Copia oculta


							$mail->IsHTML(true); // El correo se envía como HTML
							$mail->Subject = "REENVÍO - Sistema de Registro de Concurso de Divulgacion"; // Este es el titulo del email.
							$mail->Body = $html; // Mensaje a enviar
							
							$exito = $mail->Send(); // Envía el correo.
							

							//$exito ="1";//
							//$exito =0;//
				
								if($exito):

									echo "<div class='alert alert-success'>Correo enviado</div>";


								else:

									echo "<div class='alert alert-warning'>Hubo un problema con el formato del correo electrónico</div>";
	

								 


								endif;
							}//try
							catch (Exception $e) {
					
							      echo "<div class='alert alert-warning'> Erro al enviar el correo. Excepción: ".$e->getMessage()."</div>";
							};//catch

                    

                    $mail->ClearAddresses();

                     

    }


     ///echo " - id=".$idusr." - correo: ".$correo;


   
                    

	






  
  ?>
