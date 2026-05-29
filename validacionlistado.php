<?php
	
include 'sqlconnector.php';
session_start();
$idusuario=$_SESSION["idusuario"];
	
error_reporting(0);

	
$accion = $_POST['accion'];
$val = trim($_POST['val']);

if($accion == "folio"){
	$search_val = "%{$val}%";
	$query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito, 
              A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, U.usuario AS usr_validador
	          FROM ".BD_PARTICIPANTES." as A 
	          INNER JOIN ".BD_USUARIOS." as B ON A.idusuario = B.idusuario 
	          LEFT JOIN ".BD_USUARIOS." as U ON A.status_requisitos = U.idusuario
	          WHERE A.folio like ?";
	$params = array($search_val);
}

if($accion == "nombre"){
	$search_val = "%{$val}%";
	$query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito,
              A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, concat(U.nombre,' ', U.paterno,' ',U.materno) AS usr_validador
	          FROM ".BD_PARTICIPANTES." as A 
	          INNER JOIN ".BD_USUARIOS." as B ON A.idusuario = B.idusuario 
	          LEFT JOIN ".BD_USUARIOS." as U ON A.status_requisitos = U.idusuario
	          WHERE A.sobrenombre like ? OR A.nombre like ? OR A.paterno like ?";
	$params = array($search_val, $search_val, $search_val);
}

if($accion == "todos"){
    $query = "SELECT A.*, B.fecha_nacimiento, B.usuario, B.iddistrito, A.idjuez_asignado,
              U.nombre AS nom_validador, U.paterno AS pat_validador, concat(U.nombre,' ', U.paterno,' ',U.materno) AS usr_validador
              FROM participantes  as A 
              INNER JOIN ".BD_USUARIOS." as B ON A.idusuario = B.idusuario 
              LEFT JOIN ".BD_USUARIOS." as U ON A.status_requisitos = U.idusuario
              ORDER BY A.fecha_alta DESC";
	$params = array();
}


   // echo " * ".$query." * ";
    
    // Obtener lista de jueces (TOP 10 perfil=3)
    $judges = array();
    $jq = "SELECT TOP 10 idusuario, nombre, paterno, usuario FROM ".BD_USUARIOS." WHERE perfil = 3 ORDER BY nombre";
    $rj = sqlsrv_query($conn, $jq);
    if ($rj) {
        while ($jr = sqlsrv_fetch_array($rj)) {
            $judges[] = $jr;
        }
    }

    $res = sqlsrv_query($conn, $query, $params);

    $rows_buffer = '';
    $count = 0;

    while($res && ($row = sqlsrv_fetch_array($res))) {
        $nombre_completo = trim($row["nombre"] . ' ' . $row["paterno"] . ' ' . $row["materno"]);
        $sobrenombre = htmlspecialchars($row["sobrenombre"] ?? '—');
        $folio = htmlspecialchars($row["folio"] ?? '—');
        $usuario = htmlspecialchars($row["usuario"] ?? '');
        $idensayo = intval($row["idensayo"]);
        $categoria = intval($row["categoria"]);
        
        $ensayo = $row["ensayo"];
        $status_ensayo = $row["status_ensayo"];
       
        
        $status_requisitos = $row["status_requisitos"];
        
        // Determinar validador
        $nom_v = trim(($row['nom_validador'] ?? '') . ' ' . ($row['pat_validador'] ?? ''));
        $validador_str = $nom_v !== '' ? $nom_v : ($row['usr_validador'] ?? '—');
        
        // Determinar badge de documento
        if ($status_ensayo == 1 ) {
            $doc_badge = '<span class="badge-val badge-val--ok"><i class="fas fa-check-circle"></i> Validado</span>';

        } elseif ($ensayo != null && $status_ensayo == 0) {
            $doc_badge = '<button class="btn-val-action" onclick="fnValidar(' . $idensayo . ',' . $categoria . ')"><i class="fas fa-clock"></i> Por validar</button>';
        } else {
            $doc_badge = '<span class="badge-val badge-val--none"><i class="fas fa-times-circle"></i> Sin documentación</span>';
        }
        
        $count++;
        // Construir select de jueces: solo se habilita cuando el documento está en "Validado" (status_ensayo=1)
        $assigned = intval($row['idjuez_asignado'] ?? 0);

        $puedeAsignar = ($status_ensayo == 1 && !empty($ensayo));

        $selectDisabled = ($assigned > 0 || !$puedeAsignar) ? ' disabled' : '';
        $select = '<select class="vd-select" data-nombre="' . htmlspecialchars($nombre_completo, ENT_QUOTES) . '" data-prev="' . $assigned . '" onchange="fnAsignarJuez(' . $idensayo . ', this)"' . $selectDisabled . '>';
        $select .= '<option value="">--</option>';
        
        foreach ($judges as $jj) {
            $jid = intval($jj['idusuario']);
            $jname = trim(($jj['nombre'] ?? '') . ' ' . ($jj['paterno'] ?? '') . ' (' . ($jj['usuario'] ?? '') . ')');
            $sel = ($jid === $assigned) ? ' selected' : '';
            $select .= '<option value="' . $jid . '"' . $sel . '>' . htmlspecialchars($jname, ENT_QUOTES) . '</option>';
        }
        $select .= '</select>';

        if ($assigned > 0) {
            $select .= '<span class="badge-val badge-val--ok" style="display:inline-block;margin-top:6px;"><i class="fas fa-gavel"></i> Jurado asignado</span>';
        } elseif (!$puedeAsignar) {
            $select .= '<span class="badge-val badge-val--none" style="display:inline-block;margin-top:6px;"><i class="fas fa-lock"></i> Disponible una vez validado</span>';
        }

        $rows_buffer .= '<tr>
            <td class="td-num">' . $count . '</td>
            <td class="td-name"><span class="name-main">' . htmlspecialchars($nombre_completo) . '</span></td>
            <td class="td-pseudo">' . $sobrenombre . '</td>
            <td class="td-folio"><code>' . $folio . '</code></td>
            <td class="td-doc">' . $doc_badge . '</td>
            <td class="td-assign">' . $select . '</td>
            <td class="td-user"><span class="validator-chip">' . $validador_str . '</span></td>
        </tr>';
    }
    

    if ($count === 0) {
        echo '<div class="vl-empty"><i class="fas fa-search" style="font-size:2rem;opacity:.35;margin-bottom:10px;"></i><br>No se encontraron registros.</div>';
    } else {
        echo '<div class="vl-table-wrap">';
        echo '<div class="vl-count">Se encontraron <strong>' . $count . '</strong> registro' . ($count !== 1 ? 's' : '') . '</div>';
        echo '<div class="vl-table-scroll">';
        echo '<table class="vl-table">';
        echo '<thead><tr>
            <th class="th-num">#</th>
            <th>Nombre completo</th>
            <th>Seudónimo</th>
            <th>Folio</th>
            <th>Ensayo</th>
            <th>Asignación del jurado</th>
            <th>Validador</th>
        </tr></thead>';
        echo '<tbody>' . $rows_buffer . '</tbody>';
        echo '</table>';
        echo '</div></div>';
    }
    
    /////// aqui quite cosas////

?>