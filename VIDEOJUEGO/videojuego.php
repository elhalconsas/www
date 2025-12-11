<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">Entidad VIDEOJUEGO</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="videojuego_insert.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="codigo" class="form-label">Código</label>
            <input type="number" class="form-control" id="codigo" name="codigo"   min="0" required>
        </div>

        <div class="mb-3">
            <label for="desarrollador" class="form-label">Desarrollador</label>
            <input type="text" class="form-control" id="desarrollador" name="desarrollador" required>
        </div>

        <div class="mb-3">
            <label for="anio_lanzamiento" class="form-label">Año de lanzamiento</label>
            <?php $currentYear = intval(date('Y')); $startYear = 1950; ?>
            <select class="form-select" id="anio_lanzamiento" name="anio_lanzamiento" required>
                <option value="">Seleccione año...</option>
                <?php for($y = $currentYear; $y >= $startYear; $y--): ?>
                    <option value="<?= $y; ?>" <?= ($y === $currentYear ? 'selected' : ''); ?>><?= $y; ?></option>
                <?php endfor; ?>
            </select>
        </div>

        

        <button type="submit" class="btn btn-primary">Agregar</button>

    </form>
    
</div>

<?php
// Importar el código del otro archivo
require("videojuego_select.php");
            
// Verificar si llegan datos
if($resultadovideo and $resultadovideo->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">codigo</th>
                <th scope="col" class="text-center">desarrollador</th>
                <th scope="col" class="text-center">año_lanzamiento</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadovideo as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["codigo"]; ?></td>
                <td class="text-center"><?= $fila["desarrollador"]; ?></td>
                <td class="text-center"><?= $fila["anio_lanzamiento"]; ?></td>
                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="videojuego_delete.php" method="post">
                        <input hidden type="text" name="codigoEliminar" value="<?= $fila["codigo"]; ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>

            </tr>

            <?php
            // Cerrar los estructuras de control
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<?php
endif;

include "../includes/footer.php";
?>