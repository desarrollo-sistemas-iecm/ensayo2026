<?php
$filename = 'reporte_calificaciones_ensayo_' . date('Ymd_H-i-s') . '.xls';

header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

include('sqlconnector.php');
include('rutasitio.php');
error_reporting(0);

session_start();
$idusuario = $_SESSION['idusuario'] ?? 0;
$perfil    = intval($_SESSION['perfil'] ?? 0);

// Query principal: un registro por participante con datos de participantes y promedio global
$sql = "SELECT
    P.idensayo,
    P.idusuario AS idusuario_participante,
    LTRIM(RTRIM(CONCAT(P.nombre, ' ', P.paterno, ' ', P.materno))) AS nombre_completo,
    P.folio,
    P.sobrenombre,
    P.categoria,
    P.titulo,
    P.curp,
    P.clave_elector,
    P.nombre_obra,
    V1.prom_global,
    V1.fecha_alta,
    V1.fecha_modifica
FROM " . BD_PARTICIPANTES . " AS P
LEFT JOIN dbo.vw_calificaciones_ensayos AS V1 ON V1.idensayo = P.idensayo
WHERE V1.idensayo IS NOT NULL
ORDER BY V1.fecha_modifica DESC, P.idensayo ASC";

$res = sqlsrv_query($conn, $sql, array());

// Query de detalle por juez para cada ensayo
$sqlJueces = "SELECT
    C.idensayo,
    C.nombre_juez,
    C.califica1,
    C.califica2,
    C.califica3,
    C.califica4,
    C.califica5,
    C.califica6,
    ROUND(C.califica1+C.califica2+C.califica3+C.califica4+C.califica5+C.califica6, 1) AS total,
    /*C.observaciones_gral,*/
    C.fecha_alta,
    ROW_NUMBER() OVER (PARTITION BY C.idensayo ORDER BY C.idcalifica ASC) AS rn
FROM calificaciones AS C
WHERE C.idensayo IS NOT NULL";

$resJueces = sqlsrv_query($conn, $sqlJueces, array());

// Agrupar calificaciones por idensayo
$juecesData = array();
while ($resJueces && ($rowJ = sqlsrv_fetch_array($resJueces, SQLSRV_FETCH_ASSOC))) {
    $ide = $rowJ['idensayo'];
    $rn  = $rowJ['rn'];
    $juecesData[$ide][$rn] = $rowJ;
}

$style_th      = 'background:#2E86AB;color:#FFFFFF;font-weight:bold;font-size:11px;padding:6px 10px;border:1px solid #1d5a75;white-space:nowrap;';
$style_td_even = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#e6f4f9;';
$style_td_odd  = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#FFFFFF;';
$style_folio   = 'font-weight:700;color:#0f2027;font-size:11px;padding:5px 10px;border:1px solid #d1d5db;';

function fmtFechaExcel($fecha) {
    if (!$fecha) return '';
    if (is_object($fecha) && method_exists($fecha, 'format')) {
        return $fecha->format('d/m/Y H:i:s');
    }
    return htmlspecialchars((string)$fecha, ENT_QUOTES, 'UTF-8');
}

function celda($val, $style) {
    return '<td style="' . $style . '">' . htmlspecialchars((string)($val ?? ''), ENT_QUOTES, 'UTF-8') . '</td>';
}

function celdaNum($val, $style, $dec = 1) {
    return '<td style="' . $style . '">' . number_format((float)($val ?? 0), $dec) . '</td>';
}

// Total de columnas: 4 fijas + (10 por juez * 7 jueces) + 1 calif final = 75
$totalCols = 75;

echo '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-family:Calibri,Arial,sans-serif;width:100%;">';

echo '<tr>
        <td colspan="' . $totalCols . '" style="padding:10px 18px;background:#ffffff;border-bottom:1px solid #e5e7eb;vertical-align:middle;text-align:center;">
          <div style="font-size:14px;font-weight:bold;color:#0f2027;letter-spacing:.03em;">
            Instituto Electoral de la Ciudad de México
          </div>
        </td>
      </tr>';
