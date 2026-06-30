<?php
session_start();
if (!isset($_SESSION['idusuario']) || empty($_SESSION['idusuario'])) {
    if (isset($_POST['action']) && $_POST['action'] == 'update') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'msg' => 'Sesión expirada. Por favor, vuelva a iniciar sesión.']);
        exit;
    }
}
$idusuario=$_SESSION['idusuario'];

$id=$_POST['id'];
$categoria=$_POST['categoria'];

if(isset($_POST['action'])){
	$action=$_POST['action'];

}else{
	$action="";
}



include('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');
include('phpmailer/PHPMailerAutoload.php');
include('sqlconnector.php');
	
include('rutasitio.php');
define("UPLOAD_DIR", "uploads/");



if($action=='update'){//////////////////código del update y el correo
    header('Content-Type: application/json; charset=utf-8');

		$id=$_POST["id"];

	    $estatus_ensayo=$_POST["estatus_ensayo"];
	    $observa_ensayo=$_POST["observa_ensayo"];

		$observa_requi=$_POST["observa_requi"];


		// Modificado por Bruno Corona: Se verifica si el participante ya cuenta con un folio asignado 
		// para evitar que se reasigne o se genere uno nuevo si ya existe en la base de datos.
		$query_check = "SELECT folio FROM ".BD_PARTICIPANTES." WHERE idensayo = ?";
		$stmt_check = sqlsrv_query($conn, $query_check, array($id));
		$folio = null;
		if ($stmt_check && $res_check = sqlsrv_fetch_array($stmt_check)) {
			$folio = $res_check['folio'];
		}

		if (empty($folio)) {
			sqlsrv_begin_transaction($conn);

			try {
				// WITH (UPDLOCK, HOLDLOCK) bloquea la lectura hasta que se haga
				// COMMIT, evitando que otro proceso lea el mismo COUNT simultáneamente
				$query1 = "SELECT COUNT(idensayo) as numero 
				           FROM " . BD_PARTICIPANTES . " WITH (UPDLOCK, HOLDLOCK)
				           WHERE folio IS NOT NULL 
				           AND categoria = ?";

				$row = sqlsrv_query($conn, $query1, array(intval($categoria)));

				if ($res = sqlsrv_fetch_array($row)) {
					$num = intval($res['numero']) + 1;
				} else {
					sqlsrv_rollback($conn);
					echo json_encode(['ok' => null, 'msg' => 'Error al generar folio']);
					exit;
				}

				// Verificar que el folio candidato no exista (dentro de la transacción)
				$folio_candidato = "CE" . intval($categoria) . "-" . $num;
				$query2 = "SELECT COUNT(idensayo) as existe 
				           FROM " . BD_PARTICIPANTES . " 
				           WHERE folio = ?";

				$row2 = sqlsrv_query($conn, $query2, array($folio_candidato));
				$res2 = sqlsrv_fetch_array($row2);

				while ($res2 && intval($res2['existe']) >= 1) {
					$num++;
					$folio_candidato = "CE" . intval($categoria) . "-" . $num;
					$row2 = sqlsrv_query($conn, $query2, array($folio_candidato));
					$res2 = sqlsrv_fetch_array($row2);
				}

				$folio = $folio_candidato;

			} catch (Exception $e) {
				sqlsrv_rollback($conn);
				echo json_encode(['ok' => null, 'msg' => 'Error interno al generar folio']);
				exit;
			}
			// El COMMIT se hace después del UPDATE para que folio + registro
			// queden en una sola transacción atómica
		}

    if ($estatus_ensayo==1){
    	$validado=true;

    }else{
    	$validado=false;
    }

    if($validado){
      $query = "UPDATE ".BD_PARTICIPANTES." SET status_ensayo = ?, observa_ensayo = ?, observa_requisitos = ?, folio = ?, status_requisitos = ? WHERE idensayo = ?";
      $params = array($estatus_ensayo, $observa_ensayo, $observa_requi, $folio, $idusuario, $id);
    }else{
      $query = "UPDATE ".BD_PARTICIPANTES." SET status_ensayo = ?, observa_ensayo = ?, observa_requisitos = ?, status_requisitos = ? WHERE idensayo = ?";
      $params = array($estatus_ensayo, $observa_ensayo, $observa_requi, $idusuario, $id);
    }

	$row = sqlsrv_query($conn, $query, $params);
    
	if ($row) {
		// Solo si el folio fue recién generado en esta petición, hacer commit
		if (isset($folio_candidato)) {
			sqlsrv_commit($conn);
		}
		$guardado = true;
	} else {
		// Si el UPDATE falla, revertir también la reserva del folio
		if (isset($folio_candidato)) {
			sqlsrv_rollback($conn);
		}
		$guardado = false;
	}

    if($guardado){
	    $query= "SELECT B.correo, B.nombre, B.paterno, B.materno FROM ".BD_PARTICIPANTES." as A INNER JOIN ".BD_USUARIOS." as B ON A.idusuario= B.idusuario and idensayo='".$id."' and A.iddistrito=0";

		$row = sqlsrv_query($conn,$query);
        if($row && ($res=sqlsrv_fetch_array($row)))
        {
        	$correo=$res['correo'];
        	$nombre=$res['nombre']." ".$res['paterno']." ".$res['materno'];
        }else{
            die( print_r( sqlsrv_errors(), true));
        }

          	/////////////////////////////////////////////correo

          			$mail = new PHPMailer(true);
					//Luego tenemos que iniciar la validación por SMTP:
					$mail->IsSMTP();
					$mail->CharSet = 'utf-8';
					$mail->SMTPAuth = false;
					$mail->Host = "145.0.40.63"; // SMTP a utilizar. Por ej. smtp.elserver.com
					$mail->Username = "actividadesccycp@iecm.mx"; // Correo completo a utilizar
					$mail->Password = "s1s3c0m"; // Contraseña
					$mail->Port = 25; // Puerto a utilizar
					$mail->From = "no-reply@iecm.mx"; // Desde donde enviamos (Para mostrar)
					$mail->FromName = "Instituto Electoral de la Ciudad de México";

          	if ($validado){
          		//////correo OK

          		$folio_64=base64_encode("/jF5i/".$folio."/jF5i/");

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
          <h1 style="margin:0 0 6px;color:#ffffff;font-size:24px;font-weight:700;"><font color="#ffffff">&#10003; &iexcl;Ensayo Validado!</font></h1>
          <p style="margin:0;color:#bfdbfe;font-size:14px;"><font color="#bfdbfe">Instituto Electoral de la Ciudad de M&eacute;xico</font></p>
          <div style="margin:16px auto 0;width:60px;height:3px;background:#22c55e;border-radius:2px;"></div>
        </td>
      </tr>
      <tr>
        <td style="background:#ffffff;padding:32px 40px 10px;">
          <p style="margin:0 0 18px;font-size:16px;color:#0f2027;">
            Estimado/a <strong>'.htmlspecialchars($nombre).'</strong>,
          </p>
          <p style="margin:0 0 20px;font-size:14px;color:#374151;line-height:1.7;">
            Has completado correctamente el registro en el <strong>Concurso Juvenil de Ensayo 2026 “Conversando con los clásicos”</strong>.
            A continuaci&oacute;n encontrar&aacute;s tu n&uacute;mero de folio asignado.
          </p>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td bgcolor="#0f2027" style="background:#0f2027;border-radius:10px;padding:20px 24px;text-align:center;">
                <p style="margin:0 0 8px;color:#5B9CB8;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;"><font color="#5B9CB8">Tu n&uacute;mero de folio es:</font></p>
                <p style="margin:0;font-family:monospace;font-size:28px;font-weight:700;color:#4A9FD5;letter-spacing:.1em;"><font color="#4A9FD5">'.htmlspecialchars($folio).'</font></p>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td bgcolor="#f0fdf4" style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:0 8px 8px 0;padding:14px 16px;">
                <p style="margin:0;font-size:13px;color:#14532d;line-height:1.65;"><font color="#14532d">
                  <strong>&#10003; Registro exitoso.</strong> Conserva este folio como comprobante de tu participaci&oacute;n.
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
                      <a href="'.URL_CONCU.'descargaracuseweb.php?v='.$folio_64.'"
                         style="display:inline-block;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:13px 36px;border-radius:30px;">
                        <font color="#ffffff">&#x2193; Descargar acuse de registro</font>
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

          //////ok

          	}else{

          		/////correro NOT OK
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
          <h1 style="margin:0 0 6px;color:#ffffff;font-size:22px;font-weight:700;"><font color="#ffffff">Revisi&oacute;n de documentos</font></h1>
          <p style="margin:0;color:#bfdbfe;font-size:14px;"><font color="#bfdbfe">Instituto Electoral de la Ciudad de M&eacute;xico</font></p>
          <div style="margin:16px auto 0;width:60px;height:3px;background:#f59e0b;border-radius:2px;"></div>
        </td>
      </tr>
      <tr>
        <td style="background:#ffffff;padding:32px 40px 10px;">
          <p style="margin:0 0 18px;font-size:16px;color:#0f2027;">
            Estimado/a <strong>'.htmlspecialchars($nombre).'</strong>,
          </p>
          <p style="margin:0 0 20px;font-size:14px;color:#374151;line-height:1.7;">
            Hemos revisado la documentaci&oacute;n que proporcionaste para el
            <strong>Concurso Juvenil de Ensayo 2026 “Conversando con los clásicos”</strong>
            y encontramos que est&aacute; <strong>incompleta o incorrecta</strong>.
            A continuaci&oacute;n te mostramos la observaci&oacute;n:
          </p>';
							if($estatus_ensayo==1) $var_A="Correcto";
							else $var_A="Incorrecto";	
		
				
						$html .= '
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;border-radius:8px;overflow:hidden;border:1px solid #e2e8f0;">
            <tr bgcolor="#2c5364" style="background:#2c5364;">
              <td style="padding:10px 14px;color:#ffffff;font-size:13px;font-weight:700;"><font color="#ffffff">Documento</font></td>
              <td style="padding:10px 14px;color:#ffffff;font-size:13px;font-weight:700;"><font color="#ffffff">Estatus</font></td>
              <td style="padding:10px 14px;color:#ffffff;font-size:13px;font-weight:700;"><font color="#ffffff">Observaciones</font></td>
            </tr>
            <tr bgcolor="#fefce8" style="background:#fefce8;">
              <td style="padding:10px 14px;font-size:13px;color:#374151;border-top:1px solid #e2e8f0;"><font color="#374151">Ensayo</font></td>
              <td style="padding:10px 14px;font-size:13px;font-weight:700;border-top:1px solid #e2e8f0;color:'.($estatus_ensayo == 1 ? '#16a34a' : '#dc2626').';"><font color="'.($estatus_ensayo == 1 ? '#16a34a' : '#dc2626').'">'.htmlspecialchars($var_A).'</font></td>
              <td style="padding:10px 14px;font-size:13px;color:#374151;border-top:1px solid #e2e8f0;"><font color="#374151">'.htmlspecialchars($observa_ensayo).'</font></td>
            </tr>
          </table>';

						if (!empty($observa_requi)) {
							$html .= '
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:20px;">
            <tr>
              <td bgcolor="#fff7ed" style="background:#fff7ed;border-left:4px solid #f59e0b;border-radius:0 8px 8px 0;padding:14px 16px;">
                <p style="margin:0 0 4px;font-size:12px;font-weight:700;color:#92400e;text-transform:uppercase;letter-spacing:.05em;"><font color="#92400e">Observaci&oacute;n general</font></p>
                <p style="margin:0;font-size:13px;color:#78350f;line-height:1.65;"><font color="#78350f">'.htmlspecialchars($observa_requi).'</font></p>
              </td>
            </tr>
          </table>';
						}

						$html .= '
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
            <tr>
              <td align="center">
                <table border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td bgcolor="#4A9FD5" style="background:#4A9FD5;background:linear-gradient(135deg,#4A9FD5,#2E86AB);border-radius:30px;">
                      <a href="'.URL_CONCU.'index.php"
                         style="display:inline-block;color:#ffffff;text-decoration:none;font-size:15px;font-weight:700;padding:13px 36px;border-radius:30px;">
                        <font color="#ffffff">Corregir documentos &rarr;</font>
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
        <td style="background:#f8fafc;border-top:1px solid #e2e8f0;padding:20px 40px;text-align:center;">
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

          		/////not ok

          	}

          	 				try{
								//$destinatario1 = 'nancy.hernandez@iecm.mx';
								//$destinatario2 = 'nancy.hernandez@iecm.mx';	
					

								//$mail->AddAddress($destinatario1); // Esta es la dirección a donde enviamos
								$mail->AddAddress($correo); // Esta es la dirección a donde enviamos
								//$mail->AddAddress($email);
								//$mail->AddAddress($destinatario2);// Esta es la dirección a donde enviamos
								//$mail->AddAddress($destinatario3);// Esta es la dirección a donde enviamos

								///////////////------>>>>>
								//$mail->AddCC("inscripciones@iedf.org.mx"); // Copia
								//$mail->AddBCC("inscripciones@iedf.org.mx"); // Copia oculta

							//	$mail->AddBCC("nancy.hernandez@iecm.mx"); // Copia oculta
								$mail->AddBCC("concursos@iecm.mx"); // Copia oculta



								$mail->IsHTML(true); // El correo se envía como HTML
								$mail->Subject = "Sistema de Registro de Concursos de Divulgacion"; // Este es el titulo del email.
								$mail->Body = $html; // Mensaje a enviar
								
								$exito = $mail->Send(); // Envía el correo.
								

								//$exito ="1";//
								//$exito =0;//
					
									if($exito):


									else:
		

									 


									endif;
							}//try
							catch (Exception $e) {
                      // No enviar salida adicional aquí para no romper el JSON de respuesta.
							};//catch

                    

                    		$mail->ClearAddresses();



          	//////////////////////////////////////////////fin correo

			// Respuesta JSON para SweetAlert
			if ($validado) {
				echo json_encode(['ok' => true, 'folio' => $folio, 'nombre' => $nombre]);
			} else {
				echo json_encode(['ok' => false, 'nombre' => $nombre]);
			}
      exit;
    } else {
        echo json_encode(['ok' => null, 'msg' => 'Error al guardar en la base de datos']);
      exit;
    }


///////////////////////////////////////////// fin código del update y el correo




}else{//////////////////////////////////////////////código con el listado de archivo

    $query="SELECT categoria, ensayo, status_ensayo, observa_ensayo, observa_requisitos FROM ".BD_PARTICIPANTES." WHERE idensayo =".$id."";

    $res=sqlsrv_query($conn,$query);
    
    if ($res) {
      $row = sqlsrv_fetch_array($res);
    } else {
      $row = null;
    }

    $categoria=$row["categoria"];

    $ensayo=$row["ensayo"];
    $status_ensayo=$row["status_ensayo"];
    $observa_ensayo=$row["observa_ensayo"];

    $observa_requisitos=$row["observa_requisitos"];

    // Obtener nombre completo del participante para mostrar en la etiqueta
    $nombreCompleto = '';
    $qname = "SELECT B.nombre, B.paterno, B.materno FROM " . BD_PARTICIPANTES . " A INNER JOIN " . BD_USUARIOS . " B ON A.idusuario = B.idusuario WHERE A.idensayo = ?";
    $rname = sqlsrv_query($conn, $qname, array($id));
    if ($rname && ($rrow = sqlsrv_fetch_array($rname))) {
      $nombreCompleto = trim(($rrow['nombre'] ?? '') . ' ' . ($rrow['paterno'] ?? '') . ' ' . ($rrow['materno'] ?? ''));
    }

		?>
<div class="vd-card">
    <div class="vd-header">
        <i class="fas fa-file-alt" style="margin-right:8px;opacity:.85;"></i>Validación de ensayo 
    </div>
    <div class="vd-body">
        <input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
        <input type="hidden" id="categoria" name="categoria" value="<?php echo $categoria; ?>">

        <!-- Ensayo -->
        <div class="vd-section">
          <div class="vd-section__label"><i class="fas fa-book" style="margin-right:5px;"></i>Está calificando a: <?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?></div>
            <div class="vd-field-row">
                <div class="vd-field-col vd-field-col--file">
                    <?php if ($ensayo): ?>
                    <a href="<?php echo UPLOAD_DIR.$ensayo; ?>" target="_blank" class="btn-view-file">
                        <i class="fas fa-file-pdf"></i> Ver ensayo
                    </a>
                    <?php else: ?>
                    <span class="vd-nofile"><i class="fas fa-exclamation-circle"></i> No existe el ensayo</span>
                    <?php endif; ?>
                </div>
                <div class="vd-field-col vd-field-col--status">
                    <label class="vd-label">Estatus</label>
                    <select class="vd-select" name="select_estatus_ensayo" id="select_estatus_ensayo">
                        <option value="0" disabled <?php echo ($status_ensayo == 0) ? 'selected' : ''; ?>>Nuevo</option>
                        <option value="1" <?php echo ($status_ensayo == 1) ? 'selected' : ''; ?>>Correcto</option>
                        <option value="2" <?php echo ($status_ensayo == 2) ? 'selected' : ''; ?>>Incorrecto</option>
                    </select>
                </div>
                <div class="vd-field-col vd-field-col--obs">
                    <label class="vd-label">Observaciones</label>
                    <input type="text" class="vd-input" name="observa_ensayo" id="observa_ensayo"
                        value="<?php echo htmlspecialchars($observa_ensayo ?? ''); ?>" maxlength="799"
                        placeholder="Escribe una observación...">
                </div>
            </div>
        </div>

        <!-- Observaciones generales -->
        <div class="vd-section">
            <div class="vd-section__label"><i class="fas fa-comment-dots" style="margin-right:5px;"></i>Observaciones
                generales de requisitos</div>
            <textarea class="vd-textarea" name="observa_requi" id="observa_requi" maxlength="799"
                placeholder="Escribe las observaciones sobre el cumplimiento de requisitos..."><?php echo htmlspecialchars($observa_requisitos ?? ''); ?></textarea>
        </div>

        <!-- Acciones -->
        <div class="vd-actions">
            <div id="div_errors"></div>
            <button type="button" class="btn-vd-save" id="btn_guardar" onclick="fnguardarvalidaciones(this)">
                <i class="fas fa-save"></i> Guardar validación
            </button>
        </div>
    </div>
</div>
<?php	
}

?>
