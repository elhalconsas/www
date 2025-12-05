<?php
include "../includes/header.php";
require('../config/conexion.php');
?>

<!-- TÍTULO. Cambiarlo, pero dejar especificada la analogía -->
<h1 class="mt-3 fw-bold">TORNEO (superentidad con subtipos EQUIPO / INDIVIDUAL)</h1>

<!-- FORMULARIO. Cambiar los campos de acuerdo a su trabajo -->
<div class="formulario p-4 m-3 border rounded-3">

    <form action="torneo_insert.php" method="post" class="form-group">

       <div class="mb-3">
            <label for="codigo_torneo" class="form-label">Código del torneo (CP ≥ 0)</label>
            <input type="number" class="form-control" id="codigo_torneo" name="codigo_torneo" min="0" required>
        </div>

        <!-- Atributos propios de TORNEO -->
        <div class="mb-3">
            <label for="nombre_oficial" class="form-label">Nombre oficial</label>
            <input type="text" class="form-control" id="nombre_oficial" name="nombre_oficial" required>
        </div>

        <div class="mb-3">
            <label for="nivel_dificultad" class="form-label">Nivel de dificultad</label>
            <select class="form-select" id="nivel_dificultad" name="nivel_dificultad" required>
                <option value="">Seleccione...</option>
                <option value="1">1 - Muy fácil</option>
                <option value="2">2 - Fácil</option>
                <option value="3">3 - Medio</option>
                <option value="4">4 - Difícil</option>
                <option value="5">5 - Muy difícil</option>
            </select>
        </div>

        <!-- Tipo de torneo = subtipo -->
        <div class="mb-3">
            <label class="form-label d-block">Tipo de torneo (subtipo)</label>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_torneo" id="tipo_equipo" value="Equipo" required>
                <label class="form-check-label" for="tipo_equipo">Por EQUIPOS</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_torneo" id="tipo_individual" value="Individual">
                <label class="form-check-label" for="tipo_individual">INDIVIDUAL</label>
            </div>
        </div>
        <?php

        $initialTipo = isset($_POST['tipo_torneo']) ? $_POST['tipo_torneo'] : '';
        ?>

        <div id="subtipo_equipo" class="subtipo-section" style="display:none;">
            <div class="mb-3">
                <label for="numero_max_jugadores" class="form-label">
                    Número máximo de jugadores por equipo
                </label>
                <input type="number" class="form-control" id="numero_max_jugadores" name="numero_max_jugadores" min="2">
            </div>

            <div class="mb-3">
                <label for="formato_competicion" class="form-label">
                    Formato de competición 
                </label>
                <input type="text" class="form-control" id="formato_competicion" name="formato_competicion">
            </div>
        </div>

        <div id="subtipo_individual" class="subtipo-section" style="display:none;">
            <!-- Atributo de subtipo INDIVIDUAL -->
            <div class="mb-3">
                <label class="form-label d-block">
                    Fases iniciales (solo si es individual)
                </label>
                <select class="form-select" id="fases_iniciales_online" name="fases_iniciales_online">
                    <option value="">Seleccione...</option>
                    <option value="online">Online</option>
                    <option value="presencial">Presencial</option>
                </select>
            </div>
        </div>

        <script>
        (function(){
            const equipoRadio = document.getElementById('tipo_equipo');
            const individualRadio = document.getElementById('tipo_individual');
            const sectEquipo = document.getElementById('subtipo_equipo');
            const sectInd = document.getElementById('subtipo_individual');

            function updateSections(){
                if(equipoRadio.checked){
                    sectEquipo.style.display = '';
                    sectInd.style.display = 'none';
                } else if(individualRadio.checked){
                    sectEquipo.style.display = 'none';
                    sectInd.style.display = '';
                } else {
                    sectEquipo.style.display = 'none';
                    sectInd.style.display = 'none';
                }
            }

            equipoRadio.addEventListener('change', updateSections);
            individualRadio.addEventListener('change', updateSections);

            // Inicializar según valor previo (si existe)
            const initial = <?= json_encode($initialTipo); ?>;
            if(initial === 'Equipo'){
                equipoRadio.checked = true;
            } else if(initial === 'Individual'){
                individualRadio.checked = true;
            }

            // Ejecutar al cargar
            updateSections();
        })();
        </script>

        <?php if(isset($_GET['error']) && $_GET['error'] === 'duplicado'): ?>
            <div class="alert alert-danger mt-3" role="alert">
                El código del torneo ya existe. Por favor elija un código único.
            </div>
        <?php endif; ?>


      

        <button type="submit" class="btn btn-primary mt-3">Crear torneo</button>

    </form>
    
</div>

<?php
// Importar el código del otro archivo
require("torneo_select.php");

// Verificar si llegan datos
if($resultadoTorneos and $resultadoTorneos->num_rows > 0):
?>

<!-- MOSTRAR LA TABLA. Cambiar las cabeceras -->
<div class="tabla mt-5 mx-3 rounded-3 overflow-hidden">

    <table class="table table-striped table-bordered">

        <!-- Títulos de la tabla, cambiarlos -->
        <thead class="table-dark">
            <tr>
                <th scope="col" class="text-center">Código</th>
                <th scope="col" class="text-center">Nombre</th>
                <th scope="col" class="text-center">Nivel</th>
                <th scope="col" class="text-center">Tipo</th>
                <th scope="col" class="text-center">N.º máx. jugadores</th>
                <th scope="col" class="text-center">Formato</th>
                <th scope="col" class="text-center">Fases iniciales</th>
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            // Iterar sobre los registros que llegaron
            foreach ($resultadoTorneos as $fila):
            ?>

            <!-- Fila que se generará -->
            <tr>
                <!-- Cada una de las columnas, con su valor correspondiente -->
                <td class="text-center"><?= htmlspecialchars($fila["codigo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nivel_dificultad"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["tipo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["numero_max_jugadores"] ?? ''); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["formato_competicion"] ?? ''); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["fases_iniciales_online"] ?? ''); ?></td>

                <!-- Botón de eliminar. Debe de incluir la CP de la entidad para identificarla -->
                <td class="text-center">
                    <form action="torneo_delete.php" method="post">
                        <input hidden type="text" name="codigoEliminar" value="<?= htmlspecialchars($fila["codigo_torneo"]); ?>">
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