echo '<tr><td colspan="' . $totalCols . '" style="height:4px;background:#ffffff;"></td></tr>';
echo '<tr>
        <td colspan="' . $totalCols . '" style="padding:4px 18px;background:#ffffff;text-align:center;font-size:13px;font-weight:bold;color:#2E86AB;">
          Reporte de calificaciones del Concurso Juvenil de Ensayo 2026
        </td>
      </tr>';
echo '<tr>
        <td colspan="' . $totalCols . '" style="padding:4px 18px 10px;background:#ffffff;border-bottom:3px solid #0f2027;text-align:center;font-size:11px;color:#6b7280;letter-spacing:.04em;">
          FECHA Y HORA &nbsp;' . date('d/m/Y H:i:s') . '
        </td>
      </tr>';
echo '<tr><td colspan="' . $totalCols . '" style="height:6px;"></td></tr>';

// Encabezados
$headers  = '<tr>';
$headers .= '<th style="' . $style_th . '">Folio</th>';
$headers .= '<th style="' . $style_th . '">Participante</th>';
$headers .= '<th style="' . $style_th . '">Obra</th>';
$headers .= '<th style="' . $style_th . '">Categoría</th>';

for ($j = 1; $j <= 7; $j++) {
    $headers .= '<th style="' . $style_th . '">Nombre Jurado ' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Fecha Calif. J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Formato J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Claridad J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Contenido J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Originalidad J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Estilo J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Conclusión J' . $j . '</th>';
    $headers .= '<th style="' . $style_th . '">Total J' . $j . '</th>';
    // $headers .= '<th style="' . $style_th . '">Observaciones J' . $j . '</th>';
}

$headers .= '<th style="' . $style_th . 'background:#0f5c2e;">Calif. Final</th>';
$headers .= '</tr>';
echo $headers;

$i    = 0;
$rows = '';

while ($res && ($row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC))) {
    $i++;
    $td         = ($i % 2 === 0) ? $style_td_even : $style_td_odd;
    $folioStyle = ($i % 2 === 0)
        ? $style_folio . 'background:#e6f4f9;'
        : $style_folio . 'background:#FFFFFF;';

    $ide        = $row['idensayo'];
    $folio      = htmlspecialchars($row['folio']           ?? '-', ENT_QUOTES, 'UTF-8');
    $participante = htmlspecialchars($row['nombre_completo'] ?? '', ENT_QUOTES, 'UTF-8');
    $obra       = htmlspecialchars($row['nombre_obra']     ?? '-', ENT_QUOTES, 'UTF-8');
    $categoria  = htmlspecialchars((string)($row['categoria'] ?? ''), ENT_QUOTES, 'UTF-8');
    $promGlobal = number_format((float)($row['prom_global'] ?? 0), 2);

    $rows .= '<tr>';
    $rows .= '<td style="' . $folioStyle . '">' . $folio . '</td>';
    $rows .= celda($participante, $td);
    $rows .= celda($obra, $td);
    $rows .= celda($categoria, $td);

    for ($j = 1; $j <= 7; $j++) {
        $juez = $juecesData[$ide][$j] ?? null;
        $rows .= celda($juez['nombre_juez']      ?? '-',  $td);
        $rows .= celda(fmtFechaExcel($juez['fecha_alta'] ?? ''), $td);
        $rows .= celdaNum($juez['califica1']     ?? 0,    $td);
        $rows .= celdaNum($juez['califica2']     ?? 0,    $td);
        $rows .= celdaNum($juez['califica3']     ?? 0,    $td);
        $rows .= celdaNum($juez['califica4']     ?? 0,    $td);
        $rows .= celdaNum($juez['califica5']     ?? 0,    $td);
        $rows .= celdaNum($juez['califica6']     ?? 0,    $td);
        $rows .= celdaNum($juez['total']         ?? 0,    $td);
        // $rows .= celda($juez['observaciones_gral'] ?? '-', $td);
    }

    $rows .= '<td style="' . $td . 'font-weight:700;text-align:center;color:#0f5c2e;">' . $promGlobal . '</td>';
    $rows .= '</tr>';
}

if ($i === 0) {
    echo '<tr><td colspan="' . $totalCols . '" style="padding:18px;text-align:center;background:#fff;color:#6b7280;">No hay calificaciones registradas.</td></tr>';
} else {
    echo $rows;
}

echo '</table>';
?>