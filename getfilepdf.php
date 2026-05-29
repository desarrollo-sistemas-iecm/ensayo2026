<?php
    header("Content-type: application/pdf");
	header("Content-Disposition: inline; filename=filename.pdf");
	//define("URL_SITE", "http://145.0.40.76/concursos2019/");
	//define("URL_SITE", "");///localhost
	include('rutasitio.php');

	define("UPLOAD_DIR", "uploads/");

	$id_64=$_GET['data'];

	$id_file=base64_decode($id_64);
	//$id_file=$id_64;
	@readfile(URL_CONCU.UPLOAD_DIR.$id_file);

?>