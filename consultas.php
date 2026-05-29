<?php
 include('sqlconnector.php');
session_start();
?>

<div >
     <div >
      <hr>
          
         <?php
        if($_SESSION['perfil']=='1'){
          echo '<div class="row">
                  <form action="nueva_sesion_asamblea.php" method="POST">
                    <input type="submit" name="nueva_conv" class="btn btn-light btn-sm" value="Nueva Registro" />
                  
                  </form>
                
                </div>';
          }
          ?>
          <hr>

          <div class="row headerconsultas">
              
			  <p align="center">Participantes de Mayor de Edad</p>
			  
                  <div  class="col-sm"> <img src="img/Bloc-notes.png" > <a href="#">Declaración bajo protesta de decir verdad que la obra es original, personal e inédita y que toda la información es verdadera</a></div>
			  <br>
                    <div class="col-sm"> <img src="img/Bloc-notes.png" > <a href="#">Manifiesto bajo protesta de decir verdad que quien participa no es familiar de alguna persona funcionaria del IECM hasta segundo grado ascendente o descendente en linea</a></div>
			  <br>
                    <div class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Carta de cesión de derechos </a></div>
			  <br>
			       <div class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Formato de protección de datos</a></div>
               
          </div>
		 <br>
		        
		 <div class="row headerconsultas">
              
			  <p align="center">Participantes de Menor de Edad</p>
			  
                  <div  class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Declaración bajo protesta de decir verdad que la obra es original, personal e inédita y que toda la información es verdadera, firmada por la madre el padre o el tutor/a </a></div>
			 <br>
                    <div class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Manifiesto bajo protesta de decir verdad que quien participa no es familiar de alguna persona funcionaria del IECM hasta segundo grado ascendente o descendente en linea, firmada por la madre, el padre o el tutor/a </a></div>
			 <br>
                    <div class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Carta de cesión de derechos, firmada por la madre, el padre o el tutor/a </a></div>
			 <br>
			       <div class="col-sm"><img src="img/Bloc-notes.png" > <a href="#">Formato de protección de datos</a></div>
               
          </div>

          <form action="editar_sesion_asamblea.php" method="POST">
          <div class="row" id="mensaje"></div>
          <input type="hidden" id="maxsize_frm" name="maxsize_frm" value="<?php echo return_bytes( ini_get('upload_max_filesize') ); ?>" disabled>
          <!--<input type="text" value="<?php  echo ini_get('upload_max_filesize'); ?>" disabled> -->


