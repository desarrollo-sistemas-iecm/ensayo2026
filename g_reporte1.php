<?php
$filename = 'reporte_validados_ensayo_' . date('Ymd_H-i-s') . '.xls';

header("Content-Disposition: attachment; filename=\"$filename\"");
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';

include('sqlconnector.php');
error_reporting(0);

$sql = "SELECT
	A.folio,
	A.categoria,
	A.titulo,
	A.sobrenombre ,
	A.curp,
	A.clave_elector,
	A.nombre_obra,
	A.paterno,
	A.materno,
	A.nombre,
	A.genero,
	A.tel1 ,
	A.tel2,
	A.correo,
	B.fecha_nacimiento,
	B.usuario,
	B.iddistrito,
	A.idjuez_asignado,
	A.alcaldia,
	A.te_enteraste,
	concat(B.nombre, ' ', B.paterno, ' ', B.materno) AS usr_validador
FROM
	participantes as A
INNER JOIN usuarios as B ON
	A.idusuario = B.idusuario
WHERE
	folio IS NOT NULL
ORDER BY
	CAST(SUBSTRING(folio, 4, 3) AS INT) DESC";

// ── Estilos inline para Excel con tema azul ensayo ──
$style_th      = 'background:#2E86AB;color:#FFFFFF;font-weight:bold;font-size:11px;padding:6px 10px;border:1px solid #1d5a75;white-space:nowrap;';
$style_td_even = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#e6f4f9;';
$style_td_odd  = 'font-size:11px;padding:5px 10px;border:1px solid #d1d5db;background:#FFFFFF;';
$style_folio   = 'font-weight:700;color:#0f2027;font-size:11px;padding:5px 10px;border:1px solid #d1d5db;';

echo '<table border="0" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-family:Calibri,Arial,sans-serif;width:100%;">';

// ── Header del reporte ──
echo '<tr>
        <td colspan="18" style="padding:10px 18px;background:#ffffff;border-bottom:1px solid #e5e7eb;vertical-align:middle;text-align:center;">
          <div style="font-size:14px;font-weight:bold;color:#0f2027;letter-spacing:.03em;">
            Instituto Electoral de la Ciudad de México
          </div>
        </td>
      </tr>';
echo '<tr><td colspan="18" style="height:4px;background:#ffffff;"></td></tr>';
echo '<tr>
        <td colspan="18" style="padding:4px 18px;background:#ffffff;text-align:center;font-size:13px;font-weight:bold;color:#2E86AB;">
          Reporte de personas Validadas del Concurso Juvenil de Ensayo 2026
        </td>
      </tr>';
echo '<tr>
        <td colspan="18" style="padding:4px 18px 10px;background:#ffffff;border-bottom:3px solid #0f2027;text-align:center;font-size:11px;color:#6b7280;letter-spacing:.04em;">
          FECHA Y HORA &nbsp;' . date('d/m/Y H:i:s') . '
        </td>
      </tr>';
echo '<tr><td colspan="15" style="height:6px;"></td></tr>';

// Cabeceras
echo '<tr>
        <th style="' . $style_th . '">Folio</th>
        <th style="' . $style_th . '">Categoría</th>
        <th style="' . $style_th . '">Título del Ensayo</th>
        <th style="' . $style_th . '">Seudónimo</th>
        <th style="' . $style_th . '">CURP</th>
        <th style="' . $style_th . '">Clave de elector</th>
        <th style="' . $style_th . '">Obra</th>
        <th style="' . $style_th . '">Apellido paterno</th>
        <th style="' . $style_th . '">Apellido materno</th>
        <th style="' . $style_th . '">Nombre(s)</th>
        <th style="' . $style_th . '">Sexo</th>
        <th style="' . $style_th . '">Fecha de nacimiento</th>
        <th style="' . $style_th . '">Tel. casa</th>
        <th style="' . $style_th . '">Tel. celular</th>
        <th style="' . $style_th . '">Correo electrónico</th>
        <th style="' . $style_th . '">Validado por</th>
        <th style="' . $style_th . '">Alcaldía o Entidad</th>
        <th style="' . $style_th . '">Cómo se enteró</th>
      </tr>';

$row = sqlsrv_query($conn, $sql);
$i = 0;

while ($res = sqlsrv_fetch_array($row)) {
    $i++;
    $td = ($i % 2 === 0) ? $style_td_even : $style_td_odd;
    $folio_style = ($i % 2 === 0) ? $style_folio . 'background:#e6f4f9;' : $style_folio . 'background:#FFFFFF;';

    echo '<tr>';
    echo '<td style="' . $folio_style . '">' . htmlspecialchars($res['folio']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['categoria']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['titulo']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['sobrenombre']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['curp'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['clave_elector'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['nombre_obra'] ?? '') . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['paterno']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['materno']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['nombre']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['genero']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['fecha_nacimiento']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['tel1']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['tel2']) . '</td>';
    echo '<td style="' . $td . '">' . htmlspecialchars($res['correo']) . '</td>';
    

    $status_requisitos = $res["status_requisitos"];

    echo '<td style="' . $td . '">' . htmlspecialchars($res['usr_validador']) . '</td>';

    $alcaldia = $res['alcaldia'];

    if ($alcaldia != 18) {
        switch ($alcaldia) {
              
	  				case 2:
	  					$alcalde="Azcapotzalco";
	  					break;
	  				case 3:
	  					$alcalde="Coyoacan";
                         break;
                      case 4:
                        $alcalde="Cuajimalpa de Morelos";
                        break;
                      case 5:
                        $alcalde="Gustavo A Madero";
                        break;
                      case 6:
                        $alcalde="Iztacalco";
                        break;
                      case 7:
                        $alcalde="Iztapalapa";
                        break;
                      case 8:
                        $alcalde="La Magdalena Contreras";
                         break;
                      case 9:
                        $alcalde="Milpa Alta";
                        break;
              case 10:
                        $alcalde="Alvaro Obregón";
                        break;
              case 11:
                        $alcalde="Tlahuac";
                        break;
              case 12:
                        $alcalde="Tlalpan";
                        break;
              case 13:
                        $alcalde="Xochimilco";
                        break;
              case 14:
                        $alcalde="Benito Juarez";
                        break;
              case 15:
                        $alcalde="Cuauhtemoc";
                        break;
              case 16:
                        $alcalde="Miguel Hidalgo";
                        break;
              case 17:
                        $alcalde="Venustiano Carraza";
                        break;

                            default:
	  					$alcalde="--";
	  			}      

        echo '<td style="' . $td . '">' . htmlspecialchars($alcalde) . '</td>';
    } else {
        echo '<td style="' . $td . '">' . htmlspecialchars($res['entidad']) . '</td>';
    }

    $medio = $res['te_enteraste'];
      
                    switch($medio)
                        {
                    case 1:
	  					$medio="Página de Internet";
	  					break;
	  				case 2:
	  					$medio="Redes sociales";
                         break;
                    case 3:
                        $medio="Dirección Distrital";
                        break;
                    case 4:
                        $medio="Cartel";
                        break;
                    case 5:
                        $medio="Díptico";
                        break;
                    case 6:
                        $medio="Escuela";
                        break;
                            
                        default:
                            $medio="--";
                    }

    echo '<td style="' . $td . '">' . htmlspecialchars($medio) . '</td>';
    echo '</tr>';
}

echo '</table>';
?>