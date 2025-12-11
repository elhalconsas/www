<?php
include "../includes/header.php";
?>

<h1 class="mt-3 fw-bold">Búsqueda 2</h1>

<p class="mt-3 fw-bold">Caso general:</p>
<p class="mt-3">
    Al ingresar la cedúala de un empleado se deben mostrar todos los datos de este empleado
    junto con todos los datos de los cerdos que este empleado cuida pero solo de aquellos
    cerdos que no están listos para la venta.
</p>

<p class="mt-3 fw-bold">Caso particular:</p>
<p class="mt-3">
    Al ingresar el código de un videojuego de mostrarán los datos de dicho juego junto con los torneos de tipo individual
    asociados que sean presenciales.
</p>

<div class="formulario p-4 m-3 border rounded-3">

    <form action="busqueda2.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="codigo_videojuego" class="form-label">Código del Videojuego (Numérico)</label>
            <input type="number" class="form-control" id="codigo_videojuego" name="codigo_videojuego" placeholder="Ej: 101, 500..." required>
        </div>

        <button type="submit" class="btn btn-primary">Buscar</button>

    </form>
    
</div>

<?php
// Verificación del método POST
if ($_SERVER['REQUEST_METHOD'] === 'POST'):

    // Crear conexión con la BD
    require('../config/conexion.php');

    // Recibimos el código y lo aseguramos como entero
    $codigo_buscado = (int)$_POST["codigo_videojuego"];

    // Query SQL:
    // 1. Unimos Videojuego (v) con Torneo (t).
    // 2. Filtramos donde el código del videojuego sea exactamente el ingresado.
    // 3. Filtramos que sea INDIVIDUAL.
    // 4. Filtramos que fases_iniciales_online sea 0 (Falso/Presencial).

    $query = "SELECT 
                v.codigo,
                v.desarrollador,
                v.anio_lanzamiento,
                t.codigo_torneo,
                t.nombre_oficial,
                t.nivel_dificultad,
                t.tipo_torneo,
                t.numero_max_jugadores,
                t.fases_iniciales_online,
                t.videojuego_codigo
              FROM videojuego v
              JOIN torneo t ON v.codigo = t.videojuego_codigo
              WHERE v.codigo = $codigo_buscado
              AND t.tipo_torneo = 'INDIVIDUAL'
              AND t.fases_iniciales_online = 0";

    // Ejecutar la consulta
    $resultadoB2 = mysqli_query($conn, $query) or die(mysqli_error($conn));

    mysqli_close($conn);

    // Verificar si llegan datos
    if($resultadoB2 and $resultadoB2->num_rows > 0):
?>

<div class="tabla mt-5 mx-3 rounded-3 table-responsive" style="max-height: 500px; overflow: auto;">

    <table class="table table-striped table-bordered align-middle">

        <thead class="table-dark">
            <tr>
                <th colspan="3" class="text-center bg-primary border-end">Datos del videojuego</th>
                <th colspan="7" class="text-center bg-secondary border-end">Datos del torneo</th>
            </tr>
            <tr>
                <th scope="col" class="text-center">codigo</th>
                <th scope="col" class="text-center">desarrollador</th>
                <th scope="col" class="text-center">año_lanzamiento</th>
                
                <th scope="col" class="text-center bg-dark border-start">codigo_torneo</th>
                <th scope="col" class="text-center bg-dark">nombre_oficial</th>
                <th scope="col" class="text-center bg-dark">nivel_dificultad</th>
                <th scope="col" class="text-center bg-dark">tipo_torneo</th>
                <th scope="col" class="text-center bg-dark">numero_max_jugadores</th>
                <th scope="col" class="text-center bg-dark">fases_iniciales_online</th>
                <th scope="col" class="text-center bg-dark">videojuego_codigo</th>
            </tr>
        </thead>

        <tbody>

            <?php
            foreach ($resultadoB2 as $fila):
            ?>

            <tr>
                <td class="text-center"><?= htmlspecialchars($fila["codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["desarrollador"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["anio_lanzamiento"]); ?></td>
                
                <td class="text-center border-start"><?= htmlspecialchars($fila["codigo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
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
                <td class="text-center"><?= htmlspecialchars($fila["videojuego_codigo"]); ?></td>
            <?php
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<?php
else:
?>

<div class="alert alert-warning text-center mt-5">
    <p class="mb-0">No se encontraron resultados.</p>
    <small>Verifique que el código exista y que tenga torneos individuales presenciales asociados.</small>
</div>

<?php
    endif;
endif;

include "../includes/footer.php";
?>