<?php
include('sqlconnector.php');

// Agregar columnas si no existen
$columns_to_add = [
    'paterno_tutor' => 'VARCHAR(50) NULL',
    'materno_tutor' => 'VARCHAR(50) NULL',
    'nombre_tutor' => 'VARCHAR(50) NULL',
    'clave_elector' => 'VARCHAR(30) NULL'
];

foreach ($columns_to_add as $column_name => $column_def) {
    // Verificar si la columna ya existe
    $query_check = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'participantes' AND COLUMN_NAME = ?";
    $res_check = sqlsrv_query($conn, $query_check, array($column_name));
    
    if ($res_check === false) {
        echo "Error al verificar columna $column_name: " . print_r(sqlsrv_errors(), true) . "<br>";
        continue;
    }
    
    $found = sqlsrv_fetch_array($res_check, SQLSRV_FETCH_ASSOC);
    
    if (!$found) {
        // La columna no existe, crearla
        $alter_query = "ALTER TABLE dbo.participantes ADD $column_name $column_def";
        echo "Ejecutando: $alter_query<br>";
        
        $res = sqlsrv_query($conn, $alter_query);
        
        if ($res === false) {
            echo "❌ ERROR al agregar $column_name: " . print_r(sqlsrv_errors(), true) . "<br>";
        } else {
            echo "✅ AGREGADO: $column_name<br>";
        }
    } else {
        echo "⚠️ La columna $column_name ya existe<br>";
    }
}

echo "<hr>";
echo "<h3>Verificación final de columnas:</h3>";

$tutor_fields = ['paterno_tutor', 'materno_tutor', 'nombre_tutor', 'clave_elector'];
foreach ($tutor_fields as $field) {
    $query_check = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = 'participantes' AND COLUMN_NAME = ?";
    $res_check = sqlsrv_query($conn, $query_check, array($field));
    if ($res_check) {
        $found = sqlsrv_fetch_array($res_check, SQLSRV_FETCH_ASSOC);
        $status = $found ? "✅ EXISTE" : "❌ NO EXISTE";
        echo "<p><strong>$field:</strong> $status</p>";
    }
}
?>
