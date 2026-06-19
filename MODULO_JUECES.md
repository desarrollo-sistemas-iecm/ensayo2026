# Modulo de jueces (asignacion -> listado -> calificacion)

## Objetivo
Documentar el flujo tecnico del modulo de jueces, desde la asignacion por el perfil central hasta la calificacion final, incluyendo tablas y consultas.

## Flujo general
1) Perfil central asigna un juez a un participante con ensayo validado.
2) El juez entra a su panel, busca sus ensayos asignados y abre el formulario.
3) El juez califica y se guarda una calificacion unica por participante y juez.

## Pantallas y endpoints
- Panel de jueces (entrada al modulo): mainjueces.php
- Listado central para asignar juez (perfil central): validacionlistado.php
- Guardar asignacion (backend): assign_juez.php
- Pantalla de busqueda/listado del juez: g_validacion_juez.php
- Listado de ensayos asignados al juez (backend): validacionlistado_juez.php
- Formulario y guardado de calificacion (backend + UI): g_calificacion_juez.php
- Funciones JS del flujo: js/funcionesajax_test.js

## Reglas de negocio y estados
- Solo perfil central (perfil=2) puede asignar jueces.
- Solo perfil juez (perfil=3) puede ver y calificar.
- Asignacion permitida solo cuando:
  - participantes.status_ensayo = 1 (validado), y
  - participantes.ensayo no es nulo o vacio.
- Si participantes.status_ensayo != 1:
  - El ensayo queda "Por validar" o "Sin documentacion" y no permite asignacion.
- Si ya existe participantes.idjuez_asignado > 0:
  - La asignacion queda bloqueada y no se puede cambiar.
- Limite de carga por juez:
  - Un juez no puede tener mas de 5 participantes asignados.
- Calificacion unica:
  - No se permite que el mismo juez califique dos veces al mismo participante.

## Asignacion de juez (perfil central)
### UI/Frontend
- El select de jueces se muestra en el listado central y se habilita solo si el ensayo esta validado.
- Al confirmar, el frontend llama al endpoint de asignacion.

### Backend
Archivo: assign_juez.php

### Consultas usadas (y campos)
- Validar perfil del usuario que asigna:
  - SELECT perfil FROM usuarios WHERE idusuario = ?
  - Campos usados: usuarios.idusuario, usuarios.perfil
- Validar que el juez exista y sea perfil=3:
  - SELECT idusuario FROM usuarios WHERE idusuario = ? AND perfil = 3
  - Campos usados: usuarios.idusuario, usuarios.perfil
- Validar estado del ensayo y asignacion previa:
  - SELECT status_ensayo, ensayo, idjuez_asignado FROM participantes WHERE idensayo = ?
  - Campos usados: participantes.idensayo, participantes.status_ensayo, participantes.ensayo, participantes.idjuez_asignado
- Contar asignaciones actuales del juez:
  - SELECT COUNT(idensayo) AS cnt FROM participantes WHERE idjuez_asignado = ?
  - Campos usados: participantes.idensayo, participantes.idjuez_asignado
- Guardar asignacion:
  - UPDATE participantes SET idjuez_asignado = ? WHERE idensayo = ?
  - Campos usados: participantes.idjuez_asignado, participantes.idensayo

### Logica de habilitacion en el listado central
- El select de jueces se habilita solo cuando:
  - participantes.status_ensayo = 1, y
  - participantes.ensayo tiene archivo.
- Si participantes.idjuez_asignado > 0:
  - El select queda deshabilitado y se muestra badge de asignado.

## Listado del juez (busqueda y carga)
### UI/Frontend
- Busqueda por seudonimo o mostrar todos.
- El listado se actualiza via Ajax.

### Backend
Archivo: validacionlistado_juez.php

### Consultas usadas (y campos)
- Validar perfil juez:
  - SELECT perfil FROM usuarios WHERE idusuario = ?
  - Campos usados: usuarios.idusuario, usuarios.perfil
