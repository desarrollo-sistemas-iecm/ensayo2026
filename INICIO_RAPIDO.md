# INICIO RÁPIDO - Verificación Post-Actualización

## 🚀 PASOS INMEDIATOS

### 1. Verificar PHP 8
```powershell
php -v
```
Debe mostrar PHP 8.0, 8.1, 8.2 o 8.3

### 2. Verificar Extensiones PHP
Crear archivo `test_extensions.php`:
```php
<?php
echo "GD: " . (extension_loaded('gd') ? '✅' : '❌') . "\n";
echo "sqlsrv: " . (extension_loaded('sqlsrv') ? '✅' : '❌') . "\n";
echo "mbstring: " . (extension_loaded('mbstring') ? '✅' : '❌') . "\n";
?>
```

Ejecutar:
```powershell
php test_extensions.php
```

### 3. Verificar Captcha
Abrir en navegador:
```
http://localhost/2026/ensayo/captcha.php
```

Debe mostrar una imagen con 4 caracteres.

### 4. Probar Conexión Base de Datos
El archivo `sqlconnector.php` debe conectar sin errores.

## ⚠️ SI HAY ERRORES

### Error: "Call to undefined function sqlsrv_connect"

**Solución:**

1. Descargar drivers desde:
   https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server

2. Copiar archivos a `C:\xampp\php\ext\`:
   - `php_sqlsrv_81_ts.dll` (para PHP 8.1)
   - `php_pdo_sqlsrv_81_ts.dll`

3. Editar `C:\xampp\php\php.ini`:
```ini
extension=php_sqlsrv_81_ts
extension=php_pdo_sqlsrv_81_ts
```

4. Reiniciar Apache

### Error: "Call to undefined function imagecreatetruecolor"

**Solución:**

Editar `C:\xampp\php\php.ini`:
```ini
extension=gd
```

Reiniciar Apache

### Error con PHPMailer: "magic_quotes_runtime"

**Solución:**

Ver archivo `ACTUALIZAR_PHPMAILER.md`

O instalar vía Composer:
```powershell
cd c:\xampp\htdocs\2026\ensayo
composer require phpmailer/phpmailer
```

## ✅ CHECKLIST DE VERIFICACIÓN

Marcar cada ítem después de probarlo:

- [ ] PHP 8 instalado y funcionando
- [ ] Extensión GD habilitada
- [ ] Extensión sqlsrv habilitada
- [ ] Captcha genera imagen correctamente
- [ ] Conexión a base de datos funciona
- [ ] Registro de usuario funciona
- [ ] Login funciona
- [ ] Subida de archivos funciona
- [ ] PHPMailer actualizado (si se usa envío de correos)

## 🔧 CONFIGURACIÓN RÁPIDA PHP

### Archivo php.ini recomendado:
```ini
; Errores (desarrollo)
display_errors = On
error_reporting = E_ALL

; Errores (producción)
display_errors = Off
error_reporting = E_ALL & ~E_DEPRECATED

; Uploads
upload_max_filesize = 5M
post_max_size = 8M
max_execution_time = 300
memory_limit = 256M

; Extensiones
extension=gd
extension=mbstring
extension=php_sqlsrv_81_ts
extension=php_pdo_sqlsrv_81_ts

; Sesiones
session.cookie_httponly = 1
session.use_strict_mode = 1
```

## 🧪 TEST RÁPIDO DEL SISTEMA

### Script de Prueba (test_sistema.php):
```php
<?php
session_start();

echo "<h1>Test del Sistema</h1>";

// 1. PHP Version
echo "<h2>1. PHP Version</h2>";
echo phpversion() . " - " . (version_compare(phpversion(), '8.0.0', '>=') ? '✅' : '❌') . "<br>";

// 2. Extensiones
echo "<h2>2. Extensiones</h2>";
echo "GD: " . (extension_loaded('gd') ? '✅' : '❌') . "<br>";
echo "sqlsrv: " . (extension_loaded('sqlsrv') ? '✅' : '❌') . "<br>";
echo "mbstring: " . (extension_loaded('mbstring') ? '✅' : '❌') . "<br>";

// 3. Sesiones
echo "<h2>3. Sesiones</h2>";
$_SESSION['test'] = 'OK';
echo "Session: " . ($_SESSION['test'] == 'OK' ? '✅' : '❌') . "<br>";

// 4. Archivos importantes
echo "<h2>4. Archivos</h2>";
echo "captcha.php: " . (file_exists('captcha.php') ? '✅' : '❌') . "<br>";
echo "sqlconnector.php: " . (file_exists('sqlconnector.php') ? '✅' : '❌') . "<br>";
echo "usuarios.php: " . (file_exists('usuarios.php') ? '✅' : '❌') . "<br>";

// 5. Permisos carpetas
echo "<h2>5. Permisos Carpetas</h2>";
echo "uploads/: " . (is_writable('uploads') ? '✅' : '❌') . "<br>";

// 6. Base de datos
echo "<h2>6. Base de Datos</h2>";
include('sqlconnector.php');
if($conn){
    echo "Conexión: ✅<br>";
} else {
    echo "Conexión: ❌<br>";
}
?>
```

Guardar este archivo en la raíz del proyecto y acceder a:
```
http://localhost/2026/ensayo/test_sistema.php
```

## 📞 CONTACTO Y AYUDA

Si encuentras problemas:

1. Revisa los logs:
   - `C:\xampp\php\logs\php_error_log`
   - `C:\xampp\apache\logs\error.log`

2. Verifica la configuración:
   - `C:\xampp\php\php.ini`

3. Consulta la documentación:
   - `README_PHP8.md` - Guía completa
   - `RESUMEN_CAMBIOS.md` - Lista de cambios
   - `ACTUALIZAR_PHPMAILER.md` - Actualizar email

## ✨ TODO LISTO

Si todos los checks pasan:
- ✅ Sistema adaptado a PHP 8
- ✅ Captcha funcionando
- ✅ SQL seguro con prepared statements
- ✅ Listo para producción (después de actualizar PHPMailer)

---

**Última actualización:** 25 de febrero de 2026
