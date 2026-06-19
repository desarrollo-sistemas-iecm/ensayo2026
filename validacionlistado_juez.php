<?php
include 'sqlconnector.php';
session_start();
error_reporting(0);

if (!isset($_SESSION['idusuario'])) {
    echo '<div class="vl-empty">Sesion no valida.</div>';
    exit;
}

$idJuez = intval($_SESSION['idusuario']);
$queryPerfil = "SELECT perfil FROM " . BD_USUARIOS . " WHERE idusuario = ?";
$resPerfil = sqlsrv_query($conn, $queryPerfil, array($idJuez));
$rowPerfil = $resPerfil ? sqlsrv_fetch_array($resPerfil) : null;
if (!$rowPerfil || intval($rowPerfil['perfil']) !== 3) {
    echo '<div class="vl-empty">Acceso restringido.</div>';
    exit;
}

$juezUsuario = $_SESSION['usr'] ?? '';
$accion = $_POST['accion'] ?? '';
$val = trim($_POST['val'] ?? '');

$baseSelect = "SELECT A.idensayo, A.idusuario, A.nombre, A.paterno, A.materno, A.titulo, A.sobrenombre, A.folio, A.categoria, A.ensayo, A.status_ensayo, A.nombre_obra,
                      U.nombre AS nom_validador, U.paterno AS pat_validador, U.usuario AS usr_validador
               FROM " . BD_PARTICIPANTES . " AS A
               INNER JOIN " . BD_USUARIOS . " AS B ON A.idusuario = B.idusuario
               LEFT JOIN " . BD_USUARIOS . " AS U ON A.status_requisitos = U.idusuario";

$params = array();

if ($accion === 'folio') {
    $query = $baseSelect . " WHERE A.status_ensayo = 1 AND A.folio LIKE ?
             ORDER BY A.categoria ASC, A.fecha_alta ASC";
    $params[] = "%{$val}%";
} elseif ($accion === 'nombre') {
    $query = $baseSelect . " WHERE A.status_ensayo = 1 AND (A.sobrenombre LIKE ? OR A.nombre LIKE ? OR A.paterno LIKE ?)
             ORDER BY A.categoria ASC, A.fecha_alta ASC";
    $search = "%{$val}%";
    $params[] = $search;
    $params[] = $search;
    $params[] = $search;
} else {
    $query = $baseSelect . " WHERE A.status_ensayo = 1
             ORDER BY A.categoria ASC, A.fecha_alta ASC";
}

$res = sqlsrv_query($conn, $query, $params);

$rowsPendientes = '';
$rowsCalificados = '';
$count = 0;
$countPendientes = 0;
$countCalificados = 0;

while ($res && ($row = sqlsrv_fetch_array($res))) {

    $idensayo  = intval($row['idensayo']);
    $categoria = intval($row['categoria']);
    $titulo    = htmlspecialchars($row['titulo'] ?? '-', ENT_QUOTES, 'UTF-8');
    $seudonimo = htmlspecialchars($row['sobrenombre'] ?? '-', ENT_QUOTES, 'UTF-8');
    $folio     = htmlspecialchars($row['folio'] ?? '-', ENT_QUOTES, 'UTF-8');
    $obra      = htmlspecialchars($row['nombre_obra'] ?? '-', ENT_QUOTES, 'UTF-8');
    $ensayo    = $row['ensayo'] ?? null;

    $idCalifica = 0;
    $qCal = "SELECT TOP 1 idcalifica FROM calificaciones WHERE idjuez = ? AND idensayo = ?";
    $rCal = sqlsrv_query($conn, $qCal, array($idJuez, $idensayo));
    if ($rCal && ($rc = sqlsrv_fetch_array($rCal))) {
        $idCalifica = intval($rc['idcalifica'] ?? 0);
    }

    $docBadge = '<span class="badge-val badge-val--ok"><i class="fas fa-check-circle"></i> Validado</span>';

    if ($idCalifica > 0) {
        $promedioJuez = null;
        $qProm = "SELECT juez1, juez2, juez3, juez4, juez5, juez6, juez7 FROM vw_calificaciones_ensayos WHERE idensayo = ? AND idusuario = ?";
        $rProm = sqlsrv_query($conn, $qProm, array($idensayo, $idJuez));
        if ($rProm && ($rp = sqlsrv_fetch_array($rProm))) {
            foreach (array('juez1','juez2','juez3','juez4','juez5','juez6','juez7') as $col) {
                if ($rp[$col] !== null) {
                    $promedioJuez = floatval($rp[$col]);
                    break;
                }
            }
        }
        $accionBtn = '<span class="badge-val badge-val--ok" style="display:inline-block;margin-top:6px;"><i class="fas fa-check-circle"></i> Ya calificaste este ensayo</span>';
        if ($promedioJuez !== null) {
            $accionBtn .= '<span class="badge-val badge-val--ok" style="display:inline-block;margin-top:6px;">Promedio: ' . number_format($promedioJuez, 2) . '</span>';
        }
        $countCalificados++;
        $targetRows = &$rowsCalificados;
    } else {
        $accionBtn = '<button class="btn-val-action" onclick="fnCalificarEnsayoJuez(' . $idensayo . ',' . $categoria . ')"><i class="fas fa-star"></i>Ensayo por calificar</button>';
        $countPendientes++;
        $targetRows = &$rowsPendientes;
    }

    $targetRows .= '<tr>'
        . '<td class="td-num">~</td>'
        . '<td class="td-pseudo">' . $titulo . '</td>'
        . '<td class="td-doc">' . $obra . '</td>'
        . '<td class="td-pseudo">' . $seudonimo . '</td>'
        . '<td class="td-folio"><code>' . $folio . '</code></td>'
        . '<td class="td-doc">' . $accionBtn . '</td>'
        . '</tr>';

    unset($targetRows);
}

$rows = $rowsPendientes . $rowsCalificados;
$count = $countPendientes + $countCalificados;

if ($count === 0) {
    echo '<div class="vl-empty"><i class="fas fa-search" style="font-size:2rem;opacity:.35;margin-bottom:10px;"></i><br>No se encontraron registros.</div>';
    exit;
}

// Renumerar filas
$numero = 1;
$rows = preg_replace_callback('/<td class="td-num">~<\/td>/', function() use (&$numero) {
    return '<td class="td-num">' . $numero++ . '</td>';
}, $rows);

echo '<div class="vl-table-wrap">';
echo '<div class="vl-count">Se encontraron <strong>' . $count . '</strong> registro' . ($count !== 1 ? 's' : '') . '</div>';
echo '<div class="vl-table-scroll">';
echo '<table class="vl-table">';
echo '<thead><tr>'
    . '<th class="th-num">#</th>'
    . '<th>Título</th>'
    . '<th>Obra a la que interpela</th>'
    . '<th>Seudónimo</th>'
    . '<th>Folio</th>'
    . '<th>Calificar Ensayo</th>'
    . '</tr></thead>';
echo '<tbody>' . $rows . '</tbody>';
echo '</table>';
echo '</div></div>';