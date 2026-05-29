// Validador de Clave de Elector: 18 caracteres alfanuméricos y debe contener
// al menos una letra y un dígito. Se expone globalmente.
function validarClaveElector(clave){
  if(!clave) return false;
  var cleaned = String(clave).trim().toUpperCase().replace(/\s+/g,'');
  var basic = /^[A-Z0-9]{18}$/;
  if(!basic.test(cleaned)) return false;
  if(!/[A-Z]/.test(cleaned)) return false;
  if(!/[0-9]/.test(cleaned)) return false;
  return true;
}

// Attach clave de elector input listeners: validate on input and blur, show inline error + SweetAlert on invalid
(function(){
  var claveElectorEl = document.getElementById('clave_elector');
  if(!claveElectorEl) return;

  claveElectorEl.addEventListener('input', function(){
    try{ validarClaveElectorField(); }catch(e){}
  });

  claveElectorEl.addEventListener('blur', function(){
    var val = (this.value || '').trim();
    if(val === ''){ validarClaveElectorField(); return; }
    if(typeof validarClaveElector === 'function' && !validarClaveElector(val)){
      try{ validarClaveElectorField(); }catch(e){}
      if(typeof Swal !== 'undefined'){
        Swal.fire({
          icon: 'error',
          title: 'Formato incorrecto',
          text: 'La clave de elector debe contener 18 caracteres alfanuméricos, con letras y números.',
          confirmButtonColor: '#4A9FD5'
        }).then(function(){ claveElectorEl.focus(); });
      } else {
        alert('La clave de elector debe contener 18 caracteres alfanuméricos, con letras y números.');
      }
    } else {
      try{ validarClaveElectorField(); }catch(e){}
    }
  });

})();

function validarClaveElectorField(){
  var el = document.getElementById('clave_elector');
  if(!el) return true; // nothing to validate here
  var ok = validarClaveElector(el.value || '');
  var errId = 'claveElectorError';
  var err = document.getElementById(errId);
  if(!err){
    err = document.createElement('div');
    err.id = errId;
    err.style.color = '#dc2626';
    err.style.marginTop = '6px';
    err.style.fontSize = '0.9rem';
    el.parentNode.appendChild(err);
  }
  if(!ok){
    err.textContent = 'La clave de elector debe tener 18 caracteres alfanuméricos y contener letras y números.';
    el.classList.add('is-invalid');
  } else {
    err.textContent = '';
    el.classList.remove('is-invalid');
  }
  return ok;
}

function normalizarCurpInput(input){
  if(!input) return;
  input.value = String(input.value || '').toUpperCase().replace(/[^A-Z0-9]/g, '').slice(0, 18);
}

(function(){
  var curpEl = document.getElementById('curp');
  if(!curpEl) return;
  curpEl.addEventListener('input', function(){ normalizarCurpInput(this); });
})();

// Exponer funciones
window.validarClaveElector = validarClaveElector;
window.validarClaveElectorField = validarClaveElectorField;
window.normalizarCurpInput = normalizarCurpInput;

