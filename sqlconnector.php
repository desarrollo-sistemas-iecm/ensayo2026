<?php
// Prevenir múltiples inclusiones
if (defined('SQLCONNECTOR_LOADED')) {
    return;
}
define('SQLCONNECTOR_LOADED', true);

// ═══════════════════════════════════════════════════════════════════════
// DESARROLLO (servidor de pruebas)
// ═══════════════════════════════════════════════════════════════════════
 $serverName = "tcp:145.0.40.72,1433";
 $connectionInfo = array(
     "Database" => "ensayo2026",
     "UID" => "user1",
     "PWD" => "u53r1",
     "CharacterSet" => "UTF-8",
     "ReturnDatesAsStrings" => "true",
     "Encrypt" => "false",
     "TrustServerCertificate" => "true"
  );


//$serverName = "tcp:145.0.40.72,1433"; 
//$connectionInfo = array( "Database"=>"sipcecc2026", "UID"=>"user1", "PWD"=>"u53r1", "ReturnDatesAsStrings" =>true,"Encrypt" =>false,"TrustServerCertificate" =>true, "CharacterSet" => "UTF-8");

// ═══════════════════════════════════════════════════════════════════════
// PRODUCCIÓN (servidor seguro)
// ═══════════════════════════════════════════════════════════════════════
// $serverName = "145.0.40.70";
// $connectionInfo = array(
//    "Database" => "ensayo2026",
//    "UID" => "ensayo2026_db",
//    "PWD" => "Nj6%$>D3$3Y8",
//   "CharacterSet" => "UTF-8",
//    "ReturnDatesAsStrings" => "true",
//    "Encrypt" => "true",
//   "TrustServerCertificate" => "true"
// );

// ═══════════════════════════════════════════════════════════════════════
// CONEXIÓN A LA BASE DE DATOS
// ═══════════════════════════════════════════════════════════════════════
$conn = sqlsrv_connect($serverName, $connectionInfo);

if ($conn) {
    // echo "Conexión establecida.<br />";
} else {
    echo "Error de conexión a la base de datos.";
    die(print_r(sqlsrv_errors(), true));
}

// ═══════════════════════════════════════════════════════════════════════
// DEFINIR TABLAS (evita redefinir si ya se incluyó en otro punto)
// ═══════════════════════════════════════════════════════════════════════
if (!defined('BD_USUARIOS')) {
    define("BD_USUARIOS", "usuarios");
}
if (!defined('BD_PARTICIPANTES')) {
    define("BD_PARTICIPANTES", "participantes");
}
?>

