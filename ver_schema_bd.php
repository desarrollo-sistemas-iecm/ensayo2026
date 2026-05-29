<?php
include('sqlconnector.php');

echo "<h2>Esquema de la tabla participantes</h2>";

// Obtener todas las columnas
$query = "SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME='participantes' ORDER BY ORDINAL_POSITION";
$res = sqlsrv_query($conn, $query);

if (!$res) {
    die("Error en query: " . print_r(sqlsrv_errors(), true));
}

echo "<table border='1' cellpadding='5'><tr><th>Columna</th><th>Tipo</th><th>Nullable</th></tr>";
while ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC)) {
    echo "<tr><td>" . $row['COLUMN_NAME'] . "</td><td>" . $row['DATA_TYPE'] . "</td><td>" . $row['IS_NULLABLE'] . "</td></tr>";
}
echo "</table>";

echo "<hr><h2>Datos de participantes (primeros 5 registros)</h2>";

$query2 = "SELECT TOP 5 * FROM participantes";
$res2 = sqlsrv_query($conn, $query2);

if (!$res2) {
    die("Error en query: " . print_r(sqlsrv_errors(), true));
}

while ($row = sqlsrv_fetch_array($res2, SQLSRV_FETCH_ASSOC)) {
    echo "<pre>";
    foreach ($row as $key => $val) {
        echo "$key: " . ($val === null ? "NULL" : $val) . "\n";
    }
    echo "</pre><hr>";
}
?>
