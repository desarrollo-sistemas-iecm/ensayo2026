<?php
require_once 'libs/tcpdf.php';
require_once 'sqlconnector.php';
require_once 'cat_alcaldia.php';

$folio = isset($_POST['folio']) ? $_POST['folio'] : (isset($_GET['folio']) ? $_GET['folio'] : '');

$vg_mes = array(
    1 => 'enero', 2 => 'febrero', 3 => 'marzo', 4 => 'abril', 5 => 'mayo', 6 => 'junio',
    7 => 'julio', 8 => 'agosto', 9 => 'septiembre', 10 => 'octubre', 11 => 'noviembre', 12 => 'diciembre'
);

// Query (preserve original DB logic)
$query = "SELECT * FROM participantes WHERE folio = '" . addslashes($folio) . "'";
$res = sqlsrv_query($conn, $query);

if ($res === false) {
    die('Error en consulta');
}

$row = sqlsrv_fetch_array($res, SQLSRV_FETCH_ASSOC);

if (!$row) {
    die('Folio no encontrado');
}

// map values from row (keep original keys)
$titulo = $row['titulo'] ?? '';
$seudonimo = $row['sobrenombre'] ?? '';
$nombre_completo = $row['nombre_completo'] ?? $row['nombre'] ?? '';
$fecha_nacimiento = isset($row['fecha_nacimiento']) ? (is_object($row['fecha_nacimiento']) ? $row['fecha_nacimiento']->format('d/m/Y') : $row['fecha_nacimiento']) : '';
$correo = $row['correo'] ?? '';
$tel1 = $row['telefono'] ?? '';
$tel2 = $row['movil'] ?? '';
$genero = $row['sexo'] ?? '';
$demarcacion = $row['demarcacion'] ?? '';
$entidad = $row['entidad'] ?? '';
$fecha_alta = isset($row['fecha_alta']) ? (is_object($row['fecha_alta']) ? $row['fecha_alta']->format('d/m/Y') : $row['fecha_alta']) : '';
$ensayo = !empty($row['ensayo']);
$cartacesion = !empty($row['cartacesion']);
$manifestacion = !empty($row['manifestacion']);
$edad = $row['edad'] ?? '';
$categoria = $row['categoria'] ?? '';

$dia = date_format(date_create($fecha_alta ?: date('Y-m-d')), "d");
$mes_num = intval(date_format(date_create($fecha_alta ?: date('Y-m-d')), "m"));
$mes = $vg_mes[$mes_num] ?? '';

// TCPDF setup
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('IECM');
$pdf->SetTitle('Acuse - IECM');
$pdf->SetSubject('Acuse de registro');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(10, 5, 10, true);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
if (@file_exists('libs/include/lang/spa.php')) {
    require_once('libs/include/lang/spa.php');
    if (isset($l) && is_array($l)) $pdf->setLanguageArray($l);
}
$pdf->SetFont('helvetica', '', 9);
$pdf->AddPage();

// Build HTML content (clean white layout matching reference)
$folio_html = htmlspecialchars($folio);
$titulo_html = htmlspecialchars($titulo);
$seudonimo_html = ($seudonimo);

$html = <<<HTML
<div style="font-family: Helvetica, Arial, sans-serif; font-size:10pt; color:#222;">
  <table width="100%" cellpadding="6" style="border-collapse:collapse; vertical-align:middle; margin-top:6px;">
    <tr>
      <td width="28%" align="left" style="vertical-align:middle;">
        <img src="img/IECM-1.png" width="84" alt="IECM" />
      </td>
      <td width="44%" align="center" style="vertical-align:middle;">&nbsp;</td>
      <td width="28%" align="right" style="vertical-align:middle;">
        <img src="img/Grafico-min.png" width="120" alt="Grafico" style="border-radius:6px; display:block;" />
      </td>
    </tr>
  </table>
  <!-- <hr style="border:none;border-top:1px solid #e9e9e9;margin:10px 0 14px 0;" /> -->

  <p style="margin:0 0 6px 0; font-size:9pt; text-align:center;">Folio: {$folio_html}</p>

  <h2 style="margin:6px 0 6px 0; text-align:center; font-weight:700; font-size:15pt; color:#111;">Concurso Juvenil de Ensayo 2026 "Conversando con los Clásicos"</h2>
  <h3 style="margin:2px 0 12px 0; text-align:center; font-weight:700; font-size:13pt; color:#000;">ACUSE DE REGISTRO DE LA PERSONA PARTICIPANTE</h3>

  <p style="text-align:justify; font-size:9pt; line-height:1.45; margin:0 0 16px 0; color:#333;">El Instituto Electoral de la Ciudad de México (IECM) te da la bienvenida como participante de este concurso, donde la expresión de ideas y tu entusiasmo son fundamentales para construir una efectiva participación. En el IECM creemos que tú eres quien hace de los concursos un gran espacio para dialogar.</p>

  <h4 style="font-size:11pt; margin:12px 0 8px 0;">Datos de la persona participante</h4>

  <p style="font-size:10pt; margin:8px 0 18px 0;"><strong>Título del ensayo:</strong><br><span style="display:inline-block; border-bottom:1px solid #000; width:70%; padding:6px 0;">{$titulo_html}</span></p>

  <p style="font-size:10pt; margin:8px 0 40px 0;"><strong>Seudónimo: </strong><br><span style="display:inline-block; border-bottom:1px solid #000; width:40%; padding:6px 0;">{$seudonimo_html}</span></p>

  <div style="height:200px;"></div>

  <p style="text-align:center; color:#777777; font-size:9pt; margin:6px 0 2px 0;">Instituto Electoral de la Ciudad de México &copy;</p>
  <p style="text-align:center; color:#777777; font-size:8pt; margin:2px 0 0 0;">Huizaches 25 • Rancho Los Colorines • Tlalpan • C.P. 14386 • Ciudad de México • Conmutador: (55) 5483 3800</p>

</div>
HTML;

// Output PDF
$pdf->writeHTML($html, true, false, true, false, '');
ob_end_clean();
$pdf->Output('acuse.pdf', 'I');

exit;