function crearusuario(){
 
       var nombre=document.getElementById("nombre").value;
	
	   var paterno=document.getElementById("paterno").value;
	
	   var materno=document.getElementById("materno").value;

     var curp=document.getElementById("curp").value;

       var genero=document.getElementById("genero").value;
    
       var fecha_nacimiento=document.getElementById("fecha_nacimiento").value;

       var edad_califica=document.getElementById("edad_califica").value;

       var user=document.getElementById("user").value;
       var pass1=document.getElementById("pass1").value;
       var pass2=document.getElementById("pass2").value;

       var area=document.getElementById("sel").value;
       var correo=document.getElementById("correo").value;
       var correo2=document.getElementById("correo2").value;
	
	   	var tmptxt=document.getElementById("tmptxt").value;
        var error=0;
        var error_string="";


	
       
        if (nombre == 0)
        {
            error_string+="<p>El campo nombre no puede estar vacío.</p>";
            error++;
            
        } //nombre
	
	
	    if (paterno == 0)
        {
            error_string+="<p>El campo primer apellido no puede estar vacío.</p>";
            error++;
            
        } //paterno
	
	
	    if (materno == 0)
        {
           error_string+="<p>El campo segundo apellido no puede estar vacío.</p>";
        
            error++;
            
        } //materno



        if (genero == 0)
        {
            error_string+="<p>Selecciona una opción en el campo sexo.</p>";
            error++;
            
        } //genero


        if (curp == 0)
        {
          error_string+="<p>El campo CURP no debe estar vacío.</p>";
          error++;
        }
      if (curp.length <18)
      {
        error_string+="<p>El campo CURP no debe ser menor a 18 caracteres.</p>";
        error++;
      }

        if (edad_califica == 0 ||edad_califica == '0')
        {
            error_string+="<p>"+"Fecha de nacimiento fuera del rango permitido."+"</p>";
            
            error++;
            
        } //materno
        
    
        if (user == 0)
        //alert("Hola!!");
        {
            error_string+="<p>"+"El campo nombre de usuario no debe estar vacío."+"</p>";
            //$("#user").focus();
            error++;
            
        }//user
        
        if (pass1 == 0)
        //alert("Hola!!");
        {
            error_string+="<p>"+"El campo contraseña no debe estar vacío."+"</p>";
            //$("#pass1").focus();
            error++;
            
        }//pass1

        if (pass2 == 0)
        //alert("Hola!!");
        {
            error_string+="<p>"+"El campo repetir contraseña no debe estar vacío."+"</p>";
            //$("#pass2").focus();
            error++;
            
        }//pass2
        
        if (pass1 != pass2)
        //alert ("Hola");
        {
            error_string+="<p>"+"La contraseña no coincide."+"</p>";
            error++;
            
        }// valida contraseña
        
        
        if ((area == undefined)||(area == 0))
        {
            alert("Seleccione un tipo de Concurso");
            //$("#sel").focus();
            error++;
            
        }//sel
        
        
        if (correo == 0)
        {
            error_string+="<p>"+"El campo correo electrónico no debe estar vacío."+"</p>";
            error++;
            //$("#correo").focus();
            
        }//correo
        
        if (/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,4})$/.test(correo)){
            //alert("La dirección de email es correcta.");
        } else {
            error_string+="<p>"+"La dirección de correo electrónico es incorrecta."+"</p>";
            error++;
            

        }
        

        if (correo2 == 0)
        {
            error_string+="<p>"+"El campo repetir correo electrónico no debe estar vacío."+"</p>";
            //$("#correo2").focus();
            error++;
            
        }//correo
        
        if (correo != correo2)
        //alert ("Hola");
        {
            error_string+="<p>"+"El correo electrónico no coincide."+"</p>";
            error++;
            
        }
        
        if(tmptxt==0)
		{
		   error_string+="<p>"+"El campo del CAPTCHA no debe estar vacío."+"</p>";
            //$("#tmptxt").focus();
            error++;
            
		}

        var textloader='<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
        document.getElementById("errormsg").innerHTML=textloader;

        document.getElementById("main_container").setAttribute("style", "pointer-events: none;");

        //alert(edad_califica+" * ");

        if(error>0){
            document.getElementById("btn_crearusuario").disabled=false;
            document.getElementById("btn_crearusuario").innerHTML="Crear cuenta de usuario";
            
            // Mostrar errores de validación con toast
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                html: error_string,
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
            
            document.getElementById("errormsg").innerHTML="";
            document.getElementById("main_container").setAttribute("style", "pointer-events: auto;");
            return false;

        }
        
               
    var datos="action=insert"+"&nombre="+nombre+"&paterno="+paterno+"&materno="+materno+"&curp="+curp+"&genero="+genero+"&fecha_nacimiento="+fecha_nacimiento+"&user="+user+"&pass1="+pass1+"&pass2="+pass2+"&area="+area+"&concurso="+area+"&correo="+correo+"&tmptxt="+tmptxt;
       
       
	var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                var resultado=this.responseText.trim();
                //alert(resultado);

                if(resultado=='1'||resultado=='2'||resultado=='12'||resultado==1||resultado==2||resultado==12||resultado=='3'||resultado==3){
                    var mensaje = "";
                    var icono = "warning";
                    
                    if(resultado=='1'||resultado=='12'||resultado==1||resultado==12)mensaje="El nombre de usuario ya está registrado.";
                    else if(resultado=='2'||resultado=='12'||resultado==2||resultado==12)mensaje="El correo ya está registrado.";
                    else if(resultado=='3'||resultado==3){
                        mensaje="El código CAPTCHA no es correcto. Se ha generado uno nuevo.";
                        // Recargar imagen del captcha
                        var captchaImage = document.getElementById('tmptxt2');
                        if(captchaImage){
                            var captchaSrc = captchaImage.src;
                            captchaSrc = captchaSrc.substring(0, captchaSrc.lastIndexOf("?"));
                            captchaImage.src = captchaSrc + "?rand=" + Math.random() * 1000;
                        }
                        // Limpiar el campo del captcha
                        document.getElementById('tmptxt').value = '';
                    }

                    // Mostrar toast de SweetAlert2
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: icono,
                        title: mensaje,
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer);
                            toast.addEventListener('mouseleave', Swal.resumeTimer);
                        }
                    });

                    document.getElementById("errormsg").innerHTML ="";
                    document.getElementById("btn_crearusuario").disabled=false;
                    document.getElementById("btn_crearusuario").innerHTML="Crear cuenta de usuario";
                    document.getElementById("main_container").setAttribute("style", "pointer-events: auto;");
                    //return false;
                    
                }else if(resultado.indexOf('SUCCESS:') !== -1){
                    var match = resultado.match(/SUCCESS:(\d+)/);
                    var idUsuarioCreado = match ? parseInt(match[1], 10) : 0;
                    document.getElementById("main_container").setAttribute("style", "pointer-events: auto;");
                    document.getElementById("errormsg").innerHTML = '';
                    // Intentar con SweetAlert2; si no está disponible usar alert nativo
                    try {
                        if(typeof Swal !== 'undefined'){
                            Swal.fire({
                                icon: 'success',
                                title: '<span style="font-family:\'Bakbak One\',sans-serif;color:#4A9FD5">\u00a1Registro exitoso!</span>',
                                html:
                                    '<p style="margin-bottom:6px;">Tu cuenta de persona usuaria fue creada correctamente.</p>' +
                                    '<p style="margin-bottom:16px;">Se envió un correo electrónico confirmando tu registro.<br>' +
                                    '<small style="color:#6b7280">(Si no aparece en recibidos, revisa la bandeja de <strong>No deseados / Spam</strong>)</small></p>' +
                                    '<p style="margin:0 0 8px;">\u00bfNo recibiste ningún correo?</p>' +
                                    '<button onclick="fnReenviarCorreo('+idUsuarioCreado+'); this.disabled=true; this.textContent=\'\u2713 Correo reenviado\';" ' +
                                    'style="background:linear-gradient(135deg,#4A9FD5,#2E86AB);color:#fff;border:none;border-radius:30px;padding:9px 22px;font-size:.9rem;cursor:pointer;">' +
                                    '<i class="fas fa-paper-plane" style="margin-right:7px;"></i>Enviar correo otra vez</button>',
                                showConfirmButton: true,
                                confirmButtonText: '<i class="fas fa-sign-in-alt" style="margin-right:6px;"></i>Ir al inicio de sesión',
                                confirmButtonColor: '#4A9FD5',
                                allowOutsideClick: false,
                                width: '560px'
                            }).then(function(result){
                                if(result.isConfirmed){
                                    window.location.href = 'index.php?concurso=ensayo';
                                }
                            });
                        } else if(typeof window.onRegistroExitoso === 'function'){
                            window.onRegistroExitoso(idUsuarioCreado);
                        } else {
                            alert('¡Registro exitoso! Tu cuenta fue creada. Serás redirigido al inicio de sesión.');
                            window.location.href = 'index.php?concurso=ensayo';
                        }
                    } catch(e) {
                        alert('¡Registro exitoso! Tu cuenta fue creada. Serás redirigido al inicio de sesión.');
                        window.location.href = 'index.php?concurso=ensayo';
                    }
                }else if(resultado==='FAIL'){
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Error al crear el usuario. Por favor intenta de nuevo.',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                    document.getElementById("errormsg").innerHTML="";
                    document.getElementById("btn_crearusuario").disabled=false;
                    document.getElementById("btn_crearusuario").innerHTML="Crear cuenta";
                    document.getElementById("main_container").setAttribute("style", "pointer-events: auto;");
                }else{
                    // Respuesta inesperada - mostrar error, no reemplazar la página
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'warning',
                        title: 'Respuesta inesperada del servidor. Por favor intenta de nuevo.',
                        showConfirmButton: false,
                        timer: 4000,
                        timerProgressBar: true
                    });
                    document.getElementById("errormsg").innerHTML="";
                    document.getElementById("btn_crearusuario").disabled=false;
                    document.getElementById("btn_crearusuario").innerHTML="Crear cuenta";
                    document.getElementById("main_container").setAttribute("style", "pointer-events: auto;");
                }
                


            }
        };
	
	xmlhttp.open("POST", "usuarios.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
    xmlhttp.send(datos);
/**/
}

