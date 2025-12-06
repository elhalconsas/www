<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Consulta requerida -->
<h1 class="mt-3 fw-bold">Consulta 1 — Videojuego favorito en torneos distintos</h1>

<p class="mt-3 fw-bold">Descripción:</p>
<p class="mt-3">
    Mostrar el videojuego (código y desarrollador) que es favorito de más equipos
    que están inscritos en torneos cuya base NO coincide con ese mismo videojuego.
    Se mostrará también la cantidad de equipos que cumplen la condición.
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL a la BD: contar por videojuego las apariciones en equipos inscritos en
// torneos cuya columna `videojuego_codigo` es distinta al videojuego favorito.
$query = "SELECT v.codigo AS videojuego_codigo, v.desarrollador, COUNT(*) AS equipos_contados\n"
    . "FROM videojuego v\n"
    . "JOIN equipo e ON e.videojuego_favorito_codigo = v.codigo\n"
    . "JOIN torneo t ON e.codigo_torneo = t.codigo_torneo\n"
    . "WHERE (t.videojuego_codigo IS NULL OR t.videojuego_codigo <> v.codigo)\n"
    . "GROUP BY v.codigo, v.desarrollador\n"
    . "ORDER BY equipos_contados DESC\n"
    . "LIMIT 1";

// Ejecutar la consulta
$resultadoC1 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if($resultadoC1 and $resultadoC1->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Código videojuego</th>
                <th scope="col" class="text-center">Desarrollador</th>
                <th scope="col" class="text-center">Equipos (conteo)</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoC1 as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= htmlspecialchars($fila["videojuego_codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["desarrollador"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["equipos_contados"]); ?></td>
            </tr>

            <?php
            // Cerrar los estructuras de control
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<!-- Mensaje de error si no hay resultados -->
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