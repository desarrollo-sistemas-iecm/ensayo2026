<?php

	include 'sqlconnector.php';
 //session_start();
 //$my_user=$_SESSION["usr"];
 $idusuario=$_SESSION["idusuario"];
 define("UPLOAD_DIR", "uploads/");

?>
 

 
   

<hr>

<div class="form-check" >

    <label class="form-check-label">

        <input type="checkbox" class="form-check-input" name="ensayo" id="ensayo">
        Ensayo
    </label>

</div>
<hr>

<div class="form-check" >

    <label class="form-check-label">
    
        <input type="checkbox" class="form-check-input" name="identificacion" id="identificacion">
        Identificacion oficial
    </label>

</div>
<hr>




<div class="form-check" >

    <label class="form-check-label">
        
        <input type="checkbox" class="form-check-input" name="carta" id="carta">
        Carta de cesión de derechos
    </label>

</div>
<hr>

<div class="form-check" >

    <label class="form-check-label">
        
        <input type="checkbox" class="form-check-input" name="formato" id="formato">
        Formato de protección de datos personales
    </label>

</div>
<hr>
<div class="form-check" >

    <label class="form-check-label">
        
        <input type="checkbox" class="form-check-input" name="manifestacion" id="manifestacion">
        Manifestación bajo protesta
    </label>

</div>
<hr>






<div id="div_guardar" class="row">

  
  <input type="button" value="Guardar" id="btn_guardar" class="control-form btn btn-primary btnguardar" <?php echo $style_disabled2;?>  onclick="this.disabled=true; guardarparticipantedistrito()">

</div>
    
                   

