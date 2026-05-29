<?php

error_reporting(0);



include('sqlconnector.php');



?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recuperar contraseña - Concurso de Ensayo</title>

  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/mycss.css" rel="stylesheet">
  <link rel="stylesheet" href="css/all.css">
  <link rel="stylesheet" href="css/inicio.css">
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

  <script src="js/jquery-3.3.1.min.js"></script>
  <script src="js/popper.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/funcionesajax_test.js"></script>
  <!-- SweetAlert2 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    .login-input {
      background: rgba(255,255,255,.08) !important;
      border: 1px solid rgba(255,255,255,.2) !important;
      color: #fff !important;
      padding: 0.75rem 1rem !important;
      border-radius: 8px !important;
      transition: all 0.3s ease !important;
    }
    .login-input:focus {
      background: rgba(255,255,255,.12) !important;
      border-color: rgba(74,159,213,.6) !important;
      box-shadow: 0 0 0 3px rgba(74,159,213,.15) !important;
      outline: none !important;
    }
    .login-input::placeholder {
      color: rgba(255,255,255,.4) !important;
    }
    .btn-inicio {
      position: relative;
      overflow: hidden;
      transition: all 0.3s ease;
      background: linear-gradient(135deg,#2E86AB,#4A9FD5);
      color: #fff;
      border: none;
      border-radius: 30px;
      padding: 12px 40px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      box-shadow: 0 4px 18px rgba(74,159,213,.35);
    }
    .btn-inicio::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255,255,255,.2);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }
    .btn-inicio:hover::before {
      width: 300px;
      height: 300px;
    }
    .btn-inicio:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 20px rgba(74,159,213,.4);
    }
  </style>
</head>

<body>

  <?php $show_back_button = true;
  include('header.php'); ?>

  <div style="background:linear-gradient(135deg,#0f2027 0%,#203a43 50%,#2c5364 100%); min-height:100vh; display:flex; align-items:center; justify-content:center; padding: 2rem 1rem;">
    <div style="width:100%; max-width:500px;">

      <div class="card info-card" style="padding:2.2rem 2rem; display:flex; flex-direction:column;">

        <!-- Ícono + Encabezado -->
        <div style="text-align:center; margin-bottom:1.5rem;">
          <div style="width:72px;height:72px;border-radius:50%;background:rgba(74,159,213,.25);border:2px solid rgba(74,159,213,.6);display:flex;align-items:center;justify-content:center;margin:0 auto 1.2rem;">
            <i class="fas fa-key" style="font-size:1.8rem;color:#4A9FD5;"></i>
          </div>
          <h3 style="color:#fff;font-family:'Inter',sans-serif;font-weight:700;text-transform:uppercase;letter-spacing:.06em;margin-bottom:.25rem;">Recuperar contraseña</h3>
          <p style="color:rgba(255,255,255,.65);font-size:.9rem;margin-bottom:0;">Ingresa tu correo registrado y te enviaremos los datos de acceso.</p>
        </div>

        <hr style="border-color:rgba(255,255,255,.15);margin-bottom:1.5rem;">

        <!-- Formulario -->
        <div id="divcorreo">
          <div class="form-group mb-4">
            <label style="color:rgba(255,255,255,.75);font-size:.85rem;display:block;margin-bottom:8px;font-weight:600;">
              <i class="fas fa-envelope fa-sm"></i> Correo electrónico
            </label>
            <input type="email" class="form-control login-input" id="correo" placeholder="tucorreo@ejemplo.com" required>
          </div>

          <button class="btn-inicio" style="display:block;width:100%;font-size:1rem;padding:.85rem;position:relative;z-index:1;" onclick="fnReenviarCorreo(0);">
            <span style="position:relative;z-index:2;"><i class="fas fa-paper-plane"></i> &nbsp;Enviar instrucciones</span>
          </button>
        </div>

        <div style="text-align:center; margin-top:1.6rem;">
          <a href="index.php" style="color:rgba(255,255,255,.65);font-size:.88rem;text-decoration:none;transition:color 0.3s;" onmouseover="this.style.color='#4A9FD5'" onmouseout="this.style.color='rgba(255,255,255,.65)'">
            <i class="fas fa-arrow-left fa-sm"></i> Regresar al inicio de sesión
          </a>
        </div>

      </div>
    </div>
  </div>


  <?php
  include('footer.php');
  ?>


</body>

</html>

<?php



?>