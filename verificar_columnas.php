<?php
include('sqlconnector.php');

// Verificar columnas de la tabla participantes
$query = "SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'BD_PARTICIPANTES' ORDER BY ORDINAL_POSITION";
$res = sqlsrv_query($conn, $query);

if ($res === false) {
    echo "Error: " . print_r(sqlsrv_errors(), true);
    exit;
}

echo "<h2>Columnas de BD_PARTICIPANTES:</h2>";
echo "<table border='1' cellpadding='10'>";
echo "<tr><th>Columna</th><th>Tipo</th></tr>";

while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    echo "<tr><td>" . $row['COLUMN_NAME'] . "</td><td>" . $row['DATA_TYPE'] . "</td></tr>";
}

echo "</table>";

// Buscar específicamente los campos del tutor
echo "<h3>Búsqueda de campos del tutor:</h3>";
$tutor_fields = ['paterno_tutor', 'materno_tutor', 'nombre_tutor', 'clave_elector'];
foreach ($tutor_fields as $field) {
    $query_check = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'BD_PARTICIPANTES' AND COLUMN_NAME = ?";
    $res_check = sqlsrv_query($conn, $query_check, array($field));
    if ($res_check) {
        $found = sqlsrv_fetch_array($res_check, SQLSRV_FETCH_ASSOC);
        $status = $found ? "✅ EXISTE" : "❌ NO EXISTE";
        echo "<p><strong>$field:</strong> $status</p>";
    }
}
?>
