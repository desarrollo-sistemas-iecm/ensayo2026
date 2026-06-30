<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

if (isset($_SESSION['idusuario']) && !empty($_SESSION['idusuario'])) {
    // Refrescar el tiempo de vida de la sesión actual
    // Al acceder a $_SESSION en PHP, automáticamente se actualiza el tiempo de expiración de la cookie
    echo json_encode(['ok' => true, 'msg' => 'Sesión renovada']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'No hay sesión activa']);
}
exit;
?>
