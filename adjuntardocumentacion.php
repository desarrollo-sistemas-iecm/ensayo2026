<?php
include 'sqlconnector.php';
$idusuario = $_SESSION["idusuario"];
define("UPLOAD_DIR", "uploads/");

if ($registrado) {

    $query = "SELECT A.*,B.fecha_nacimiento,B.fecha_alta as fecha_alta_usuario 
    FROM " . BD_PARTICIPANTES . " as A LEFT JOIN " . BD_USUARIOS . " as B ON A.idusuario= B.idusuario WHERE A.idusuario =" . $idusuario . "";
    $res = sqlsrv_query($conn, $query);

    if ($row = sqlsrv_fetch_array($res)) {
        $fecha_nacimiento = $row["fecha_nacimiento"];
        $fecha_alta = $row["fecha_alta_usuario"];
        $ensayo = $row["ensayo"];
        $status_ensayo = $row["status_ensayo"];
        $observa_ensayo = $row["observa_ensayo"];
        //$manifestacion = $row["manifestacion"];
        //$status_manifest = $row["status_manifest"];
        //$observa_manifest = $row["observa_manifest"];
        //$cartacesion = $row["cartacesion"];
        //$status_carta = $row["status_carta"];
        //$observa_carta = $row["observa_carta"];
    }

    if ($ensayo) {
        $completado = true;
        $mensaje = '<div class="alert alert-success" style="border-radius:10px;border-left:4px solid #10b981;">
                <i class="fas fa-check-circle"></i> <strong>¡Registro completo!</strong> Tu ensayo está en proceso de validación. Una vez validado te enviaremos tu acuse con tu número de folio.
            </div>';
    } else {
        $completado = false;
        $mensaje = '<div class="alert alert-warning" style="border-radius:10px;border-left:4px solid #f59e0b;">
                <i class="fas fa-exclamation-triangle"></i> <strong>Documentación incompleta.</strong> Adjunta tu ensayo para continuar con el concurso.
            </div>';
    }

    $validado_disabled = "";
    $validado_disabled2 = "";

    if ($status_ensayo == '1' ) {
        $validado_disabled = " disabled";
        $validado_disabled2 = ' style="pointer-events: none; opacity: 0.6;"';
        $validado = "OK";
    }

    $edad = date_diff(date_create($fecha_nacimiento), date_create($fecha_alta))->y;

  //  if (!($ensayo || $manifestacion || $cartacesion)) {
        //if (!($ensayo )) {
       // echo '<div class="alert" style="background:linear-gradient(135deg,#4A9FD5,#2E86AB);color:#fff;
                  //   border-radius:10px;padding:16px 20px;margin-bottom:20px;box-shadow:0 4px 12px rgba(74,159,213,.25);">
              
          //  </div>';
    //}

    echo '<div class="row">';

    // ══════════════════════════════════════════════════════════════════
    // COLUMNA 1: DESCARGAS
    // ══════════════════════════════════════════════════════════════════
  /*  echo '<div class="col-md-6 mb-4">
            <div class="form-section">
                <div class="form-section__header" style="background:#2E86AB;">
                    <i class="fas fa-download"></i> Formatos para descarga
                </div>
                <div class="form-section__body">';

    if ($edad >= 18) {
        echo '<p style="color:#6b7280;font-size:.9rem;margin-bottom:16px;">
                <i class="fas fa-info-circle" style="color:#4A9FD5;"></i> 
                Descarga los siguientes formatos y llénalos con tinta azul o negro.
              </p>';

        echo '<div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px;margin-bottom:12px;
                     background:#f9fafb;transition:all .2s;cursor:pointer;"
                  onmouseover="this.style.background=\'#eff6ff\';this.style.borderColor=\'#93c5fd\';"
                  onmouseout="this.style.background=\'#f9fafb\';this.style.borderColor=\'#e5e7eb\';">
                <div class="d-flex align-items-center">
                    <div style="width:40px;height:40px;border-radius:8px;background:#1e40af;
                                display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="far fa-file-word" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div style="flex:1;">
                        <a href="documentos_concursos/carta_cesion.docx" target="_blank" 
                           style="color:#1e40af;font-weight:600;text-decoration:none;font-size:.9rem;">
                            Carta de cesión de derechos
                        </a>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:2px;">
                            Formato DOCX
                        </div>
                    </div>
                    <i class="fas fa-external-link-alt" style="color:#9ca3af;font-size:.85rem;"></i>
                </div>
              </div>';

        echo '<div style="border:1px solid #e5e7eb;border-radius:8px;padding:14px 16px;
                     background:#f9fafb;transition:all .2s;cursor:pointer;"
                  onmouseover="this.style.background=\'#eff6ff\';this.style.borderColor=\'#93c5fd\';"
                  onmouseout="this.style.background=\'#f9fafb\';this.style.borderColor=\'#e5e7eb\';">
                <div class="d-flex align-items-center">
                    <div style="width:40px;height:40px;border-radius:8px;background:#1e40af;
                                display:flex;align-items:center;justify-content:center;margin-right:12px;">
                        <i class="far fa-file-word" style="color:#fff;font-size:1.2rem;"></i>
                    </div>
                    <div style="flex:1;">
                        <a href="documentos_concursos/manifestacion_bajo_protesta.docx" target="_blank" 
                           style="color:#1e40af;font-weight:600;text-decoration:none;font-size:.9rem;">
                            Manifestación bajo protesta de decir verdad
                        </a>
                        <div style="font-size:.78rem;color:#6b7280;margin-top:2px;">
                            Cumple con la edad requerida para registrarse
                        </div>
                    </div>
                    <i class="fas fa-external-link-alt" style="color:#9ca3af;font-size:.85rem;"></i>
                </div>
              </div>';
    }

    echo '</div>
          </div>
        </div>';*/

    // ══════════════════════════════════════════════════════════════════
    // COLUMNA 2: ADJUNTAR DOCUMENTACIÓN
    // ══════════════════════════════════════════════════════════════════
    echo '<div class="col-md-12 mb-4">
            <div class="form-section">
                <div class="form-section__header" style="background:#4A9FD5;">
                    <i class="fas fa-paperclip"></i> Adjuntar ensayo
                </div>
                <div class="form-section__body">
                    <div id="mensajeadjuntos">' . $mensaje . '</div>';

    // ────────────────────────────────────────────────────────────────
    // DOCUMENTO 1: ENSAYO
    // ────────────────────────────────────────────────────────────────
    $tooltip0 = "Deberá estar en un archivo PDF no mayor a 4MB. Con una extensión mínima de 7 a 14 cuartillas, incluyendo portada y bibliografía; en hoja tamaño carta,
     letra Arial de 12 puntos, interlineado sencillo, los cuatro márgenes de 2.5 centímetros, párrafos justificados, páginas numeradas,
      citas y referencias en formato APA (7a. edición).";

    echo '<div style="border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px;background:#fff;">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div style="font-weight:600;color:#374151;font-size:.88rem;margin-bottom:4px;">
                        <i class="fas fa-file-alt" style="color:#4A9FD5;margin-right:6px;"></i> Mi ensayo
                    </div>';

    if ($ensayo) {
        echo '<a href="uploads/' . $ensayo . '" target="_blank" 
                 style="display:inline-flex;align-items:center;gap:6px;background:#4A9FD5;color:#fff;
                        padding:6px 14px;border-radius:6px;text-decoration:none;font-size:.82rem;font-weight:600;">
                <i class="fas fa-eye"></i> Ver archivo
              </a>';

        if ($status_ensayo == 1) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#dcfce7;
                         border:1px solid #86efac;color:#15803d;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;">
                    <i class="fas fa-check-circle"></i> Validado
                  </span>';
        }
        if ($status_ensayo == 2) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#fef9c3;
                         border:1px solid #fde047;color:#854d0e;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;cursor:pointer;"
                         data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($observa_ensayo) . '">
                    <i class="fas fa-exclamation-triangle"></i> Observación
                  </span>';
        }
    } else {
        echo '<span style="color:#9ca3af;font-size:.85rem;font-style:italic;">Sin adjuntar</span>';
    }

    echo '</div>
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                        <label for="file-select-0" style="display:flex;align-items:center;justify-content:center;gap:8px;
                               background:linear-gradient(135deg,#4A9FD5,#3d8ac7,#2E86AB);color:#fff;
                               padding:10px 16px;border-radius:8px;cursor:pointer;font-weight:600;
                               font-size:.85rem;margin:0;transition:transform .15s,box-shadow .15s;
                               box-shadow:0 2px 8px rgba(74,159,213,.3);"
                               onmouseover="if(!this.parentElement.style.pointerEvents) {this.style.transform=\'translateY(-2px)\';this.style.boxShadow=\'0 4px 12px rgba(74,159,213,.4)\'}"
                               onmouseout="this.style.transform=\'\';this.style.boxShadow=\'0 2px 8px rgba(74,159,213,.3)\'">
                            <i class="fas fa-upload"></i> Adjuntar ensayo
                        </label>
                        <input type="file" id="file-select-0" class="form-control-file" 
                               style="display:none;" onchange="fnUpload(' . $idusuario . ',0)" ' . $validado_disabled . '>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#" data-toggle="tooltip" data-html="true" title="' . $tooltip0 . '"
                       style="color:#9ca3af;font-size:1.3rem;">
                        <i class="far fa-question-circle"></i>
                    </a>
                </div>
            </div>
          </div>';

    // ────────────────────────────────────────────────────────────────
    // DOCUMENTO 2: CARTA DE CESIÓN
    // ────────────────────────────────────────────────────────────────
  /*  $tooltip4 = "Llenar con tinta azul toda la información que se solicita en el formato, escanearlo en PDF y que el archivo no sea mayor a 4 MB.";

    echo '<div style="border:1px solid #e5e7eb;border-radius:10px;padding:16px;margin-bottom:16px;background:#fff;">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div style="font-weight:600;color:#374151;font-size:.88rem;margin-bottom:4px;">
                        <i class="fas fa-file-signature" style="color:#5B9CB8;margin-right:6px;"></i> Carta de cesión
                    </div>';

    if ($cartacesion) {
        echo '<a href="uploads/' . $cartacesion . '" target="_blank" 
                 style="display:inline-flex;align-items:center;gap:6px;background:#5B9CB8;color:#fff;
                        padding:6px 14px;border-radius:6px;text-decoration:none;font-size:.82rem;font-weight:600;">
                <i class="fas fa-eye"></i> Ver archivo
              </a>';

        if ($status_carta == 1) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#dcfce7;
                         border:1px solid #86efac;color:#15803d;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;">
                    <i class="fas fa-check-circle"></i> Validado
                  </span>';
        }
        if ($status_carta == 2) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#fef9c3;
                         border:1px solid #fde047;color:#854d0e;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;cursor:pointer;"
                         data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($observa_carta) . '">
                    <i class="fas fa-exclamation-triangle"></i> Observación
                  </span>';
        }
    } else {
        echo '<span style="color:#9ca3af;font-size:.85rem;font-style:italic;">Sin adjuntar</span>';
    }

    echo '</div>
                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                        <label for="file-select-2" style="display:flex;align-items:center;justify-content:center;gap:8px;
                               background:linear-gradient(135deg,#4A9FD5,#3d8ac7,#2E86AB);color:#fff;
                               padding:10px 16px;border-radius:8px;cursor:pointer;font-weight:600;
                               font-size:.85rem;margin:0;transition:transform .15s,box-shadow .15s;
                               box-shadow:0 2px 8px rgba(74,159,213,.3);"
                               onmouseover="if(!this.parentElement.style.pointerEvents) {this.style.transform=\'translateY(-2px)\';this.style.boxShadow=\'0 4px 12px rgba(74,159,213,.4)\'}"
                               onmouseout="this.style.transform=\'\';this.style.boxShadow=\'0 2px 8px rgba(74,159,213,.3)\'">
                            <i class="fas fa-upload"></i> Adjuntar carta de cesión
                        </label>
                        <input type="file" id="file-select-2" class="form-control-file" 
                               style="display:none;" onchange="fnUpload(' . $idusuario . ',2)" ' . $validado_disabled . '>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#" data-toggle="tooltip" data-html="true" title="' . $tooltip4 . '"
                       style="color:#9ca3af;font-size:1.3rem;">
                        <i class="far fa-question-circle"></i>
                    </a>
                </div>
            </div>
          </div>';*/

    // ────────────────────────────────────────────────────────────────
    // DOCUMENTO 3: MANIFESTACIÓN
    // ────────────────────────────────────────────────────────────────
  /*  $tooltip3 = "Llenar con tinta azul toda la información que se solicita en el formato, escanearlo en PDF y que el archivo no sea mayor a 4 MB.";

    echo '<div style="border:1px solid #e5e7eb;border-radius:10px;padding:16px;background:#fff;">
            <div class="row align-items-center">
                <div class="col-md-4 mb-2 mb-md-0">
                    <div style="font-weight:600;color:#374151;font-size:.88rem;margin-bottom:4px;">
                        <i class="fas fa-file-contract" style="color:#2E86AB;margin-right:6px;"></i> Manifestación
                    </div>';

    if ($manifestacion) {
        echo '<a href="uploads/' . $manifestacion . '" target="_blank" 
                 style="display:inline-flex;align-items:center;gap:6px;background:#2E86AB;color:#fff;
                        padding:6px 14px;border-radius:6px;text-decoration:none;font-size:.82rem;font-weight:600;">
                <i class="fas fa-eye"></i> Ver archivo
              </a>';

        if ($status_manifest == 1) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#dcfce7;
                         border:1px solid #86efac;color:#15803d;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;">
                    <i class="fas fa-check-circle"></i> Validado
                  </span>';
        }
        if ($status_manifest == 2) {
            echo '<span style="display:inline-flex;align-items:center;gap:4px;background:#fef9c3;
                         border:1px solid #fde047;color:#854d0e;padding:4px 10px;border-radius:6px;
                         font-size:.75rem;font-weight:700;margin-left:8px;cursor:pointer;"
                         data-toggle="tooltip" data-placement="top" title="' . htmlspecialchars($observa_manifest) . '">
                    <i class="fas fa-exclamation-triangle"></i> Observación
                  </span>';
        }
    } else {
        echo '<span style="color:#9ca3af;font-size:.85rem;font-style:italic;">Sin adjuntar</span>';
    }

    echo '</div>



                <div class="col-md-6 mb-2 mb-md-0">
                    <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                        <label for="file-select-4" style="display:flex;align-items:center;justify-content:center;gap:8px;
                               background:linear-gradient(135deg,#4A9FD5,#3d8ac7,#2E86AB);color:#fff;
                               padding:10px 16px;border-radius:8px;cursor:pointer;font-weight:600;
                               font-size:.85rem;margin:0;transition:transform .15s,box-shadow .15s;
                               box-shadow:0 2px 8px rgba(74,159,213,.3);"
                               onmouseover="if(!this.parentElement.style.pointerEvents) {this.style.transform=\'translateY(-2px)\';this.style.boxShadow=\'0 4px 12px rgba(74,159,213,.4)\'}"
                               onmouseout="this.style.transform=\'\';this.style.boxShadow=\'0 2px 8px rgba(74,159,213,.3)\'">
                            <i class="fas fa-upload"></i> Adjuntar manifestación
                        </label>
                        <input type="file" id="file-select-4" class="form-control-file" 
                               style="display:none;" onchange="fnUpload(' . $idusuario . ',4)" ' . $validado_disabled . '>
                    </div>
                </div>
                <div class="col-md-2 text-center">
                    <a href="#" data-toggle="tooltip" data-html="true" title="' . $tooltip3 . '"
                       style="color:#9ca3af;font-size:1.3rem;">
                        <i class="far fa-question-circle"></i>
                    </a>
                </div>
            </div>
          </div>';*/

    echo '</div>
          </div>
        </div>';

    echo '</div>'; // cierra row principal

    echo '<div id="errormsg"></div>';
} else {
    // Usuario no registrado: no mostrar alerta visual aquí (interfaz principal maneja el estado)
}
?>

