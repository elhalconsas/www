<?php
include "../includes/header.php";
require('../config/conexion.php');
?>

<h1 class="mt-3 fw-bold">TORNEO (supertipo con subtipos EQUIPO / INDIVIDUAL)</h1>

<div class="formulario p-4 m-3 border rounded-3">

    <form action="torneo_insert.php" method="post" class="form-group">

       <div class="mb-3">
            <label for="codigo_torneo" class="form-label">Código del torneo (CP ≥ 0)</label>
            <input type="number" class="form-control" id="codigo_torneo" name="codigo_torneo" min="0" required>
        </div>

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

        <div class="mb-3">
            <label class="form-label d-block">Tipo de torneo (subtipo)</label>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_torneo" id="tipo_equipo" value="EQUIPO" required>
                <label class="form-check-label" for="tipo_equipo">Por EQUIPOS</label>
            </div>

            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="tipo_torneo" id="tipo_individual" value="INDIVIDUAL">
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
        </div>

        <div id="subtipo_individual" class="subtipo-section" style="display:none;">
            <div class="mb-3">
                <label class="form-label d-block">
                    Fases iniciales online
                </label>
                <select class="form-select" id="fases_iniciales_online" name="fases_iniciales_online">
                    <option value="">Seleccione...</option>
                    <option value="1">Online</option>
                    <option value="0">Presencial</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="videojuego_codigo" class="form-label">Videojuego (código)</label>
            <select class="form-select" id="videojuego_codigo" name="videojuego_codigo">
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

        <script>
        (function(){
            const equipoRadio = document.getElementById('tipo_equipo');
            const individualRadio = document.getElementById('tipo_individual');
            const sectEquipo = document.getElementById('subtipo_equipo');
            const sectInd = document.getElementById('subtipo_individual');
            const numMaxInput = document.getElementById('numero_max_jugadores');
            
            function updateSections(){
                if(equipoRadio.checked){
                    sectEquipo.style.display = '';
                    sectInd.style.display = 'none';
                    if(numMaxInput){
                        numMaxInput.disabled = false;
                        if(numMaxInput.value === '2') numMaxInput.value = '';
                    }
                } else if(individualRadio.checked){
                    sectEquipo.style.display = 'none';
                    sectInd.style.display = '';
                    if(numMaxInput){
                        numMaxInput.value = '';
                        numMaxInput.disabled = true;
                    }
                } else {
                    sectEquipo.style.display = 'none';
                    sectInd.style.display = 'none';
                    if(numMaxInput) numMaxInput.disabled = false;
                }
            }

            equipoRadio.addEventListener('change', updateSections);
            individualRadio.addEventListener('change', updateSections);

            const initial = <?= json_encode($initialTipo); ?>;
            if(initial === 'EQUIPO'){
                equipoRadio.checked = true;
            } else if(initial === 'INDIVIDUAL'){
                individualRadio.checked = true;
            }

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
// Importar el código del otro archivo para listar
require("torneo_select.php");

// Verificar si llegan datos
if($resultadoTorneos and $resultadoTorneos->num_rows > 0):
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
                <th scope="col" class="text-center">Acciones</th>
            </tr>
        </thead>

        <tbody>

            <?php
            foreach ($resultadoTorneos as $fila):
            ?>

            <tr>
                <td class="text-center"><?= htmlspecialchars($fila["codigo_torneo"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nombre_oficial"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["nivel_dificultad"]); ?></td>
                <td class="text-center"><?= htmlspecialchars($fila["tipo_torneo"]); ?></td>
                <td class="text-center"><?= ($fila["tipo_torneo"] == 'EQUIPO') ? htmlspecialchars($fila["numero_max_jugadores"]) : '-'; ?>
                </td>
                
                <td class="text-center">
                    <?php
                    // Si es torneo INDIVIDUAL, mostramos si es Online o Presencial
                    if ($fila["tipo_torneo"] == 'INDIVIDUAL') {
                        // En la BD: 1 = Online, 0 = Presencial
                        if ($fila["fases_iniciales_online"] == 1) {
                            echo "Sí";
                        } else {
                            echo "No";
                        }
                    } else {
                        // Si es torneo de EQUIPO, este campo no aplica (es NULL)
                        echo "-";
                    }
                    ?>
                </td>

                <td class="text-center"><?= htmlspecialchars($fila["videojuego_codigo"] ?? ''); ?></td>

                <td class="text-center">
                    <form action="torneo_delete.php" method="post">
                        <input hidden type="text" name="codigoEliminar" value="<?= htmlspecialchars($fila["codigo_torneo"]); ?>">
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </form>
                </td>

            </tr>

            <?php
            endforeach;
            ?>

        </tbody>

    </table>
</div>

<?php
endif;

include "../includes/footer.php";
?>