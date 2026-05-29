<?php
include('sqlconnector.php');

// Para este test, usamos el ID de sesión si existe
session_start();
$idusuario = $_SESSION["idusuario"] ?? 1; // Cambiar a ID real

echo "<h2>Debug: Datos del usuario ID: $idusuario</h2>";

// Datos en usuarios
echo "<h3>Tabla USUARIOS:</h3>";
$query1 = "SELECT idusuario, nombre, paterno, materno, curp FROM usuarios WHERE idusuario = ?";
$res1 = sqlsrv_query($conn, $query1, array($idusuario));
if ($row1 = sqlsrv_fetch_array($res1, SQLSRV_FETCH_ASSOC)) {
    echo "<pre>";
    print_r($row1);
    echo "</pre>";
} else {
    echo "No encontrado en usuarios<br>";
}

// Datos en participantes
echo "<h3>Tabla PARTICIPANTES:</h3>";
$query2 = "SELECT idusuario, nombre, paterno, materno, curp, clave_elector, nombre_obra, paterno_tutor, materno_tutor, nombre_tutor FROM participantes WHERE idusuario = ?";
$res2 = sqlsrv_query($conn, $query2, array($idusuario));
if ($row2 = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) {
    echo "<pre>";
    print_r($row2);
    echo "</pre>";
} else {
    echo "No encontrado en participantes<br>";
}

echo "<hr>";
echo "<p><strong>¿Está registrado como participante?</strong> " . ($row2 ? "SÍ" : "NO") . "</p>";
?>
