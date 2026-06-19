# Concurso Juvenil de Ensayo 2026 “Conversando con los clásicos”
## Sistema de Registro, Validación y Evaluación de Ensayos

Este repositorio alberga la plataforma web oficial para la gestión del **Concurso Juvenil de Ensayo 2026**, diseñado para el registro de participantes, carga de ensayos, validación administrativa de requisitos, evaluación por parte de jueces y notificación automática por correo electrónico.

El nuevo diseño visual, la reestructuración general del proyecto y el módulo completo de evaluación de jueces fueron creados y desarrollados desde cero por **Bruno Corona**.

El sistema ha sido modernizado para ofrecer compatibilidad total con **PHP 8.x**, reforzando la seguridad mediante la prevención de inyección SQL y optimizando el flujo de folios.

---

## 🛠️ Tecnologías Utilizadas

El proyecto está construido bajo una arquitectura cliente-servidor clásica y ligera:

* **Backend:** PHP 8.0 - 8.3 (Programación procedimental y estructurada).
* **Base de Datos:** Microsoft SQL Server (utilizando la extensión nativa `sqlsrv` de PHP).
* **Frontend:** HTML5, JavaScript (Vanilla JS y jQuery para peticiones asíncronas vía AJAX) y CSS3 (Bootstrap y estilos personalizados).
* **Generación de Captcha:** Librería nativa `GD` de PHP.
* **Envío de Correos:** PHPMailer (configurado con SMTP).

---

## 📐 Arquitectura y Estructura del Proyecto

El sistema se divide en tres capas principales:

1. **Capa de Presentación (Frontend):**
   * Contiene los formularios de registro, login y visualización de estatus.
   * [index.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/index.php): Página principal y acceso.
   * [datospersonales.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/datospersonales.php): Formulario de registro de datos del participante.
   * [adjuntardocumentacion.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/adjuntardocumentacion.php): Interfaz para la carga de ensayos.
   * [js/funcionesajax_test.js](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/js/funcionesajax_test.js): Lógica de cliente, validaciones y peticiones asíncronas.

2. **Capa de Controladores / Backend (PHP):**
   * Procesa peticiones AJAX, valida credenciales, manipula archivos y gestiona estados.
   * [login.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/login.php) & [usuarios.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/usuarios.php): Registro y autenticación.
   * [upload.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/upload.php): Carga segura de documentos en formato PDF y actualización de estatus a pendiente.
   * [g_validaciondocumentos.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/g_validaciondocumentos.php): Módulo administrativo para aprobar/rechazar ensayos y generar folios.

3. **Capa de Datos y Servicios:**
   * [sqlconnector.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/sqlconnector.php): Conexión centralizada a la base de datos (con perfiles de desarrollo y producción).
   * [captcha.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/captcha.php): Generador dinámico de captcha visual para evitar registros automatizados.
   * [phpmailer/](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/phpmailer): Librería para el envío de notificaciones por correo.

---

## 🔄 Flujos Clave del Sistema

### 1. Flujo del Participante
1. **Registro e Inicio de Sesión:** El usuario ingresa sus datos personales y valida un código captcha.
2. **Carga del Ensayo:** Sube un archivo PDF (máximo 4MB). Al hacerlo, el estatus del ensayo se establece en `0` (Pendiente) y se limpia la validación anterior en caso de re-subida.
3. **Espera de Resultados:** Visualiza el estatus del proceso y recibe notificaciones de aprobación o rechazo en su correo.

### 2. Flujo del Administrador (Validación y Folios)
1. El administrador ingresa a la bandeja de validación ([validacionlistado.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/validacionlistado.php)) y selecciona un participante.
2. Revisa el documento y asigna un estatus:
   * **Incorrecto (`2`):** Se guarda la observación y se notifica al usuario por correo para que re-suba su ensayo.
   * **Correcto (`1`):** Se genera y guarda el número de folio oficial.
3. Se envía de forma automática un correo electrónico con el acuse de registro correspondiente.

---

## 🚀 Mejoras e Innovaciones Recientes

