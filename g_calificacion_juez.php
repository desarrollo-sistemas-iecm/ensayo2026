<?php
include 'sqlconnector.php';
session_start();
error_reporting(E_ALL ^ E_NOTICE);

if (!isset($_SESSION['idusuario'])) {
    http_response_code(401);
    echo 'Sesion no valida.';
    exit;
}

$idJuez = intval($_SESSION['idusuario']);
$usuarioJuez = $_SESSION['usr'] ?? '';
$nombreJuez = $usuarioJuez;

$queryPerfil = "SELECT perfil FROM " . BD_USUARIOS . " WHERE idusuario = ?";
$resPerfil = sqlsrv_query($conn, $queryPerfil, array($idJuez));
$rowPerfil = $resPerfil ? sqlsrv_fetch_array($resPerfil) : null;
if (!$rowPerfil || intval($rowPerfil['perfil']) !== 3) {
  http_response_code(403);
  echo 'Acceso restringido.';
  exit;
}

$accion = $_POST['action'] ?? '';

if ($accion === 'save') {
    header('Content-Type: application/json; charset=utf-8');

    $idensayo = intval($_POST['idensayo'] ?? 0);
    $categoria = intval($_POST['categoria'] ?? 0);
    $cal1 = floatval($_POST['califica1'] ?? 0);
    $cal2 = floatval($_POST['califica2'] ?? 0);
    $cal3 = floatval($_POST['califica3'] ?? 0);
    $cal4 = floatval($_POST['califica4'] ?? 0);
    $cal5 = floatval($_POST['califica5'] ?? 0);
    $cal6 = floatval($_POST['califica6'] ?? 0);
    $observaciones = trim($_POST['observaciones_gral'] ?? '');

    if ($idensayo <= 0) {
        echo json_encode(array('ok' => false, 'msg' => 'Ensayo invalido.')); 
        exit;
    }

    $queryEnsayo = "SELECT idusuario, nombre, paterno, materno, categoria FROM " . BD_PARTICIPANTES . " WHERE idensayo = ?";
    $resEnsayo = sqlsrv_query($conn, $queryEnsayo, array($idensayo));
    $rowEnsayo = $resEnsayo ? sqlsrv_fetch_array($resEnsayo) : null;

    if (!$rowEnsayo) {
        echo json_encode(array('ok' => false, 'msg' => 'No se encontro el ensayo.'));
        exit;
    }

    $idParticipante = intval($rowEnsayo['idusuario']);
    $nombreCompleto = trim(($rowEnsayo['nombre'] ?? '') . ' ' . ($rowEnsayo['paterno'] ?? '') . ' ' . ($rowEnsayo['materno'] ?? ''));
    $categoriaFinal = intval($rowEnsayo['categoria'] ?? $categoria);

    $fechaActual = date('Y-m-d H:i:s');
    $totalCalificacion = round($cal1 + $cal2 + $cal3 + $cal4 + $cal5 + $cal6, 1);

    $queryExiste = "SELECT idcalifica FROM calificaciones WHERE idusuario = ? AND nombre_juez = ?";
    $resExiste = sqlsrv_query($conn, $queryExiste, array($idParticipante, $nombreJuez));
    $rowExiste = $resExiste ? sqlsrv_fetch_array($resExiste) : null;

    // Si ya existe calificación del mismo juez para este participante, no permitir nueva calificación
    if ($rowExiste) {
      echo json_encode(array('ok' => false, 'msg' => 'Este ensayo ya fue calificado por usted. No puede calificarlo nuevamente.'));
      exit;
    }

    // Insertar nueva calificación
    $querySave = "INSERT INTO calificaciones
              (nombre_completo, categoria, nombre_juez, califica1, califica2, califica3, califica4, califica5, califica6,
               fecha_alta, fecha_modifica, observaciones_gral, total, idusuario)
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $paramsSave = array(
      $nombreCompleto,
      $categoriaFinal,
      $nombreJuez,
      $cal1,
      $cal2,
      $cal3,
      $cal4,
      $cal5,
      $cal6,
      $fechaActual,
      $fechaActual,
      $observaciones,
      $totalCalificacion,
      $idParticipante
    );

    $ok = sqlsrv_query($conn, $querySave, $paramsSave);

    if ($ok) {
        echo json_encode(array('ok' => true, 'msg' => 'Calificacion guardada correctamente.'));
    } else {
        echo json_encode(array('ok' => false, 'msg' => 'No se pudo guardar la calificacion.'));
    }
    exit;
}

$idensayo = intval($_POST['id'] ?? 0);
$categoria = intval($_POST['categoria'] ?? 0);

$query = "SELECT idensayo, idusuario, nombre, paterno, materno, sobrenombre, categoria, ensayo
          FROM " . BD_PARTICIPANTES . " WHERE idensayo = ?";
$res = sqlsrv_query($conn, $query, array($idensayo));
$row = $res ? sqlsrv_fetch_array($res) : null;

if (!$row) {
    echo '<div class="card"><div class="card-body">No se encontro la persona participante.</div></div>';
    exit;
}

$idParticipante = intval($row['idusuario']);
$nombreCompleto = trim(($row['nombre'] ?? '') . ' ' . ($row['paterno'] ?? '') . ' ' . ($row['materno'] ?? ''));
$categoriaFinal = intval($row['categoria'] ?? $categoria);
$ensayo = $row['ensayo'] ?? '';

