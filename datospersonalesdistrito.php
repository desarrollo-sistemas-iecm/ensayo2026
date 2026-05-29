<?php

//include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];

    //echo "/".$edad."/"
    error_reporting(E_ALL ^ E_NOTICE);
    include 'cat_alcaldia.php';

?> 

	

	

	 <form method="POST" id="guardarparticipante">

                  <input type="hidden" id="area" value="<?php echo $area; ?>" >
                  <input type="hidden" id="idusuario" value="<?php echo $idusuario; ?>" >
                  <input type="hidden" id="edad" value="<?php echo $edad; ?>" >
                   <input type="hidden" id="distrito" value="<?php echo $distrito; ?>" >
                  
                  <div > <!--conv-->




                   <div class="row form-group">

                    <label  class="control-label col-sm-4" for="nombre">* Título del ensayo</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" name="titulo" id="titulo"  value="<?php echo $titulo; ?>" maxlength="25" <?php echo $style_disabled2;?> >
                    </div>
                  </div>
                  <div class="row form-group">

                    <label  class="control-label col-sm-4" for="nombre">* Seudónimo</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" name="sobrenombre" id="sobrenombre"  value="<?php echo $sobrenombre; ?>" maxlength="25" <?php echo $style_disabled2;?> >
                    </div>
                  </div>



                  <div class="row form-group">

                    <label  class="control-label col-sm-4" for="nombre">*Primer apellido</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder"  name="paterno" id="paterno"  value="<?php echo $paterno; ?>" <?php echo $style_disabled;?> maxlength="25">
                    </div>
                  </div>

                  <div class="row form-group">

                    <label  class="control-label col-sm-4" for="nombre">*Segundo apellido</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" name="materno" id="materno" value="<?php echo $materno; ?>" <?php echo $style_disabled;?> maxlength="25">
                    </div>
                  </div>

                  <div class="row form-group">

                    <label  class="control-label col-sm-4" for="nombre">*Nombre(s)</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" name="nombre" id="nombre" value="<?php echo $nombre; ?>" <?php echo $style_disabled;?> maxlength="25">
                    </div>
                  </div>



                 <div class="form-group row">
        
                      <label class="col-sm-4 control-label">*Género</label>
                      <div class="col-sm-6">
                          <select  class="form-control input-medium" name="genero" id="genero" required>                               
                            <option value="0" selected disabled>Selecciona una opción</option>
                            <option value="M" >Masculino</option>
                            <option value="F" >Femenino</option>
                            <option value="X" >Otro</option>
                    </select>
                      </div>
                </div>


                  <div class="form-group row">
        
                    <label class="col-sm-4 control-label">*Fecha de nacimiento</label>
                    <div class="col-sm-3">
                        <input class="form-control input-small" type="date" name="fecha_nacimiento" id="fecha_nacimiento" onchange="fnEdad();" step='1' min='1900-01-01' max='2001-12-31' value="2000-00-00" required>

                        <input type="hidden" name="edad_califica" id="edad_califica" value="0">
                        <input type="hidden" name="edad" id="edad" value="0">



                    </div>
                    <div class="col-sm" id="erroredad"></div>
                  </div>
                  <div class="form-group row">

                    <label class="col-sm-4 control-label">Categoría</label>
                    <div class="col-sm-8">
                      <select  class="form-control input-medium" name="categoria" id="categoria"  disabled>                               
                         <option value="0" selected disabled >Selecciona una opción</option>
                        <option value="1" <?php if($edad>=15&&$edad<=17) echo 'selected';?> >15 a 17 años</option>
                        <option value="2" <?php if(($edad>=18&&$edad<=23) echo 'selected';?> >18 a 23 años</option>
                        
                      </select>
                    </div>

                  </div>

                  
                  <?php

                  if($edad>=18){
                    echo '<input  type="hidden" class="form-control noborder" id="tutor" name="tutor" value="">';

                  }else{
                    echo '<div class="row form-group">

                            <label  class="control-label col-sm-4">Nombre completo de tu madre, padre, tutor o tutora</label>

                            <div class="col-sm-8">
                              <input type="text" class="form-control noborder" id="tutor" name="tutor"  value="'.$tutor.'" maxlength="99" '.$style_disabled2.' >
                            </div>
                          </div>'; 
                  }
                  ?>
                  <div class="row form-group">

                    <label class="control-label col-sm-4" for="nombre">*Correo electrónico del participante</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" id="correo"  name="correo" value="<?php echo $correo; ?>" <?php echo $style_disabled;?> >
                    </div>
                  </div>    

                  <div class="row form-group">

                    <label class="control-label col-sm-4" for="nombre">Teléfono de casa</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" id="tel1" name="tel1" value="<?php echo $tel1; ?>" <?php echo $style_disabled2;?> maxlength="20">
                    </div>
                  </div>

                  <div class="row form-group">

                    <label class="control-label col-sm-4" for="nombre">*Teléfono celular</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" id="tel2" name="tel2" value="<?php echo $tel2; ?>" <?php echo $style_disabled2;?> maxlength="20">
                    </div>
                  </div> 


                   <div class="row form-group" style="display: none;">

                    <label class="control-label col-sm-4" for="nombre">* Domicilio</label>

                    <div class="col-sm-8">
                      <input  type="text" class="form-control noborder" id="domicilio" name="domicilio" value="<?php echo $domicilio; ?>" maxlength="99" <?php echo $style_disabled2;?> >
                    </div>
                  </div>


                   



                  <div class="row form-group">

                    <label  class="control-label col-sm-4">* Demarcación territorial. Si no vives en la Ciudad de México, la entidad (estado) de donde eres.</label>

                    <div class="col-sm-8">
                      <select class="form-control noborder" id="alcaldia" name="alcaldia"  <?php echo $style_disabled2;?> onchange="fnSelectEntidad()">
                        <option value="0"  selected disabled>Selecciona una opción</option>


                        <?php
                          for($i=2;$i<sizeof($cat_alcaldia);$i++){
                            echo '<option value="'.$i.'"';
                              if($alcaldia==$i){echo 'selected';}

                               echo '>'.$cat_alcaldia[$i].'</option>';

                          }

                        ?>
                        <option value="18"  <?php if($alcaldia==18){echo 'selected';} ?> >Otro</option>
                        
                      </select>

                      <div class="row form-group bloqueentidad" id="bloqueentidad">

                        <label  class="control-label col-sm-4">Entidad</label>

                        <div class="col-sm-8">
                          <input type="text" class="form-control noborder" id="entidad" name="entidad"  value="<?php echo $entidad; ?>" maxlength="99" <?php echo $style_disabled2;?> <?php if($alcaldia!=18){echo 'disabled';} ?> >
                        </div>
                      </div>
                    </div>
                  </div>

                  <div class="row form-group">
                                            
                    <label class="control-label col-sm-4" for="nombre">* Supuesto en el que recae mi derecho a participar</label>
                    
                    <div class="col-sm-8">
                      <input type="checkbox" name="resido_cdmx" id="resido_cdmx" <?php if($resido_cdmx) echo "checked"; ?> <?php echo $style_disabled2;?>> Resido en la Ciudad de México <br>
                      <input type="checkbox" name="soyoriundo" id="soyoriundo" <?php if($soyoriundo) echo "checked"; ?> <?php echo $style_disabled2;?> > Soy oriundo de la Ciudad de México <br>
                      <input type="checkbox" name="soyoriginario" id="soyoriginario" <?php if($soyoriginario) echo "checked"; ?> <?php echo $style_disabled2;?> > Soy hija/o de madre o padre originario de la Ciudad de México <br>
                    </div>
                  </div>

                  
                  

                  <div class="row form-group">

                    <label class="control-label col-sm-4" for="nombre">* ¿Cómo te enteraste del concurso?</label>

                    <div class="col-sm-8">
                      <select class="form-control noborder" id="te_enteraste" name="te_enteraste" <?php echo $style_disabled2;?>>
                        <option value="0" disabled selected >Selecciona una opción</option>
                        <option value="1" <?php if($te_enteraste==1){echo 'selected';} ?> >Página de internet</option>
                        <option value="2" <?php if($te_enteraste==2){echo 'selected';} ?> >Redes sociales</option>
                        <option value="3" <?php if($te_enteraste==3){echo 'selected';} ?> >Dirección distrital</option>
                        <option value="4" <?php if($te_enteraste==4){echo 'selected';} ?> >Cartel</option>
                        <option value="5" <?php if($te_enteraste==5){echo 'selected';} ?> >Díptico</option>
                        <option value="6" <?php if($te_enteraste==6){echo 'selected';} ?> >Escuela</option>
                      </select>
                    </div>
                  </div> 

                  <p class="badge badge-secondary">*Campos obligatorios</p> 


               
                <br>



            </div> <!--- conv -->





                  <!--<div id="div_guardar" class="row">

                              <div id="errorMsg"></div>
                         
                               <input type="button" value="Guardar" id="btn_guardar" class="control-form btn btn-primary btnguardar" <?php echo $style_disabled2;?>  onclick="this.disabled=true; guardarparticipantedistrito()">
                         
                  </div>-->

                  <div id="div_guardar" class="row">

                              <div id="errorMsg"></div>
                         
                               <input type="button" value="Siguiente" id="btn_siguiente" class="control-form btn btn-primary btnguardar"  onclick="fnsiguiente()">
                         
                  </div>




    </form>
	
		  
	