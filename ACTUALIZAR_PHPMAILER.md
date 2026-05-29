# Actualización de PHPMailer para PHP 8

## Problema
La versión actual de PHPMailer usa funciones deprecadas que fueron eliminadas en PHP 8.0:
- `get_magic_quotes_runtime()`
- `set_magic_quotes_runtime()`

## Solución

### Opción 1: Actualizar vía Composer (RECOMENDADO)

1. Instalar Composer si no lo tienes: https://getcomposer.org/

2. En la terminal, navega a la carpeta del proyecto:
```bash
cd c:\xampp\htdocs\2026\ensayo
```

3. Ejecuta:
```bash
composer require phpmailer/phpmailer
```

4. Actualiza los archivos PHP para usar el autoloader de Composer:
```php
<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);
// ... resto del código
```

### Opción 2: Descarga Manual

1. Descarga la última versión desde: https://github.com/PHPMailer/PHPMailer/releases/latest

2. Extrae los archivos en la carpeta `phpmailer/`

3. Actualiza las rutas de inclusión en:
   - usuarios.php
   - reenviarcorreo.php

## Archivos a Actualizar

Busca estas líneas en los archivos PHP:
```php
include('phpmailer/class.phpmailer.php');
include('phpmailer/class.smtp.php');
include('phpmailer/PHPMailerAutoload.php');
```

Y reemplázalas por:
```php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
```

## Archivos Afectados
- usuarios.php (línea 9-11)
- reenviarcorreo.php (línea 3-5)
