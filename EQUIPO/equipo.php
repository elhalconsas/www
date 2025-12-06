<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">Entidad Equipo</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="equipo_insert.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="codigo" class="form-label">codigo del equipo</label>
            <input type="number" class="form-control" id="codigo" name="codigo" required>
        </div>

        <div class="mb-3">
            <label for="nombre_oficial" class="form-label">Nombre del equipo</label>
            <input type="text" class="form-control" id="nombre_oficial" name="nombre_oficial" required>
        </div>

        <div class="mb-3">
            <label for="fecha_primer_juego" class="form-label">Fecha del primer juego</label>
            <input type="date" class="form-control" id="fecha_primer_juego" name="fecha_primer_juego" required>
        </div>
        
        <div class="mb-3">
            <label for="fecha_ultimo_juego" class="form-label">Fecha del último juego</label>
            <input type="date" class="form-control" id="fecha_ultimo_juego" name="fecha_ultimo_juego" required>
        </div>
        
        <!-- Consultar la lista de clientes y desplegarlos -->
        <div class="mb-3">
            <label for="videojuego_favorito_codigo" class="form-label">Videojuego favorito (código)</label>
            <select class="form-select" id="videojuego_favorito_codigo" name="videojuego_favorito_codigo">
                <option value="">Seleccione videojuego...</option>
                <?php
                // Cargar opciones desde VIDEOJUEGO/videojuego_select.php
                require_once('../VIDEOJUEGO/videojuego_select.php');
                if(isset($resultadovideo) && $resultadovideo->num_rows > 0):
                    while($v = mysqli_fetch_assoc($resultadovideo)):
                ?>
                    <option value="<?= htmlspecialchars($v['codigo']); ?>"><?= htmlspecialchars($v['codigo']); ?> - <?= htmlspecialchars($v['desarrollador'] ?? $v['nombre'] ?? ''); ?></option>
                <?php
                    endwhile;
                endif;
                ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Agregar</button>
        <button type="submit" name="guardar_todo" class="btn btn-success ms-2">Guardar todo</button>

    </form>
    
    <?php if(isset($_GET['error']) && $_GET['error'] === 'fecha'): ?>
        <div class="alert alert-danger mt-3" role="alert">
            La fecha de participación no puede ser mayor que la fecha de fundación.
        </div>
    <?php endif; ?>

    <script>
    // Validación cliente-side: fecha_participacion no puede ser mayor que fecha_fundacion
    (function(){
        const form = document.querySelector('.formulario form');
        if(!form) return;
        form.addEventListener('submit', function(e){
            const fFund = document.getElementById('fecha_fundacion').value;
            const fPart = document.getElementById('fecha_participacion').value;
            if(fFund && fPart){
                const dFund = new Date(fFund);
                const dPart = new Date(fPart);
                if(dPart > dFund){
                    e.preventDefault();
                    alert('La fecha de participación no puede ser mayor que la fecha de fundación.');
                }
            }
        });
    })();
    </script>
    
</div>

<?php
// Importar el código del otro archivo
require("equipo_select.php");

// Verificar si llegan datos
if(isset($resultadoEquipo) && $resultadoEquipo and $resultadoEquipo->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Código</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Fecha primer juego</th>
                <th scope="col" class="text-center">Fecha último juego</th>
                <th scope="col" class="text-center">Videojuego código</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoEquipo as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= htmlspecialchars($fila["codigo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["fecha_primer_juego"] ?? ''); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["fecha_ultimo_juego"] ?? ''); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["videojuego_favorito_codigo"] ?? ''); ?></td>
                
                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="equipo_delete.php" method="post">
                        <input hidden type="text" name="codigoEliminar" value="<?= htmlspecialchars($fila["codigo"]); ?>">
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