$queryCalif = "SELECT TOP 1 * FROM calificaciones WHERE idusuario = ? AND nombre_juez = ? ORDER BY idcalifica DESC";
$resCalif = sqlsrv_query($conn, $queryCalif, array($idParticipante, $nombreJuez));
$rowCalif = $resCalif ? sqlsrv_fetch_array($resCalif) : null;

$cal1 = floatval($rowCalif['califica1'] ?? 0);
$cal2 = floatval($rowCalif['califica2'] ?? 0);
$cal3 = floatval($rowCalif['califica3'] ?? 0);
$cal4 = floatval($rowCalif['califica4'] ?? 0);
$cal5 = floatval($rowCalif['califica5'] ?? 0);
$cal6 = floatval($rowCalif['califica6'] ?? 0);
$total = isset($rowCalif['total']) ? floatval($rowCalif['total']) : round(($cal1 + $cal2 + $cal3 + $cal4 + $cal5 + $cal6), 1);
$observaciones = htmlspecialchars($rowCalif['observaciones_gral'] ?? '', ENT_QUOTES, 'UTF-8');

function opcionesCalificacion($minimo, $maximo, $valorActual) {
  $html = '';
  for ($valor = $minimo; $valor <= $maximo + 0.0001; $valor += 0.1) {
    $valorRedondeado = round($valor, 1);
    $selected = (abs($valorActual - $valorRedondeado) < 0.0001) ? ' selected' : '';
    $label = number_format($valorRedondeado, 1);
    $html .= '<option value="' . $label . '"' . $selected . '>' . $label . '</option>';
  }
  return $html;
}
?>
<div class="vd-card">
  <div class="vd-header">
    <i class="fas fa-star" style="margin-right:8px;opacity:.85;"></i>Calificación del ensayo
  </div>
  <div class="vd-body">
    <input type="hidden" id="idensayo_cal" value="<?php echo $idensayo; ?>">
    <input type="hidden" id="categoria_cal" value="<?php echo $categoriaFinal; ?>">

    <div class="vd-section" style="margin-bottom:14px;">
      <div class="vd-field-row" style="align-items:center;">
        <div class="vd-field-col--obs" style="min-width:280px;">
          <label class="vd-label">Persona participante</label>
          <input type="text" class="vd-input" value="<?php echo htmlspecialchars($nombreCompleto, ENT_QUOTES, 'UTF-8'); ?>" readonly>
        </div>
        <div class="vd-field-col--obs" style="min-width:200px;">
          <label class="vd-label">Seudónimo</label>
          <input type="text" class="vd-input" value="<?php echo htmlspecialchars($row['sobrenombre'] ?? '-', ENT_QUOTES, 'UTF-8'); ?>" readonly>
        </div>
        <div class="vd-field-col--file">
          <?php if (!empty($ensayo)) { ?>
            <a href="<?php echo 'uploads/' . htmlspecialchars($ensayo, ENT_QUOTES, 'UTF-8'); ?>" target="_blank" class="btn-view-file"><i class="fas fa-file-pdf"></i> Ver ensayo</a>
          <?php } ?>
        </div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Formato</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="Hoja tamaño carta. Letra Arial de 12 puntos. Interlineado sencillo (1.0). Márgenes de 2.5 centímetros. Párrafos justificados. Citas y referencias en formato APA (7a edición)." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica1" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 0.5, $cal1); ?></select></div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Claridad y coherencia</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="Tesis clara, bien definida y fácil de identificar. Organización lógica y transiciones fluidas entre párrafos." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica2" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 2.0, $cal2); ?></select></div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Contenido y profundidad</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="Relevancia del contenido respecto al tema. Análisis sólido, evidencias y ejemplos para respaldar argumentos." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica3" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 2.0, $cal3); ?></select></div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Originalidad y creatividad</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="Perspectiva original y aportación de nuevas ideas o enfoques con argumentación innovadora." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica4" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 2.0, $cal4); ?></select></div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Estilo, redacción, gramática y ortografía</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="Lenguaje claro y adecuado para todo público, sin errores gramaticales u ortográficos." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica5" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 2.0, $cal5); ?></select></div>
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Conclusión</div>
      <div class="vd-field-row">
        <div class="vd-field-col--obs"><input type="text" class="vd-input" value="La conclusión resume los puntos clave y refuerza la tesis del ensayo." readonly></div>
        <div class="vd-field-col--status"><label class="vd-label">Calificación</label><select class="vd-select cal-juez" id="califica6" onchange="fnCalcularTotalCalificacionJuez()"><?php echo opcionesCalificacion(0.0, 1.5, $cal6); ?></select></div>
      </div>
    </div>

    <div class="vd-section" style="margin-bottom:16px;">
      <div class="vd-field-row" style="justify-content:flex-end;align-items:center;">
        <label class="vd-label" style="margin:0 8px 0 0;">Calificación total</label>
        <input type="text" class="vd-input" id="total_calificacion" value="<?php echo number_format($total, 1); ?>" readonly style="max-width:120px;text-align:center;font-weight:700;">
      </div>
    </div>

    <div class="vd-section">
      <div class="vd-section__label">Observaciones generales</div>
      <textarea class="vd-textarea" id="observaciones_gral_cal" maxlength="500" placeholder="Escribe observaciones generales de la calificacion..." style="min-height:80px;"><?php echo $observaciones; ?></textarea>
    </div>

    <div class="vd-actions">
      <div id="div_errors_cal"></div>
      <button type="button" class="btn-vd-save" id="btn_guardar_cal" onclick="fnGuardarCalificacionJuez(this)"> 
        <i class="fas fa-save"></i> Guardar calificación
      </button>
    </div>
  </div>
</div>
