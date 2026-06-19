<?php

include 'sqlconnector.php';
session_start();
$idusuario = $_SESSION["idusuario"];

error_reporting(0);


$accion = $_POST['accion'];
$val = trim($_POST['val']);

if ($accion == "folio") {
    $search_val = "%{$val}%";
    $query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito, 
              A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, U.usuario AS usr_validador,
              V.jueces_que_calificaron, V.prom_global
	          FROM " . BD_PARTICIPANTES . " as A 
	          INNER JOIN " . BD_USUARIOS . " as B ON A.idusuario = B.idusuario 
	          LEFT JOIN " . BD_USUARIOS . " as U ON A.status_requisitos = U.idusuario
              LEFT JOIN (
                  SELECT idensayo,
                         SUM(jueces_que_calificaron) AS jueces_que_calificaron,
                         AVG(prom_global) AS prom_global
                  FROM dbo.vw_calificaciones_ensayos
                  GROUP BY idensayo
              ) AS V ON V.idensayo = A.idensayo
	          WHERE A.folio like ?
              ORDER BY A.categoria ASC, CASE WHEN  A.folio IS NULL THEN 0 ELSE 1 END ASC, A.folio ASC";
    $params = array($search_val);
}

if ($accion == "nombre") {
    $search_val = "%{$val}%";
    $query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito,
              A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, concat(U.nombre,' ', U.paterno,' ',U.materno) AS usr_validador,
              V.jueces_que_calificaron, V.prom_global
	          FROM " . BD_PARTICIPANTES . " as A 
	          INNER JOIN " . BD_USUARIOS . " as B ON A.idusuario = B.idusuario 
	          LEFT JOIN " . BD_USUARIOS . " as U ON A.status_requisitos = U.idusuario
              LEFT JOIN (
                  SELECT idensayo,
                         SUM(jueces_que_calificaron) AS jueces_que_calificaron,
                         AVG(prom_global) AS prom_global
                  FROM dbo.vw_calificaciones_ensayos
                  GROUP BY idensayo
              ) AS V ON V.idensayo = A.idensayo
	          WHERE A.sobrenombre like ? OR A.nombre like ? OR A.paterno like ?
              ORDER BY A.categoria ASC, CASE WHEN  A.folio IS NULL THEN 0 ELSE 1 END ASC, A.folio ASC";
    $params = array($search_val, $search_val, $search_val);
}

if ($accion == "todos") {
    $query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito, A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, concat(U.nombre,' ', U.paterno,' ',U.materno) AS usr_validador,
              V.jueces_que_calificaron, V.prom_global
              FROM participantes  as A 
              INNER JOIN " . BD_USUARIOS . " as B ON A.idusuario = B.idusuario 
              LEFT JOIN " . BD_USUARIOS . " as U ON A.status_requisitos = U.idusuario
              LEFT JOIN (
                  SELECT idensayo,
                         SUM(jueces_que_calificaron) AS jueces_que_calificaron,
                         AVG(prom_global) AS prom_global
                  FROM dbo.vw_calificaciones_ensayos
                  GROUP BY idensayo
              ) AS V ON V.idensayo = A.idensayo
              ORDER BY A.categoria ASC, CASE WHEN  A.folio IS NULL THEN 0 ELSE 1 END ASC, A.folio ASC";
    $params = array();
}


//    echo " * ".$query." * ";


$res = sqlsrv_query($conn, $query, $params);

$rows_buffer = '';
$count = 0;