function guardarparticipante(){

         errors=0;
         error_string="";
   
        var nombre=document.getElementById("nombre").value;
        var paterno=document.getElementById("paterno").value;
        var materno=document.getElementById("materno").value;
        var genero=document.getElementById("genero").value;
        var fecha_nacimiento=document.getElementById("fecha_nacimiento").value;
        var idusuario=document.getElementById("idusuario").value;
        var area=document.getElementById("area").value;
        var distrito=document.getElementById("distrito").value;
        var correo=document.getElementById("correo").value;
        var categoria=document.getElementById("categoria").value;
      //  var tutor=document.getElementById("tutor").value;

        var manifestacion1 = $('input[name="manifestacion1"]:checked').val();
        var manifestacion2 = $('input[name="manifestacion2"]:checked').val();
        var manifestacion3 = $('input[name="manifestacion3"]:checked').val();
        var manifestacion4 = $('input[name="manifestacion4"]:checked').val();
        var manifestacion5 = $('input[name="manifestacion5"]:checked').val();
        var manifestacion6 = $('input[name="manifestacion6"]:checked').val();
        var manifestacion7 = $('input[name="manifestacion7"]:checked').val();

//console.log ({manifestacion1, manifestacion2, manifestacion3, manifestacion4, manifestacion5, manifestacion6, manifestacion7});
if(manifestacion1==undefined||manifestacion2==undefined||manifestacion3==undefined||manifestacion4==undefined||manifestacion5==undefined||manifestacion6==undefined||manifestacion7==undefined){
  // Mostrar SweetAlert específico para manifestaciones y evitar continuar
  try { if(document.getElementById('btn_guardar')) document.getElementById('btn_guardar').disabled = false; } catch(e){}
  if(typeof Swal !== 'undefined'){
    Swal.fire({
      icon: 'warning',
      title: 'Faltan manifestaciones',
      html: 'Debes seleccionar todas y en cada una de las <b>manifestaciones</b>.',
      confirmButtonColor: '#4A9FD5'
    }).then(function(){
      // Llevar al usuario hacia las manifestaciones
      var first = document.querySelector('input[name="manifestacion1"]');
      if(first) first.scrollIntoView({behavior: 'smooth', block: 'center'});
    });
  } else {
    alert('Debes seleccionar las opciones en cada una de las manifestaciones.');
  }
  return false;
}
// console.log ('mannifestaciones: '+manifestacion1+' '+manifestacion2+' '+manifestacion3+' '+manifestacion4+' '+manifestacion5+' '+manifestacion6+' '+manifestacion7);


       var sobrenombre=document.getElementById("sobrenombre").value;
       if(sobrenombre==""){
        errors++;
        error_string+="<p>El campo <b>seudónimo</b> no puede estar vacío.</p>";

       }

      var nombre_obra=document.getElementById("nombre_obra").value;
      if(nombre_obra==""){
       errors++;
       error_string+="<p>El campo <b>obra, obras o tomo a la que interpela</b> no puede estar vacío.</p>";

      }
       
       
       var titulo=document.getElementById("titulo").value;
       if(titulo==""){
        errors++;
        error_string+="<p>El campo <b>título del ensayo</b> no puede estar vacío.</p>";

       }

       var edad=document.getElementById("edad").value;

       if(edad<17){
        var nombre_tutor=document.getElementById("nombre_tutor").value;
         if(nombre_tutor==""){
          errors++;
          error_string+="<p>El campo <b>nombre de padre o tutor</b> no puede estar vacío.</p>";
         }

          var paterno_tutor=document.getElementById("paterno_tutor").value;
         if(paterno_tutor==""){
          errors++;
          error_string+="<p>El campo <b>apellido paterno del tutor</b> no puede estar vacío.</p>";
         }

          var materno_tutor=document.getElementById("materno_tutor").value;
         if(materno_tutor==""){
          errors++;
          error_string+="<p>El campo <b>apellido materno del tutor</b> no puede estar vacío.</p>";
         }

          var clave_elector=document.getElementById("clave_elector").value || '';
        if(clave_elector==""){
          errors++;
          error_string+="<p>El campo <b>clave de elector</b> no puede estar vacío.</p>";
         } else {
          // Normalizar (quitar espacios) y validar longitud mínima de 18
          var clave_clean = clave_elector.replace(/\s+/g, '');
          if (clave_clean.length < 18) {
            errors++;
            error_string += "<p>El campo <b>clave de elector</b> no debe ser menor a 18 caracteres.</p>";
          } else if (typeof validarClaveElector === 'function' && !validarClaveElector(clave_elector)) {
            try { if(document.getElementById('btn_guardar')) document.getElementById('btn_guardar').disabled = false; } catch(e){}
            if (typeof Swal !== 'undefined') {
              Swal.fire({
                icon: 'error',
                title: 'Formato incorrecto',
                html: 'La clave de elector debe tener 18 caracteres alfanuméricos y contener letras y números.',
                confirmButtonColor: '#4A9FD5'
              }).then(function(){ var claveElectorEl=document.getElementById('clave_elector'); if(claveElectorEl) claveElectorEl.focus(); });
            } else {
              alert('La clave de elector debe tener 18 caracteres alfanuméricos y contener letras y números.');
            }
            return false;
          }
        }

       }else{

         var nombre_tutor="";
         var paterno_tutor="";
         var materno_tutor="";
         var clave_elector="";


       }

       
       var tel1=document.getElementById("tel1").value;
       if(isNaN(tel1)&&tel1!=""){
        errors++;
        error_string+="<p>El <b>teléfono local</b> debe contener números únicamente.</p>";

        if(tel1.toString().length<10){
        errors++;
        error_string+="<p>El <b>teléfono local</b> debe contener al menos 10 dígitos.</p>";

       }

       }


       var tel2=document.getElementById("tel2").value;
       if(isNaN(tel2)||tel2==""){
        errors++;
        error_string+="<p>El <b>teléfono celular</b> debe contener números únicamente.</p>";

       }
       if(tel2.toString().length<10){
        errors++;
        error_string+="<p>El <b>teléfono celular</b> debe contener al menos 10 dígitos.</p>";

       }

       var alcaldia=document.getElementById("alcaldia").value;
       if(alcaldia=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>demaración territorial o entidad.</b></p>";

       }

       var entidad="";

       if(alcaldia=="18"){

        entidad=document.getElementById("entidad").value;
        if(entidad==""){
            errors++;
            error_string+="<p>Debes escribir el nombre de una <b>demaración territorial o entidad.</b></p>";
                 }

       }

     //  var suma=0;


      // var resido_cdmx=+document.getElementById("resido_cdmx").checked;
       //var soyoriundo=+document.getElementById("soyoriundo").checked;
    //   var soyoriginario=+document.getElementById("soyoriginario").checked;
      // var suma=resido_cdmx+soyoriundo+soyoriginario;
      // if(suma==0){
        //errors++;
        //error_string+="<p>Debes seleccionar al menos uno de los checkbox de <b>Resido en la Ciudad de México, o Soy oriundo de la Ciudad de México o Soy hija/o de madre o padre originario de la Ciudad de México</b></p>";

       //}

      var curp=document.getElementById("curp").value;
      if(curp==""){
       errors++;
       error_string+="<p>El campo <b>CURP</b> no puede estar vacío.</p>";
      }

      var domicilio=document.getElementById("domicilio").value;
       if(domicilio==""){
        //errors++;
        //error_string+="<p>El campo <b>domicilio</b> no puede estar vacío.</p>";

       }

       


       var te_enteraste=document.getElementById("te_enteraste").value;
       if(te_enteraste=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>cómo te enteraste.</b></p>";

       }



        if(errors>0){

           // alert(document.getElementById('div_errors').innerHTML);
            //document.getElementById("div_errors").innerHTML="xxxxxxxxx";
            document.getElementById('div_errors').innerHTML='<div class="alert alert-warning">'+error_string+'</div>';
            document.getElementById("btn_guardar").disabled=false;
           // $('#spanguardar').addClass('badge badge-warning');
           // document.getElementById("spanguardar").innerHTML='!';



              $("html, body").animate({ scrollTop: 0 }, "slow");
           
            return false;


        }

        // Validar CURP antes de guardar (debe tener exactamente 18 caracteres)
        if (!validarCurpAntesDeGuardar()) {
            document.getElementById("btn_guardar").disabled = false;
            return false;
        }
               
    var datos="action=insert"+"&nombre="+nombre+"&paterno="+paterno+"&materno="+materno+"&curp="+curp+"&sobrenombre="+sobrenombre+"&genero="+genero+"&correo="+correo;
    datos+="&fecha_nacimiento="+fecha_nacimiento+"&idusuario="+idusuario+"&area="+area+"&edad="+edad;
    datos+="&titulo="+titulo+"&nombre_obra="+nombre_obra+"&categoria="+categoria+"&clave_elector="+clave_elector+"&paterno_tutor="+paterno_tutor+"&materno_tutor="+materno_tutor+"&nombre_tutor="+nombre_tutor;
    datos+="&tel1="+tel1+"&tel2="+tel2+"&alcaldia="+alcaldia+"&entidad="+entidad+"&domicilio="+domicilio;
    datos+="&manifestacion1="+manifestacion1+"&manifestacion2="+manifestacion2+"&manifestacion3="+manifestacion3+"&manifestacion4="+manifestacion4+"&manifestacion5="+manifestacion5+"&manifestacion6="+manifestacion6+"&manifestacion7="+manifestacion7;
    datos+="&te_enteraste="+te_enteraste+"&distrito="+distrito;
    
    //document.getElementById("menu1").innerHTML = datos;
   // alert(datos);
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
              // Solo aceptar confirmación explícita del servidor
              if (this.responseText.trim() === 'OK') {
                    // Mostrar alerta de éxito y luego recargar la página
                    Swal.fire({
                        icon: 'success',
                        title: '¡Datos guardados!',
                        text: 'Serás redirigido a la sección de Adjuntar Documentación.',
                        confirmButtonText: 'Continuar',
                        confirmButtonColor: '#4A9FD5',
                        timer: 2000,
                        timerProgressBar: true,
                        allowOutsideClick: false
                    }).then(function() {
                        // Recargar la página para actualizar el estado de registro
                        window.location.href = 'main.php';
                    });
                } else {
                    // Si hay error, mostrar el mensaje
                    document.getElementById("menu1").innerHTML = this.responseText;
                    document.getElementById('div_errors').innerHTML='';
                }
            }
        };
    
    xmlhttp.open("POST", "guardarparticipante.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
    xmlhttp.send(datos);

}


