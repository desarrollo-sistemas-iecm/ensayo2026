# Guía de Migración a PHP 8 - Sistema de Concursos

## Cambios Realizados

### 1. ✅ Captcha Modernizado
- **Archivo**: `captcha.php`
- **Cambios**: 
  - Reemplazado jpgraph_antispam (obsoleto) por código nativo usando GD library
  - Compatible con PHP 8+
  - Genera códigos de 4 caracteres aleatorios
  - Añade ruido visual para seguridad

### 2. ✅ Seguridad - Prepared Statements
Se implementaron prepared statements en todos los archivos para prevenir inyección SQL:

#### Archivos Corregidos:
- **guardarparticipante.php**
  - INSERT con parámetros seguros
  - Validación con `filter_var()` y `FILTER_VALIDATE_INT`
  
- **login.php**
  - SELECT de autenticación con parámetros
  
- **usuarios.php**
  - INSERT de nuevos usuarios
  - SELECT de validación
  - Validación de CAPTCHA en servidor
  
- **upload.php**
  - UPDATE de archivos con parámetros
  
- **updaterow.php**
  - SELECT con parámetros
  
- **reenviarcorreo.php**
  - SELECT con parámetros

### 3. ✅ Validación de CAPTCHA
- **JavaScript** (`funcionesajax_test.js`):
  - Corregida lógica de comparación (era `==` debería ser `!=`)
  
- **PHP** (`usuarios.php`):
  - Validación en servidor agregada
  - Limpieza de sesión después de validar
  - Comparación case-insensitive

### 4. ⚠️ PHPMailer - Requiere Actualización Manual
Ver archivo `ACTUALIZAR_PHPMAILER.md` para instrucciones detalladas.

## Requisitos del Servidor

### Extensiones PHP Requeridas:
```ini
extension=gd
extension=sqlsrv
extension=pdo_sqlsrv
extension=mbstring
```

### Configuración Recomendada (php.ini):
```ini
upload_max_filesize = 5M
post_max_size = 8M
max_execution_time = 300
memory_limit = 256M
display_errors = Off (en producción)
error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT
```

## Cambios de Seguridad Importantes

### Validación de Entrada:
Todos los datos POST ahora se validan con:
- `filter_var($var, FILTER_VALIDATE_INT)` - Para enteros
- `filter_var($var, FILTER_SANITIZE_EMAIL)` - Para emails
- `trim()` - Para cadenas de texto

### Consultas SQL:
**ANTES (Vulnerable):**
```php
$query = "SELECT * FROM usuarios WHERE usuario ='$usr' and contrasena='$pwd'";
$row = sqlsrv_query($conn, $query);
```

**DESPUÉS (Seguro):**
```php
$query = "SELECT * FROM usuarios WHERE usuario = ? and contrasena = ?";
$params = array($usr, $pwd);
$row = sqlsrv_query($conn, $query, $params);
```

## Testing

### Pruebas Recomendadas:

1. **Captcha**:
   - Accede a `captcha.php` directamente
   - Verifica que se genere una imagen
   - Intenta registrar un usuario con captcha correcto/incorrecto

2. **Registro de Usuario**:
   - Crear nuevo usuario
   - Validar recepción de email
   - Intentar SQL injection en campos (debe estar protegido)

3. **Login**:
   - Login con credenciales válidas
   - Login con credenciales inválidas
   - Intentar SQL injection (debe estar protegido)

4. **Subida de Archivos**:
   - Subir PDF válido
   - Intentar subir archivo no-PDF (debe rechazar)
   - Verificar límite de tamaño (4MB)

5. **CRUDs**:
   - Crear participante
   - Actualizar datos
   - Consultar información

## Errores Comunes y Soluciones

### Error: "Undefined function 'sqlsrv_connect'"
**Solución**: Instalar o habilitar la extensión sqlsrv:
```bash
# En Windows con XAMPP
# Descarga los drivers desde: https://learn.microsoft.com/en-us/sql/connect/php/download-drivers-php-sql-server
# Copia los archivos .dll a php/ext/
# Edita php.ini y añade:
extension=php_sqlsrv_81_ts.dll
extension=php_pdo_sqlsrv_81_ts.dll
```

### Error: "Call to undefined function imagecreatetruecolor()"
**Solución**: Habilitar extensión GD:
```ini
# En php.ini
extension=gd
```

### Error con PHPMailer: "magic_quotes_runtime"
**Solución**: Ver `ACTUALIZAR_PHPMAILER.md`

## Base de Datos

Las constantes de conexión están en `sqlconnector.php`:
```php
define("BD_USUARIOS","usuarios_ensayo");
define("BD_PARTICIPANTES","participantes_ensayo");
```

## Próximos Pasos

1. ✅ Actualizar PHPMailer (ver ACTUALIZAR_PHPMAILER.md)
2. ⬜ Implementar hash de contraseñas (actualmente en texto plano)
3. ⬜ Añadir rate limiting para evitar spam
4. ⬜ Implementar HTTPS (obligatorio para producción)
5. ⬜ Considerar usar reCAPTCHA de Google como alternativa

## Notas Adicionales

- Las contraseñas se almacenan en texto plano. **URGENTE**: Implementar `password_hash()` y `password_verify()`
- Considerar implementar sesiones seguras con `session.cookie_httponly = 1` y `session.cookie_secure = 1`
- Revisar permisos de carpetas `uploads/` y `documentos_concursos/`

## Soporte
Para dudas o problemas, revisar:
- Logs de PHP: `c:\xampp\php\logs\php_error_log`
- Logs de Apache: `c:\xampp\apache\logs\error.log`