<style>
    .form-section {
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .09);
        margin-bottom: 22px;
    }

    .form-section__header {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 18px;
        font-weight: 700;
        font-size: .95rem;
        color: #fff;
    }

    .form-section__body {
        padding: 18px 20px;
        background: #fff;
    }

    .upload-btn-wrapper {
        position: relative;
        display: inline-block;
        width: 100%;
    }

    @media (max-width: 768px) {
        .form-section__body {
            padding: 14px 12px;
        }

        .form-section__header {
            font-size: .88rem;
            padding: 10px 14px;
        }
    }
</style>

<script>
// Función mejorada con SweetAlert2 Toast
function fnUpload(idusuario, tipo) {
    var fileInput = document.getElementById('file-select-' + tipo);
    var file = fileInput.files[0];

    if (!file) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'No se seleccionó ningún archivo',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
        return;
    }

    // Validar tipo de archivo (solo PDF)
    if (file.type !== 'application/pdf') {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Solo se permiten archivos PDF',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
        fileInput.value = '';
        return;
    }

    // Validar tamaño (4MB máximo)
    if (file.size > 4 * 1024 * 1024) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'El archivo no debe exceder 4 MB',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
        fileInput.value = '';
        return;
    }

    var formData = new FormData();
    formData.append('idusuario', idusuario);
    formData.append('index', tipo);
    formData.append('file-select', file);

    // Mostrar loading
    Swal.fire({
        title: 'Subiendo archivo...',
        html: '<div style="font-size:.9rem;color:#6b7280;margin-top:8px;">Por favor espera</div>',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'upload.php', true);

    xhr.onload = function() {
        if (xhr.status === 200) {
            var response = xhr.responseText.trim();

            if (response.indexOf('alert-success') !== -1 || response.indexOf('éxito') !== -1) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '¡Archivo subido correctamente!',
                    html: '<span style="font-size:.85rem;">Tu documento se ha guardado exitosamente</span>',
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    background: '#f0fdf4',
                    iconColor: '#10b981'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: 'Error al subir el archivo',
                    html: '<span style="font-size:.85rem;">' + response + '</span>',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true
                });
            }
        } else {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Error de conexión',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    };

    xhr.onerror = function() {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Error de red',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true
        });
    };

    xhr.send(formData);
}