function fnsiguiente(){

        errors=0;
         error_string="";
   
     
      

       var nombre=document.getElementById("nombre").value;

      var nombre=document.getElementById("nombre").value;
      if(nombre==""){
        errors++;
        error_string+="<p>El campo <b>nombre</b> no puede estar vacío.</p>";

      }


       var paterno=document.getElementById("paterno").value;

       if(paterno==""){
        errors++;
        error_string+="<p>El campo <b>primer apellido</b> no puede estar vacío.</p>";

      }
       var materno=document.getElementById("materno").value;
       if(materno==""){
        errors++;
        error_string+="<p>El campo <b>segundo apellido</b> no puede estar vacío.</p>";

      }
       var genero=document.getElementById("genero").value;
       if(genero==""){
        errors++;
        error_string+="<p> Debes seleccionar una opción en <b>género</b></p>";

      }
       var fecha_nacimiento=document.getElementById("fecha_nacimiento").value;

       if (edad_califica == 0 ||edad_califica == '0')
        {
            error_string+="<p>"+"Fecha de nacimiento fuera del rango permitido"+"</p>";
            
            errors++;
            
        } //materno
       var idusuario=document.getElementById("idusuario").value;
       var area=document.getElementById("area").value;
       var distrito=document.getElementById("distrito").value;
       var correo=document.getElementById("correo").value;
       if (correo == 0)
        {
            error_string+="<p>"+"El campo correo no debe estar vacío"+"</p>";
            errors++;
            //$("#correo").focus();
            
        }//correo


      var sobrenombre=document.getElementById("sobrenombre").value;
       if(sobrenombre==""){
        errors++;
        error_string+="<p>El campo <b>seudónimo</b> no puede estar vacío</p>";

       }
       



       
       var titulo=document.getElementById("titulo").value;
       if(titulo==""){
        errors++;
        error_string+="<p>El campo <b>título</b> no puede estar vacío</p>";

       }

       var edad=document.getElementById("edad").value;

       if(edad<18){
        var tutor=document.getElementById("tutor").value;
         if(tutor==""){
          errors++;
          error_string+="<p>El campo <b>nombre de padre o tutor</b> no puede estar vacío</p>";
         }

       }else{
         var tutor="";

       }

       

      


       var tel1=document.getElementById("tel1").value;
       if(isNaN(tel1)&&tel1!=""){
        errors++;
        error_string+="<p>El <b>teléfono local</b> debe contener números únicamente</p>";
             if(tel1.toString().length<10){
              errors++;
              error_string+="<p>El <b>teléfono local</b> debe contener al menos 10 dígitos</p>";

             }

       }

       var tel2=document.getElementById("tel2").value;
       if(isNaN(tel2)||tel2==""){
        errors++;
        error_string+="<p>El <b>teléfono celular</b> debe contener números únicamente</p>";

       }
       if(tel2.toString().length<10){
        errors++;
        error_string+="<p>El <b>teléfono celular</b> debe contener al menos 10 dígitos</p>";

       }

       var alcaldia=document.getElementById("alcaldia").value;
       if(alcaldia=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>alcaldía</b>.</p>";

       }

       var entidad="";

       if(alcaldia=="18"){

        entidad=document.getElementById("entidad").value;
        if(entidad==""){
            errors++;
            error_string+="<p>Debes escribir el nombre de una <b>entidad</b></p>";
        }

     

       }

       var suma=0;

        var nombre=document.getElementById("nombre").value; // This line is unchanged
       var resido_cdmx=+document.getElementById("resido_cdmx").checked;
       var soyoriundo=+document.getElementById("soyoriundo").checked;
       var soyoriginario=+document.getElementById("soyoriginario").checked;
       var suma=resido_cdmx+soyoriundo+soyoriginario;
       if(suma==0){
        errors++;
        error_string+="<p>Debes seleccionar al menos uno de los checkbox de <b>Resido en la Ciudad de México, o Soy oriundo de la Ciudad de México o Soy hija/o de madre o padre originario de la Ciudad de México</b></p>";

       }

       var domicilio=document.getElementById("domicilio").value;
        if(document.getElementById('clave_elector')){
          var claveElectorVal = document.getElementById('clave_elector').value || '';
          if (claveElectorVal != '') {
            if (typeof validarClaveElector === 'function') {
              if (!validarClaveElector(claveElectorVal)) {
                // Mostrar SweetAlert y bloquear el envío
                try {
                  var btn = document.getElementById('btn_guardar');
                  if (btn) btn.disabled = false;
                  var mainContainer = document.getElementById('main_container');
                  if (mainContainer) mainContainer.setAttribute('style', 'pointer-events: auto;');
                } catch (e) {}

                if (typeof Swal !== 'undefined') {
                  Swal.fire({
                    icon: 'error',
                    title: 'Formato incorrecto',
                    html: 'La clave de elector debe tener 18 caracteres alfanuméricos y contener letras y números.',
                    confirmButtonColor: '#4A9FD5'
                  }).then(function() {
                    var claveElectorEl = document.getElementById('clave_elector'); if (claveElectorEl) claveElectorEl.focus();
                  });
                } else {
                  alert('La clave de elector debe tener 18 caracteres alfanuméricos y contener letras y números.');
                }

                return false;
              } else {
                document.getElementById('clave_elector').classList.remove('is-invalid');
              }
            }
          }
        }
       if(domicilio==""){
        //errors++;
        //error_string+="<p>El campo <b>domicilio</b> no puede estar vacío.</p>";

       }

       


       var te_enteraste=document.getElementById("te_enteraste").value;
       if(te_enteraste=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>cómo te enteraste</b>.</p>";

       }

     



      

        if(errors>0){

          
            document.getElementById('div_errors').innerHTML='<div class="alert alert-warning">'+error_string+'</div>';
          //  document.getElementById("btn_guardar").disabled=false;
          



              $("html, body").animate({ scrollTop: 0 }, "slow");
           
          


        }else{

            document.getElementById('div_errors').innerHTML='';
            


            $("#menu1").removeClass("active");

            $("#navmenu2").removeClass("disabled");
            $("#menu2").addClass("active");

        }



}

function evaluarCorreoRepetido(){

  var datos="mail="+document.getElementById('correo').value;

  //var repetido="x";
  

  var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //repetido= this.responseText;
                document.getElementById('correo_repetido').innerHTML=this.responseText;
               
                //alert("evaluar");


              
                



            }
        };
    
    xmlhttp.open("POST", "evaluarcorreorepetido.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
    xmlhttp.send(datos);
     //return repetido;

    

      


}


