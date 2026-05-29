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
$perfil = intval($_SESSION['perfil'] ?? 0);
$usuarioSesion = $_SESSION['usr'] ?? '';

$soloJuez = ($perfil === 3);

$sql = "SELECT C.idcalifica, C.nombre_completo, C.categoria, C.nombre_juez, C.califica1, C.califica2, C.califica3, C.califica4, C.califica5, C.califica6,
         C.fecha_alta, C.fecha_modifica, C.observaciones_gral, C.total, C.idusuario,
         P.folio, P.sobrenombre, P.titulo, P.curp, P.clave_elector, P.nombre_obra
        FROM calificaciones AS C
        LEFT JOIN " . BD_PARTICIPANTES . " AS P ON P.idusuario = C.idusuario
        " . ($soloJuez ? "WHERE C.nombre_juez = ?" : "") . "
        ORDER BY C.fecha_modifica DESC, C.fecha_alta DESC, C.idcalifica DESC";

$params = $soloJuez ? array($usuarioSesion) : array();
$res = sqlsrv_query($conn, $sql, $params);

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

echo '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-family:Calibri,Arial,sans-serif;width:100%;">';
echo '<tr>
        <td colspan="19" style="padding:10px 18px;background:#ffffff;border-bottom:1px solid #e5e7eb;vertical-align:middle;text-align:center;">
          <div style="font-size:14px;font-weight:bold;color:#0f2027;letter-spacing:.03em;">
            Instituto Electoral de la Ciudad de México
          </div>
        </td>
      </tr>';
echo '<tr><td colspan="19" style="height:4px;background:#ffffff;"></td></tr>';
echo '<tr>
        <td colspan="19" style="padding:4px 18px;background:#ffffff;text-align:center;font-size:13px;font-weight:bold;color:#2E86AB;">
          Reporte de calificaciones del Concurso Juvenil de Ensayo 2026
        </td> 
      </tr>';
echo '<tr>
        <td colspan="19" style="padding:4px 18px 10px;background:#ffffff;border-bottom:3px solid #0f2027;text-align:center;font-size:11px;color:#6b7280;letter-spacing:.04em;">
          FECHA Y HORA &nbsp;' . date('d/m/Y H:i:s') . '
        </td>
      </tr>';
echo '<tr><td colspan="14" style="height:6px;"></td></tr>';

echo '<tr>
        <th style="' . $style_th . '">Folio</th>
        <th style="' . $style_th . '">Participante</th>
        <th style="' . $style_th . '">Categoría</th>
        <th style="' . $style_th . '">Juez</th>
        <th style="' . $style_th . '">Fecha de calificación</th>
        <th style="' . $style_th . '">Formato</th>
        <th style="' . $style_th . '">Claridad</th>
        <th style="' . $style_th . '">Contenido</th>
        <th style="' . $style_th . '">Originalidad</th>
        <th style="' . $style_th . '">Estilo</th>
        <th style="' . $style_th . '">Conclusión</th>
        <th style="' . $style_th . '">Total</th>
        <th style="' . $style_th . '">Observaciones</th>
      </tr>';

$i = 0;
$rows = '';

while ($res && ($row = sqlsrv_fetch_array($res))) {
    $i++;
    $td = ($i % 2 === 0) ? $style_td_even : $style_td_odd;
    $folioStyle = ($i % 2 === 0) ? $style_folio . 'background:#e6f4f9;' : $style_folio . 'background:#FFFFFF;';

    $folio = htmlspecialchars($row['folio'] ?? '-', ENT_QUOTES, 'UTF-8');
    $participante = htmlspecialchars($row['nombre_completo'] ?? '', ENT_QUOTES, 'UTF-8');
    $categoria = htmlspecialchars($row['categoria'] ?? '', ENT_QUOTES, 'UTF-8');
    $juez = htmlspecialchars($row['nombre_juez'] ?? '', ENT_QUOTES, 'UTF-8');
    $fechaAlta = fmtFechaExcel($row['fecha_modifica'] ?? '');
    $c1 = htmlspecialchars((string)($row['califica1'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c2 = htmlspecialchars((string)($row['califica2'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c3 = htmlspecialchars((string)($row['califica3'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c4 = htmlspecialchars((string)($row['califica4'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c5 = htmlspecialchars((string)($row['califica5'] ?? ''), ENT_QUOTES, 'UTF-8');
    $c6 = htmlspecialchars((string)($row['califica6'] ?? ''), ENT_QUOTES, 'UTF-8');
    $total = htmlspecialchars((string)($row['total'] ?? ''), ENT_QUOTES, 'UTF-8');
    $obs = htmlspecialchars($row['observaciones_gral'] ?? '', ENT_QUOTES, 'UTF-8');

    $rows .= '<tr>'
        . '<td style="' . $folioStyle . '">' . $folio . '</td>'
        . '<td style="' . $td . '">' . $participante . '</td>'
        . '<td style="' . $td . '">' . $categoria . '</td>'
        . '<td style="' . $td . '">' . $juez . '</td>'
        . '<td style="' . $td . '">' . $fechaAlta . '</td>'
        . '<td style="' . $td . '">' . $c1 . '</td>'
        . '<td style="' . $td . '">' . $c2 . '</td>'
        . '<td style="' . $td . '">' . $c3 . '</td>'
        . '<td style="' . $td . '">' . $c4 . '</td>'
        . '<td style="' . $td . '">' . $c5 . '</td>'
        . '<td style="' . $td . '">' . $c6 . '</td>'
        . '<td style="' . $td . '">' . $total . '</td>'
        . '<td style="' . $td . '">' . $obs . '</td>'
        . '</tr>';
}

if ($i === 0) {
    echo '<tr><td colspan="14" style="padding:18px;text-align:center;background:#fff;color:#6b7280;">No hay calificaciones registradas.</td></tr>';
} else {
    echo $rows;
}

echo '</table>';
?>