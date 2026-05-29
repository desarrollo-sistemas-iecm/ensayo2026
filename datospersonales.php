<?php

include 'cat_alcaldia.php';
?>

<style>
  /* ── Secciones del formulario ── */
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

  /* Inputs mejorados */
  .form-section .form-control {
    border: 1px solid #d1d5db !important;
    border-radius: 8px !important;
    padding: 8px 12px;
    font-size: .9rem;
    transition: border-color .2s, box-shadow .2s;
    background: #fff !important;
  }

  .form-section .form-control:focus {
    border-color: #4A9FD5 !important;
    box-shadow: 0 0 0 3px rgba(74, 159, 213, .12) !important;
    outline: none;
  }

  .form-section .form-control[readonly],
  .form-section .form-control:disabled {
    background: #f3f4f6 !important;
    color: #6b7280 !important;
    cursor: not-allowed;
  }

  .form-section select.form-control {
    cursor: pointer;
  }

  .form-section label.control-label {
    font-size: .85rem;
    font-weight: 600;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 5px;
  }

  .form-section label.control-label .badge-req {
    color: #dc2626;
  }

  .form-field-row {
    margin-bottom: 14px;
  }

  /* ── Responsivo móvil ── */
  @media (max-width: 575px) {

    /* Secciones: menos padding lateral */
    .form-section__body {
      padding: 14px 12px;
    }

    .form-section__header {
      font-size: .88rem;
      padding: 10px 14px;
    }

    /* Labels: ancho completo, sin float */
    .form-section .row.form-field-row > label.control-label {
      width: 100%;
      max-width: 100%;
      flex: 0 0 100%;
      padding-bottom: 3px;
    }

    /* Inputs: ancho completo */
    .form-section .row.form-field-row > div[class*="col-"] {
      width: 100%;
      max-width: 100%;
      flex: 0 0 100%;
    }

    /* Botón guardar */
    #div_guardar {
      padding: 0 15px;
    }

    #btn_guardar {
      width: 100%;
      padding: 12px 20px !important;
      font-size: .92rem !important;
    }
  }
</style>

<!-- Validaciones JavaScript -->
<script src="js/validaciones.js"></script>