function guardarparticipantedistrito(){

       errors=0;
         error_string="";
   
     
      
      var categoria=document.getElementById("categoria").value;

      var nombre=document.getElementById("nombre").value;

      var nombre=document.getElementById("nombre").value;
      if(nombre==""){
        errors++;
        error_string+="<p>El campo <b>nombre</b> no puede estar vacío</p>";

      }


       var paterno=document.getElementById("paterno").value;

       if(paterno==""){
        errors++;
        error_string+="<p>El campo <b>primer apellido</b> no puede estar vacío</p>";

      }
       var materno=document.getElementById("materno").value;
       if(materno==""){
        errors++;
        error_string+="<p>El campo <b>segundo apellido</b> no puede estar vacío</p>";

      }
       var genero=document.getElementById("genero").value;
       if(genero==""){
        errors++;
        error_string+="<p> Debes seleccionar una opción en <b>género</b></p>";

      }
       var fecha_nacimiento=document.getElementById("fecha_nacimiento").value;

       if (edad_califica == 0 ||edad_califica == '0')
        {
            error_string+="<p>"+"Fecha de nacimiento fuera del rango permitido"+"</p>";
            
            errors++;
            
        } //materno
       var idusuario=document.getElementById("idusuario").value;
       var area=document.getElementById("area").value;
       var distrito=document.getElementById("distrito").value;
       var correo=document.getElementById("correo").value;
       if (correo == 0)
        {
            error_string+="<p>"+"El campo correo no debe estar vacío"+"</p>";
            errors++;
            //$("#correo").focus();
            
        }//correo


      var sobrenombre=document.getElementById("sobrenombre").value;
       if(sobrenombre==""){
        errors++;
        error_string+="<p>El campo <b>seudónimo</b> no puede estar vacío</p>";

       }

        var nombre_obra=document.getElementById("nombre_obra").value;
        if(nombre_obra==""){
         errors++;
         error_string+="<p>El campo <b>obra, obras o tomo a la que interpela</b> no puede estar vacío</p>";

        }
       

       
       var titulo=document.getElementById("titulo").value;
       if(titulo==""){
        errors++;
        error_string+="<p>El campo <b>título</b> no puede estar vacío</p>";

       }

       var edad=document.getElementById("edad").value;

       if(edad<18){
        var tutor=document.getElementById("tutor").value;
         if(tutor==""){
          errors++;
          error_string+="<p>El campo <b>nombre de padre o tutor</b> no puede estar vacío</p>";
         }

       }else{
         var tutor="";

       }

       

      


       var tel1=document.getElementById("tel1").value;
       if(isNaN(tel1)&&tel1!=""){
        errors++;
        error_string+="<p>El <b>teléfono local</b> debe contener números únicamente</p>";

        if(tel1.toString().length<10){
              errors++;
              error_string+="<p>El <b>teléfono local</b> debe contener al menos 10 dígitos</p>";

        }

       }

       var tel2=document.getElementById("tel2").value;
       if(isNaN(tel2)||tel2==""){
        errors++;
        error_string+="<p>El <b>teléfono celular</b> debe contener números únicamente</p>";

       }

       var alcaldia=document.getElementById("alcaldia").value;
       if(alcaldia=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>alcaldía</b>.</p>";

       }

       var entidad="";

       if(alcaldia=="18"){

        entidad=document.getElementById("entidad").value;
        if(entidad==""){
            errors++;
            error_string+="<p>Debes escribir el nombre de una <b>entidad</b></p>";
        }

     

       }

       var suma=0;


       var resido_cdmx=+document.getElementById("resido_cdmx").checked;
       var soyoriundo=+document.getElementById("soyoriundo").checked;
       var soyoriginario=+document.getElementById("soyoriginario").checked;
       var suma=resido_cdmx+soyoriundo+soyoriginario;
       if(suma==0){
        errors++;
        error_string+="<p>Debes seleccionar al menos uno de los checkbox de <b>Resido en la Ciudad de México, o Soy oriundo de la Ciudad de México o Soy hija/o de madre o padre originario de la Ciudad de México</b></p>";

       }

       var domicilio=document.getElementById("domicilio").value;
       if(domicilio==""){
        //errors++;
       // error_string+="<p>El campo <b>domicilio</b> no puede estar vacío.</p>";

       }

       


       var te_enteraste=document.getElementById("te_enteraste").value;
       if(te_enteraste=="0"){
        errors++;
        error_string+="<p>Debes seleccionar una opción en el campo <b>cómo te enteraste</b>.</p>";

       }

       
       

         //////////////
        var ensayo= +document.getElementById("ensayo").checked;
        if(ensayo==0){

            error_string+="<p>"+"Falta presentar el documento correspondiente al ensayo "+"</p>";
            errors++;

        }
        var identificacion= +document.getElementById("identificacion").checked;
        if(identificacion==0){

            error_string+="<p>"+"Falta presentar el documento correspondiente a la identificacion "+"</p>";
            errors++;

        }
       
        var manifestacion= +document.getElementById("manifestacion").checked;
        if(manifestacion==0){

            error_string+="<p>"+"Falta presentar el documento correspondiente a la manifestacion "+"</p>";
            errors++;

        }
        var carta= +document.getElementById("carta").checked;
        if(carta==0){

            error_string+="<p>"+"Falta presentar el documento correspondiente a la carta "+"</p>";
            errors++;

        }
        var formato= +document.getElementById("formato").checked;
        if(formato==0){

            error_string+="<p>"+"Falta presentar el documento correspondiente al formato "+"</p>";
            errors++;

        }

       
       

       ////////////




      

        if(errors>0){

           // alert(document.getElementById('div_errors').innerHTML);
            //document.getElementById("div_errors").innerHTML="xxxxxxxxx";
            document.getElementById('div_errors').innerHTML='<div class="alert alert-warning">'+error_string+'</div>';
            document.getElementById("btn_guardar").disabled=false;
             $("html, body").animate({ scrollTop: 0 }, "slow");
            return false;


        }

       
               
    var datos="action=insert"+"&nombre="+nombre+"&paterno="+paterno+"&materno="+materno+"&sobrenombre="+sobrenombre+"&genero="+genero+"&correo="+correo;
    datos+="&fecha_nacimiento="+fecha_nacimiento+"&idusuario="+idusuario+"&area="+area;
    datos+="&titulo="+titulo+"&categoria="+categoria+"&resido_cdmx="+resido_cdmx+"&soyoriundo="+soyoriundo+"&soyoriginario="+soyoriginario;
    datos+="&tel1="+tel1+"&tel2="+tel2+"&alcaldia="+alcaldia+"&entidad="+entidad+"&domicilio="+domicilio;
    datos+="&te_enteraste="+te_enteraste+"&distrito="+distrito;
    //document.getElementById("menu1").innerHTML = datos;
    //alert(datos);
    var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("div_registro").innerHTML = this.responseText;
                document.getElementById('div_errors').innerHTML='';
                



            }
        };
    
    xmlhttp.open("POST", "guardarparticipantedistrito.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
    xmlhttp.send(datos);

}







/*function rec_con(){

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("container").innerHTML = this.responseText;


            }
        };


        
        xmlhttp.open("POST", "rec_con.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
        

       var user=document.getElementById("user").value;
       var correo=document.getElementById("correo").value;
       var pass1=document.getElementById("pass1").value;

    
    
        if (user == 0)
        //alert("Hola!!");
        {
            alert("El campo usuario no debe estar vacío!!");
            $("#user").focus();
            return false;
        }//user
        
        if (correo == 0)
        {
            alert("El campo correo no debe estar vacío!!");
            $("#correo").focus();
            return false;
        }//correo

        if (/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,4})$/.test(correo)){
            //alert("La dirección de email es correcta.");
        } else {
            alert("La dirección de email es incorrecta.");
                return false;
        }

        if (pass1 == 0)
        {
            alert("El campo contraseña no debe estar vacío!!");
            $("#pass1").focus();
            return false;
        }//pass1
        
                    //alert("hola!!");

               
        // var datos="action=update&user="+user+"&correo="+correo+"&pass1="+pass1;
      var datos="action=update"+"&user="+user+"&correo="+correo+"&pass1="+pass1;
      
                    //alert("hola!!");
           
       //var datos="action=insert"+"&nombre="+nombre+"&user="+user+"&pass1="+"&area="+area+"&correo="+correo;

        xmlhttp.send(datos);
  
        alert ("Contraseña actualiza correctamente!!");
}*/




