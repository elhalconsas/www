<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Consulta 2</h1>

<p class="mt-3 fw-bold">Caso general:</p>
<p class="mt-3">
    Mostrar todos los datos de la vaca que tiene mayor
    valor en promedio litros día y que nunca ha sido inseminada artificialmente.
</p>

<p class="mt-3 fw-bold">Caso particular:</p>
<p class="mt-3">
    Mostrar los datos del torneo en equipo con el número máximo de jugadores más alto 
    en el cual aún no se ha registrado ningún equipo participante.
    <br><span class="text-muted fst-italic">Nota: Si hay varios torneos empatados, se muestran todos.</span>
</p>

<?php
// Crear conexión con la BD
require('../config/conexion.php');

// Query SQL Modificada para Empates:
// 1. SELECT principal: Trae los datos de la tabla 'torneo'.
// 2. LEFT JOIN ... WHERE e.codigo IS NULL: Para asegurar que traemos solo los que NO tienen equipos.
// 3. Subconsulta: Calculamos el MAX(numero_max_jugadores) pero SOLO considerando 
//    los torneos que cumplen la condición (tipo equipo y sin inscritos).

$query = "SELECT t.codigo_torneo, t.nombre_oficial, t.nivel_dificultad, t.tipo_torneo, t.numero_max_jugadores, t.fases_iniciales_online, t.videojuego_codigo "
       . "FROM torneo t "
       . "LEFT JOIN equipo e ON t.codigo_torneo = e.codigo_torneo "
       . "WHERE t.tipo_torneo = 'EQUIPO' "
       . "AND e.codigo IS NULL "
       . "AND t.numero_max_jugadores = ( "
            . "SELECT MAX(t2.numero_max_jugadores) "
            . "FROM torneo t2 "
            . "LEFT JOIN equipo e2 ON t2.codigo_torneo = e2.codigo_torneo "
            . "WHERE t2.tipo_torneo = 'EQUIPO' "
            . "AND e2.codigo IS NULL "
       . ")";

// Ejecutar la consulta
$resultadoC2 = mysqli_query($conn, $query) or die(mysqli_error($conn));

mysqli_close($conn);
?>

<?php
// Verificar si llegan datos
if($resultadoC2 and $resultadoC2->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">codigo_torneo</th>
                <th scope="col" class="text-center">nombre_oficial</th>
                <th scope="col" class="text-center">nivel_dificultad</th>
                <th scope="col" class="text-center">tipo_torneo</th>
                <th scope="col" class="text-center">numero_max_jugadores</th>
                <th scope="col" class="text-center">fases_iniciales_online</th>
                <th scope="col" class="text-center">videojuego_codigo</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros (ahora pueden ser varios si hay empate)
            foreach ($resultadoC2 as $fila):
            ?>

            <tr>
                <td class="text-center"><?= htmlspecialchars($fila["codigo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nivel_dificultad"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["tipo_torneo"]); ?></td>
                <td class="text-center fw-bold text-success"><?= htmlspecialchars($fila["numero_max_jugadores"]); ?></td>
                <td class="text-center">
                    <?php 
                    $valor = $fila["fases_iniciales_online"]; 

                    if ($valor === null): ?>
                        <span class="text-muted">-</span>
                    <?php elseif ($valor == 1): ?>
                        <span class="badge bg-success">Sí</span>
                    <?php else: ?>
                        <span class="badge bg-secondary">No</span>
                    <?php endif; ?>
                </td>
                <td class="text-center"><?= htmlspecialchars($fila["videojuego_codigo"]); ?></td>
            </tr>

            <?php
            endforeach;
            ?>

        </tbody>

    </table>
    
    <div class="mt-2 text-end text-muted mx-2">
        <small>Registros encontrados: <strong><?= $resultadoC2->num_rows; ?></strong></small>
    </div>
</div>

<?php
else:
?>

<div class="alert alert-warning text-center mt-5">
    No se encontró ningún torneo de tipo 'EQUIPO' sin participantes registrados.
</div>

<?php
endif;

include "../includes/footer.php";
?>