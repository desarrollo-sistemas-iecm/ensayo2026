<?php

 include('sqlconnector.php');
 error_reporting(E_ALL ^ E_NOTICE);
 $cat_genero=$arrayName = array('M' =>'Masculino' ,'F' =>'Femenino','X' =>'Otro' );
 

  session_start();
  ///////////////////////////////////////////////////////sesión
  if(isset($_SESSION['idusuario'])){
    $idusuario=$_SESSION["idusuario"];
    $my_user=$_SESSION["usr"];
    $my_pwd=$_SESSION["pwd"];
    $area=$_SESSION["area"];
    $concurso=$area;
    $distrito=0;

      //$queryA="SELECT * FROM usuarios WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='".$area."'";
      $queryA="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario." and usuario='".$my_user."' and area='ensayo'";

    //  echo $query;
      $resA=sqlsrv_query($conn,$queryA);
      if($rowA= sqlsrv_fetch_array($resA)){
       

      }else{
        session_destroy();
        header("location:index.php");
        
      }

 
    
  } else{
    
        session_destroy();
        header("location:index.php");
  }

  $query1="SELECT * FROM ".BD_PARTICIPANTES." WHERE idusuario =".$idusuario."";
  //  echo $query;
    $res1=sqlsrv_query($conn,$query1);
    if($row1= sqlsrv_fetch_array($res1)){
      $registrado=true;
      $folio=$row1['folio'];
       $style_disabled2=" disabled style='background-color:#f7f7f7;'";

    }else{
      $registrado=false;
      $folio=" - ";
      $style_disabled2="";
    }
     $style_disabled=" disabled style='background-color:#f7f7f7;'";
      $idusuario=$_SESSION["idusuario"];


 

 if($registrado){

    $query = "SELECT A.*,B.correo, B.area, B.fecha_nacimiento FROM ".BD_PARTICIPANTES." as A LEFT JOIN ".BD_USUARIOS." as B ON A.idusuario = B.idusuario WHERE A.idusuario = ?";
    $res = sqlsrv_query($conn, $query, array($idusuario));
    
    if($row= sqlsrv_fetch_array($res)){
      $nombre=$row["nombre"];
      $paterno=$row["paterno"];
      $materno=$row["materno"];
      $area=$row["area"];
      $genero=$row["genero"];
      $fecha_nacimiento=$row["fecha_nacimiento"];
      $fecha_alta=$row["fecha_alta"];
      $correo=$row["correo"];

      $categoria=$row["categoria"];
      $titulo=$row["titulo"];
    $curp=$row["curp"] ?? '';
      $tutor=$row["tutor"];
      
      // Separar tutor en sus tres partes usando el delimitador |
      $partes_tutor = explode('|', $tutor ?? '');
      $nombre_tutor = trim($partes_tutor[0] ?? '');
      $paterno_tutor = trim($partes_tutor[1] ?? '');
      $materno_tutor = trim($partes_tutor[2] ?? '');
    //   $correo_tutor=$row["correo_tutor"]; 
      $alcaldia=$row["alcaldia"];
      $entidad=$row["entidad"];
    //   $domicilio=$row["domicilio"];

      $escuela = $row["escuela"] ?? '';
      $tel_escuela = $row["tel_escuela"] ?? '';
      $te_enteraste=$row["te_enteraste"];
    $nombre_obra=$row["nombre_obra"] ?? '';
    $clave_elector=$row["clave_elector"] ?? '';
      

      $sobrenombre=$row["sobrenombre"];
      $tel1=$row["tel1"];
      $tel2=$row["tel2"];
     
    }


    //$style_disabled2=" disabled style='background-color:#f7f7f7;'";

 }
 if(!$registrado){


 

    $query="SELECT * FROM ".BD_USUARIOS." WHERE idusuario =".$idusuario."";
  //  echo $query;
    $res=sqlsrv_query($conn,$query);
    if($row= sqlsrv_fetch_array($res)){
      $nombre=$row["nombre"];
      $paterno=$row["paterno"];
      $materno=$row["materno"];
      $area=$row["area"];
      $genero=$row["genero"];
      $fecha_nacimiento=$row["fecha_nacimiento"];

      $fecha_alta=$row["fecha_alta"];
      $correo=$row["correo"];

      $categoria="";
      $titulo="";
      $curp=$row["curp"] ?? '';
      $tutor="";
      $paterno_tutor="";
      $materno_tutor="";
      $nombre_tutor="";
      $correo_tutor="";
      $alcaldia="";
      $entidad="";

      $escuela="";
      $tel_escuela="";
      $te_enteraste="";
      $domicilio="";
    $nombre_obra="";
    $clave_elector="";
      

      $sobrenombre="";
     
      $soyoriundo="";
      $soyoriginario="";
      $tel1="";
      $tel2="";
      

    }
    //$style_disabled2="";//" disabled style='background-color:#FFF;'";

  }

    //$style_disabled=" disabled style='background-color:#f7f7f7;'";

    $edad=date_diff(date_create($fecha_nacimiento), date_create($fecha_alta))->y;
    if($edad>=15&&$edad<=17){$categoria="1";} 
    if($edad>=18&&$edad<=23){$categoria="2";}
   // if($edad>=14&&$edad<=18){$categoria="3";}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Concurso de Ensayo | Panel de Participante</title>
	
	
 <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/mycss.css" rel="stylesheet">

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/funcionesajax_test.js"></script>
    <link rel="stylesheet" href="css/all.css">
	
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
/* ═══════════════════════════════════════════════════════════════════ */
/* TOP MENU BAR - ZONA USUARIO */
/* ═══════════════════════════════════════════════════════════════════ */
#topmenu {
    background: linear-gradient(135deg, #0f2027 0%, #2c5364 100%);
    padding: 14px 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,.15);
    margin-bottom: 0;
    border-radius: 0 0 8px 8px;
}

