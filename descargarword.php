<?php

	 $full_path=base64_decode($_GET['f']);//"documentos_concursos/grafiti/mayor/3_declaracion.docx";

	 	header('Content-Description: File Transfer');
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment; filename='.$full_path.'');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));
        //ob_clean();
        //flush();
        readfile($full_path);
        //exit(); 

	
  






?>