function validar(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-z\s]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

function validar2(e) { // 1
    tecla = (document.all) ? e.keyCode : e.which; // 2
    if (tecla==8) return true; // 3
    patron =/[A-Za-z\d]/; // 4
    te = String.fromCharCode(tecla); // 5
    return patron.test(te); // 6
}

// Permite letras, espacios y guiones medios (para paterno y materno)
function validarConGuiones(e) {
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla==8) return true; // Permite backspace
    patron = /[A-Za-z\s\-]/; // Letras, espacios y guiones
    te = String.fromCharCode(tecla);
    return patron.test(te);
}






function fnUpload(id,index){

          // alert("suuub");
            

            //document.getElementById("upload-button").innerHTML = 'Uploading...';

        //alert("file-select-"+index);

         var files =  document.getElementById("file-select-"+index).files[0];

         document.getElementById("file-select-"+index).value="";
   
         //var filesQueja = document.getElementById("file-quejaini")
         var diverrormsg_="div_errors";

            if(files!=undefined){

                var vsize=files.size;

                var servermaxsize=4000000;//document.getElementById("maxsize_frm").value;

                var maxsize_mb=((servermaxsize/1000)/1000).toFixed(1);


                if(vsize>servermaxsize){
                     //document.getElementById('upload-button').disabled=false;
                      $("#"+diverrormsg_).html("<div class='alert alert-warning'>El archivo excede el tamaño permitido: "+maxsize_mb+" MB <button type='button' class='close' data-dismiss='alert'>&times;</button></div>");
                       $("html, body").animate({ scrollTop: 0 }, "slow");

                    return false;
                }

                


            }


            
            

            

            
            var formData = new FormData();
           

            formData.append('file-select',files);
            formData.append('idusuario',id);     
            formData.append('index',index); 
            
            var textloader='<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
            document.getElementById("row-"+index).innerHTML = textloader;
/*
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                     fnUpdateRow(id,index);
                     document.getElementById(diverrormsg_).innerHTML = this.responseText;
                     
                    



                }
            };
    
            xmlhttp.open("POST", "upload.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded; charset=UTF-8"); 
            xmlhttp.send(formData);
            
*/



         var xhr = new XMLHttpRequest();

            xhr.open('POST', 'upload.php', true);


            xhr.onload = function () {
              if (xhr.status === 200) {
                // File(s) uploaded.
                //document.getElementById("upload-button").innerHTML = 'Uploaded!';

                //document.getElementById(divfileid).innerHTML = this.responseText;
                
                document.getElementById(diverrormsg_).innerHTML = this.responseText;
                fnUpdateRow(id,index);
                fnUpdateMensaje(id);
                 $("html, body").animate({ scrollTop: 0 }, "slow");

              } else {
                //alert('An error occurred!');
              }
            };

            xhr.send(formData);
            /**/
            


}

function fnUpdateRow(id,index){
  var divrow="row-"+index;

  var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById('div_errors').innerHTML = this.responseText;
                document.getElementById(divrow).innerHTML = this.responseText;
                //$('#select_colonia').append = this.responseText;
              
            }
        };

        //alert(usuario+" / "+convocatoria);

        
        var parametros="idusuario="+id+"&index="+index;
        xmlhttp.open("POST", "updaterow.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xmlhttp.send(parametros);
}

function fnUpdateMensaje(id){
  

  var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById('div_errors').innerHTML = this.responseText;
                document.getElementById("mensajeadjuntos").innerHTML = this.responseText;
                //$('#select_colonia').append = this.responseText;
              
            }
        };

        //alert(usuario+" / "+convocatoria);

        
        var parametros="idusuario="+id;
        xmlhttp.open("POST", "mensajeadjuntos.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xmlhttp.send(parametros);
}

function fnEdad(){

        var fecha_nacimiento=document.getElementById("fecha_nacimiento").value;

        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById('div_errors').innerHTML = this.responseText;

                var resultado=JSON.parse(this.responseText);
               
                document.getElementById("erroredad").innerHTML = resultado.mensaje;
                document.getElementById("edad_califica").value=resultado.califica;
                document.getElementById("edad").value=resultado.edad;
                if(resultado.edad>=15&&resultado.edad<=17){
                  document.getElementById("categoria").value=1;

                }
                if(resultado.edad>=18&&resultado.edad<=23){
                  document.getElementById("categoria").value=2;

                }

                

               
                //$('#select_colonia').append = this.responseText;
              
            }
        };

        //alert(usuario+" / "+convocatoria);

        
        var parametros="fecha_nacimiento="+fecha_nacimiento;
        xmlhttp.open("POST", "calculoedad.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xmlhttp.send(parametros);

        



}

function fnReenviarCorreo(id){

        var correo="";// document.getElementById("correo").value;

        
        //var correo="x";

        var myid=id;
       

        if(myid=='0'){
             
            correo=document.getElementById("correo").value;

        }else{

            correo="";

        }

        var textloader='<div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>';
        document.getElementById("divcorreo").innerHTML = textloader;

        //alert(correo);


        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                
                document.getElementById("divcorreo").innerHTML = this.responseText;

                
               
                //$('#select_colonia').append = this.responseText;
              
            }
        };

        //alert(usuario+" / "+convocatoria);

        
        var parametros="id="+id+"&correo="+correo;
        xmlhttp.open("POST", "reenviarcorreo.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        
        xmlhttp.send(parametros);


}
        
    
////////////////////////////////////////////////////////////validación

 function fnRadioBusqueda(){
          var num=$("input[type='radio'][name='radio_busqueda']:checked").val()
 

           switch(num){
            case '1':  
            document.getElementById('select_nombre').disabled=false;
            document.getElementById('btn_nombre').disabled=false;


            document.getElementById('select_distrito').disabled=true;
             //document.getElementById('btn_distrito').disabled=true;

            document.getElementById('select_folio').disabled=true;
             document.getElementById('btn_folio').disabled=true;
            break;

            case '2':  
            document.getElementById('select_folio').disabled=false;
            document.getElementById('btn_folio').disabled=false;

            document.getElementById('select_nombre').disabled=true;
            document.getElementById('btn_nombre').disabled=true; 
            document.getElementById('select_distrito').disabled=true;
            //document.getElementById('btn_distrito').disabled=true;

            break;

            case '3':  
            document.getElementById('select_distrito').disabled=false;
            document.getElementById('btn_todos').disabled=false; 
                   //document.getElementById('btn_distrito').disabled=false;

            document.getElementById('select_folio').disabled=true; 
    
            document.getElementById('select_nombre').disabled=true;
           // document.getElementById('btn_nombre').disabled=true;
               // document.getElementById('select_colonia').disabled=true;
               break;
            
          }

 }

////    /////////////////////////

function fnBusquedaFolio(){

  //alert ("entro funcion folio");
  
  var val=document.getElementById('select_folio').value;

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader; 

  var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
                    //document.getElementById('div_errors').innerHTML = this.responseText;
                    document.getElementById('menu_').innerHTML = this.responseText;
                    //$('#select_colonia').append = this.responseText;

        }
    };


        var parametros="accion=folio&val="+val;
        xmlhttp.open("POST", "validacionlistado.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        xmlhttp.send(parametros);

}