#topmenu .user-info-btn {
    background: rgba(255,255,255,.15);
    border: 1px solid rgba(255,255,255,.3);
    color: #fff;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    font-size: .9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    transition: all .2s;
}

#topmenu .user-info-btn:hover {
    background: rgba(255,255,255,.25);
    transform: translateY(-1px);
}

#topmenu .logout-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    border: none;
    color: #fff;
    padding: 8px 18px;
    border-radius: 8px;
    font-weight: 600;
    font-size: .9rem;
    box-shadow: 0 2px 8px rgba(239,68,68,.3);
    transition: all .2s;
}

#topmenu .logout-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(239,68,68,.4);
}

/* ═══════════════════════════════════════════════════════════════════ */
/* HEADER PRINCIPAL CON FOLIO */
/* ═══════════════════════════════════════════════════════════════════ */
.page-header-container {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 24px;
    flex-wrap: wrap;
    gap: 16px;
}

.page-title {
    font-size: 2rem;
    font-weight: 700;
    color: #1f2937;
    margin: 0;
    letter-spacing: -.5px;
}

.folio-card {
    background: linear-gradient(135deg, #4A9FD5, #2E86AB);
    color: #fff;
    padding: 14px 24px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(74,159,213,.3);
    display: inline-flex;
    align-items: center;
    gap: 12px;
    font-weight: 700;
    font-size: 1.05rem;
    transition: transform .2s, box-shadow .2s;
}

.folio-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 18px rgba(74,159,213,.4);
}

.folio-card i {
    font-size: 1.3rem;
    opacity: .9;
}

.folio-card .folio-label {
    opacity: .95;
    font-weight: 500;
    font-size: .9rem;
}

.folio-card .folio-number {
    font-weight: 800;
    font-size: 1.2rem;
    letter-spacing: .5px;
}

/* ═══════════════════════════════════════════════════════════════════ */
/* TABS DE NAVEGACIÓN MODERNOS */
/* ═══════════════════════════════════════════════════════════════════ */
.nav-tabs {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 28px;
    gap: 8px;
}

.nav-tabs .nav-item {
    margin-bottom: -2px;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 3px solid transparent;
    color: #6b7280;
    font-weight: 600;
    font-size: .95rem;
    padding: 12px 24px;
    border-radius: 8px 8px 0 0;
    transition: all .2s;
    position: relative;
}

.nav-tabs .nav-link:hover:not(.disabled) {
    background: #f3f4f6;
    color: #4A9FD5;
    border-bottom-color: #93c5fd;
}

.nav-tabs .nav-link.active {
    background: #fff;
    color: #4A9FD5;
    border-bottom-color: #4A9FD5;
    font-weight: 700;
}

.nav-tabs .nav-link.disabled {
    color: #d1d5db;
    cursor: not-allowed;
    opacity: .5;
}

.nav-tabs .nav-link i {
    margin-right: 6px;
}

/* ═══════════════════════════════════════════════════════════════════ */
/* ALERT DE BIENVENIDA (cuando no está registrado) */
/* ═══════════════════════════════════════════════════════════════════ */
#anim {
    margin-bottom: 20px;
}

