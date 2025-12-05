<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario. Cada input se identifica con su "name"
$codigo = isset($_POST['codigo_torneo']) ? $_POST['codigo_torneo'] : null;
$nombre = isset($_POST['nombre_oficial']) ? $_POST['nombre_oficial'] : '';
$nivel = isset($_POST['nivel_dificultad']) ? intval($_POST['nivel_dificultad']) : 0;
$tipo = isset($_POST['tipo_torneo']) ? $_POST['tipo_torneo'] : '';
$num_max = isset($_POST['numero_max_jugadores']) ? $_POST['numero_max_jugadores'] : null;
$formato = isset($_POST['formato_competicion']) ? $_POST['formato_competicion'] : '';
$fases_online = isset($_POST['fases_iniciales_online']) ? $_POST['fases_iniciales_online'] : null;


// Sanitizar antes de insertar
$codigo_s = mysqli_real_escape_string($conn, $codigo);
$nombre_s = mysqli_real_escape_string($conn, $nombre);
$nivel_s = intval($nivel);
$tipo_s = mysqli_real_escape_string($conn, $tipo);
$num_max_s = $num_max !== null ? intval($num_max) : 'NULL';
$formato_s = $formato !== '' ? "'".mysqli_real_escape_string($conn, $formato)."'" : 'NULL';
$fases_online_s = $fases_online !== null && $fases_online !== '' ? intval($fases_online) : 'NULL';

// Verificar unicidad de codigo_torneo
if($codigo_s === '' || $codigo_s === null){
	header('Location: torneo.php?error=codigo');
	exit;
}
$checkQuery = "SELECT COUNT(*) AS cnt FROM torneo WHERE codigo_torneo = '$codigo_s'";
$checkRes = mysqli_query($conn, $checkQuery) or die(mysqli_error($conn));
$row = mysqli_fetch_assoc($checkRes);
if($row && isset($row['cnt']) && intval($row['cnt']) > 0){
	// Código ya existe
	header('Location: torneo.php?error=duplicado');
	exit;
}

// Construir query (ajusta columnas según tu tabla `torneo` real)
$query = "INSERT INTO `torneo` (`codigo_torneo`,`nombre_oficial`,`nivel_dificultad`,`tipo_torneo`,`numero_max_jugadores`,`formato_competicion`,`fases_iniciales_online`) VALUES ('$codigo_s','$nombre_s',$nivel_s,'$tipo_s',".($num_max_s === 'NULL' ? 'NULL' : $num_max_s).",".$formato_s.",".($fases_online_s === 'NULL' ? 'NULL' : $fases_online_s).")";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma pagina
if($result):
	header("Location: torneo.php");
else:
	echo "Ha ocurrido un error al crear el torneo";
endif;

mysqli_close($conn);