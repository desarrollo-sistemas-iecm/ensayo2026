<?php
    //$fecha_nacimiento="2001-12-31";
    
    $fecha_nacimiento=$_POST['fecha_nacimiento'];

   /* $fecha_inicio_año_concurso="2026-05-01";
    $fecha_inicio_concurso="2026-05-01";
    $fecha_fin_concurso="2026-08-08";
    $fecha_fin_año_concurso="2026-12-31";*/

    

 $años_hoy=date_diff(date_create($fecha_nacimiento), date_create('today'))->y;

   /* $años_inicio=date_diff(date_create($fecha_nacimiento), date_create($fecha_inicio_concurso))->y;
    $años_fin=date_diff(date_create($fecha_nacimiento), date_create($fecha_fin_concurso))->y;


    $años_fin_año=date_diff(date_create($fecha_nacimiento), date_create($fecha_fin_año_concurso))->y;

    $años_inicio_año=date_diff(date_create($fecha_nacimiento), date_create($fecha_inicio_año_concurso))->y;*/


   $califica=false;


   if($años_hoy <=23 && $años_hoy >= 15) {
    	
        $califica=true;
    } 


  //  echo "Edad hoy: ".$años_hoy." años. ";
//echo "Califica: ".($califica ? "Sí" : "No").". ";

        if($califica){

            $resultado=array(
                        'edad'=>$años_hoy,
                        'mensaje'=>'Tu edad hoy: '.$años_hoy.'',
                        'califica'=>'1'
                        );



        }else{


             $resultado=array(
                        'edad'=>$años_hoy,
                        'mensaje'=>'Derivado de tu fecha de nacimiento NO puedes pertenecer a ninguna de las 2 categorías establecidas en la Convocatoria.</br>
                         (Categoría 1: 15 a 17 años, categoría 2: 18 a 23 años).</p>',
                        'califica'=>'0'
                        );
             
        }

        echo json_encode($resultado);

?>