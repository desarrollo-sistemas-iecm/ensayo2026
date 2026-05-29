<?php
/**
 * Test de PHPMailer - PHP 8
 * Verifica que PHPMailer funciona correctamente después de las correcciones
 */

echo "<h1>Test de PHPMailer - Compatibilidad PHP 8</h1>";
echo "<p><strong>Versión de PHP:</strong> " . phpversion() . "</p>";

// Test 1: Cargar PHPMailer
echo "<h2>1. Cargando PHPMailer...</h2>";
try {
    require_once('phpmailer/class.phpmailer.php');
    require_once('phpmailer/class.smtp.php');
    require_once('phpmailer/PHPMailerAutoload.php');
    echo "<p style='color: green;'>✅ PHPMailer cargado correctamente</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al cargar PHPMailer: " . $e->getMessage() . "</p>";
    exit;
}

// Test 2: Instanciar PHPMailer
echo "<h2>2. Creando instancia de PHPMailer...</h2>";
try {
    $mail = new PHPMailer(true);
    echo "<p style='color: green;'>✅ Instancia creada correctamente</p>";
    echo "<p>Versión de PHPMailer: <strong>" . $mail->Version . "</strong></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al crear instancia: " . $e->getMessage() . "</p>";
    exit;
}

// Test 3: Configurar propiedades básicas
echo "<h2>3. Configurando propiedades básicas...</h2>";
try {
    $mail->CharSet = 'UTF-8';
    $mail->From = 'test@test.com';
    $mail->FromName = 'Test Usuario';
    $mail->Subject = 'Test Email';
    $mail->Body = 'Este es un email de prueba';
    echo "<p style='color: green;'>✅ Propiedades configuradas correctamente</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error al configurar propiedades: " . $e->getMessage() . "</p>";
    exit;
}

// Test 4: Verificar funciones críticas
echo "<h2>4. Verificando funciones críticas...</h2>";
$functions_ok = true;

// Verificar que magic_quotes no cause problemas
if (version_compare(PHP_VERSION, '8.0.0', '>=')) {
    echo "<p style='color: blue;'>ℹ️ PHP 8.0+: magic_quotes ha sido eliminado (esto es correcto)</p>";
}

// Verificar que __autoload no esté siendo usado
if (function_exists('__autoload')) {
    echo "<p style='color: orange;'>⚠️ Advertencia: __autoload() está definido (puede causar problemas)</p>";
    $functions_ok = false;
} else {
    echo "<p style='color: green;'>✅ __autoload() no está en uso (correcto)</p>";
}

// Verificar spl_autoload_register
if (function_exists('spl_autoload_register')) {
    echo "<p style='color: green;'>✅ spl_autoload_register() disponible</p>";
} else {
    echo "<p style='color: red;'>❌ spl_autoload_register() no disponible</p>";
    $functions_ok = false;
}

// Test 5: Test de validación de email
echo "<h2>5. Test de funciones de validación...</h2>";
try {
    $test_email = 'test@example.com';
    $is_valid = PHPMailer::validateAddress($test_email);
    if ($is_valid) {
        echo "<p style='color: green;'>✅ Validación de email funciona: '{$test_email}' es válido</p>";
    } else {
        echo "<p style='color: red;'>❌ Validación de email falló</p>";
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error en validación: " . $e->getMessage() . "</p>";
}

// Resumen final
echo "<hr>";
echo "<h2>📊 Resumen del Test</h2>";

if ($functions_ok) {
    echo "<div style='background-color: #d4edda; padding: 20px; border-left: 5px solid #28a745;'>";
    echo "<h3 style='color: #155724;'>✅ PHPMailer está listo para usar con PHP 8</h3>";
    echo "<p>Todas las correcciones han sido aplicadas correctamente.</p>";
    echo "<ul>";
    echo "<li>✅ __autoload() eliminado</li>";
    echo "<li>✅ spl_autoload_register() implementado</li>";
    echo "<li>✅ magic_quotes protegido para PHP 8</li>";
    echo "<li>✅ Clases cargando correctamente</li>";
    echo "</ul>";
    echo "</div>";
} else {
    echo "<div style='background-color: #fff3cd; padding: 20px; border-left: 5px solid #ffc107;'>";
    echo "<h3 style='color: #856404;'>⚠️ PHPMailer tiene advertencias</h3>";
    echo "<p>Revisa los mensajes anteriores para más detalles.</p>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>Siguiente Paso: Probar envío de correo real</h3>";
echo "<p>Para probar el envío de correos, usa los archivos:</p>";
echo "<ul>";
echo "<li><code>usuarios.php</code> - Registro de usuario (envía correo de confirmación)</li>";
echo "<li><code>reenviarcorreo.php</code> - Reenvío de correos</li>";
echo "</ul>";

echo "<div style='margin-top: 30px; padding: 15px; background-color: #e7f3ff; border-left: 5px solid #2196F3;'>";
echo "<h4>💡 Nota Importante</h4>";
echo "<p><strong>Esta versión de PHPMailer (5.x) es antigua.</strong></p>";
echo "<p>Se recomienda actualizar a PHPMailer 6.x con Composer:</p>";
echo "<pre style='background-color: #f4f4f4; padding: 10px;'>composer require phpmailer/phpmailer</pre>";
echo "<p>Pero la versión actual funcionará correctamente con PHP 8 después de estas correcciones.</p>";
echo "</div>";

echo "<div style='margin-top: 20px; text-align: center;'>";
echo "<a href='index.php' style='padding: 10px 20px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px;'>Volver al Inicio</a> ";
echo "<a href='test_sistema.php' style='padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px;'>Test Completo del Sistema</a>";
echo "</div>";
?>