/////////// funcion de busqueda para el nombre 
function fnBusquedaNombre(){

 // alert ("entro funcion Nombre");
  
  var val=document.getElementById('select_nombre').value;

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader; 

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
                //document.getElementById('div_errors').innerHTML = this.responseText;
                document.getElementById('menu_').innerHTML = this.responseText;
                //$('#select_colonia').append = this.responseText;

              }
            };


            var parametros="accion=nombre&val="+val;
            xmlhttp.open("POST", "validacionlistado.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.send(parametros);

          }

// Función para mostrar todos los registros
function fnBusquedaTodos(){

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader; 

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
                document.getElementById('menu_').innerHTML = this.responseText;
              }
            };

            var parametros="accion=todos&val=";
            xmlhttp.open("POST", "validacionlistado.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.send(parametros);

          }

///////////distrito ////////////

function fnBusquedaDistrito(){

 // alert ("entro funcion disrito");
  
  var val=document.getElementById('select_distrito').value;

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader; 

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
                //document.getElementById('div_errors').innerHTML = this.responseText;
                document.getElementById('menu_').innerHTML = this.responseText;
                //$('#select_colonia').append = this.responseText;

              }
            };


            var parametros="accion=distrito&val="+val;
            xmlhttp.open("POST", "validacionlistado.php", true);
            xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

            xmlhttp.send(parametros);

          }
function fnValidar(id,cat){

  

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader; 

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('menu_').innerHTML = this.responseText;
    }
  };

  var parametros="id="+id+"&categoria="+cat;
  xmlhttp.open("POST", "g_validaciondocumentos.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xmlhttp.send(parametros);

}

function fnguardarvalidaciones(btn){

  // Deshabilitar el botón inmediatamente
  // Deshabilitar el botón inmediatamente
  var btnLabel = btn ? btn.innerHTML : '';
  if (btn) {
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
  }

  var id= document.getElementById("id").value;
  var categoria = document.getElementById("categoria").value;

  var estatus_ensayo = document.getElementById("select_estatus_ensayo").value;
  var observa_ensayo = document.getElementById("observa_ensayo").value;
 
    
// var estatus_identifica = document.getElementById("select_estatus_identifica").value;
  //var observa_identifica = document.getElementById("observa_identifica").value;
  
  var observa_requi = document.getElementById("observa_requi").value;
  
  errors=0;
/////////////////

  if(errors>0){
    document.getElementById('div_errors').innerHTML='<div class="alert alert-warning">'+error_string+'</div>';
    if (btn) { btn.disabled=false; btn.innerHTML=btnLabel; }
    return false;
  }

    
  var datos="action=update"+"&id="+id+"&categoria="+categoria+"&estatus_ensayo="+estatus_ensayo+"&observa_ensayo="+observa_ensayo;
  datos+="&observa_requi="+observa_requi;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      var resp = this.responseText.trim();
      var data = null;
      try { data = JSON.parse(resp); } catch(e) { data = null; }

      if (data && data.ok === true) {
        // ✓ Correcto: botón queda deshabilitado permanentemente
        if (btn) {
          btn.disabled = true;
          btn.innerHTML = '<i class="fas fa-check-circle"></i> Validado';
          btn.style.background = 'linear-gradient(135deg,#065f46,#059669)';
        }
        Swal.fire({
          icon: 'success',
          title: '¡Folio asignado!',
          html: 'El registro de <strong>' + data.nombre + '</strong> fue validado correctamente.<br><br>' +
                'Folio asignado: <code style="font-size:1.1rem;color:#4A9FD5;font-weight:700;">' + data.folio + '</code>',
          confirmButtonColor: '#4A9FD5',
          confirmButtonText: 'Aceptar'
        }).then(function() { fnBusquedaTodos(); });

      } else if (data && data.ok === false) {
        // ✗ Incorrecto: re-habilitar botón para poder corregir
        if (btn) { btn.disabled = false; btn.innerHTML = btnLabel; }
        Swal.fire({
          icon: 'warning',
          title: 'Ensayo marcado como incorrecto',
          html: 'Se notificó a <strong>' + data.nombre + '</strong> por correo electrónico ' +
                'que su ensayo presentó observaciones.<br><span style="font-size:.85rem;color:#6b7280;">El participante podrá corregir y volver a enviar su documento.</span>',
          confirmButtonColor: '#4A9FD5',
          confirmButtonText: 'Entendido'
        }).then(function() { fnBusquedaTodos(); });

      } else {
        // Error inesperado: re-habilitar botón
        if (btn) { btn.disabled = false; btn.innerHTML = btnLabel; }
        Swal.fire({
          icon: 'error',
          title: 'Error al guardar',
          text: 'Ocurrió un error inesperado. Intenta de nuevo.',
          confirmButtonColor: '#4A9FD5'
        });
      }
    } else if (this.readyState == 4) {
      // Error de red
      if (btn) { btn.disabled = false; btn.innerHTML = btnLabel; }
      Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'No se pudo contactar el servidor.', confirmButtonColor: '#4A9FD5' });
    }
  };

  //var parametros="action=update&id="+id;
  xmlhttp.open("POST", "g_validaciondocumentos.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

  xmlhttp.send(datos);

}

////////////////////viene de archivo datospersonales.php
function fnSelectEntidad(){
  var alcaldia=document.getElementById('alcaldia').value;
  if(alcaldia==18){
    document.getElementById('entidad').disabled=false;
    document.getElementById('bloqueentidad').style.display='block';
  }else{
    document.getElementById('bloqueentidad').style.display='none';
    document.getElementById('entidad').disabled=true;
    document.getElementById('entidad').value="";

  }
}

//////////////////// funciones perfil juez ////////////////////
function fnRadioBusquedaJuez(){
  var radio = document.getElementsByName('radio_busqueda');
  var opcion = '1';

  for (var i = 0; i < radio.length; i++) {
    if (radio[i].checked) {
      opcion = radio[i].value;
      break;
    }
  }

  var inputNombre = document.getElementById('select_nombre');
  var btnNombre = document.getElementById('btn_nombre');
  var inputFolio = document.getElementById('select_folio');
  var btnFolio = document.getElementById('btn_folio');

  if (inputNombre && btnNombre) {
    inputNombre.disabled = opcion !== '1';
    btnNombre.disabled = opcion !== '1';
  }

  if (inputFolio && btnFolio) {
    inputFolio.disabled = opcion !== '2';
    btnFolio.disabled = opcion !== '2';
  }
}

function fnBusquedaNombreJuez(){
  var input = document.getElementById('select_nombre');
  var val = input ? input.value : '';

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('menu_').innerHTML = this.responseText;
    }
  };

  var parametros="accion=nombre&val="+encodeURIComponent(val);
  xmlhttp.open("POST", "validacionlistado_juez.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(parametros);
}

function fnBusquedaFolioJuez(){
  var input = document.getElementById('select_folio');
  var val = input ? input.value : '';

  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('menu_').innerHTML = this.responseText;
    }
  };

  var parametros="accion=folio&val="+encodeURIComponent(val);
  xmlhttp.open("POST", "validacionlistado_juez.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(parametros);
}

function fnBusquedaTodosJuez(){
  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('menu_').innerHTML = this.responseText;
    }
  };

  var parametros="accion=todos&val=";
  xmlhttp.open("POST", "validacionlistado_juez.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(parametros);
}