// Activar tooltips de Bootstrap
$(function() {
    $('[data-toggle="tooltip"]').tooltip();
});

// ══════════════════════════════════════════════════════════════════
// Alerta automática cuando hay documentos con observaciones
// ══════════════════════════════════════════════════════════════════
<?php
$tiene_observaciones = false;
$docs_incorrectos = [];

if ($status_ensayo == 2) {
    $tiene_observaciones = true;
    $docs_incorrectos[] = [
        'nombre' => 'Ensayo',
        'observacion' => $observa_ensayo
    ];
}

if ($tiene_observaciones):
?>
window.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var html = '<p style="font-size:.95rem;margin-bottom:14px;">Tus documentos fueron revisados y presentan <strong>observaciones que debes corregir</strong>:</p>';
        
        html += '<div style="text-align:left;margin:16px 0;">';
        <?php foreach ($docs_incorrectos as $doc): ?>
        html += '<div style="background:#fef9c3;border:1px solid #fde047;border-radius:8px;padding:10px 14px;margin-bottom:10px;">' +
                '<div style="font-weight:700;color:#854d0e;font-size:.88rem;margin-bottom:4px;">' +
                '<i class="fas fa-exclamation-triangle" style="margin-right:6px;"></i><?php echo $doc['nombre']; ?></div>' +
                <?php if (!empty($doc['observacion'])): ?>
                '<div style="font-size:.85rem;color:#78350f;"><?php echo addslashes($doc['observacion']); ?></div>' +
                <?php endif; ?>
                '</div>';
        <?php endforeach; ?>
        html += '</div>';
        
        html += '<p style="font-size:.88rem;color:#6b7280;margin-top:12px;">Por favor vuelve a subir tu ensayo corregido en esta sección.</p>';
        
        Swal.fire({
            icon: 'warning',
            title: '¡Atención! Tu ensayo tiene observaciones que debes corregir',
            html: html,
            confirmButtonText: '<i class="fas fa-upload" style="margin-right:6px;"></i>Entendido, voy a corregirlos',
            confirmButtonColor: '#4A9FD5',
            allowOutsideClick: false,
            width: '560px'
        });
    }, 700);
});
<?php endif; ?>