<form method="POST" id="guardarparticipante">
  <input type="hidden" id="area" value="<?php echo $area; ?>">
  <input type="hidden" id="idusuario" value="<?php echo $idusuario; ?>">
  <input type="hidden" id="edad" value="<?php echo $edad; ?>">
  <input type="hidden" value="0" id="distrito" name="distrito">

  <!-- ══ SECCIÓN 1: DATOS DEL ENSAYO ══ -->
  <div class="form-section">
    <div class="form-section__header" style="background:#2E86AB;">
      <i class="fas fa-book-open"></i> Datos del ensayo
    </div>
    <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> Título del ensayo
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="titulo" id="titulo"
            value="<?php echo $titulo; ?>" maxlength="300"
            placeholder="Escribe el título de tu ensayo"
            <?php echo $style_disabled2; ?>>
        </div>
      </div>
      <div class="row form-field-row mb-0">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> Seudónimo
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="sobrenombre" id="sobrenombre"
            value="<?php echo $sobrenombre; ?>" maxlength="30"
            placeholder="Nombre inventado"
            <?php echo $style_disabled2; ?>>
        </div>
      </div>
      <br>
      <div class="row form-field-row mb-0">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> Obra, obras o tomo a la que interpela
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="nombre_obra" id="nombre_obra"
            value="<?php echo $nombre_obra; ?>" maxlength="200"
            placeholder="Escribe la obra, obras o tomo"
            <?php echo $style_disabled2; ?>>
        </div>
      </div>
    </div>
  </div>

  <!-- ══ SECCIÓN 2: DATOS PERSONALES ══ -->
  <div class="form-section">
    <div class="form-section__header" style="background:#4A9FD5;">
      <i class="fas fa-user"></i> Datos personales
      <span style="font-size:.78rem;font-weight:400;margin-left:6px;opacity:.8;">(datos del registro, no editables)</span>
    </div>
    <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> Primer apellido
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="paterno" id="paterno"
            data-validar="letras"
            value="<?php echo $paterno; ?>" <?php echo $style_disabled; ?> maxlength="30"
            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+" 
            title="Solo se permiten letras, espacios y guiones">
        </div>
      </div>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> Segundo apellido
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="materno" id="materno"
            data-validar="letras"
            value="<?php echo $materno; ?>" <?php echo $style_disabled; ?> maxlength="30"
            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+"
            title="Solo se permiten letras, espacios y guiones">
        </div>
      </div>
      <br>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> CURP
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="curp" id="curp"
            value="<?php echo $curp; ?>" <?php echo $style_disabled; ?> minlength="18" maxlength="18"
            placeholder="Escribe tu CURP"
            pattern="[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}"
            title="CURP (18 caracteres): 4 letras, fecha AAAAMMDD, H/M, 5 letras, 2 caracteres alfanuméricos"
            oninput="formatCurpInput(this)">
        </div>
      </div>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-user fa-sm" style="color:#9ca3af;"></i> Nombre(s)
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="nombre" id="nombre"
            data-validar="letras"
            value="<?php echo $nombre; ?>" <?php echo $style_disabled; ?> maxlength="30"
            pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+"
            title="Solo se permiten letras y espacios">
        </div>
      </div>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-venus-mars fa-sm" style="color:#9ca3af;"></i> Genero
        </label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="genero" name="genero"
            value="<?php echo $genero; ?>" <?php echo $style_disabled; ?>>
        </div>
      </div>

      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-birthday-cake fa-sm" style="color:#9ca3af;"></i> Edad
        </label>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="edad" name="edad"
            value="<?php echo $edad; ?>" <?php echo $style_disabled; ?>>
        </div>
        <div class="col-sm-4">
          <input type="text" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
            value="<?php echo $fecha_nacimiento; ?>" <?php echo $style_disabled; ?>>
        </div>
      </div>

      <div class="row form-field-row mb-0">
        <label class="control-label col-sm-4">Categoría</label>
        <div class="col-sm-8">
          <select class="form-control" name="categoria" id="categoria" <?php echo $style_disabled; ?>>
            <option value="1" <?php if ($edad >= 15 && $edad <= 17) echo 'selected'; ?>>Categoría 1 (15 a 17 años)</option>
            <option value="2" <?php if ($edad >= 18 && $edad <= 23) echo 'selected'; ?>>Categoría 2 (18 a 23 años)</option>
          </select>
        </div>
      </div>
    </div>
  </div>

  <?php
  if ($edad >= 15 && $edad <= 17) {
   
  //echo "se pinta edad:".$edad;

  //<!-- ══ SECCIÓN 3: CONTACTO Y UBICACIÓN ══ -->
  echo'  <div class="form-section">
    <div class="form-section__header" style="background:#4A9FD5;">
      <i class="fas fa-address-book"></i> Datos de madre, padre, tutora o tutor
    </div>
        <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> Primer apellido
        </label>
        <div class="col-sm-8">

          <input type="text" class="form-control" name="paterno_tutor" id="paterno_tutor" maxlength="30" 
            data-validar="letras"  value="'.$paterno_tutor.'" 
            placeholder="Escribe el  apellido paterno" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+" title="Solo se permiten letras, espacios y guiones" '.$style_disabled2.'>

        </div>
      </div>';

     echo' <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> Segundo apellido
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="materno_tutor" id="materno_tutor" maxlength="30" 
            data-validar="letras"  value="'.$materno_tutor.'" placeholder="Escribe el  apellido materno" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\-]+"  title="Solo se permiten letras, espacios y guiones" '.$style_disabled2.'>
        </div>
      </div>';

     echo' <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-user fa-sm" style="color:#9ca3af;"></i> Nombre(s)
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="nombre_tutor" id="nombre_tutor" maxlength="30" 
            data-validar="letras" value="'.$nombre_tutor.'"  placeholder="Escribe el nombre(s)" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s]+" title="Solo se permiten letras y espacios" '.$style_disabled2.'>
        </div>
      </div>';

       echo'  <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-id-card fa-sm" style="color:#9ca3af;"></i> Clave de Elector
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" name="clave_elector" id="clave_elector" maxlength="18"
            value="'.$clave_elector.'" placeholder="Escribe tu clave de elector" '.$style_disabled2.' >
            <small class="text-muted" style="font-size:.78rem;">Captura tu CLAVE DE ELECTOR con mayúscula, sin espacios y la siguiente estructura: ABCDEF12345678A123</small>
        </div>
      </div>
   
        </div>
      </div>
    </div>
  </div>';


  }
 else 
    {
      //echo "nada";


    }
  ?>

  <!-- ══ SECCIÓN 3: CONTACTO Y UBICACIÓN ══ -->
    <div class="form-section">
    <div class="form-section__header" style="background:#4A9FD5;">
      <i class="fas fa-address-book"></i> Contacto y ubicación
    </div>
    <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-envelope fa-sm" style="color:#9ca3af;"></i> Correo electrónico de la persona participante
        </label>
        <div class="col-sm-8">
          <input type="email" class="form-control" id="correo" name="correo"
            value="<?php echo $correo; ?>" <?php echo $style_disabled; ?>
            pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}"
            title="Ingresa un correo electrónico válido">
        </div>
      </div>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <i class="fas fa-phone fa-sm" style="color:#9ca3af;"></i> Teléfono de casa
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="tel1" name="tel1"
            data-validar="numeros"
            value="<?php echo $tel1; ?>" <?php echo $style_disabled2; ?> maxlength="12"
            pattern="[0-9]{10,12}"
            title="Solo números, entre 10 y 12 dígitos"
            placeholder="10 dígitos">
        </div>
      </div>
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> <i class="fas fa-mobile-alt fa-sm" style="color:#9ca3af;"></i> Teléfono celular
        </label>
        <div class="col-sm-8">
          <input type="text" class="form-control" id="tel2" name="tel2"
            data-validar="numeros"
            value="<?php echo $tel2; ?>" <?php echo $style_disabled2; ?> maxlength="12"
            pattern="[0-9]{10,12}"
            title="Solo números, entre 10 y 12 dígitos"
            placeholder="10 dígitos" required>
        </div>
      </div>

      <div class="row form-group" style="display: none;">
        <label class="control-label col-sm-4">* Domicilio</label>
        <div class="col-sm-8">
          <input type="text" class="form-control noborder" id="domicilio" name="domicilio"
            value="<?php echo $domicilio; ?>" maxlength="99" <?php echo $style_disabled2; ?>>
        </div>
      </div>

      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> <i class="fas fa-map-marker-alt fa-sm" style="color:#9ca3af;"></i> Demarcación territorial
        </label>
        <div class="col-sm-8">
          <select class="form-control" id="alcaldia" name="alcaldia"
            <?php echo $style_disabled2; ?> onchange="fnSelectEntidad()">
            <option value="0" selected disabled>Selecciona una opción</option>
            <?php
            for ($i = 2; $i < sizeof($cat_alcaldia); $i++) {
              echo '<option value="' . $i . '"';
              if ($alcaldia == $i) {
                echo 'selected';
              }
              echo '>' . $cat_alcaldia[$i] . '</option>';
            }
            ?>
            <option value="18" <?php if ($alcaldia == 18) {
                                  echo 'selected';
                                } ?>>Otro</option>
          </select>

          <div class="row form-field-row bloqueentidad mt-2" id="bloqueentidad">
            <label class="control-label col-sm-4">Ingresa Municipio, Ciudad, Estado y País</label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="entidad" name="entidad"
                value="<?php echo $entidad; ?>" maxlength="300"
                placeholder="Municipio, Ciudad, Estado y País"
                <?php echo $style_disabled2; ?>
                <?php if ($alcaldia != 18) {
                  echo 'disabled'; 
                } ?>>
            </div>
          </div>
        </div>
      </div>

      <div class="row form-field-row mb-0">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> <i class="fas fa-bullhorn fa-sm" style="color:#9ca3af;"></i> ¿Cómo te enteraste del concurso?
        </label>
        <div class="col-sm-8">
          <select class="form-control" id="te_enteraste" name="te_enteraste" <?php echo $style_disabled2; ?>>
            <option value="0" disabled selected>Selecciona una opción</option>
            <option value="1" <?php if ($te_enteraste == 1) { 
                                echo 'selected';
                              } ?>>Página de internet</option>
            <option value="2" <?php if ($te_enteraste == 2) {
                                echo 'selected';
                              } ?>>Redes sociales</option>
            <option value="3" <?php if ($te_enteraste == 3) {
                                echo 'selected';
                              } ?>>Dirección distrital</option>
            <option value="4" <?php if ($te_enteraste == 4) {
                                echo 'selected';
                              } ?>>Cartel</option>
            <option value="5" <?php if ($te_enteraste == 5) {
                                echo 'selected';
                              } ?>>Volante</option>
            <option value="6" <?php if ($te_enteraste == 6) {
                                echo 'selected';
                              } ?>>Escuela</option>
            <option value="7" <?php if ($te_enteraste == 7) {
                                echo 'selected';
                              } ?>>Otra</option>
          </select>
        </div>
      </div>
    </div>
  </div>



 <!-- ══ SECCIÓN 4: MANIFESTACIONES ══ -->
    <div class="form-section">
    <div class="form-section__header" style="background:#4A9FD5;">
      <i class="fas fa-address-book"></i>Declaraciones
    </div>

    <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> Manifestación 1
        </label>
         <input type="radio" name="manifestacion1" id="manifestacion1" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto que conozco y acepto los términos establecidos en la convocatoria.</p>
        </div>
   
      </div>
    </div>

   <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
          <span class="badge-req">*</span> Manifestación 2
        </label>
         <input type="radio" name="manifestacion2" id="manifestacion2" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto, bajo protesta de decir verdad, que no soy persona funcionaria pública del IECM.</p>
        </div>
   
      </div>
    </div>

   <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
        <span class="badge-req">*</span> Manifestación 3
        </label>
         <input type="radio" name="manifestacion3" id="manifestacion3" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto, bajo protesta de decir verdad, que cumplo con la edad requerida en la convocatoria.</p>
        </div>
   
      </div>
    </div>

   <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
        <span class="badge-req">*</span> Manifestación 4
        </label>
         <input type="radio" name="manifestacion4" id="manifestacion4" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto, bajo protesta de decir verdad, que toda la información proporcionada es verídica.</p>
        </div>
   
      </div>
    </div>

       <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
        <span class="badge-req">*</span> Manifestación 5
        </label>
         <input type="radio" name="manifestacion5" id="manifestacion5" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto que el ensayo es una obra original, inédita, no publicada anteriormente y de autoría intelectual propia. 
          También autorizo al Instituto para utilizar libre y gratuitamente el contenido total o parcial de la obra, para ser impreso en libro o en versión electrónica, 
          gráfica, plástica, audiovisual, fotográfica u otro medio. Esta reproducción atenderá al cumplimiento de los fines institucionales del IECM 
          en materia de divulgación de la cultura democrática y educación cívica, además, se otorgará el crédito autoral correspondiente.</p>
        </div>
   
      </div>
    </div>
   
       <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
        <span class="badge-req">*</span> Manifestación 6
        </label>
         <input type="radio" name="manifestacion6" id="manifestacion6" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>Manifiesto que leí el aviso de privacidad del sistema de registro de participantes en los concursos para la promoción de la participación ciudadana y divulgación de la cultura democrática.</p>
        </div>
   
      </div>
    </div>

       <div class="form-section__body">
      <div class="row form-field-row">
        <label class="control-label col-sm-4">
        <span class="badge-req">*</span> Manifestación 7
        </label>
         <input type="radio" name="manifestacion7" id="manifestacion7" value="1" <?php echo $style_disabled2; ?>>
        <div class="col-sm-4">
         <p>En caso de ser una de las personas ganadoras, autorizo que el área editorial del IECM me contacte, si es necesario, durante el proceso de edición.</p>
        </div>
   
      </div>
    </div>
    </div>
      <p style="color:red; font-size: 20px;">*Campos obligatorios</p>
  </div>



  <?php
  // Obtener estado de documentos para determinar estado del botón
  $status_ensayo = 0;
  $status_manifest = 0;
  $status_carta = 0;
  $ensayo_file = '';
  $manifest_file = '';
  $carta_file = '';
 
  
  if ($registrado) {
      $query_docs = "SELECT ensayo, status_ensayo 
                     FROM " . BD_PARTICIPANTES . " WHERE idusuario = ?";
      $res_docs = sqlsrv_query($conn, $query_docs, array($idusuario));
      if ($row_docs = sqlsrv_fetch_array($res_docs)) {
          $status_ensayo = $row_docs['status_ensayo'] ?? 0;
           $ensayo_file = $row_docs['ensayo'] ?? '';
         // $status_manifest = $row_docs['status_manifest'] ?? 0;
          //$status_carta = $row_docs['status_carta'] ?? 0;
      
          //$manifest_file = $row_docs['manifestacion'] ?? '';
          //$carta_file = $row_docs['cartacesion'] ?? '';

      }
  }
  
  // Determinar estado del botón
  $todos_validados = ($status_ensayo == 1 );

  $en_revision = ($ensayo_file ) && 
                 ($status_ensayo == 0 ) &&
                 ($status_ensayo != 2 );

  $tiene_observaciones = ($status_ensayo == 2 );
  
  // Texto y estado del botón
  if ($todos_validados) {
      $btn_label = '✅  Documentos validados';
      $btn_disabled = 'disabled';
      $btn_opacity = 'opacity:.6;cursor:not-allowed;';
  } elseif ($en_revision) {
      $btn_label = '⏳  En proceso de validación';
      $btn_disabled = 'disabled';
      $btn_opacity = 'opacity:.6;cursor:not-allowed;';
  } elseif ($tiene_observaciones) {
      $btn_label = '💾  Guardar información';
      $btn_disabled = '';
      $btn_opacity = '';
  } else {
      $btn_label = '💾  Guardar información';
      $btn_disabled = $style_disabled2 ? 'disabled' : '';
      $btn_opacity = $style_disabled2 ? 'opacity:.6;cursor:not-allowed;' : '';
  }
  ?>

  <p>&nbsp;</p>
  <div id="div_guardar" class="row justify-content-center">
    <div class="col-sm-6 text-center">
      <input type="button" value="<?php echo $btn_label; ?>" id="btn_guardar"
        class="control-form btn btnguardar"
        style="background:linear-gradient(135deg,#2E86AB,#4A9FD5);color:#fff;
                    border:none;border-radius:30px;padding:12px 40px;font-size:1rem;
                    font-weight:700;letter-spacing:.04em;width:100%;
                    box-shadow:0 4px 18px rgba(74,159,213,.35);
                    transition:transform .15s,box-shadow .15s;cursor:pointer;
                    <?php echo $btn_opacity; ?>"
        onmouseover="<?php echo (!$btn_disabled && !$style_disabled2) ? "this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(74,159,213,.45)'" : ''; ?>"
        onmouseout="<?php echo (!$btn_disabled && !$style_disabled2) ? "this.style.transform='translateY(0)';this.style.boxShadow='0 4px 18px rgba(74,159,213,.35)'" : ''; ?>"
        <?php echo $btn_disabled; ?> onclick="this.disabled=true; guardarparticipante()">
      <div id="errorMsg"></div>
    </div>
  </div>
  <span id="spanguardar"></span>