- Query base:
  - SELECT A.idensayo, A.idusuario, A.nombre, A.paterno, A.materno, A.sobrenombre, A.folio, A.categoria, A.ensayo, A.status_ensayo, A.idjuez_asignado,
    U.nombre AS nom_validador, U.paterno AS pat_validador, U.usuario AS usr_validador,
    C.idcalifica, C.total
    FROM participantes AS A
    INNER JOIN usuarios AS B ON A.idusuario = B.idusuario
    LEFT JOIN usuarios AS U ON A.status_requisitos = U.idusuario
    LEFT JOIN calificaciones AS C ON C.idusuario = A.idusuario
  - Joins:
    - participantes A -> usuarios B por A.idusuario = B.idusuario (identidad del participante)
    - participantes A -> usuarios U por A.status_requisitos = U.idusuario (validador)
    - participantes A -> calificaciones C por C.idusuario = A.idusuario (calificaciones previas)
  - Campos usados:
    - participantes: idensayo, idusuario, nombre, paterno, materno, sobrenombre, folio, categoria, ensayo, status_ensayo, idjuez_asignado, status_requisitos
    - usuarios B: idusuario
    - usuarios U: nombre, paterno, usuario
    - calificaciones: idcalifica, total
- Filtros segun busqueda:
  - Por folio: WHERE A.idjuez_asignado = ? AND A.folio LIKE ?
  - Por nombre/seudonimo: WHERE A.idjuez_asignado = ? AND (A.sobrenombre LIKE ? OR A.nombre LIKE ? OR A.paterno LIKE ?)
  - Todos: WHERE A.idjuez_asignado = ?
- Verificar si el juez ya califico:
  - SELECT TOP 1 idcalifica FROM calificaciones WHERE idusuario = ? AND nombre_juez = ?
  - Campos usados: calificaciones.idusuario, calificaciones.nombre_juez, calificaciones.idcalifica

## Formulario y guardado de calificacion
### UI/Frontend
- El formulario se carga via Ajax y muestra el ensayo.
- El total se calcula en el navegador y se confirma antes de guardar.

### Backend
Archivo: g_calificacion_juez.php

### Consultas usadas (y campos)
- Validar perfil juez:
  - SELECT perfil FROM usuarios WHERE idusuario = ?
  - Campos usados: usuarios.idusuario, usuarios.perfil
- Obtener datos del ensayo:
  - SELECT idusuario, nombre, paterno, materno, categoria FROM participantes WHERE idensayo = ?
  - Campos usados: participantes.idensayo, idusuario, nombre, paterno, materno, categoria
- Verificar si ya califico:
  - SELECT idcalifica FROM calificaciones WHERE idusuario = ? AND nombre_juez = ?
  - Campos usados: calificaciones.idusuario, calificaciones.nombre_juez, calificaciones.idcalifica
- Insertar calificacion:
  - INSERT INTO calificaciones (nombre_completo, categoria, nombre_juez, califica1, califica2, califica3, califica4, califica5, califica6,
    fecha_alta, fecha_modifica, observaciones_gral, total, idusuario)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
  - Campos usados: calificaciones.nombre_completo, categoria, nombre_juez, califica1..califica6, fecha_alta, fecha_modifica, observaciones_gral, total, idusuario
- Consultar calificacion previa para precarga:
  - SELECT TOP 1 * FROM calificaciones WHERE idusuario = ? AND nombre_juez = ? ORDER BY idcalifica DESC
  - Campos usados: calificaciones.idusuario, calificaciones.nombre_juez, calificaciones.idcalifica, califica1..califica6, total, observaciones_gral

## Tablas y campos principales
- usuarios
  - idusuario, perfil, usuario, nombre, paterno, materno
- participantes
  - idensayo, idusuario, nombre, paterno, materno, sobrenombre, folio, categoria, ensayo, status_ensayo,
    status_requisitos, idjuez_asignado, fecha_alta
- calificaciones
  - idcalifica, idusuario, nombre_completo, categoria, nombre_juez,
    califica1, califica2, califica3, califica4, califica5, califica6,
    total, observaciones_gral, fecha_alta, fecha_modifica
