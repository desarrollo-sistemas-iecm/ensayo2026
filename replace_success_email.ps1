# Script to replace email templates in g_validaciondocumentos.php
$file = "c:\xampp\htdocs\2026\ensayo\g_validaciondocumentos.php"

# Read all lines
$lines = Get-Content $file

# Find line containing ban-iecm.jpg (should be around line 161 for first template)
for($i=0; $i -lt $lines.Count; $i++) {
    if($lines[$i] -match 'ban-iecm\.jpg.*width="146"' -and $i -lt 200) {
        Write-Host "Found first (SUCCESS) email template at line $($i+1)"
        $successStartLine = $i
        break
    }
}

# Find the end of first template (look for </html>)
for($i=$successStartLine; $i -lt $lines.Count; $i++) {
    if($lines[$i] -match '^\t\t\t\t\t<\/html>') {
        Write-Host "Found end of SUCCESS template at line $($i+1)"
        $successEndLine = $i
        break
    }
}

if($successStartLine -and $successEndLine) {
    Write-Host "Will replace lines $($successStartLine+1) to $($successEndLine+1)"
    
    # New SUCCESS email template (single long string - will be inserted as one "line")
    $newSuccessTemplate  = @'
<!DOCTYPE html>
<html lang="es">
<head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"></head>
<body style="margin:0;padding:0;background:#f0f2f5;font-family:Arial,Helvetica,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" border="0" style="background:#f0f2f5;padding:30px 10px;">
  <tr><td align="center">
    <table width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;width:100%;border-radius:14px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.13);">
      <tr>
        <td style="background:linear-gradient(135deg,#2E86AB 0%,#4A9FD5 55%,#5DADE2 100%);padding:36px 40px 28px;text-align:center;">
          <img src="'.URL_CONCU.'img/IECM-1.png" width="80" height="80" alt="IECM" style="display:block;margin:0 auto 14px;">
          <h1 style="margin:0 0 6px;color:#ffffff;font-size:24px;font-weight:700;">&#10003; ¡Registro Validado!</h1>
          <p style="margin:0;color:rgba(255,255,255,.75);font-size:14px;">Instituto Electoral de la Ciudad de México</p>
          <div style="margin:16px auto 0;width:60px;height:3px;background:linear-gradient(90deg,#6ee7b7,#60a5fa);border-radius:2px;"></div>
        </td>
      </tr>
      <tr>
        <td style="background:#ffffff;padding:32px 40px 10px;">
          <p style="margin:0 0 18px;font-size:16px;color:#1e3a8a;">
            Estimado/a <strong>'.htmlspecialchars($nombre).'</strong>,
          </p>
          <p style="margin:0 0 20px;font-size:14px;color:#374151;line-height:1.7;">
            Has completado correctamente el registro en el <strong>Concurso de Ensayo 2022 "Retos y perspectivas a 11 años del Presupuesto Participativo en la Ciudad de México"</strong>.
            A continuación encontrarás tu número de folio asignado.
          </p>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td style="background:#1e3a8a;border-radius:10px;padding:20px 24px;text-align:center;">
                <p style="margin:0 0 8px;color:#93c5fd;font-size:13px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;">Tu número de folio es:</p>
                <p style="margin:0;font-family:monospace;font-size:28px;font-weight:700;color:#7dd3fc;letter-spacing:.1em;">'.htmlspecialchars($folio).'</p>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:24px;">
            <tr>
              <td style="background:#f0fdf4;border-left:4px solid #22c55e;border-radius:0 8px 8px 0;padding:14px 16px;">
                <p style="margin:0;font-size:13px;color:#14532d;line-height:1.65;">
                  <strong>&#10003; Registro exitoso.</strong> Conserva este folio como comprobante de tu participación.
                </p>
              </td>
            </tr>
          </table>
          <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:28px;">
            <tr>
              <td align="center">
                <a href="'.URL_CONCU.'descargaracuseweb.php?v='.$folio_64.'"
                   style="display:inline-block;background:linear-gradient(135deg,#2E86AB,#4A9FD5);color:#ffffff;
                          text-decoration:none;font-size:15px;font-weight:700;
                          padding:13px 36px;border-radius:30px;">
                  &#x2193; Descargar acuse de registro
                </a>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="background:#eff6ff;border-top:1px solid #e2e8f0;padding:20px 40px;text-align:center;">
          <p style="margin:0 0 4px;font-size:12px;color:#6b7280;">
            <strong style="color:#2E86AB;">Instituto Electoral de la Ciudad de M&eacute;xico</strong>
          </p>
          <p style="margin:0;font-size:11px;color:#9ca3af;line-height:1.7;">
            Huizaches 25 &bull; Rancho Los Colorines &bull; Tlalpan &bull; C.P. 14386 &bull; Ciudad de M&eacute;xico<br>
            Conmutador: (55) 5483 3800 &bull; <a href="https://www.iecm.mx" style="color:#4A9FD5;text-decoration:none;">www.iecm.mx</a>
          </p>
        </td>
      </tr>
    </table>
  </td></tr>
</table>
</body>
</html>
'@

    # Build new file content
    $newLines = @()
    
    # Add lines before the template
    for($i=0; $i -lt $successStartLine; $i++) {
        $newLines += $lines[$i]
    }
    
    # Add new template
    $newLines += $newSuccessTemplate
    
    # Add lines after the template
    for($i=$successEndLine+1; $i -lt $lines.Count; $i++) {
        $newLines += $lines[$i]
    }
    
    # Write to file
    $newLines | Out-File -FilePath $file -Encoding UTF8
    
    Write-Host "SUCCESS email template replaced!"
} else {
    Write-Host "ERROR: Could not find template boundaries"
}