</form>

<!-- ══ BOTÓN FLOTANTE: AVISO DE PRIVACIDAD ══ -->
<button type="button" id="btnAvisoPrivacidad" onclick="document.getElementById('modalAvisoPrivacidad').style.display='flex'"
  title="Aviso de Privacidad"
  style="position:fixed;bottom:28px;right:28px;z-index:9999;
         width:60px;height:60px;border-radius:50%;border:none;cursor:pointer;
         background:#0f2027;box-shadow:0 4px 18px rgba(0,0,0,.4);
         display:flex;align-items:center;justify-content:center;
         transition:transform .2s,box-shadow .2s;"
  onmouseover="this.style.transform='scale(1.12)';this.style.boxShadow='0 6px 24px rgba(0,0,0,.5)'"
  onmouseout="this.style.transform='scale(1)';this.style.boxShadow='0 4px 18px rgba(0,0,0,.4)'">
  <i class="fas fa-shield-alt" style="font-size:1.5rem;color:#4A9FD5;"></i>
</button>

<!-- ══ MODAL AVISO DE PRIVACIDAD ══ -->
<div id="modalAvisoPrivacidad"
  style="display:none;position:fixed;inset:0;z-index:10000;
         background:rgba(0,0,0,.65);align-items:center;justify-content:center;padding:16px;">
  <div style="background:#fff;border-radius:14px;max-width:700px;width:100%;
              max-height:90vh;display:flex;flex-direction:column;
              box-shadow:0 20px 60px rgba(0,0,0,.45);overflow:hidden;">

    <!-- Cabecera del modal -->
    <div style="background:#0f2027;padding:18px 24px;display:flex;align-items:center;gap:12px;">
      <i class="fas fa-shield-alt" style="font-size:1.4rem;color:#4A9FD5;"></i>
      <strong style="color:#fff;font-family:'Funnel Sans',sans-serif;font-size:1.05rem;letter-spacing:.04em;">
        AVISO DE PRIVACIDAD SIMPLIFICADO
      </strong>
    </div>

    <!-- Cuerpo del modal (scroll) -->
    <div style="padding:24px;overflow-y:auto;flex:1;font-size:.9rem;color:#374151;line-height:1.75;text-align:justify;">
      <p>
        El <strong>Instituto Electoral de la Ciudad de México (IECM)</strong>, a través de la Dirección Ejecutiva de Género, Derechos Humanos, 
        Educación Cívica y Construcción Ciudadana, es el responsable del tratamiento de los datos personales que nos proporcione, 
        los cuales serán protegidos en el Sistema de registro de participantes en los concursos para la promoción de la participación ciudadana 
        y divulgación de la cultura democrática.
        <br>
        Los datos personales que recabemos serán utilizados con la finalidad siguiente: llevar a cabo el registro de datos personales 
        de las personas interesadas en participar en los concursos que organice, promueva o difunda el Instituto Electoral. 
        <br>
        Los datos personales podrán ser transferidos a la Comisión de Derechos Humanos de la Ciudad de México, para la investigación de quejas y denuncias 
        por presuntas violaciones a los derechos humanos; al Instituto de Transparencia, Acceso a la Información Pública, Protección de Datos Personales y 
        Rendición de Cuentas de la Ciudad de México, para la sustanciación de recursos de revisión, recurso de inconformidad, denuncias y procedimientos de 
        verificación para determinar el presunto incumplimiento a la Ley de Protección de Datos Personales en posesión de Sujetos Obligados de la Ciudad de México; 
        a la Auditoría Superior de la Ciudad de México, para el ejercicio de sus funciones de fiscalización; a Órganos de Control Interno, 
        para la realización de auditorías o desarrollo de investigaciones por presuntas faltas administrativas; a Órganos Jurisdiccionales Locales y Federales, 
        para la sustanciación de los procesos jurisdiccionales tramitados ante ellos y al Instituto Nacional Electoral, para la sustanciación de procedimientos 
        administrativos sancionadores. Este Sistema de Datos Personales no cuenta con Encargados; ni con Despacho de Auditores Externos encargados del ejercicio 
        de las funciones de fiscalización.
        <br>
        Usted podrá manifestar la negativa al tratamiento de sus datos personales directamente ante la Unidad de Transparencia del IECM, 
        ubicada en la Calle de Huizaches No. 25, Rancho los Colorines, Planta Baja, Tlalpan, C. P. 14386, Ciudad de México, 
        con número telefónico 55 5483 3800 ext. 4725 y 4727 y al correo electrónico <b>unidad.transparencia@iecm.mx</b>.
        <br>
        Para conocer el Aviso de privacidad Integral, puede acudir directamente a la Unidad de Transparencia o ingresar al 
        Sitio de Internet:<b> https://www.iecm.mx/proteccion-de-datos-personales/.  
        <br>
        Fecha de última actualización: 30 de enero de 2026

    </div>

    <!-- Pie: checkbox de aceptación -->
    <div style="padding:16px 24px;border-top:1px solid #e5e7eb;background:#f9fafb;
                display:flex;align-items:center;justify-content:space-between;gap:16px;flex-wrap:wrap;">
      <label style="display:flex;align-items:center;gap:10px;cursor:pointer;font-size:.9rem;color:#374151;font-weight:600;">
        <input type="checkbox" id="checkAvisoPrivacidad" style="width:18px;height:18px;cursor:pointer;"
          onchange="onCheckAviso(this)">
        He leído y acepto el Aviso de Privacidad
      </label>
      <div id="mensajeAvisoAceptado" style="display:none;align-items:center;gap:6px;
           background:#dcfce7;border:1px solid #86efac;border-radius:8px;padding:6px 14px;">
        <i class="fas fa-check-circle" style="color:#16a34a;"></i>
        <span style="color:#15803d;font-size:.88rem;font-weight:600;">Aviso aceptado</span>
      </div>
      <button type="button" onclick="cerrarModalAviso()"
        style="background:none;border:1px solid #d1d5db;border-radius:8px;padding:6px 18px;
               cursor:pointer;font-size:.88rem;color:#6b7280;">
        Cerrar
      </button>
    </div>

  </div>
