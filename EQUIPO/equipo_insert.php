<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario. Cada input se identifica con su "name"
// Datos del formulario
$nit = isset($_POST["nit"]) ? $_POST["nit"] : '';
$nombre = isset($_POST["nombre"]) ? $_POST["nombre"] : '';
$presupuesto = isset($_POST["presupuesto"]) ? $_POST["presupuesto"] : 0;
$cliente = isset($_POST["cliente"]) ? $_POST["cliente"] : '';

// Fechas (opcionalmente enviadas desde el formulario)
$fecha_fundacion = isset($_POST['fecha_fundacion']) ? $_POST['fecha_fundacion'] : null;
$fecha_participacion = isset($_POST['fecha_participacion']) ? $_POST['fecha_participacion'] : null;

// Validación server-side: fecha_participacion no puede ser mayor que fecha_fundacion
if($fecha_fundacion && $fecha_participacion){
	$tFund = strtotime($fecha_fundacion);
	$tPart = strtotime($fecha_participacion);
	if($tPart > $tFund){
		// Redirigir de vuelta al formulario con un error
		header("Location: equipo.php?error=fecha");
		exit;
	}
}

// Query SQL a la BD. Si tienen que hacer comprobaciones, hacerlas acá (Generar una query diferente para casos especiales)
$query = "INSERT INTO `empresa`(`nit`,`nombre`, `presupuesto`, `cliente`) VALUES ('$nit', '$nombre', '$presupuesto', '$cliente')";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma pagina
if($result):
    // Si fue exitosa, redirigirse de nuevo a la página de la entidad
	header("Location: empresa.php");
else:
	echo "Ha ocurrido un error al crear la persona";
endif;

mysqli_close($conn);