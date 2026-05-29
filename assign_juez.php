<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
include('sqlconnector.php');

if (!isset($_SESSION['idusuario'])) {
    echo json_encode(['ok' => false, 'msg' => 'Sesión no válida']);
    exit;
}

$idSesion = intval($_SESSION['idusuario']);

// Solo perfil central (perfil=2) puede asignar
$qPerfil = "SELECT perfil FROM " . BD_USUARIOS . " WHERE idusuario = ?";
$resP = sqlsrv_query($conn, $qPerfil, array($idSesion));
$rowP = $resP ? sqlsrv_fetch_array($resP) : null;
if (!$rowP || intval($rowP['perfil']) !== 2) {
    echo json_encode(['ok' => false, 'msg' => 'Acceso denegado']);
    exit;
}

$id = isset($_POST['id']) ? intval($_POST['id']) : 0; // idensayo
$idjuez = isset($_POST['idjuez']) ? intval($_POST['idjuez']) : 0;

if ($id <= 0 || $idjuez <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Parámetros inválidos']);
    exit;
}

// Verificar que el juez existe y tiene perfil=3
$qj = "SELECT idusuario FROM " . BD_USUARIOS . " WHERE idusuario = ? AND perfil = 3";
$rj = sqlsrv_query($conn, $qj, array($idjuez));
if (!$rj || !($jr = sqlsrv_fetch_array($rj))) {
    echo json_encode(['ok' => false, 'msg' => 'Juez no válido']);
    exit;
}

// El participante debe estar en estatus 'Validado' (status_ensayo=1) con ensayo cargado
$qdoc = "SELECT status_ensayo, ensayo, idjuez_asignado FROM " . BD_PARTICIPANTES . " WHERE idensayo = ?";
$rdoc = sqlsrv_query($conn, $qdoc, array($id));
$rowDoc = $rdoc ? sqlsrv_fetch_array($rdoc) : null;
if (!$rowDoc) {
    echo json_encode(['ok' => false, 'msg' => 'Participante no encontrado']);
    exit;
}

$statusEnsayo = intval($rowDoc['status_ensayo'] ?? 0);
$ensayo = $rowDoc['ensayo'] ?? null;
$juezAsignado = intval($rowDoc['idjuez_asignado'] ?? 0);

// Validar que status_ensayo sea 1 (validado)
if ($statusEnsayo !== 1 || empty($ensayo)) {
    echo json_encode(['ok' => false, 'msg' => 'Solo se puede asignar un jurado cuando el ensayo está validado']);
    exit;
}

// Validar que no tenga juez asignado ya
if ($juezAsignado > 0) {
    echo json_encode(['ok' => false, 'msg' => 'Este participante ya tiene un jurado asignado. No se puede cambiar la asignación.']);
    exit;
}

// Contar cuántos participantes ya tiene asignados
$qcount = "SELECT COUNT(idensayo) AS cnt FROM " . BD_PARTICIPANTES . " WHERE idjuez_asignado = ?";
$rc = sqlsrv_query($conn, $qcount, array($idjuez));
$cnt = 0;
if ($rc && ($crow = sqlsrv_fetch_array($rc))) {
    $cnt = intval($crow['cnt']);
}

// Si el juez ya tiene 5 asignaciones, rechazar
if ($cnt >= 5) {
    echo json_encode(['ok' => false, 'msg' => 'Este jurado ya tiene 5 asignaciones']);
    exit;
}

// Realizar update
$qup = "UPDATE " . BD_PARTICIPANTES . " SET idjuez_asignado = ? WHERE idensayo = ?";
$rup = sqlsrv_query($conn, $qup, array($idjuez, $id));
if ($rup) {
    echo json_encode(['ok' => true, 'msg' => 'Asignación guardada correctamente']);
    exit;
} else {
    echo json_encode(['ok' => false, 'msg' => 'Error al guardar la asignación']);
    exit;
}