.anim1 {
    background: linear-gradient(135deg, #2E86AB, #4A9FD5);
    padding: 16px 20px;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(74,159,213,.25);
    font-weight: 600;
    font-size: .95rem;
    display: flex;
    align-items: center;
    gap: 10px;
    animation: slideInDown .5s ease-out;
}

.anim1 i {
    font-size: 1.3rem;
    animation: bounce 2s infinite;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(8px);
    }
}

/* ═══════════════════════════════════════════════════════════════════ */
/* CONTENEDOR PRINCIPAL */
/* ═══════════════════════════════════════════════════════════════════ */
#main_container {
    background: #fff;
    padding: 28px 24px;
    border-radius: 12px;
    box-shadow: 0 2px 16px rgba(0,0,0,.08);
    margin-bottom: 32px;
}

/* ═══════════════════════════════════════════════════════════════════ */
/* RESPONSIVE */
/* ═══════════════════════════════════════════════════════════════════ */
@media (max-width: 768px) {
    .page-header-container {
        flex-direction: column;
        align-items: flex-start;
    }

    .page-title {
        font-size: 1.5rem;
    }

    .folio-card {
        width: 100%;
        justify-content: center;
    }

    .nav-tabs .nav-link {
        padding: 10px 16px;
        font-size: .88rem;
    }

    #main_container {
        padding: 20px 16px;
    }

    #topmenu {
        padding: 12px 10px;
    }

    #topmenu .user-info-btn {
        font-size: .82rem;
        padding: 6px 12px;
    }

    #topmenu .logout-btn {
        display: none;
    }
}
</style>
	
</head>

<body style="background:#f8fafc;">
	
<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<!-- TOP MENU BAR -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<!-- <div class="container-fluid" id="topmenu">
    <div class="row align-items-center">
        <div class="col-md-8"></div>
        <div class="col-md-3 text-right mb-2 mb-md-0">
            <button class="user-info-btn">
                <i class="fas fa-user-circle"></i>
                <?php echo "Usuario: ".$my_user; ?>
            </button>
        </div>
        <div class="col-md-1 text-right">
            <a href="logout.php" class="btn logout-btn">
                <i class="fas fa-sign-out-alt"></i> Salir
            </a>
        </div>
    </div>
</div> -->

<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<!-- HEADER -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->		
<?php include('header.php');?>

<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<!-- CONTENIDO PRINCIPAL -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<div class="container" id="main_container">

    <!-- HEADER CON TITULO Y FOLIO -->
    <div class="page-header-container">
        <h2 class="page-title">
            <i class="fas fa-pen-fancy" style="color:#4A9FD5;margin-right:10px;"></i>
            <?php echo ucfirst($concurso);?>
        </h2>
        
        <div class="folio-card">
            <i class="fas fa-id-card"></i>
            <div>
                <div class="folio-label">No. de folio</div>
                <div class="folio-number"><?php echo $folio; ?></div>
            </div>
        </div>
    </div>
              
    <div id="div_errors"></div>

    <!-- TABS DE NAVEGACIÓN -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link <?php if(!$registrado) echo "active"?>" data-toggle="tab" href="#menu1">
                <i class="fas fa-user-edit"></i> Datos Personales
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if($registrado) echo "active"; if(!$registrado) echo " disabled"?>" 
               data-toggle="tab" href="#menu2">
                <i class="fas fa-file-upload"></i> Adjuntar Ensayo
            </a>
        </li>
    </ul>

    <!-- CONTENIDO DE LAS TABS -->
    <div class="tab-content">
        
        <!-- TAB 1: DATOS PERSONALES -->
        <div class="tab-pane container <?php if(!$registrado) echo "active"?>" id="menu1">

            <?php
            if(!$registrado){
                echo'
                    <div class="row" id="anim">
                        <div class="col-12">
                            <div class="anim1">
                                <i class="fas fa-long-arrow-alt-down"></i>
                                <span style="color:#ffffff;">Primero, completa tus datos personales. Luego podrás adjuntar tu archivo.</span>
                            </div>
                        </div>
                    </div>';
            }
            ?>

            <?php include("datospersonales.php"); ?>

        </div>
        
        <!-- TAB 2: ADJUNTAR DOCUMENTACIÓN -->
        <div class="tab-pane container <?php if($registrado) echo "active"?>" id="menu2">
            <?php include("adjuntardocumentacion.php"); ?>
        </div>

    </div>

</div> <!-- cierra container -->

<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<!-- FOOTER -->
<!-- ═══════════════════════════════════════════════════════════════════════ -->	
<?php include('footer.php');?>

</body>
</html>
