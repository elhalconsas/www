<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario. Cada input se identifica con su "name"
$codigo = isset($_POST['codigo']) ? intval($_POST['codigo']) : null;
$nombre_oficial = isset($_POST['nombre_oficial']) ? $_POST['nombre_oficial'] : '';
$fecha_primer_juego = isset($_POST['fecha_primer_juego']) && $_POST['fecha_primer_juego'] !== '' ? $_POST['fecha_primer_juego'] : null;
$fecha_ultimo_juego = isset($_POST['fecha_ultimo_juego']) && $_POST['fecha_ultimo_juego'] !== '' ? $_POST['fecha_ultimo_juego'] : null;
$videojuego_favorito_codigo = isset($_POST['videojuego_favorito_codigo']) && $_POST['videojuego_favorito_codigo'] !== '' ? intval($_POST['videojuego_favorito_codigo']) : null;

// Sanitizar
$codigo_s = $codigo !== null ? intval($codigo) : null;
$nombre_s = mysqli_real_escape_string($conn, trim($nombre_oficial));
$fecha_primer_s = $fecha_primer_juego !== null ? mysqli_real_escape_string($conn, $fecha_primer_juego) : null;
$fecha_ultimo_s = $fecha_ultimo_juego !== null ? mysqli_real_escape_string($conn, $fecha_ultimo_juego) : null;
$videojuego_favorito_codigo_s = $videojuego_favorito_codigo !== null ? intval($videojuego_favorito_codigo) : null;

// Validación server-side: fecha_ultimo_juego no puede ser menor que fecha_primer_juego
if($fecha_primer_s && $fecha_ultimo_s){
	$tPrim = strtotime($fecha_primer_s);
	$tUlt = strtotime($fecha_ultimo_s);
	if($tUlt < $tPrim){
		header('Location: equipo.php?error=fecha');
		exit;
	}
}

// Insertar en la tabla `equipo` (ajusta nombres de columnas según tu esquema)
// Si no se seleccionó videojuego favorito, insertar NULL en esa columna.
if ($videojuego_favorito_codigo_s === null) {
	$stmt = $conn->prepare("INSERT INTO `equipo` (`codigo`, `nombre_oficial`, `fecha_primer_juego`, `fecha_ultimo_juego`, `videojuego_favorito_codigo`) VALUES (?, ?, ?, ?, NULL)");
	if(!$stmt){
		die('Error al preparar la consulta: ' . mysqli_error($conn));
	}
	// Bind: i = int, s = string, s = string, s = string
	$stmt->bind_param('isss', $codigo_s, $nombre_s, $fecha_primer_s, $fecha_ultimo_s);
	$exec = $stmt->execute();
} else {
	$stmt = $conn->prepare("INSERT INTO `equipo` (`codigo`, `nombre_oficial`, `fecha_primer_juego`, `fecha_ultimo_juego`, `videojuego_favorito_codigo`) VALUES (?, ?, ?, ?, ?)");
	if(!$stmt){
		die('Error al preparar la consulta: ' . mysqli_error($conn));
	}
	// Bind: i = int, s = string, s = string, s = string, i = int
	$stmt->bind_param('isssi', $codigo_s, $nombre_s, $fecha_primer_s, $fecha_ultimo_s, $videojuego_favorito_codigo_s);
	$exec = $stmt->execute();
}
if($exec){
	$stmt->close();
	mysqli_close($conn);
	header('Location: equipo.php');
	exit;
} else {
	$err = $stmt->error;
	$stmt->close();
	mysqli_close($conn);
	echo 'Ha ocurrido un error al crear el equipo: ' . htmlspecialchars($err);
}