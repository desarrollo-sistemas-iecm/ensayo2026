<?php
$filename = 'solicitudes_ensayo_' . date('Ymd_H-i-s') . '.xls';

header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

include('sqlconnector.php');
include('rutasitio.php');
define("UPLOAD_DIR", URL_CONCU . "uploads/");

error_reporting(0);

$sql = "SELECT * FROM " . BD_PARTICIPANTES . " ORDER BY idensayo DESC";

// ── Estilos inline para Excel con tema azul ensayo ──
$style_th      = 'background:#2E86AB;color:#FFFFFF;font-weight:bold;font-size:11px;padding:6px 10px;border:1px solid #1d5a75;white-space:nowrap;';
$style_td_even = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#e6f4f9;';
$style_td_odd  = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#FFFFFF;';
$style_folio   = 'font-weight:700;color:#0f2027;font-size:11px;padding:5px 10px;border:1px solid #d1d5db;';

echo '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-family:Calibri,Arial,sans-serif;width:100%;">';

// ── Header del reporte ──
echo '<tr>
        <td colspan="14" style="padding:10px 18px;background:#ffffff;border-bottom:1px solid #e5e7eb;vertical-align:middle;text-align:center;">
          <div style="font-size:14px;font-weight:bold;color:#0f2027;letter-spacing:.03em;">
            Instituto Electoral de la Ciudad de México
          </div>
        </td>
      </tr>';
echo '<tr><td colspan="14" style="height:4px;background:#ffffff;"></td></tr>';
echo '<tr>
        <td colspan="14" style="padding:4px 18px;background:#ffffff;text-align:center;font-size:13px;font-weight:bold;color:#2E86AB;">
          Solicitudes de Registro al Concurso Juvenil de Ensayo 2026
        </td>
      </tr>';
echo '<tr>
        <td colspan="14" style="padding:4px 18px 10px;background:#ffffff;border-bottom:3px solid #0f2027;text-align:center;font-size:11px;color:#6b7280;letter-spacing:.04em;">
          FECHA Y HORA &nbsp;' . date('d/m/Y H:i:s') . '
        </td>
      </tr>';
echo '<tr><td colspan="15" style="height:6px;"></td></tr>';

// Cabeceras
echo '<tr>
        <th style="' . $style_th . '">Folio</th>
        <th style="' . $style_th . '">Categoría</th>
        <th style="' . $style_th . '">Apellido paterno</th>
        <th style="' . $style_th . '">Apellido materno</th>
        <th style="' . $style_th . '">Nombre(s)</th>
        <th style="' . $style_th . '">CURP</th>
        <th style="' . $style_th . '">Clave de elector</th>
        <th style="' . $style_th . '">Obra</th>
        <th style="' . $style_th . '">Sexo</th>
        <th style="' . $style_th . '">Fecha de nacimiento</th>
        <th style="' . $style_th . '">Ensayo</th>
        <th style="' . $style_th . '">Observaciones ensayo</th>
        <th style="' . $style_th . '">Observaciones generales</th>
        <th style="' . $style_th . '">Alcaldía/Entidad</th>
      </tr>';

$row = sqlsrv_query($conn, $sql);
$i = 0;

while ($res = sqlsrv_fetch_array($row)) {
    $i++;
    $td = ($i % 2 === 0) ? $style_td_even : $style_td_odd;
    $folio_style = ($i % 2 === 0) ? $style_folio . 'background:#e6f4f9;' : $style_folio . 'background:#FFFFFF;';

    echo '<tr>';

    // Folio
    if ($res['folio'] != '') {
        echo '<td style="' . $folio_style . '">' . htmlspecialchars($res['folio']) . '</td>';
    } else {
        echo '<td style="' . $td . '">--</td>';
    }

    echo '<td style="' . $td . '">' . htmlspecialchars($res['categoria']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['paterno']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['materno']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['nombre']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['curp'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['clave_elector'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['nombre_obra'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['genero']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['fecha_nacimiento']) . '</td>';

    // Ensayo
    if ($res['ensayo'] != '') {
      echo '<td style="' . $td . '"><a href="' . URL_CONCU . 'uploads/' . htmlspecialchars($res['ensayo']) . '" target="_blank">ver/ensayo</a></td>';
    } else {
        echo '<td style="' . $td . '">--</td>';
    }
    echo '<td style="' . $td . '">' . htmlspecialchars($res['observa_ensayo']) . '</td>';

    echo '<td style="' . $td . '">' . htmlspecialchars($res['observa_requisitos']) . '</td>';

    // Alcaldía
    $alcaldia = $res['alcaldia'];
    if ($alcaldia != 18) {
        switch ($alcaldia) {
            case 2:  $alcalde = "Azcapotzalco"; break;
            case 3:  $alcalde = "Coyoacán"; break;
            case 4:  $alcalde = "Cuajimalpa de Morelos"; break;
            case 5:  $alcalde = "Gustavo A Madero"; break;
            case 6:  $alcalde = "Iztacalco"; break;
            case 7:  $alcalde = "Iztapalapa"; break;
            case 8:  $alcalde = "La Magdalena Contreras"; break;
            case 9:  $alcalde = "Milpa Alta"; break;
            case 10: $alcalde = "Álvaro Obregón"; break;
            case 11: $alcalde = "Tláhuac"; break;
            case 12: $alcalde = "Tlalpan"; break;
            case 13: $alcalde = "Xochimilco"; break;
            case 14: $alcalde = "Benito Juárez"; break;
            case 15: $alcalde = "Cuauhtémoc"; break;
            case 16: $alcalde = "Miguel Hidalgo"; break;
            case 17: $alcalde = "Venustiano Carranza"; break;
            default: $alcalde = ""; break;
        }
        echo '<td style="' . $td . '">' . htmlspecialchars($alcalde) . '</td>';
    } else {
        echo '<td style="' . $td . '">' . htmlspecialchars($res['entidad']) . '</td>';
    }

    echo '</tr>';
}

echo '</table>';
?>
