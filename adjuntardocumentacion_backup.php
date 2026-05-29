<?php
include 'sqlconnector.php';
//session_start();
//$my_user=$_SESSION["usr"];
$idusuario = $_SESSION["idusuario"];
define("UPLOAD_DIR", "uploads/");




if ($registrado) {

    $query = "SELECT A.*,B.fecha_nacimiento,B.fecha_alta as fecha_alta_usuario FROM " . BD_PARTICIPANTES . " as A LEFT JOIN " . BD_USUARIOS . " as B ON A.idusuario= B.idusuario WHERE A.idusuario =" . $idusuario . "";
    //  echo $query;
    $res = sqlsrv_query($conn, $query);

    if ($row = sqlsrv_fetch_array($res)) {

        $fecha_nacimiento = $row["fecha_nacimiento"];
        $fecha_alta = $row["fecha_alta_usuario"];


        $ensayo = $row["ensayo"];
        $status_ensayo = $row["status_ensayo"];
        $observa_ensayo = $row["observa_ensayo"];

        //$identificacion=$row["identificacion"];
        //$status_identifica=$row["status_identifica"];
        //$observa_identifica=$row["observa_identifica"];


        $manifestacion = $row["manifestacion"];
        $status_manifest = $row["status_manifest"];
        $observa_manifest = $row["observa_manifest"];

        $cartacesion = $row["cartacesion"];
        $status_carta = $row["status_carta"];
        $observa_carta = $row["observa_carta"];

        //$formato=$row["formato"];
        //$status_formato=$row["status_formato"];
        //que se $observa_formato=$row["observa_formato"];




    }

    if ($ensayo && $manifestacion && $cartacesion) {
        $completado = true;
        $mensaje = '<div class="alert alert-success">
                <p>Tu registro fue exitoso. Tienes que esperar la validación de documentos para poder continuar con el concurso. Una vez validados verás tu número de folio.</p>
            </div>';
    } else {
        $completado = false;

        $mensaje = '<div class="alert alert-warning">
                <p>Para poder continuar con el concurso deberás adjuntar todos los documentos.</p>
            </div>';
    }

    $validado_disabled = "";
    $validado_disabled2 = "";

    if ($status_ensayo == '1' && $status_manifest == '1' && $status_carta == '1') {
        $validado_disabled = " disabled";
        $validado_disabled2 = ' style="pointer-events: none;"';
        $validado = "OK";
    }



    /*

     if($status_boceto=='2'&&$status_identifica=='2'&&$status_declara=='2'&&$status_manifest=='2'&&$status_carta=='2'&&$status_formato=='2'){
        $validado_disabled_incorrecto=" disabled";
        $validado="Incorrecto";

     }*/


    $edad = date_diff(date_create($fecha_nacimiento), date_create($fecha_alta))->y;
    //echo " /".$edad."/ ";


    if (!($ensayo || $manifestacion || $cartacesion)) {
        echo '
                          <div class="row" id="anim">
                          
                            <div class="col-sm-6">
                                <div class="bg-info text-white anim1">
                                  <i class="fas fa-long-arrow-alt-down"></i> Descarga los formatos aquí   
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="bg-info text-white anim2" style="opacity:0;">
                                   Adjunta tus documentos aquí  <i class="fas fa-long-arrow-alt-down"></i> 
                                </div>
                            </div>
                            
                          </div>';
    }



    echo '<div class="row">
                <div class="col-sm-6">
                   <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Descargas</h4>';
    if ($edad >= 18) {
        echo '<p>Descarga los siguientes formatos y llénalos con tinta azul.</p>';
        echo "<hr>";

        echo ' <div class="row">
                                        <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11">                                                                        
                                        <a href="documentos_concursos/carta_cesion.docx" target="_blank">Carta de cesión de derechos.</a>
                                        
                                        </div>
                                      </div>';

        echo "<hr>";



        echo '<div class="row">
                                        <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="documentos_concursos/manifestacion_bajo_protesta.docx" target="_blank">Manifestación bajo protesta de decir verdad que cumple con la edad requerida para registrarse en la convocatoria.</a></div>
                                      </div>';
    } else {
        //echo '<p>Menor de edad</p>';
        /*  echo '<p>Descarga los siguientes formatos y llénalos con tinta azul.</p>';
                                 echo "<hr>";
                              
                               echo '<div class="row">
                                        <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="descargarword.php?f='.base64_encode('documentos_concursos/ensayo/menor/cartacesion.docx').'" target="_blank">Carta de cesión de derechos.</a></div>
                                      </div>';
                               
                                echo "<hr>";
                                
                                echo '<div class="row">
                                        <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="descargarword.php?f='.base64_encode('documentos_concursos/ensayo/menor/formato.docx').'" target="_blank">Formato de protección de datos personales.</a></div>
                                      </div>';
                                 echo "<hr>";

                                echo '<div class="row">
                                        <div class="col-sm-1"><i class="far fa-file-word"></i></div><div class="col-sm-11"><a href="descargarword.php?f='.base64_encode('documentos_concursos/ensayo/menor/manifestacion.docx').'" target="_blank">Manifestación bajo protesta de decir verdad que quien participa no es familiar de alguna persona funcionaria del IECM hasta segundo grado ascendente o descendente en línea directa.</a></div>
                                      </div>';*/
    }


    echo '</div>
                   </div>
                </div>

                <div class="col-sm-6">
                
                   <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Adjuntar documentación</h4>
                            <div id="mensajeadjuntos">' . $mensaje . '</div>';
    $ensayo_64 = ($ensayo);
    //$ensayo_64=$ensayo;
    /*
                            echo '<div class="row">
                                    <div class="col-sm">
                                        Mis archivos
                                    </div>
                                    <div class="col-sm">
                                    </div>
                                    <div class="col-sm-1">
                                    </div>
                                </div>';
                            */
    echo '<hr>';



    //////////////////////////////////////////////////////////
    $tooltip0 = "Escanear en formato PDF el ensayo original, en tamaño carta, ahí no escribas tu nombre. Extensión de 15 a 20 cuartillas. El archivo no deberá exceder los 4 MB.";


    echo '<div class="row" id="row-0">
                
                                    <div class="col-sm">';
    if ($ensayo) {
        echo '<a href="uploads/' . $ensayo_64 . '" target="_blank" class="btn btn-primary">Mi ensayo</a>';


        if ($status_ensayo == 1) {
            echo '<span class="badge badge-success">OK</span>';
        }
        if ($status_ensayo == 2) {
            echo '
                                                <span class="badge badge-warning"data-toggle="tooltip" data-placement="top" title="' . $observa_ensayo . '">
                                                  !
                                                </span>';
        }
    } else {
        echo '-';
    }

    echo '</div>
                                    <div class="col-sm">
                                                                                                                       
                                        <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                                            <div class="btn-upload-custom"><i class="fas fa-upload"></i> Adjuntar ensayo</div>
                                            <input type="file" id="file-select-0" class="form-control-file"  onchange="fnUpload(' . $idusuario . ',0)" ' . $validado_disabled . '>
                                        </div>
                                        
                                        
                                    </div>
                                    <div class="col-sm-1">
                                         <a href="" data-toggle="tooltip" data-html="true" title="' . $tooltip0 . '">
                                            <i class="far fa-question-circle"></i>
                                        </a>

                                    </div>
                                    
                                
                                </div>'; /////row-0

    ///////////////////////////////////////////////////////////  0
    echo '<hr>';

    ////////////////////////////////////////////// 1
    /*     $identificacion_64=base64_encode($identificacion);
                                $tooltip1="Escanear en formato PDF la identificación de tu mamá, papá, tutor o tutora: credencial para votar vigente, licencia para conducir, cartilla del Servicio Militar Nacional, pasaporte, cédula profesional, matrícula consular o identificación oficial emitida por autoridad del país de origen o del país en que reside. Asegurándose de que ajuste a una hoja tamaño carta y que el archivo no sea mayor a 4MB";
                                echo '<div class="row" id="row-1">
                                       
                                        <div class="col-sm">';
                                        if($identificacion){
                                            echo '<a href="getfilepdf.php?data='.$identificacion_64.'" target="_blank" class="btn btn-primary">Mi identificación</a>';


                                            if($status_identifica==1){
                                                echo '<span class="badge badge-success">OK</span>';
                                            }
                                            if($status_identifica==2){
                                                echo '
                                                <span class="badge badge-warning"data-toggle="tooltip" data-placement="top" title="'.$observa_identifica.'">
                                                  !
                                                </span>';
                                            }
                                        }
                                        else{
                                            echo '-';
                                        }

                                echo   '</div>
                                        <div class="col-sm">
                                                                                                                       
                                            <div class="upload-btn-wrapper" '.$validado_disabled2.'>
                                                <div class="btn-upload-custom"><i class="fas fa-upload"></i> Adjuntar identificación oficial</div>
                                                <input type="file" id="file-select-1" class="form-control-file"  onchange="fnUpload('.$idusuario.',1)"  '.$validado_disabled.'>
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-1">
                                            

                                            <a href="#" data-toggle="tooltip" data-html="true" title="'.$tooltip1.'">
                                                <i class="far fa-question-circle"></i>
                                            </a>

                                        </div>
                                        
                                    
                                      </div>';*/ ///row1

    ////////////////////////////////////////////// 1
    echo '<hr>';
    ///////////////////////////////////////////// 2
    $cartacesion_64 = ($cartacesion);
    $tooltip4 = "Llenar con tinta azul toda la información que se solicita en el formato, escanearlo en PDF y que el archivo no sea mayor a 4 MB.";
    echo '<div class="row" id="row-2">
                                       
                                        <div class="col-sm">';
    if ($cartacesion) {
        echo '<a href="uploads/' . $cartacesion_64 . '" target="_blank" class="btn btn-primary">Mi carta de cesión...</a>';

        if ($status_carta == 1) {
            echo '<span class="badge badge-success">OK</span>';
        }
        if ($status_carta == 2) {
            echo '
                                                <span class="badge badge-warning"data-toggle="tooltip" data-placement="top" title="' . $observa_carta . '">
                                                  !
                                                </span>';
        }
    } else {
        echo '-';
    }

    echo   '</div>
                                        <div class="col-sm">
                                                                                                                       
                                            <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                                                <div class="btn-upload-custom"><i class="fas fa-upload"></i> Adjuntar carta de cesión de derechos</div>
                                                <input type="file" id="file-select-2" class="form-control-file"  onchange="fnUpload(' . $idusuario . ',2)" ' . $validado_disabled . ' >
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-1">
                                            

                                            <a href="#" data-toggle="tooltip" data-html="true" title="' . $tooltip4 . '">
                                                <i class="far fa-question-circle"></i>
                                            </a>

                                        </div>
                                        
                                    
                                      </div>';
    ///////////////////////////////////////////// 2
    echo '<hr>';

    ///////////////////////////////////////////// 3
    /*            $formato_64=base64_encode($formato);
                                    $tooltip5="Llenar con tinta azul el formato, escanearlo a color en formato PDF, asegurándose de que ajuste a una hoja tamaño carta y que el archivo no sea mayor a 4MB y firmada por la madre, el padre o el tutor/a.";
                                echo '<div class="row" id="row-3">
                                       
                                        <div class="col-sm">';
                                        if($formato){
                                            echo '<a href="getfilepdf.php?data='.$formato_64.'" target="_blank" class="btn btn-primary">Mi formato...</a>';

                                            if($status_formato==1){
                                                echo '<span class="badge badge-success">OK</span>';
                                            }
                                            if($status_formato==2){
                                                echo '
                                                <span class="badge badge-warning"data-toggle="tooltip" data-placement="top" title="'.$observa_formato.'">
                                                  !
                                                </span>';
                                            }
                                        }
                                        else{
                                            echo '-';
                                        }

                                echo   '</div>
                                        <div class="col-sm">
                                                                                                                       
                                            <div class="upload-btn-wrapper" '.$validado_disabled2.'>
                                                <div class="btn-upload-custom"><i class="fas fa-upload"></i> Adjuntar formato de protección de datos</div>
                                                <input type="file" id="file-select-3" class="form-control-file"  onchange="fnUpload('.$idusuario.',3)" '.$validado_disabled.' >
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-1">
                                            

                                            <a href="#" data-toggle="tooltip" data-html="true" title="'.$tooltip5.'">
                                                <i class="far fa-question-circle"></i>
                                            </a>

                                        </div>
                                        
                                    
                                      </div>';*/
    ///////////////////////////////////////////// 3

    echo '<hr>';


    ///////////////////////////////////////////// 4
    $manifestacion_64 = ($manifestacion);
    $tooltip3 = "Llenar con tinta azul toda la información que se solicita en el formato, escanearlo en PDF y que el archivo no sea mayor a 4 MB.";
    echo '<div class="row" id="row-4">
                                       
                                        <div class="col-sm">';
    if ($manifestacion) {
        echo '<a href="uploads/' . $manifestacion_64 . '" target="_blank" class="btn btn-primary">Mi manifestación...</a>';
        if ($status_manifest == 1) {
            echo '<span class="badge badge-success">OK</span>';
        }
        if ($status_manifest == 2) {
            echo '
                                                <span class="badge badge-warning"data-toggle="tooltip" data-placement="top" title="' . $observa_manifest . '">
                                                  !
                                                </span>';
        }
    } else {
        echo '-';
    }

    echo   '</div>
                                        <div class="col-sm">
                                                                                                                       
                                            <div class="upload-btn-wrapper" ' . $validado_disabled2 . '>
                                                <div class="btn-upload-custom"><i class="fas fa-upload"></i> Adjuntar manifestación bajo protesta</div>
                                                <input type="file" id="file-select-4" class="form-control-file"  onchange="fnUpload(' . $idusuario . ',4)" ' . $validado_disabled . ' >
                                            </div>
                                            
                                            
                                        </div>
                                        <div class="col-sm-1">
                                            

                                            <a href="#" data-toggle="tooltip" data-html="true" title="' . $tooltip3 . '">
                                                <i class="far fa-question-circle"></i>
                                            </a>

                                        </div>
                                        
                                    
                                      </div>'; ///row3

    ///////////////////////////////////////////// 4
    ////card
    echo '</div>
                   </div>
                </div>
            
              </div>'; ///row principal


    echo '<hr>';


    echo '<div id="errormsg"></div>';
} else {
    echo "No registrado";
}
