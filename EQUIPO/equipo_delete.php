<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar la clave primaria de la entidad enviada desde el formulario
$codigoEliminar = isset($_POST['codigoEliminar']) ? $_POST['codigoEliminar'] : null;

// Query SQL a la BD (tabla `equipo` con columna `codigo`)
$query = "DELETE FROM equipo WHERE codigo = '" . mysqli_real_escape_string($conn, $codigoEliminar) . "'";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

if($result): 
    // Si fue exitosa, redirigirse de nuevo a la página de la entidad
    header ("Location: equipo.php");
else:
    echo "Ha ocurrido un error al eliminar este registro";
endif;
 
mysqli_close($conn);