### 🛡️ 1. Seguridad Contra Inyección SQL
Toda la lógica de comunicación con la base de datos SQL Server mediante la extensión `sqlsrv` ha sido reestructurada para utilizar **Sentencias Preparadas (Prepared Statements)**.
* *Antes (Vulnerable):* Las variables POST se concatenaban directamente en la consulta SQL.
* *Ahora (Seguro):* Se utilizan marcadores de parámetros `?` y un arreglo de datos tipados en `sqlsrv_query`, neutralizando cualquier intento de inyección de código malicioso.

### 🎟️ 2. Control Inteligente de Folios (Mejora de Bruno Corona)
Se implementó una solución en [g_validaciondocumentos.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/g_validaciondocumentos.php) para solucionar un error crítico del flujo original, donde al validar nuevamente a un participante (por ejemplo, después de corregir y re-subir un ensayo) se le generaba un folio nuevo y diferente.
* **Solución:** Se añadió una consulta previa para comprobar si el participante ya tiene un folio registrado en la base de datos.
  * Si ya cuenta con uno, **se conserva el folio original** para mantener su identidad en el concurso.
  * Si no tiene folio, se calcula uno nuevo de acuerdo al correlativo histórico de su categoría.

### 🤖 3. Captcha Nativo compatible con PHP 8
Se eliminó la dependencia externa obsoleta de `jpgraph_antispam` (incompatible con PHP 8) por una implementación nativa basada en la extensión `GD` de PHP. Ésta genera dinámicamente un captcha aleatorio seguro con ruido visual directamente en el servidor.

### ⚖️ 4. Módulo de Evaluación por Jueces (Creado desde cero por Bruno Corona)
Se diseñó e implementó un sistema completamente nuevo para la asignación y calificación de ensayos por parte del jurado:
* **Asignación Eficiente:** Permite asociar jueces a participantes utilizando identificadores únicos para evitar colisiones o inconsistencias.
* **Panel de Evaluación:** Los jueces cuentan con interfaces dedicadas para calificar las rúbricas y emitir dictámenes.
* **Seguridad de Datos:** Integración total con sentencias preparadas y control de accesos de sesión específicos de jueces.

---

## ⚙️ Configuración y Requisitos de Instalación

### Requisitos del Servidor
* Servidor Web (Apache, Nginx o IIS) con **PHP 8.0 o superior**.
* Servidor **Microsoft SQL Server**.
* Extensión **GD habilitada** en PHP (necesario para captcha).
* Driver de SQL Server instalado y habilitado en PHP (`php_sqlsrv` y `php_pdo_sqlsrv`).

### Configuración del Entorno (`php.ini`)
Asegúrate de tener habilitadas las siguientes directivas en tu archivo de configuración de PHP:
```ini
extension=gd
extension=mbstring
extension=php_sqlsrv_81_ts ; Ajustar versión según corresponda (ej. 81 para PHP 8.1)
extension=php_pdo_sqlsrv_81_ts

; Configuración para subida de archivos
upload_max_filesize = 5M
post_max_size = 8M
```

### Configuración de la Base de Datos
Edita el archivo [sqlconnector.php](file:///c:/xampp/htdocs/2026/ENSAYO2026/ensayo2026/sqlconnector.php) para alternar o configurar las credenciales del servidor:
* Habilita el bloque de **DESARROLLO** para pruebas locales.
* Configura el bloque de **PRODUCCIÓN** con certificados y encriptación activa al desplegar.

---

## 🧪 Pruebas y Diagnóstico del Sistema
Para validar que el entorno cuenta con todos los prerrequisitos correctamente configurados, puedes ejecutar el script de diagnóstico del sistema abriendo en tu navegador local:
```
http://localhost/2026/ENSAYO2026/ensayo2026/test_sistema.php
```
Este script comprobará automáticamente la versión de PHP, la disponibilidad de las extensiones requeridas (`gd`, `sqlsrv`, `mbstring`), la escritura en carpetas temporales (`uploads/`) y el estado de la conexión a la base de datos.
