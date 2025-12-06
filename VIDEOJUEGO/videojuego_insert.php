<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar los datos del formulario. Cada input se identifica con su "name"
$codigo = $_POST["codigo"];
$desarrollador = $_POST["desarrollador"];
$anio_lanzamiento = $_POST["anio_lanzamiento"];

// Query SQL a la BD. Si tienen que hacer comprobaciones, hacerlas acá (Generar una query diferente para casos especiales)
$query = "INSERT INTO `videojuego`(`codigo`,`desarrollador`, `anio_lanzamiento`) VALUES ('$codigo', '$desarrollador', '$anio_lanzamiento')";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Redirigir al usuario a la misma pagina
if($result):
    // Si fue exitosa, redirigirse de nuevo a la página de la entidad
	header("Location: videojuego.php");
else:
	echo "Ha ocurrido un error al crear el videojuego";
endif;

mysqli_close($conn);