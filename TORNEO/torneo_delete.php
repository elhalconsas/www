<?php
 
// Crear conexión con la BD
require('../config/conexion.php');

// Sacar la CP de la entidad
// Obtener el código a eliminar (aceptar ambos nombres por compatibilidad)
if(isset($_POST['codigoEliminar'])){
    $codigo = $_POST['codigoEliminar'];
} elseif(isset($_POST['codigo_torneo'])){
    $codigo = $_POST['codigo_torneo'];
} else {
    // Si no llegó código, volver
    header("Location: torneo.php");
    exit;
}

// Sanitizar antes de usar en la consulta
$codigo_s = mysqli_real_escape_string($conn, $codigo);

// Query SQL a la BD
$query = "DELETE FROM torneo WHERE codigo_torneo = '$codigo_s'";

// Ejecutar consulta
$result = mysqli_query($conn, $query) or die(mysqli_error($conn));

// Verificar que se haya eliminado alguna fila
if($result && mysqli_affected_rows($conn) > 0):
    header ("Location: torneo.php");
else:
    echo "No se encontró el registro o ocurrió un error al eliminar este registro.";
endif;
 
mysqli_close($conn);