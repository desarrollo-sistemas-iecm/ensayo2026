<?php
/**
 * Script de Verificación del Sistema
 * Verifica que todas las configuraciones estén correctas
 */

session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test del Sistema - PHP 8</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 50px auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
        }
        h2 {
            color: #555;
            margin-top: 30px;
            border-left: 4px solid #007bff;
            padding-left: 10px;
        }
        .test-item {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .success {
            background-color: #d4edda;
            border-left: 4px solid #28a745;
        }
        .error {
            background-color: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #28a745;
            color: white;
        }
        .badge-error {
            background-color: #dc3545;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: black;
        }
        .info-box {
            background-color: #e7f3ff;
            border-left: 4px solid #2196F3;
            padding: 15px;
            margin: 20px 0;
        }
        code {
            background-color: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
        }
        .summary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-top: 30px;
            text-align: center;
        }
        .summary h3 {
            margin: 0 0 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔍 Test del Sistema - Migración PHP 8</h1>
        <p><strong>Fecha:</strong> <?php echo date('d/m/Y H:i:s'); ?></p>

        <?php
        $errors = 0;
        $warnings = 0;
        $success = 0;

        // Test 1: Versión PHP
        echo "<h2>1. Versión de PHP</h2>";
        $php_version = phpversion();
        $php8_compatible = version_compare($php_version, '8.0.0', '>=');
        
        if($php8_compatible) {
            echo "<div class='test-item success'>";
            echo "<span>Versión de PHP: <strong>{$php_version}</strong></span>";
            echo "<span class='badge badge-success'>✅ OK</span>";
            echo "</div>";
            $success++;
        } else {
            echo "<div class='test-item error'>";
            echo "<span>Versión de PHP: <strong>{$php_version}</strong> (Se requiere PHP 8.0+)</span>";
            echo "<span class='badge badge-error'>❌ ERROR</span>";
            echo "</div>";
            $errors++;
        }

        // Test 2: Extensiones PHP
        echo "<h2>2. Extensiones PHP</h2>";
        
        $extensions = [
            'gd' => 'GD Library (para captcha)',
            'sqlsrv' => 'SQL Server (para base de datos)',
            'mbstring' => 'MB String (para manejo de texto)',
            'session' => 'Sessions (para sesiones)'
        ];

        foreach($extensions as $ext => $desc) {
            if(extension_loaded($ext)) {
                echo "<div class='test-item success'>";
                echo "<span>{$desc}</span>";
                echo "<span class='badge badge-success'>✅ Instalada</span>";
                echo "</div>";
                $success++;
            } else {
                echo "<div class='test-item error'>";
                echo "<span>{$desc}</span>";
                echo "<span class='badge badge-error'>❌ No encontrada</span>";
                echo "</div>";
                $errors++;
            }
        }

        // Test 3: Funciones GD para Captcha
        echo "<h2>3. Funciones de Captcha (GD)</h2>";
        $gd_functions = ['imagecreatetruecolor', 'imagecolorallocate', 'imagepng', 'imagestring'];
        
        foreach($gd_functions as $func) {
            if(function_exists($func)) {
                echo "<div class='test-item success'>";
                echo "<span><code>{$func}()</code></span>";
                echo "<span class='badge badge-success'>✅ Disponible</span>";
                echo "</div>";
                $success++;
            } else {
                echo "<div class='test-item error'>";
                echo "<span><code>{$func}()</code></span>";
                echo "<span class='badge badge-error'>❌ No disponible</span>";
                echo "</div>";
                $errors++;
            }
        }

        // Test 4: Sesiones
        echo "<h2>4. Sistema de Sesiones</h2>";
        $_SESSION['test_migration'] = 'OK';
        
        if(isset($_SESSION['test_migration']) && $_SESSION['test_migration'] == 'OK') {
            echo "<div class='test-item success'>";
            echo "<span>Sesiones funcionando correctamente</span>";
            echo "<span class='badge badge-success'>✅ OK</span>";
            echo "</div>";
            $success++;
        } else {
            echo "<div class='test-item error'>";
            echo "<span>Sesiones no funcionan</span>";
            echo "<span class='badge badge-error'>❌ ERROR</span>";
            echo "</div>";
            $errors++;
        }

        // Test 5: Archivos importantes
        echo "<h2>5. Archivos del Sistema</h2>";
        $files = [
            'captcha.php' => 'Captcha modernizado',
            'sqlconnector.php' => 'Conexión a base de datos',
            'usuarios.php' => 'Gestión de usuarios',
            'login.php' => 'Sistema de login',
            'guardarparticipante.php' => 'Guardar participantes',
            'upload.php' => 'Subida de archivos',
            'README_PHP8.md' => 'Documentación',
            'RESUMEN_CAMBIOS.md' => 'Resumen de cambios'
        ];

        foreach($files as $file => $desc) {
            if(file_exists($file)) {
                echo "<div class='test-item success'>";
                echo "<span><strong>{$file}</strong> - {$desc}</span>";
                echo "<span class='badge badge-success'>✅ Existe</span>";
                echo "</div>";
                $success++;
            } else {
                echo "<div class='test-item error'>";
                echo "<span><strong>{$file}</strong> - {$desc}</span>";
                echo "<span class='badge badge-error'>❌ No encontrado</span>";
                echo "</div>";
                $errors++;
            }
        }

        // Test 6: Permisos de carpetas
        echo "<h2>6. Permisos de Carpetas</h2>";
        $dirs = ['uploads', 'documentos_concursos'];
        
        foreach($dirs as $dir) {
            if(file_exists($dir)) {
                if(is_writable($dir)) {
                    echo "<div class='test-item success'>";
                    echo "<span><strong>{$dir}/</strong> - Escritura permitida</span>";
                    echo "<span class='badge badge-success'>✅ OK</span>";
                    echo "</div>";
                    $success++;
                } else {
                    echo "<div class='test-item warning'>";
                    echo "<span><strong>{$dir}/</strong> - Sin permisos de escritura</span>";
                    echo "<span class='badge badge-warning'>⚠️ ADVERTENCIA</span>";
                    echo "</div>";
                    $warnings++;
                }
            } else {
                echo "<div class='test-item warning'>";
                echo "<span><strong>{$dir}/</strong> - Carpeta no existe</span>";
                echo "<span class='badge badge-warning'>⚠️ ADVERTENCIA</span>";
                echo "</div>";
                $warnings++;
            }
        }

        // Test 7: Conexión a Base de Datos
        echo "<h2>7. Conexión a Base de Datos</h2>";
        if(file_exists('sqlconnector.php')) {
            include('sqlconnector.php');
            
            if(isset($conn) && $conn !== false) {
                echo "<div class='test-item success'>";
                echo "<span>Conexión a SQL Server establecida</span>";
                echo "<span class='badge badge-success'>✅ OK</span>";
                echo "</div>";
                
                // Verificar constantes
                if(defined('BD_USUARIOS') && defined('BD_PARTICIPANTES')) {
                    echo "<div class='test-item success'>";
                    echo "<span>Constantes de tablas definidas: <code>".BD_USUARIOS."</code>, <code>".BD_PARTICIPANTES."</code></span>";
                    echo "<span class='badge badge-success'>✅ OK</span>";
                    echo "</div>";
                    $success += 2;
                } else {
                    echo "<div class='test-item error'>";
                    echo "<span>Constantes de tablas no definidas</span>";
                    echo "<span class='badge badge-error'>❌ ERROR</span>";
                    echo "</div>";
                    $errors++;
                }
            } else {
                echo "<div class='test-item error'>";
                echo "<span>No se pudo conectar a SQL Server</span>";
                echo "<span class='badge badge-error'>❌ ERROR</span>";
                echo "</div>";
                echo "<div class='info-box'>";
                echo "<strong>Consejo:</strong> Verifica los datos de conexión en <code>sqlconnector.php</code>";
                echo "</div>";
                $errors++;
            }
        }

        // Test 8: Test del Captcha
        echo "<h2>8. Test del Captcha</h2>";
        if(file_exists('captcha.php')) {
            echo "<div class='test-item success'>";
            echo "<span>Archivo captcha.php encontrado</span>";
            echo "<span class='badge badge-success'>✅ OK</span>";
            echo "</div>";
            echo "<div class='info-box'>";
            echo "<strong>Prueba visual:</strong> <a href='captcha.php' target='_blank'>Abrir captcha.php</a><br>";
            echo "Deberías ver una imagen con 4 caracteres aleatorios.";
            echo "</div>";
            $success++;
        }

        // Resumen Final
        $total = $success + $errors + $warnings;
        $percentage = $total > 0 ? round(($success / $total) * 100) : 0;
        
        echo "<div class='summary'>";
        echo "<h3>📊 Resumen de Verificación</h3>";
        echo "<p style='font-size: 24px; margin: 10px 0;'>";
        echo "<strong>{$success}</strong> OK | ";
        echo "<strong>{$errors}</strong> Errores | ";
        echo "<strong>{$warnings}</strong> Advertencias";
        echo "</p>";
        echo "<p style='font-size: 18px;'>Porcentaje de éxito: <strong>{$percentage}%</strong></p>";
        
        if($errors == 0 && $warnings == 0) {
            echo "<p style='font-size: 20px; margin-top: 20px;'>🎉 ¡Sistema completamente funcional!</p>";
        } elseif($errors == 0) {
            echo "<p style='font-size: 18px; margin-top: 20px;'>✅ Sistema funcional con advertencias menores</p>";
        } else {
            echo "<p style='font-size: 18px; margin-top: 20px;'>⚠️ Hay errores que necesitan ser corregidos</p>";
        }
        echo "</div>";

        // Siguientes pasos
        if($errors > 0 || $warnings > 0) {
            echo "<div class='info-box' style='margin-top: 30px;'>";
            echo "<h3>📋 Siguientes Pasos:</h3>";
            echo "<ol>";
            if($errors > 0) {
                echo "<li>Corregir los errores marcados en rojo</li>";
                echo "<li>Consultar <code>README_PHP8.md</code> para instrucciones detalladas</li>";
            }
            if($warnings > 0) {
                echo "<li>Revisar las advertencias y crear carpetas faltantes si es necesario</li>";
            }
            echo "<li>Reiniciar Apache después de cambios en php.ini</li>";
            echo "<li>Volver a ejecutar este test</li>";
            echo "</ol>";
            echo "</div>";
        }
        ?>

        <div style="margin-top: 30px; padding: 20px; background-color: #f8f9fa; border-radius: 5px;">
            <h3>📚 Documentación Disponible:</h3>
            <ul>
                <li><strong>README_PHP8.md</strong> - Guía completa de migración</li>
                <li><strong>RESUMEN_CAMBIOS.md</strong> - Lista detallada de todos los cambios</li>
                <li><strong>ACTUALIZAR_PHPMAILER.md</strong> - Instrucciones para PHPMailer</li>
                <li><strong>INICIO_RAPIDO.md</strong> - Guía rápida de inicio</li>
            </ul>
        </div>
    </div>
</body>
</html>
