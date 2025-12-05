<?php

// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL a la BD
$queryTorneos = "SELECT * FROM torneo ORDER BY codigo_torneo";
$resultadoTorneos = mysqli_query($conn, $queryTorneos) or die(mysqli_error($conn));


mysqli_close($conn);
?>