function fnCalificarEnsayoJuez(id, cat){
  var textloader='<div class="row"><div class="loader"></div></div>';
  document.getElementById('menu_').innerHTML = textloader;

  var xmlhttp = new XMLHttpRequest();
  xmlhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
      document.getElementById('menu_').innerHTML = this.responseText;
      fnCalcularTotalCalificacionJuez();
    }
  };

  var parametros="id="+id+"&categoria="+cat;
  xmlhttp.open("POST", "g_calificacion_juez.php", true);
  xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
  xmlhttp.send(parametros);
}

function fnCalcularTotalCalificacionJuez(){
  var ids = ['califica1', 'califica2', 'califica3', 'califica4', 'califica5', 'califica6'];
  var suma = 0;

  for (var i = 0; i < ids.length; i++) {
    var el = document.getElementById(ids[i]);
    if (el) {
      suma += parseFloat(el.value || 0);
    }
  }

  var promedio = suma / ids.length;
  var total = document.getElementById('total_calificacion');
  if (total) {
    total.value = suma.toFixed(1);
  }
}

function fnGuardarCalificacionJuez(btn){
  var idensayo = document.getElementById('idensayo_cal').value;
  var categoria = document.getElementById('categoria_cal').value;
  var cal1 = document.getElementById('califica1').value;
  var cal2 = document.getElementById('califica2').value;
  var cal3 = document.getElementById('califica3').value;
  var cal4 = document.getElementById('califica4').value;
  var cal5 = document.getElementById('califica5').value;
  var cal6 = document.getElementById('califica6').value;
  var observaciones = document.getElementById('observaciones_gral_cal').value;

  var total = (parseFloat(cal1 || 0)
    + parseFloat(cal2 || 0)
    + parseFloat(cal3 || 0)
    + parseFloat(cal4 || 0)
    + parseFloat(cal5 || 0)
    + parseFloat(cal6 || 0)).toFixed(1);

  Swal.fire({
    icon: 'warning',
    title: 'Confirma la calificacion',
    html: 'La calificacion total es <strong>' + total + '</strong>.<br>Es correcta?',
    showCancelButton: true,
    confirmButtonText: 'Si es correcta',
    cancelButtonText: 'No es correcta',
    confirmButtonColor: '#4A9FD5',
    cancelButtonColor: '#bb0808'
  }).then(function(result){
    if (!result.isConfirmed) {
      return;
    }

    var btnLabel = btn ? btn.innerHTML : '';
    if (btn) {
      btn.disabled = true;
      btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Guardando...';
    }

    var datos = "action=save"
      + "&idensayo=" + encodeURIComponent(idensayo)
      + "&categoria=" + encodeURIComponent(categoria)
      + "&califica1=" + encodeURIComponent(cal1)
      + "&califica2=" + encodeURIComponent(cal2)
      + "&califica3=" + encodeURIComponent(cal3)
      + "&califica4=" + encodeURIComponent(cal4)
      + "&califica5=" + encodeURIComponent(cal5)
      + "&califica6=" + encodeURIComponent(cal6)
      + "&observaciones_gral=" + encodeURIComponent(observaciones);

    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4) {
        if (btn) { btn.disabled = false; btn.innerHTML = btnLabel; }

        if (this.status == 200) {
          var data = null;
          try { data = JSON.parse(this.responseText); } catch (e) { data = null; }

          if (data && data.ok === true) {
            Swal.fire({
              icon: 'success',
              title: 'Calificacion guardada',
              text: 'La calificacion del ensayo se guardo correctamente.',
              confirmButtonColor: '#4A9FD5'
            }).then(function(){
              // Reemplazar el botón por badge verde y deshabilitar controles de calificación
              try{
                var badge = document.createElement('span');
                badge.className = 'badge-val badge-val--ok';
                badge.style.display = 'inline-block';
                badge.style.marginTop = '6px';
                badge.innerHTML = '<i class="fas fa-check-circle"></i> Ensayo calificado';

                var btnArea = btn.parentNode;
                if (btnArea) {
                  // reemplazar el botón por el badge
                  btnArea.innerHTML = '';
                  btnArea.appendChild(badge);
                }

                // deshabilitar selects y textarea
                var ids = ['califica1','califica2','califica3','califica4','califica5','califica6','observaciones_gral_cal'];
                ids.forEach(function(id){
                  var el = document.getElementById(id);
                  if (el) el.disabled = true;
                });
              }catch(e){
                // fallback: recargar lista si algo falla
                fnBusquedaTodosJuez();
              }
            });
          } else {
            Swal.fire({
              icon: 'error', 
              title: 'No se pudo guardar',
              text: (data && data.msg) ? data.msg : 'Ocurrio un error al guardar la calificacion.',
              confirmButtonColor: '#4A9FD5'
            });
          }
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error de conexion',
            text: 'No se pudo contactar al servidor.',
            confirmButtonColor: '#4A9FD5'
          });
        }
      }
    };

    xmlhttp.open("POST", "g_calificacion_juez.php", true);
    xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xmlhttp.send(datos);
  });
}

// Asignar juez desde el listado (admin central)
function fnAsignarJuez(idensayo, selectEl){
  if(!selectEl) return;
  var idjuez = selectEl.value ? parseInt(selectEl.value) : 0;
  var nombre = selectEl.getAttribute('data-nombre') || '';
  var prev = selectEl.getAttribute('data-prev');
  prev = prev !== null ? parseInt(prev) : 0;

  if(!idjuez){
    // Si se seleccionó vacío, no hacemos nada (podría implementarse desasignación)
    return;
  }

  Swal.fire({
    title: 'Asignar juez',
    text: '¿Estás seguro de asignar este juez al participante "' + nombre + '"?',
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Aceptar',
    cancelButtonText: 'Cancelar',
    confirmButtonColor: '#4A9FD5'
  }).then(function(result){
    if(result.isConfirmed){
      var datos = 'id=' + encodeURIComponent(idensayo) + '&idjuez=' + encodeURIComponent(idjuez);
      var xhr = new XMLHttpRequest();
      xhr.onreadystatechange = function(){
        if(this.readyState == 4){
          if(this.status == 200){
            try{
              var resp = JSON.parse(this.responseText);
              if(resp.ok){
                // actualizar data-prev para futuras comparaciones
                selectEl.setAttribute('data-prev', idjuez);
                selectEl.disabled = true;
                // Mostrar SweetAlert y, al cerrarlo, actualizar la interfaz para mostrar el badge
                Swal.fire({icon:'success', title:'Asignación guardada', text: resp.msg || ''}).then(function(){
                  try{
                    var badge = document.createElement('span');
                    badge.className = 'badge-val badge-val--ok';
                    badge.style.display = 'inline-block';
                    badge.style.marginTop = '6px';
                    badge.innerHTML = '<i class="fas fa-gavel"></i> Jurado asignado';
                    var parent = selectEl.parentNode;
                    if(parent){
                      parent.innerHTML = '';
                      parent.appendChild(badge);
                    } else {
                      // fallback: disable the select if parent not found
                      selectEl.disabled = true;
                    }
                  }catch(e){
                    // en caso de error, dejar el select deshabilitado
                    selectEl.disabled = true;
                  }
                });
              } else {
                // restaurar valor previo
                selectEl.value = prev;
                Swal.fire({icon:'error', title:'No se pudo asignar', text: resp.msg || 'El juez puede tener ya 5 asignaciones.'});
              }
            }catch(e){
              selectEl.value = prev;
              Swal.fire({icon:'error', title:'Respuesta inválida', text:'Respuesta inesperada del servidor.'});
            }
          } else {
            selectEl.value = prev;
            Swal.fire({icon:'error', title:'Error de conexión', text:'No se pudo contactar el servidor.'});
          }
        }
      };
      xhr.open('POST','assign_juez.php',true);
      xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
      xhr.send(datos);
    } else {
      // usuario canceló, restaurar valor previo
      selectEl.value = prev;
    }
  });

}
