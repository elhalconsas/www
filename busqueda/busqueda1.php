<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Búsqueda 1</h1>

<p class="mt-3 fw-bold">Caso general:</p>
<p class="mt-3">
    Ingresar dos fechas f1 y f2, f2 &gt; f1 (cada una en formato año-mes-día). Se debe
    mostrar todas las inseminaciones cuya fecha real esté comprendida entre f1 (inclusive)
    y f2 (inclusive), cada una de estas inseminaciones debe estar acompañada de todos los
    datos del ejecutor (empleado) y de todos los datos de la vaca correspondiente.
</p>

<p class="mt-3 fw-bold">Caso particular:</p>
<p class="mt-3">
    Ingresar dos fechas f1 y f2, f2 &gt; f1 (cada una en formato año-mes-día). Se debe
    mostrar todos los equipos tal que la fecha de su primera partida esté comprendida entre f1 (inclusive)
    y f2 (inclusive), cada uno de los equipos debe estar acompañado de los
    datos de su videojuego favorito y de los datos del torneo al que pertenece.
</p>

<div class="formulario p-4 m-3 border rounded-3">

    <form action="busqueda1.php" method="post" class="form-group">

        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="fecha1" class="form-label">Fecha Inicio (f1)</label>
                <input type="date" class="form-control" id="fecha1" name="fecha1" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fecha2" class="form-label">Fecha Fin (f2)</label>
                <input type="date" class="form-control" id="fecha2" name="fecha2" required>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Buscar</button>

    </form>
    
</div>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST'):

    require('../config/conexion.php');

    $f1 = $_POST["fecha1"];
    $f2 = $_POST["fecha2"];

    if ($f1 > $f2) {
        echo '<div class="alert alert-danger mx-3">Error: La fecha de inicio no puede ser mayor a la final.</div>';
    } else {

        // SQL: Seleccionamos explícitamente CADA columna de las 3 tablas.
        // Usamos alias (AS) para evitar conflictos de nombres.
        $query = "SELECT 
                    -- TABLA EQUIPO (Todos los campos)
                    e.codigo AS e_codigo,
                    e.nombre_oficial AS e_nombre,
                    e.fecha_primera_partida,
                    e.fecha_ultima_partida,
                    e.codigo_torneo AS e_ref_torneo,
                    e.videojuego_favorito_codigo AS e_ref_vj,

                    -- TABLA VIDEOJUEGO (Todos los campos)
                    v.codigo AS v_codigo,
                    v.desarrollador AS v_desarrollador,
                    v.anio_lanzamiento AS v_anio,

                    -- TABLA TORNEO (Todos los campos)
                    t.codigo_torneo AS t_codigo,
                    t.nombre_oficial AS t_nombre,
                    t.nivel_dificultad,
                    t.tipo_torneo,
                    t.numero_max_jugadores,
                    t.fases_iniciales_online,
                    t.videojuego_codigo AS t_ref_vj

                  FROM equipo e
                  JOIN videojuego v ON e.videojuego_favorito_codigo = v.codigo
                  JOIN torneo t ON e.codigo_torneo = t.codigo_torneo
                  WHERE e.fecha_primera_partida BETWEEN '$f1' AND '$f2'
                  ORDER BY e.fecha_primera_partida ASC";

        $resultadoB1 = mysqli_query($conn, $query) or die(mysqli_error($conn));
        mysqli_close($conn);

        if($resultadoB1 and $resultadoB1->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 overflow-auto" style="max-height: 600px;">

    <table class="table table-striped table-bordered align-middle table-hover" style="min-width: 1500px;">

        <thead class="table-dark sticky-top">
            <tr>
                <th colspan="6" class="text-center bg-primary border-end">Datos del equipo</th>
                <th colspan="3" class="text-center bg-secondary border-end">Datos del videojuego favorito</th>
                <th colspan="7" class="text-center bg-dark">Datos del torneo asociado</th>
            </tr>
            <tr>
                <th class="text-center small">codigo</th>
                <th class="text-center small">nombre_oficial</th>
                <th class="text-center small">fecha_primera_partida</th>
                <th class="text-center small">fecha_ultima_partida</th>
                <th class="text-center small">codigo_torneo</th>
                <th class="text-center small">videojuego_favorito_codigo</th>

                <th class="text-center small">codigo</th>
                <th class="text-center small">desarrollador</th>
                <th class="text-center small">año_lanzamiento</th>

                <th class="text-center small">codigo_torneo</th>
                <th class="text-center small">nombre_oficial</th>
                <th class="text-center small">nivel_dificultad</th>
                <th class="text-center small">tipo_torneo</th>
                <th class="text-center small">numero_max_jugadores</th>
                <th class="text-center small">fases_iniciales_online</th>
                <th class="text-center small">videojuego_codigo</th>
            </tr>
        </thead>

        <tbody>

            <?php foreach ($resultadoB1 as $fila): ?>
            <tr>
                <td class="text-center font-monospace"><?= htmlspecialchars($fila["e_codigo"]); ?></td>
                <td class="text-center fw-bold"><?= htmlspecialchars($fila["e_nombre"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["fecha_primera_partida"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["fecha_ultima_partida"]); ?></td>
                <td class="text-center text-muted small"><?= htmlspecialchars($fila["e_ref_torneo"]); ?></td>
                <td class="text-center text-muted small border-end"><?= htmlspecialchars($fila["e_ref_vj"]); ?></td>

                <td class="text-center font-monospace"><?= htmlspecialchars($fila["v_codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["v_desarrollador"]); ?></td>
                <td class="text-center border-end"><?= htmlspecialchars($fila["v_anio"]); ?></td>

                <td class="text-center font-monospace"><?= htmlspecialchars($fila["t_codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["t_nombre"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nivel_dificultad"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["tipo_torneo"]); ?></td>
                <td class="text-center">
                    <?= ($fila["tipo_torneo"] == 'EQUIPO') ? htmlspecialchars($fila["numero_max_jugadores"]) : '-'; ?>
                </td>
                <td class="text-center small">
                    <?php 
                        if ($fila["tipo_torneo"] == 'INDIVIDUAL') {
                            echo ($fila["fases_iniciales_online"] == 1) ? "Sí" : "No"; 
                        } else {
                            echo "-";
                        }
                    ?>
                </td>
                <td class="text-center text-muted small"><?= htmlspecialchars($fila["t_ref_vj"]); ?></td>
            </tr>
            <?php endforeach; ?>

        </tbody>

    </table>
</div>

<?php
        else:
?>
<div class="alert alert-warning text-center mt-5">
    No se encontraron resultados en el rango de fechas seleccionado.
</div>
<?php
        endif;
    }
endif;

include "../includes/footer.php";
?>