// ══════════════════════════════════════════════════════════════════
// Alerta cuando los documentos fueron validados
// ══════════════════════════════════════════════════════════════════
<?php
$todos_validados = ($status_ensayo == 1);
if ($todos_validados && $folio && trim($folio) !== '-'):
?>
window.addEventListener('DOMContentLoaded', function() {
    // Solo mostrar si no se ha mostrado antes (usando sessionStorage)
    if (!sessionStorage.getItem('alertaValidacionMostrada_<?php echo $folio; ?>')) {
        setTimeout(function() {
            Swal.fire({
                icon: 'success',
                title: '¡Felicidades! Tu ensayo ha sido validado',
                html: '<p style="font-size:.95rem;margin-bottom:16px;">Tu registro está completo y se te ha asignado el siguiente folio:</p>' +
                      '<div style="background:linear-gradient(135deg,#0f2027,#2c5364,#4A9FD5);border-radius:10px;padding:18px 24px;margin:16px 0;">' +
                      '<p style="margin:0 0 6px;color:rgba(255,255,255,.7);font-size:.8rem;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Tu número de folio:</p>' +
                      '<p style="margin:0;font-family:monospace;font-size:1.8rem;font-weight:700;color:#7dd3fc;letter-spacing:.1em;"><?php echo htmlspecialchars($folio); ?></p>' +
                      '</div>' +
                      '<p style="font-size:.88rem;color:#6b7280;">Se envió un correo electrónico con tu acuse de registro. ¡Conserva tu folio!</p>',
                confirmButtonText: '<i class="fas fa-check"></i> Entendido',
                confirmButtonColor: '#4A9FD5',
                allowOutsideClick: false,
                width: '540px'
            });
            sessionStorage.setItem('alertaValidacionMostrada_<?php echo $folio; ?>', 'true');
        }, 700);
    }
});
<?php endif; ?>
</script>
