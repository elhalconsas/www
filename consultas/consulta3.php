<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Consulta 3</h1>

<p class="mt-3 fw-bold">Caso general:</p>
<p class="mt-3">
    Mostrar todos los datos del cerdo de mayor valor que está listo para la venta junto con los datos de su cuidador.
</p>

<p class="mt-3 fw-bold">Caso particular:</p>
<p class="mt-3">
    Mostrar los datos del torneo en modalidad individual con el mayor nivel de dificultad 
    que además tenga sus fases iniciales en modalidad online junto con los datos del videojuego del torneo.
    <br><span class="text-muted fst-italic">Nota: Si hay varios torneos empatados, se muestran todos.</span>
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL con Subconsulta para manejar empates:
// 1. SELECT principal: Trae los datos del torneo y videojuego.
// 2. WHERE... AND nivel_dificultad = (...): Aquí está la magia. 
//    Comparamos la dificultad con el resultado de una subconsulta que busca el MAX().

$query = "SELECT 
            t.codigo_torneo, 
            t.nombre_oficial, 
            t.nivel_dificultad, 
            t.tipo_torneo,
            t.numero_max_jugadores,
            t.fases_iniciales_online,
            v.codigo AS cod_videojuego,
            v.desarrollador,
            v.anio_lanzamiento
          FROM torneo t
          JOIN videojuego v ON t.videojuego_codigo = v.codigo
          WHERE t.tipo_torneo = 'INDIVIDUAL'
          AND t.fases_iniciales_online = 1
          AND t.nivel_dificultad = (
              SELECT MAX(nivel_dificultad)
              FROM torneo
              WHERE tipo_torneo = 'INDIVIDUAL'
              AND fases_iniciales_online = 1
          )";

// Ejecutar la consulta
$resultadoC3 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if($resultadoC3 and $resultadoC3->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered align-middle">

        <thead class="table-dark">
            <tr>
                <th colspan="6" class="text-center bg-primary border-end">Datos del torneo</th>
                <th colspan="3" class="text-center bg-secondary border-end">Datos del videojuego</th>
            </tr>
            <tr>
                <th scope="col" class="text-center">codigo_torneo</th>
                <th scope="col" class="text-center">nombre_oficial</th>
                <th scope="col" class="text-center">nivel_dificultad</th>
                <th scope="col" class="text-center">tipo_torneo</th>
                <th scope="col" class="text-center">numero_max_jugadores</th>
                <th scope="col" class="text-center">fases_iniciales_online</th>
                <th scope="col" class="text-center bg-dark border-start">codigo</th>
                <th scope="col" class="text-center bg-dark">desarrollador</th>
                <th scope="col" class="text-center bg-dark">año_lanzamiento</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Ahora el foreach recorrerá TODAS las filas que tengan esa dificultad máxima
            foreach ($resultadoC3 as $fila):
            ?>

            <tr>
                <td class="text-center"><?= htmlspecialchars($fila["codigo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nivel_dificultad"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["tipo_torneo"]); ?></td>
                <td class="text-center">
                    <?php 
                    $valor = $fila["numero_max_jugadores"]; 

                    if ($valor === null): ?>
                        <span class="text-muted">-</span>
                    <?php elseif ($valor == 1): ?>
                        <span class="badge bg-success">Sí</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">No</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <?= ($fila["fases_iniciales_online"] == 1) ? "Sí" : "No"; ?>
                </td>
                
                <td class="text-center">
                    <?= htmlspecialchars($fila["cod_videojuego"]); ?>
                </td>
                <td class="text-center">
                    <?= htmlspecialchars($fila["desarrollador"]); ?>
                </td>
                <td class="text-center">
                    <?= htmlspecialchars($fila["anio_lanzamiento"]); ?>
                </td>
            </tr>

            <?php
            endforeach;
            ?>

        </tbody>

    </table>
    
    <div class="mt-2 text-end text-muted mx-2">
        <small>Total de torneos encontrados con la máxima dificultad: <strong><?= $resultadoC3->num_rows; ?></strong></small>
    </div>
</div>

<?php
else:
?>

<div class="alert alert-warning text-center mt-5">
    No se encontraron resultados.
</div>

<?php
endif;

include "../includes/footer.php";
?>