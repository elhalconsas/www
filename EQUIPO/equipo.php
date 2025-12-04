<?php
include "../includes/header.php";
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">Entidad análoga a EMPRESA (NOMBRE)</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="empresa_insert.php" method="post" class="form-group">

        <div class="mb-3">
            <label for="codigo" class="form-label">codigo del equipo</label>
            <input type="number" class="form-control" id="codigo" name="codigo" required>
        </div>

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre del equipo</label>
            <input type="text" class="form-control" id="nombre" name="nombre" required>
        </div>

        <div class="mb-3">
            <label for="presupuesto" class="form-label">Presupuesto</label>
            <input type="number" class="form-control" id="presupuesto" name="presupuesto" required>
        </div>
        
        <div class="mb-3">
            <label for="fecha_fundacion" class="form-label">Fecha de fundación</label>
            <input type="date" class="form-control" id="fecha_fundacion" name="fecha_fundacion" required>
        </div>

        <div class="mb-3">
            <label for="fecha_participacion" class="form-label">Fecha de participación</label>
            <input type="date" class="form-control" id="fecha_participacion" name="fecha_participacion" required>
        </div>
        
        <!-- Consultar la lista de clientes y desplegarlos -->
        <div class="mb-3">
            <label for="cliente" class="form-label">Cliente</label>
            <select name="cliente" id="cliente" class="form-select">
                
                <!-- Option por defecto -->
                <option value="" selected disabled hidden></option>

                <?php
                // Importar el código del otro archivo
                require("../cliente/cliente_select.php");
                
                // Verificar si llegan datos
                if($resultadoCliente):
                    
                    // Iterar sobre los registros que llegaron
                    foreach ($resultadoCliente as $fila):
                ?>

                <!-- Opción que se genera -->
                <option value="<?= $fila["cedula"]; ?>"><?= $fila["nombre"]; ?> - C.C. <?= $fila["cedula"]; ?></option>

                <?php
                        // Cerrar los estructuras de control
                    endforeach;
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
if($resultadoEmpresa and $resultadoEmpresa->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">NIT</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Presupuesto</th>
                <th scope="col" class="text-center">Cliente</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoEmpresa as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= $fila["nit"]; ?></td>
                <td class="text-center"><?= $fila["nombre"]; ?></td>
                <td class="text-center">$<?= $fila["presupuesto"]; ?></td>
                <td class="text-center">C.C. <?= $fila["cliente"]; ?></td>
                
                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="equipo_delete.php" method="post">
                        <input hidden type="text" name="nitEliminar" value="<?= $fila["nit"]; ?>">
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