<?php
     
     $id_distrito=$_POST['dist'];
     $id_colonia=$_POST['colonia'];

    //echo $id_distrito." / ".$id_colonia;



    $query = "SELECT * FROM sisecom_convocatorias where id_distrito=".$id_distrito." and id_colonia=".$id_colonia." and cattipo_id_sa=1 order by num_convocatoria DESC";
   //$tmpqueryusuario="select * FROM sisecom_usuarios_comites";

    //echo " * ".$query." * ";
    $row = sqlsrv_query($conn,$query);
    while($res = sqlsrv_fetch_array($row))
    {


      $id= base64_encode($res['id_convocatoria']);


      $status_incompleto_acta="";
      $status_incompleto_conv="";

       if($res['status']=='4')
        $style_red="div-incompleto";
      else
        $style_red="";

      if($res['status_acta']=='3'||$res['status_acta']=='4'||$res['status_convocatoria']=='3'||$res['status_convocatoria']=='4'){
        $status_incompleto=true;
        $style_red="div-incompleto";
        
        if($res['status_acta']=='3'||$res['status_acta']=='4'){
          $status_incompleto_acta="div-incompleto";
        }
        if($res['status_convocatoria']=='3'||$res['status_convocatoria']=='4'){
          $status_incompleto_conv="div-incompleto";
        }

      }else{
        $status_incompleto=false;
      }

      $id_conv_64=base64_encode($res['convocatoria_pdf']);
      $id_acta_64=base64_encode($res['acta_pdf']);

     




      if($res['convocatoria_pdf']==""||!$res['convocatoria_pdf']){
        $file_convocatoria='Sin archivo';  
      }else{
        $file_convocatoria='<a class="btn-icon '.$status_incompleto_conv.'" href="getfilepdf.php?f1='.$id_conv_64.'" target="_blank" alt="Convocatoria con firmas"><i class="fas fa-file-signature"></i></a>';
      }

      if($res['acta_pdf']==""||!$res['acta_pdf']){
        $file_acta='Sin archivo';  
      }else{
        $file_acta='<a class="btn-icon '.$status_incompleto_acta.'" href="getfilepdf.php?f1='.$id_acta_64.'" target="_blank"><i class="fas fa-copy"></i></a>';
      }



      
      //$res['status_convocatoria']



    //echo '<option value="'.$res['id_colonia'].'" >'.$res['nombre_colonia'].'</option>';

      echo '<div class="row listadoconsultas " id="row-'.$res['id_convocatoria'].'">
                
                    <div class="col-sm '.$style_red.'">'.$res['num_convocatoria'].'</div>
                    <div class="col-sm" style=" overflow: hidden;">'.$res['tipo_sa'].'</div>
                    <div class="col-sm">'.$res['fecha_sa'].'</div>';

                    if($res['status']=='0'&&!$status_incompleto){

                      echo '<div class="col-sm">
                              <button type="submit" class="btn btn-light" id="editar-'.$res['id_convocatoria'].'"name="action" value="'.$res['id_convocatoria'].'"><i class="fas fa-edit"></i></button>
                            </div>';
                      
                       if($res['sin_convocatoria']=='0'){
                          echo '<div class="col-sm"><a class="btn-icon" href="convocatoriapdf.php?fol='.$id.'" target="_blank"><i class="fas fa-file-download"></i></a></div>';
                       }else{
                          echo '<div class="col-sm"></div>';

                       }
                      echo '<div class="col-sm"> 
                              <div id="file'.$res['id_convocatoria'].'">Sin archivo</div> 
                                <div class="upload-btn-wrapper">
                                  <div class="btn-upload-custom"><i class="fas fa-upload"></i></div>
                                  <input type="file" id="file-select-'.$res['id_convocatoria'].'" class="form-control-file btn btn-light"  onchange="fnUploadConvocatoria('.$res['id_convocatoria'].')"  >
                                </div>
                            </div>';
                      
                      echo '<div class="col-sm" id="publicar'.$res['id_convocatoria'].'">
                               
                            </div>';
                      echo '<div class="col-sm"> 
                             
                            </div>';

                    }

                    /////////////////////////////////////////

                    if($res['status']=='1'&&!$status_incompleto){
                      echo '<div class="col-sm">
                              <button type="submit" class="btn btn-light" id="editar-'.$res['id_convocatoria'].'" name="action" value="'.$res['id_convocatoria'].'"><i class="fas fa-edit"></i></button>
                            </div>';
                      
                     if($res['sin_convocatoria']=='0'){
                          echo '<div class="col-sm"><a class="btn-icon" href="convocatoriapdf.php?fol='.$id.'" target="_blank"><i class="fas fa-file-download"></i></a></div>';
                       }else{
                          echo '<div class="col-sm"></div>';

                       }

                      echo '<div class="col-sm"> 
                              <div id="file'.$res['id_convocatoria'].'">'.$file_convocatoria.'</div> 
                              <div class="upload-btn-wrapper">
                                  <div class="btn-upload-custom"><i class="fas fa-upload"></i></div>
                                  <input type="file" id="file-select-'.$res['id_convocatoria'].'" class="form-control-file"  onchange="fnUploadConvocatoria('.$res['id_convocatoria'].')"  >
                              </div>
                            </div>';
                      
                      echo '<div class="col-sm" id="publicar'.$res['id_convocatoria'].'">
                                <a class="btn-icon" href="#" onclick="fnPublicar('.$res['id_usuario'].','.$res['id_convocatoria'].')"><i class="far fa-envelope"></i></a>
                            </div>';
                      echo '<div class="col-sm"> 
                              
                            </div>';

                    }

                    /////////////////////////////////////////

                    if($res['status']=='2'&&!$status_incompleto){

                       echo '<div class="col-sm">
                            
                            </div>';
                      
                     if($res['sin_convocatoria']=='0'){
                          echo '<div class="col-sm"><a class="btn-icon" href="convocatoriapdf.php?fol='.$id.'" target="_blank"><i class="fas fa-file-download"></i></a></div>';
                       }else{
                          echo '<div class="col-sm"></div>';

                       }

                      echo '<div class="col-sm"> 
                              <div id="file'.$res['id_convocatoria'].'">'.$file_convocatoria.'</div> 
                              <!--<input type="file" id="file-select-'.$res['id_convocatoria'].'" class="form-control-file"  onchange="fnUploadConvocatoria('.$res['id_convocatoria'].')"  >-->
                            </div>';
                     
                      echo '<div class="col-sm" id="publicar'.$res['id_convocatoria'].'">
                                <a class="btn-icon" href="#" onclick="fnPublicar('.$res['id_usuario'].','.$res['id_convocatoria'].')"><i class="far fa-envelope"></i><i class="fas fa-check-double"></i></a>
                            </div>';
                      echo '<div class="col-sm"> 
                              <div id="acta'.$res['id_convocatoria'].'">'.$file_acta.'</div> 
                              
                                <div class="upload-btn-wrapper">
                                  <div class="btn-upload-custom"><i class="fas fa-upload"></i></div>
                                  <input type="file" id="acta-select-'.$res['id_convocatoria'].'" class="form-control-file"  onchange="fnUploadActa('.$res['id_convocatoria'].')">
                                </div>
                            </div>';

                    }

                     if($res['status']=='3'&&!$status_incompleto){

                       echo '<div class="col-sm">
                            
                            </div>';
                      
                     if($res['sin_convocatoria']=='0'){
                          echo '<div class="col-sm"><a class="btn-icon" href="convocatoriapdf.php?fol='.$id.'" target="_blank"><i class="fas fa-file-download"></i></a></div>';
                       }else{
                          echo '<div class="col-sm"></div>';

                       }

                      echo '<div class="col-sm"> 
                              <div id="file'.$res['id_convocatoria'].'">'.$file_convocatoria.'</div> 
                              
                            </div>';
                     
                      echo '<div class="col-sm" id="publicar'.$res['id_convocatoria'].'">
                                <!-- <a class="btn-icon" href="#" onclick="fnPublicar('.$res['id_usuario'].','.$res['id_convocatoria'].')" disabled ><i class="far fa-envelope"></i><i class="fas fa-check-double"></i></a> -->
                            </div>';
                      echo '<div class="col-sm"> 
                              <div id="acta'.$res['id_convocatoria'].'">'.$file_acta.'</div> 
                              
                               
                            </div>';

                    }

                     if($res['status']=='4'||$status_incompleto){

                     echo '<div class="col-sm" >
                              <button type="submit" class="btn btn-light" id="editar-'.$res['id_convocatoria'].'" name="action" value="'.$res['id_convocatoria'].'"><i class="fas fa-edit"></i></button>
                            </div>';
                      
                     if($res['sin_convocatoria']=='0'){
                          echo '<div class="col-sm"><a class="btn-icon" href="convocatoriapdf.php?fol='.$id.'" target="_blank"><i class="fas fa-file-download"></i></a></div>';
                       }else{
                          echo '<div class="col-sm"></div>';

                       }

                       echo '<div class="col-sm"> 
                              <div id="file'.$res['id_convocatoria'].'">'.$file_convocatoria.'</div> 
                              <div class="upload-btn-wrapper">
                                  <div class="btn-upload-custom"><i class="fas fa-upload"></i></div>
                                  <input type="file" id="file-select-'.$res['id_convocatoria'].'" class="form-control-file"  onchange="fnUploadConvocatoria('.$res['id_convocatoria'].')"  >
                              </div>
                            </div>';
                     
                      echo '<div class="col-sm" id="publicar'.$res['id_convocatoria'].'">
                                <a class="btn-icon" href="#" onclick="fnPublicar('.$res['id_usuario'].','.$res['id_convocatoria'].')"><i class="far fa-envelope"></i><i class="fas fa-check-double"></i></a>
                            </div>';
                      echo '<div class="col-sm"> 
                              <div id="acta'.$res['id_convocatoria'].'">'.$file_acta.'</div> 
                              
                                <div class="upload-btn-wrapper">
                                  <div class="btn-upload-custom"><i class="fas fa-upload"></i></div>
                                  <input type="file" id="acta-select-'.$res['id_convocatoria'].'" class="form-control-file"  onchange="fnUploadActa('.$res['id_convocatoria'].')">
                                </div>
                            </div>';

                    }



             
              
          
               
          echo '</div>';

          echo '<div class="listadoconsultas_border" id="errormsg_'.$res['id_convocatoria'].'" >';
          echo '</div>';
          

          

      //echo '<div class="row" id="div'.$res['id_convocatoria'].'"></div>';


    
       
    }

?>
      </form>
         
     </div>
</div>

      