</div>

<script>
  // ═══════════════════════════════════════════════════════════════════════
  // AVISO DE PRIVACIDAD
  // ═══════════════════════════════════════════════════════════════════════
  // La validación de la clave de elector se encuentra en js/funcionesajax_test.js


  (function() {
    var KEY = 'avisoPrivacidadAceptado_<?php echo $_SESSION["idusuario"] ?? "0"; ?>';

    function marcarAceptado() {
      var chk = document.getElementById('checkAvisoPrivacidad');
      var msg = document.getElementById('mensajeAvisoAceptado');
      chk.checked = true;
      chk.disabled = true;
      chk.style.cursor = 'default';
      chk.parentElement.style.cursor = 'default';
      msg.style.display = 'flex';
    }

    // Restaurar estado si ya fue aceptado en esta sesión
    if (sessionStorage.getItem(KEY) === '1') {
      window.addEventListener('DOMContentLoaded', marcarAceptado);
    } else {
      // Mostrar modal automáticamente la primera vez
      setTimeout(function() {
        document.getElementById('modalAvisoPrivacidad').style.display = 'flex';
      }, 800);
    }

    window.onCheckAviso = function(checkbox) {
      if (checkbox.checked) {
        sessionStorage.setItem(KEY, '1');
        marcarAceptado();
      }
    };

    // Evita cerrar el modal si el aviso no fue aceptado
    window.cerrarModalAviso = function() {
      var chk = document.getElementById('checkAvisoPrivacidad');
      var modal = document.getElementById('modalAvisoPrivacidad');
      if (chk && chk.checked) {
        if (modal) modal.style.display = 'none';
      } else {
        if (typeof Swal !== 'undefined') {
          Swal.fire({
            icon: 'success',
            title: 'Aviso aceptado',
            text: 'Has aceptado el Aviso de Privacidad.',
            confirmButtonColor: '#4fd54a'
          }).then(function() {
            if (chk) chk.focus();
          });
        } else {
          alert('Has aceptado el Aviso de Privacidad.');
          if (chk) chk.focus();
        }
      }
    };
  })();
  
    // Mascara y validación en vivo para CURP
    function formatCurpInput(el){
      if (typeof window.aplicarMascaraCurpInput === 'function') {
        window.aplicarMascaraCurpInput(el);
        return;
      }
      if(!el) return;
      var v = String(el.value || '').toUpperCase().replace(/[^A-Z0-9]/g,'').slice(0,18);
      el.value = v;
      var re = /^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z]{2}$/;
      if(v.length === 18){
        if(re.test(v)){
          el.classList.remove('is-invalid'); el.classList.add('is-valid');
        } else {
          el.classList.remove('is-valid'); el.classList.add('is-invalid');
        }
      } else {
        el.classList.remove('is-valid'); el.classList.remove('is-invalid');
      }
    }
</script>
