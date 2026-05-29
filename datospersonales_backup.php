<?php

//include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];

    //echo "/".$edad."/"
    
include 'cat_alcaldia.php';
?>
	

	

<form method="POST" id="guardarparticipante">

  <input type="hidden" id="area" value="<?php echo $area; ?>" >
  <input type="hidden" id="idusuario" value="<?php echo $idusuario; ?>" >
  <input type="hidden" id="edad" value="<?php echo $edad; ?>" >
  <input type="hidden" value="0" id="distrito" name="distrito">
  <div class="informacion-convocatoria sombra ordenesdeldia">
    <div class="datoscomite">




     <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">* Título del ensayo</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder" name="titulo" id="titulo"  value="<?php echo $titulo; ?>" maxlength="100" <?php echo $style_disabled2;?> >
      </div>
    </div>
    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">* Seudónimo</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder" name="sobrenombre" id="sobrenombre"  value="<?php echo $sobrenombre; ?>" maxlength="49" <?php echo $style_disabled2;?> >
      </div>
    </div>



    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">Primer apellido</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder"  name="paterno" id="paterno"  value="<?php echo $paterno; ?>" <?php echo $style_disabled;?> maxlength="49">
      </div>
    </div>

    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">Segundo apellido</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder" name="materno" id="materno" value="<?php echo $materno; ?>" <?php echo $style_disabled;?> maxlength="49">
      </div>
    </div>

    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">Nombre(s)</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder" name="nombre" id="nombre" value="<?php echo $nombre; ?>" <?php echo $style_disabled;?> maxlength="49">
      </div>
    </div>



    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">Sexo</label>

      <div class="col-sm-8">
        <input  type="text" class="form-control noborder style_disabled" id="genero" name="genero" value="<?php echo $genero; ?>" <?php echo $style_disabled;?> >
      </div>
    </div>


    <div class="row form-group">

      <label  class="control-label col-sm-4" for="nombre">Edad</label>


      <div class="col-sm-4">
        <input  type="text" class="form-control noborder style_disabled" id="edad"  name="edad" value="<?php echo $edad; ?>" <?php echo $style_disabled;?> >
      </div>
      <div class="col-sm-4">
        <input  type="text" class="form-control noborder style_disabled" id="fecha_nacimiento"  name="fecha_nacimiento" value="<?php echo $fecha_nacimiento; ?>" <?php echo $style_disabled;?> >
      </div>
    </div>
    <div class="form-group row">

      <label class="col-sm-4 control-label">Categoría</label>
      <div class="col-sm-8">
        <select  class="form-control input-medium" name="categoria" id="categoria" <?php echo $style_disabled;?> >                               

          <option value="1" <?php if($edad>=17&&$edad<=59) echo 'selected';?> >18 a 59 años</option>
          <option value="2" <?php if($edad>=60) echo 'selected';?> >De 60 años en adelante</option>
          
        </select>
      </div>

    </div>

    
    <?php

    if($edad>=18){
      echo '<input  type="hidden" class="form-control noborder" id="tutor" name="tutor" value="">';

    }else{
     /* echo '<div class="row form-group">

              <label  class="control-label col-sm-4">Nombre completo de tu madre, padre, tutor o tutora</label>

              <div class="col-sm-8">
                <input type="text" class="form-control noborder" id="tutor" name="tutor"  value="<?php echo $tutor; ?>" maxlength="99" <?php echo $style_disabled2;?> >
              </div>
            </div>'; */
    }
    ?>
    <div class="row form-group">

      <label class="control-label col-sm-4" for="nombre">Correo electrónico del participante</label>

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

      <label  class="control-label col-sm-4">* Demarcación territorial: si no vives en la Ciudad de México, la entidad (estado) de donde resides.</label>

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

   <!-- <div class="row form-group">
                              
      <label class="control-label col-sm-4" for="nombre">* Supuesto en el que recae mi derecho a participar</label>
      
      <div class="col-sm-8">
        <input type="checkbox" name="resido_cdmx" id="resido_cdmx" <?php if($resido_cdmx) echo "checked"; ?> <?php echo $style_disabled2;?>> Resido en la Ciudad de México <br>
        <input type="checkbox" name="soyoriundo" id="soyoriundo" <?php if($soyoriundo) echo "checked"; ?> <?php echo $style_disabled2;?> > Soy oriundo de la Ciudad de México <br>
        <input type="checkbox" name="soyoriginario" id="soyoriginario" <?php if($soyoriginario) echo "checked"; ?> <?php echo $style_disabled2;?> > Soy hija/o de madre o padre originario de la Ciudad de México <br>
      </div>
    </div>-->

    
    

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
        
     <div class="card" style="width:100%;">
			  <div class="card-header">
          <button class="btn btn-primary btn-block" type="button" data-toggle="collapse" data-target="#collapse1">
              AVISO DE PRIVACIDAD SIMPLIFICADO 
          </button>
        </div>
		 <div class="card-body">
   
        <div class="d-flex justify-content-center">
          <p>El Instituto Electoral de la Ciudad de México (Instituto Electoral), a través de la Dirección Ejecutiva de Educación Cívica y Construcción de Ciudadanía, es el Responsable del tratamiento de los datos personales que nos proporcione, los cuales serán protegidos en el Sistema de registro de participantes en los concursos para la promoción de la participación ciudadana y divulgación de la cultura democrática.
          <br>
            Los datos personales recabados serán utilizados con la finalidad de llevar a cabo el registro de datos personales de las personas interesadas en participar en los concursos que organice, promueva o difunda el Instituto Electoral; y podrán ser transferidos a la Comisión de Derechos Humanos de la Ciudad de México, para la investigación de quejas y denuncias por presuntas violaciones a los derechos humanos; Instituto de Transparencia, Acceso a la Información Pública, Protección de Datos Personales y Rendición de Cuentas de la Ciudad de México, para la sustanciación de recursos de revisión, recurso de inconformidad, denuncias y el
            procedimiento para determinar el presunto incumplimiento a la Ley de Protección de Datos Personales en Posesión de Sujetos Obligados de la Ciudad de México; la Auditoría Superior de la Ciudad de México, para la realización de auditorías o realización de investigaciones por presuntas faltas administrativas; Órganos de Control Interno, para la realización de auditorías o desarrollo de investigaciones por presuntas faltas
            administrativas; Órganos Jurisdiccionales Locales y Federales, para la sustanciación de los procedimientos Jurisdiccionales tramitados por ellos y el Instituto Nacional Electoral, para la sustanciación de procedimientos administrativos sancionadores.
              </br>
            Este Sistema de Datos Personales no cuenta con Encargados; ni con Despacho de Auditores Externos encargados del ejercicio de las funcionesde fiscalización.
             <br>
            Usted podrá manifestar la negativa al tratamiento de sus datos personales directamente ante la Unidad de Transparencia del Instituto Electoral, ubicada en la Calle de Huizaches No. 25, Colonia Rancho los Colorines, Planta Baja, Alcaldía Tlalpan, C. P. 14386, Ciudad de México, con número telefónico
            54833800 a la extensión 4725, o bien a través de la Plataforma Nacional de Transparencia http://www.plataformadetransparencia.org.mx/ o en el correo electrónico unidad.transparencia@iecm.mx.</p>
        </div>
        
    
    </div><!--card body-->
  </div><!---card-->   

  </div>
  <br>



</div> <!--- conv -->




<div id="div_guardar" class="row">

  <div id="errorMsg"></div>

  <input type="button" value="Guardar" id="btn_guardar" class="control-form btn btn-primary btnguardar" <?php echo $style_disabled2;?>  onclick="this.disabled=true; guardarparticipante()">

</div>
<span id="spanguardar"></span>



</form>
	
		  
	