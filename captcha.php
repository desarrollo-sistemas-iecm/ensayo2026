<?php
session_start();

// Generar código aleatorio de 4 caracteres
$code = '';
$characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789'; // Sin caracteres confusos como O, 0, I, 1
for ($i = 0; $i < 4; $i++) {
    $code .= $characters[rand(0, strlen($characters) - 1)];
}

$_SESSION['tmptxt'] = $code;

// Crear imagen
$width = 120;
$height = 40;
$image = imagecreatetruecolor($width, $height);

// Colores
$bg_color = imagecolorallocate($image, 255, 255, 255);
$text_color = imagecolorallocate($image, 0, 0, 0);
$line_color = imagecolorallocate($image, 64, 64, 64);

// Fondo
imagefilledrectangle($image, 0, 0, $width, $height, $bg_color);

// Agregar líneas de ruido
for ($i = 0; $i < 3; $i++) {
    imageline($image, rand(0, $width), rand(0, $height), rand(0, $width), rand(0, $height), $line_color);
}

// Agregar puntos de ruido
for ($i = 0; $i < 50; $i++) {
    imagesetpixel($image, rand(0, $width), rand(0, $height), $line_color);
}

// Agregar texto
$font_size = 20;
$angle = rand(-10, 10);
$x = 15;
$y = 30;

// Si existe una fuente TTF, usarla; si no, usar fuente por defecto
$font_path = __DIR__ . '/fonts/arial.ttf';
if (file_exists($font_path)) {
    imagettftext($image, $font_size, $angle, $x, $y, $text_color, $font_path, $code);
} else {
    // Usar fuente integrada
    imagestring($image, 5, $x, 10, $code, $text_color);
}

// Enviar headers y la imagen
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Pragma: no-cache');
header('Expires: 0');
imagepng($image);
imagedestroy($image);
?>