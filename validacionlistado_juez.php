<?php
include 'sqlconnector.php';
session_start();
error_reporting(0);

if (!isset($_SESSION['idusuario'])) {
    echo '<div class="vl-empty">Sesion no valida.</div>';
    exit;
}

$idSesion = intval($_SESSION['idusuario']);
$queryPerfil = "SELECT perfil FROM " . BD_USUARIOS . " WHERE idusuario = ?";
$resPerfil = sqlsrv_query($conn, $queryPerfil, array($idSesion));
$rowPerfil = $resPerfil ? sqlsrv_fetch_array($resPerfil) : null;
if (!$rowPerfil || intval($rowPerfil['perfil']) !== 3) {
    echo '<div class="vl-empty">Acceso restringido.</div>';
    exit;
}

$juezUsuario = $_SESSION['usr'] ?? '';
$accion = $_POST['accion'] ?? '';
$val = trim($_POST['val'] ?? '');

$baseSelect = "SELECT A.idensayo, A.idusuario, A.nombre, A.paterno, A.materno, A.sobrenombre, A.folio, A.categoria, A.ensayo, A.status_ensayo, A.idjuez_asignado,
                      U.nombre AS nom_validador, U.paterno AS pat_validador, U.usuario AS usr_validador,
                      C.idcalifica, C.total
               FROM " . BD_PARTICIPANTES . " AS A
               INNER JOIN " . BD_USUARIOS . " AS B ON A.idusuario = B.idusuario
               LEFT JOIN " . BD_USUARIOS . " AS U ON A.status_requisitos = U.idusuario
               LEFT JOIN calificaciones AS C ON C.idusuario = A.idusuario";

$params = array();

if ($accion === 'folio') {
    $query = $baseSelect . " WHERE A.idjuez_asignado = ? AND A.folio LIKE ? ORDER BY A.fecha_alta DESC";
    $params[] = $idSesion;
    $params[] = "%{$val}%";
} elseif ($accion === 'nombre') {
    $query = $baseSelect . " WHERE A.idjuez_asignado = ? AND (A.sobrenombre LIKE ? OR A.nombre LIKE ? OR A.paterno LIKE ?) ORDER BY A.fecha_alta DESC";
    $search = "%{$val}%";
    $params[] = $idSesion;
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
} else {
    $query = $baseSelect . " WHERE A.idjuez_asignado = ? ORDER BY A.fecha_alta DESC";
    $params[] = $idSesion;
}

$res = sqlsrv_query($conn, $query, $params);

$rows = '';
$count = 0;

while ($res && ($row = sqlsrv_fetch_array($res))) {
    $count++;

    $idensayo = intval($row['idensayo']);
    $categoria = intval($row['categoria']);
    $seudonimo = htmlspecialchars($row['sobrenombre'] ?? '-', ENT_QUOTES, 'UTF-8');
    $folio = htmlspecialchars($row['folio'] ?? '-', ENT_QUOTES, 'UTF-8');

    $statusEnsayo = intval($row['status_ensayo'] ?? 0);
    $ensayo = $row['ensayo'] ?? null;

    // Comprobar si este juez ya calificó a este participante
    $idCalifica = 0;
    $qCal = "SELECT TOP 1 idcalifica FROM calificaciones WHERE idusuario = ? AND nombre_juez = ?";
    $rCal = sqlsrv_query($conn, $qCal, array(intval($row['idusuario']), $juezUsuario));
    if ($rCal && ($rc = sqlsrv_fetch_array($rCal))) {
        $idCalifica = intval($rc['idcalifica'] ?? 0);
    }

    if ($statusEnsayo === 1 && !empty($ensayo)) {
        $docBadge = '<span class="badge-val badge-val--ok"><i class="fas fa-check-circle"></i> Validado</span>';
        if ($idCalifica > 0) {
            // Si este juez ya calificó, mostrar badge y no permitir volver a calificar
            $accionBtn = '<span class="badge-val badge-val--ok" style="display:inline-block;margin-top:6px;"><i class="fas fa-check-circle"></i> Ensayo calificado</span>';
        } else {
            $accionBtn = '<button class="btn-val-action" onclick="fnCalificarEnsayoJuez(' . $idensayo . ',' . $categoria . ')"><i class="fas fa-star"></i> Calificar ensayo</button>';
        }
    } elseif (!empty($ensayo)) {
        $docBadge = '<span class="badge-val badge-val--none"><i class="fas fa-clock"></i> Por validar</span>';
        $accionBtn = '<span class="badge-val badge-val--none"><i class="fas fa-lock"></i> Espera validacion</span>';
    } else {
        $docBadge = '<span class="badge-val badge-val--none"><i class="fas fa-times-circle"></i> Sin documentacion</span>';
        $accionBtn = '<span class="badge-val badge-val--none"><i class="fas fa-ban"></i> No disponible</span>';
    }

    $rows .= '<tr>'
        . '<td class="td-num">' . $count . '</td>'
        . '<td class="td-pseudo">' . $seudonimo . '</td>'
        . '<td class="td-folio"><code>' . $folio . '</code></td>'
        . '<td class="td-doc">' . $docBadge . '</td>'
        . '<td class="td-doc">' . $accionBtn . '</td>'
        . '</tr>';
}

if ($count === 0) {
    echo '<div class="vl-empty"><i class="fas fa-search" style="font-size:2rem;opacity:.35;margin-bottom:10px;"></i><br>No se encontraron registros.</div>';
    exit;
}

echo '<div class="vl-table-wrap">';
echo '<div class="vl-count">Se encontraron <strong>' . $count . '</strong> registro' . ($count !== 1 ? 's' : '') . '</div>';
echo '<div class="vl-table-scroll">';
echo '<table class="vl-table">';
echo '<thead><tr>'
    . '<th class="th-num">#</th>'
    . '<th>Seudónimo</th>'
    . '<th>Folio</th>'
    . '<th>Estado</th>'
    . '<th>Acción</th>'
    . '</tr></thead>';
echo '<tbody>' . $rows . '</tbody>';
echo '</table>';
echo '</div></div>';
