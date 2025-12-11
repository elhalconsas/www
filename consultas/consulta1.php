<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Consulta 1</h1>

<p class="mt-3 fw-bold">Caso general:</p>
<p class="mt-3">
    Mostrar todos los datos del empleado(a) que más inseminaciones ha hecho a vacas 
    que él/ella no cuida.
</p>

<p class="mt-3 fw-bold">Caso particular:</p>
<p class="mt-3">
    Mostrar todos los datos del videojuego que es favorito de más equipos
    que están inscritos en torneos en los cuales no se juega ese mismo videojuego.
    <br>
    <span class="text-muted">Nota: En caso de empate en la cantidad, se muestra el videojuego más antiguo.</span>
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL Modificada:
// 1. Agregamos v.anio_lanzamiento al SELECT y al GROUP BY.
// 2. En ORDER BY agregamos una segunda condición: v.anio_lanzamiento ASC.
//    (ASC significa ascendente, por lo que el número de año más pequeño/antiguo va primero).

$query = "SELECT v.codigo AS videojuego_codigo, v.desarrollador, v.anio_lanzamiento, COUNT(*) AS equipos_contados\n"
    . "FROM videojuego v\n"
    . "JOIN equipo e ON e.videojuego_favorito_codigo = v.codigo\n"
    . "JOIN torneo t ON e.codigo_torneo = t.codigo_torneo\n"
    . "WHERE (t.videojuego_codigo IS NULL OR t.videojuego_codigo <> v.codigo)\n"
    . "GROUP BY v.codigo, v.desarrollador, v.anio_lanzamiento\n"
    . "ORDER BY equipos_contados DESC, v.anio_lanzamiento ASC\n"
    . "LIMIT 1";

// Ejecutar la consulta
$resultadoC1 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if($resultadoC1 and $resultadoC1->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">codigo</th>
                <th scope="col" class="text-center">desarrollador</th>
                <th scope="col" class="text-center">año_lanzamiento</th> 
            </tr>
        </thead>

        <tbody>

            <?php
            foreach ($resultadoC1 as $fila):
            ?>

            <tr>
                <td class="text-center"><?= htmlspecialchars($fila["videojuego_codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["desarrollador"]); ?></td>
                
                <td class="text-center text-primary fw-bold"><?= htmlspecialchars($fila["anio_lanzamiento"]); ?></td>
                
            </tr>

            <?php
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<?php
else:
?>

<div class="alert alert-danger text-center mt-5">
    No se encontraron resultados para esta consulta
</div>

<?php
endif;

include "../includes/footer.php";
?>