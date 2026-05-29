<?php
//include('valida_session.php');
include('sqlconnector.php');
define("UPLOAD_DIR", "uploads/");
  error_reporting(0);


$v_id=$_POST['idusuario'];
$v_index=$_POST['index'];
$upload_max=4000000;




//$documento=array("ensayo","cartacesion","formato","manifestacion");


$documento=array("ensayo");

function return_bytes($val)
{
    $val = trim($val);
    $last = strtolower($val[strlen($val)-1]);
    switch($last) {
        // The 'G' modifier is available since PHP 5.1.0
        case 'g':
            $val *= (1024 * 1024 * 1024); //1073741824
            break;
        case 'm':
            $val *= (1024 * 1024); //1048576
            break;
        case 'k':
            $val *= 1024;
            break;
    }

    return $val;
}





if (isset($_FILES["file-select"])) {
    $myFile = $_FILES["file-select"];



    if ($myFile["error"] !== UPLOAD_ERR_OK) {
        //$m_error=UploadException($myFile['file-select']['error']);
        echo '<div class="alert alert-warning alert-dismissible fade show"">
                ERROR 1: No se pudo guardar el archivo.
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                 </button>
              </div>';
        if ($myFile["error"] > 0) {
          echo "Return Code: " . $myFile["error"] . "<br>";
        }
        exit;
    }

    ///////////////////////////////pdf

    $tipoArchivo = $myFile["type"];
    
  
//echo "llego al tipo".$tipoQueja.$tipoAcuerdo;
  
  if ($tipoArchivo != "application/pdf")
    {
        
  
   
    
    echo '<div class="alert alert-warning alert-dismissible">
          Sólo se permiten subir archivos con extension PDF
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
           </div>';
          
     exit;
    }


    if($_FILES['file-select']['size'] > $upload_max) {

        echo '<div class="alert alert-warning alert-dismissible">
            El tamaño del archivo excede el límite permitido.
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
           </div>';
      
      exit;
    } 




    // ensure a safe filename
     

    
    $name = preg_replace("/[^A-Z0-9._-]/i", "_", $myFile["name"]);
    


    if(!is_dir(UPLOAD_DIR.$v_id."/")) {
        mkdir(UPLOAD_DIR.$v_id."/");
    }

    // don't overwrite an existing file
    $i = 0;
    $parts = pathinfo($name);

    $name= $documento[$v_index].".".$parts["extension"];

    while (file_exists(UPLOAD_DIR .$v_id."/". $name)) {
        $i++;
        $name = $documento[$v_index] . "-" . $i . "." . $parts["extension"];
    }

    // preserve file from temporary directory
    $success = move_uploaded_file($myFile["tmp_name"],
        UPLOAD_DIR .$v_id."/". $name);
    if (!$success) {
        //$m_error=UploadException($myFile['tmp_name']['error']); 
        echo '<div class="alert alert-danger alert-dismissible">
                   No se pudo guardar el archivo.
                   <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>

              </div>';
        if ($success["error"] > 0) {
          echo "Return Code: " . $success["error"] . "<br>";
        }
        exit;
    }

    // set proper permissions on the new file
    chmod(UPLOAD_DIR .$v_id."/". $name, 0644);
    $ruta_docto_ = $v_id."/".$name;

    if($v_index == 0){
        $query = "UPDATE participantes SET ensayo = ?, status_ensayo = '0' WHERE idusuario = ?";
        $params = array($ruta_docto_, $v_id);
    }
    
   /* if($v_index == 2){
        $query = "UPDATE participantes_ensayo SET cartacesion = ?, status_carta = '0' WHERE idusuario = ?";
        $params = array($ruta_docto_, $v_id);
    }

    if($v_index == 4){
        $query = "UPDATE participantes_ensayo SET manifestacion = ?, status_manifest = '0' WHERE idusuario = ?";
        $params = array($ruta_docto_, $v_id);
    }
    */
    $row = sqlsrv_query($conn, $query, $params);

      if($row){
        //echo ' <a href="'.UPLOAD_DIR .$ruta_docto_.'" target="_blank" class="btn-icon"><i class="fas fa-file-signature"></i><i class="far fa-thumbs-up"></i></a>';//

        echo '<div class="alert alert-success alert-dismissible">
                Archivo actualizado con éxito.
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>';



                      /////////////////////bitacora
                      $ip_v4=$_SERVER['REMOTE_ADDR'];
                      
                      /*
                      $queryB = "INSERT INTO sisecom_convocatorias_bitacora(usuario, ip,accion,texto,fecha) 
                        values (".$tmpses_idusr.",'".$ip_v4."','uploadOK_".$v_tipo."','id_".$v_id."','".date('Y-m-d H:i:s')."')";
                     
                      
                      $rowB = sqlsrv_query($conn,$queryB);
                      if($rowB)
                      {
                        //echo "bitacora OK";

                      }else{
                        die( print_r( sqlsrv_errors(), true));

                      }
                      */
                      //////////////////////fin bitacora
                

      }else{
        echo '<div class="alert alert-danger"> Error: al guardar el archivo'.print_r(sqlsrv_errors()).'
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>

              </div>'; 
        //echo "/* ".$query." */";

                 /////////////////////bitacora
              /*
                      $ip_v4=$_SERVER['REMOTE_ADDR'];
                      
                      //$valor=sqlsrv_fetch_array( sqlsrv_query($conn,"SELECT IDENT_CURRENT ('sisecom_convocatorias') AS Current_Identity") );
                      //$ultimoregistro=$valor['Current_Identity'];

                      $queryB = "INSERT INTO sisecom_convocatorias_bitacora(usuario, ip,accion,texto,fecha) 
                        values (".$tmpses_idusr.",'".$ip_v4."','uploadERROR_".$v_tipo."','id_".$v_id."','".date('Y-m-d H:i:s')."')";
                     
                      
                      $rowB = sqlsrv_query($conn,$queryB);
                      if($rowB)
                      {
                        //echo "bitacora OK";

                      }else{
                        die( print_r( sqlsrv_errors(), true));

                      }
                      //////////////////////fin bitacora
              */
      }


}



?>