while ($res && ($row = sqlsrv_fetch_array($res))) {
    $nombre_completo = trim($row["nombre"] . ' ' . $row["paterno"] . ' ' . $row["materno"]);
    $titulo = htmlspecialchars($row["titulo"] ?? '—');
    $nombre_obra = htmlspecialchars($row["nombre_obra"] ?? '—');
    $sobrenombre = htmlspecialchars($row["sobrenombre"] ?? '—');
    $folio = htmlspecialchars($row["folio"] ?? '—');
    $usuario = htmlspecialchars($row["usuario"] ?? '');
    $idensayo = intval($row["idensayo"]);
    $categoria = intval($row["categoria"]);

    $ensayo = $row["ensayo"];
    $status_ensayo = $row["status_ensayo"];


    $status_requisitos = $row["status_requisitos"];

    // Determinar validador
    $nom_v = trim(($row['nom_validador'] ?? '') . ' ' . ($row['pat_validador'] ?? ''));
    $validador_str = $nom_v !== '' ? $nom_v : ($row['usr_validador'] ?? '—');

    // Determinar badge de documento
    if ($status_ensayo == 1) {
        $doc_badge = '<span class="badge-val badge-val--ok"><i class="fas fa-check-circle"></i> Validado</span>';
    } elseif ($ensayo != null && $status_ensayo == 0) {
        $doc_badge = '<button class="btn-val-action" onclick="fnValidar(' . $idensayo . ',' . $categoria . ')"><i class="fas fa-clock"></i> Por validar</button>';
    } else {
        $doc_badge = '<span class="badge-val badge-val--none"><i class="fas fa-times-circle"></i> Sin documentación</span>';
    }

    $count++;

    $juecesCalificaron = intval($row['jueces_que_calificaron'] ?? 0);
    $promGlobal = isset($row['prom_global']) ? floatval($row['prom_global']) : null;
    if ($juecesCalificaron <= 0) {
        $badgeJurado = '<span class="badge-val badge-val--none-sin" style="display:inline-block;margin-top:6px;"><i class="fas fa-minus-circle"></i> SIN CALIFICACIONES AUN</span>';
    } elseif ($juecesCalificaron < 7) {
        $badgeJurado = '<span class="badge-val badge-val--none" style="display:inline-block;margin-top:6px;"><i class="fas fa-clock"></i> ESPERANDO CALIFICACION DEL JURADO (' . $juecesCalificaron . '/7)</span>';
    } else {
        $badgeJurado = '<span class="badge-val badge-val--ok" style="display:inline-block;margin-top:6px;"><i class="fas fa-check-circle"></i> CALIFICACION DEL JURADO COMPLETADA ✓</span>';
        if ($promGlobal !== null) {
            $promClass = ($promGlobal <= 6.0) ? 'badge-val--none-calif' : 'badge-val--ok-calif';
            $badgeJurado .= '<span class="badge-val ' . $promClass . '" style="display:inline-block;margin-top:6px;">Calif. Final: ' . number_format($promGlobal, 1) . '</span>';
        }
    }

    $rows_buffer .= '<tr>
            <td class="td-num">' . $count . '</td>
            <td class="td-name"><span class="name-main">' . htmlspecialchars($nombre_completo) . '</span></td>
            <td class="td-titulo"><span class="name-pseudo">' . $titulo . '</span></td>
            <td class="td-obra">' . $nombre_obra . '</co></td>
            <td class="td-pseudo">' . $sobrenombre . '</td>
            <td class="td-folio" style="white-space:nowrap;"><code>' . $folio . '</code></td>
            <td class="td-doc">' . $doc_badge . '</td>
            <td class="td-user"><span class="validator-chip">' . $validador_str . '</span></td>
            <td class="td-assign">' . $badgeJurado . '</td>
        </tr>';
}


if ($count === 0) {
    echo '<div class="vl-empty"><i class="fas fa-search" style="font-size:2rem;opacity:.35;margin-bottom:10px;"></i><br>No se encontraron registros.</div>';
} else {
    echo '<div class="vl-table-wrap">';
    echo '<div class="vl-count">Se encontraron <strong>' . $count . '</strong> registro' . ($count !== 1 ? 's' : '') . '</div>';
    echo '<div class="vl-table-scroll">';
    echo '<table class="vl-table">';
    echo '<thead><tr>
            <th class="th-num">#</th>
            <th>Nombre completo</th>
            <th>Título</th>
            <th>Obra a la que interpela</th>
            <th>Seudónimo</th>
            <th>Folio</th>
            <th>Ensayo</th>
            <th>Validador</th>
            <th>Calificación del jurado</th>
        </tr></thead>';
    echo '<tbody>' . $rows_buffer . '</tbody>';
    echo '</table>';
    echo '</div></div>';
}

/////// aqui quite cosas////
