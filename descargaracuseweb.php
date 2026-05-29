<?php
include('sqlconnector.php');
include('rutasitio.php');

$folio_64 = $_GET['v'];

$folio_string = base64_decode($folio_64);

$folio = str_replace("/jF5i/", "", $folio_string);
//;




?>
<!DOCTYPE html>
<html lang="es">

<head>
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   <meta name="description" content="Descargar acuse de registro - Concursos de divulgación 2026">
   <title>Descargar Acuse | Concursos de Divulgación 2026</title>

   <!-- Bootstrap CSS -->
   <link href="css/bootstrap.min.css" rel="stylesheet">
   <link href="css/mycss.css" rel="stylesheet">
   
   <!-- Font Awesome -->
   <link rel="stylesheet" href="css/all.css">
   
   <!-- SweetAlert2 -->
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

   <!-- Scripts -->
   <script src="js/jquery-3.3.1.min.js"></script>
   <script src="js/popper.min.js"></script>
   <script src="js/bootstrap.min.js"></script>
   <script src="js/funcionesajax_test.js"></script>

   <style>
      body {
         background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
         min-height: 100vh;
         font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      }

      #main_container {
         margin-top: 30px;
         margin-bottom: 50px;
      }

      .page-header-section {
         background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
         color: white;
         padding: 40px 20px;
         border-radius: 15px;
         box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
         margin-bottom: 40px;
         text-align: center;
      }

      .page-header-section h1 {
         font-weight: 700;
         margin-bottom: 10px;
         font-size: 2.5rem;
         color: white !important;
         text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      }

      .page-header-section h1 i {
         color: white !important;
      }

      .page-header-section p {
         font-size: 1.1rem;
         opacity: 0.95;
         margin-bottom: 0;
         color: white;
      }

      .acuse-card {
         background: white;
         border-radius: 20px;
         padding: 40px;
         box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
         transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .acuse-card:hover {
         transform: translateY(-5px);
         box-shadow: 0 15px 50px rgba(0, 0, 0, 0.15);
      }

      .folio-display {
         background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%);
         color: white;
         padding: 30px;
         border-radius: 15px;
         margin-bottom: 30px;
         text-align: center;
         box-shadow: 0 8px 25px rgba(44, 83, 100, 0.3);
      }

      .folio-display .folio-label {
         font-size: 0.9rem;
         font-weight: 600;
         text-transform: uppercase;
         letter-spacing: 1px;
         opacity: 0.9;
         margin-bottom: 10px;
      }

      .folio-display .folio-number {
         font-size: 2.5rem;
         font-weight: 700;
         letter-spacing: 2px;
         margin: 0;
         text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      }

      .download-section {
         text-align: center;
         padding: 30px 20px;
      }

      .download-section .instruction-text {
         font-size: 1.1rem;
         color: #4a5568;
         margin-bottom: 30px;
         line-height: 1.6;
      }

      .download-section .instruction-icon {
         font-size: 3rem;
         color: #2c5364;
         margin-bottom: 20px;
         animation: bounce 2s infinite;
      }

      @keyframes bounce {
         0%, 20%, 50%, 80%, 100% {
            transform: translateY(0);
         }
         40% {
            transform: translateY(-10px);
         }
         60% {
            transform: translateY(-5px);
         }
      }

      .btn-download {
         background: linear-gradient(135deg, #dc2626 0%, #b91c1c 50%, #991b1b 100%);
         border: none;
         color: white;
         padding: 15px 40px;
         font-size: 1.1rem;
         font-weight: 600;
         border-radius: 50px;
         box-shadow: 0 8px 20px rgba(220, 38, 38, 0.4);
         transition: all 0.3s ease;
         text-transform: uppercase;
         letter-spacing: 1px;
         width: 100%;
         max-width: 400px;
      }

      .btn-download:hover {
         background: linear-gradient(135deg, #991b1b 0%, #b91c1c 50%, #dc2626 100%);
         transform: translateY(-2px);
         box-shadow: 0 12px 30px rgba(220, 38, 38, 0.6);
         color: white;
      }

      .btn-download i {
         margin-right: 10px;
         font-size: 1.2rem;
      }

      .btn-back {
         background: white;
         border: 2px solid #2c5364;
         color: #2c5364;
         padding: 10px 25px;
         font-weight: 600;
         border-radius: 25px;
         transition: all 0.3s ease;
      }

      .btn-back:hover {
         background: #2c5364;
         color: white;
         transform: scale(1.05);
      }

      .info-box {
         background: #f7fafc;
         border-left: 4px solid #2c5364;
         padding: 20px;
         border-radius: 10px;
         margin-top: 30px;
      }

      .info-box h5 {
         color: #2c5364;
         font-weight: 600;
         margin-bottom: 15px;
      }

      .info-box ul {
         margin: 0;
         padding-left: 20px;
         color: #4a5568;
      }

      .info-box li {
         margin-bottom: 8px;
         line-height: 1.6;
      }

      .success-icon {
         color: white;
         font-size: 4rem;
         margin-bottom: 20px;
      }

      /* Responsive */
      @media (max-width: 768px) {
         .page-header-section h1 {
            font-size: 1.8rem;
         }

         .page-header-section p {
            font-size: 1rem;
         }

         .acuse-card {
            padding: 25px;
         }

         .folio-display {
            padding: 20px;
         }

         .folio-display .folio-number {
            font-size: 2rem;
         }

         .btn-download {
            padding: 12px 30px;
            font-size: 1rem;
         }
      }

      @media (max-width: 576px) {
         .page-header-section {
            padding: 30px 15px;
         }

         .page-header-section h1 {
            font-size: 1.5rem;
         }

         .acuse-card {
            padding: 20px;
         }

         .folio-display .folio-number {
            font-size: 1.5rem;
         }

         .download-section .instruction-icon {
            font-size: 2.5rem;
         }
      }
   </style>

</head>

<body>

   <?php include('header.php'); ?>

   <div class="container" id="main_container">

      <!-- Header Section -->
      <div class="row justify-content-center">
         <div class="col-12">
            <div class="page-header-section">
               <i class="fas fa-check-circle success-icon"></i>
               <h1><i class="fas fa-file-download"></i> Registro Exitoso</h1>
               <p>Tu registro ha sido completado correctamente</p>
            </div>
         </div>
      </div>

      <!-- Main Card -->
      <div class="row justify-content-center">
         <div class="col-lg-8 col-md-10 col-12">
            <div class="acuse-card">
               
               <!-- Folio Display -->
               <div class="folio-display">
                  <div class="folio-label">
                     <i class="fas fa-hashtag"></i> Tu Número de Folio
                  </div>
                  <div class="folio-number">
                     <?php echo htmlspecialchars($folio, ENT_QUOTES, 'UTF-8'); ?>
                  </div>
               </div>

               <!-- Download Section -->
               <div class="download-section">
                  <div class="instruction-icon">
                     <i class="fas fa-download"></i>
                  </div>
                  
                  <p class="instruction-text">
                     <strong>¡Importante!</strong> Descarga tu acuse de registro y consérvalo para futuras referencias.
                     Este documento es tu comprobante oficial de participación.
                  </p>

                  <form method="post" action="descargaracuse.php" target="_blank" id="formDownload">
                     <button type="submit" class="btn btn-download" id="btnDownload">
                        <i class="fas fa-file-pdf"></i> Descargar Acuse en PDF
                     </button>
                     <input type="hidden" name="folio" value="<?php echo htmlspecialchars($folio, ENT_QUOTES, 'UTF-8'); ?>">
                  </form>
               </div>

               <!-- Info Box -->
               <div class="info-box">
                  <h5><i class="fas fa-info-circle"></i> Información Importante</h5>
                  <ul>
                     <li><strong>Guarda tu folio:</strong> Lo necesitarás para consultar el estatus de tu registro</li>
                     <li><strong>Conserva el PDF:</strong> Es tu comprobante oficial de participación</li>
                     <li><strong>Revisa tu correo:</strong> Recibirás notificaciones importantes en tu email registrado</li>
                     <li><strong>Documentación:</strong> No olvides completar la carga de documentos requeridos</li>
                  </ul>
               </div>

               <!-- Back Button -->
               <div class="text-center mt-4">
                  <a href="index.php" class="btn btn-back">
                     <i class="fas fa-arrow-left"></i> Regresar al sitio principal
                  </a>
               </div>

            </div>
         </div>
      </div>

   </div> <!-- cierra container -->

   <?php include('footer.php'); ?>

   <script>
      // Mostrar toast de éxito al cargar
      document.addEventListener('DOMContentLoaded', function() {
         Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: '¡Registro completado!',
            html: '<span style="font-size:.85rem;">Tu folio ha sido generado exitosamente</span>',
            showConfirmButton: false,
            timer: 4000,
            timerProgressBar: true,
            background: '#f0fdf4',
            iconColor: '#10b981'
         });
      });

      // Efecto al descargar
      document.getElementById('formDownload').addEventListener('submit', function(e) {
         Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: 'Generando PDF...',
            html: '<span style="font-size:.85rem;">Tu acuse se está descargando</span>',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
         });
      });
   </script>
</body>

</html>