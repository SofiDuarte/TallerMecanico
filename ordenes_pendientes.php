<?php
require_once 'conexion_base.php';
require_once 'verificar_sesion_empleado.php';

$modalError = false;
$modalFinalizado = false;
$modalExito = false;
$mensajeModal = "";

$datosOrden = [];

try {
    $ordenNum = trim($_GET['orden'] ?? '');

    if (empty($ordenNum)) {
        $modalError = true;
        $mensajeModal = "No se especificó el número de orden.";
    } else {
        // OBTENER DATOS DE ORDEN
        $stmt = $conexion->prepare(" SELECT o.orden_numero, o.vehiculo_patente, o.orden_fecha,
                   v.vehiculo_marca, v.vehiculo_modelo, v.vehiculo_anio,
                   s.servicio_nombre, ot.complejidad, ot.orden_kilometros,
                   ot.orden_comentario, ot.orden_estado
            FROM ordenes o
            JOIN vehiculos v ON o.vehiculo_patente = v.vehiculo_patente
            JOIN orden_trabajo ot ON o.orden_numero = ot.orden_numero
            JOIN servicios s ON ot.servicio_codigo = s.servicio_codigo
            WHERE o.orden_numero = :orden
        ");
        $stmt->execute(['orden' => $ordenNum]);

        if ($stmt->rowCount() === 0) {
            $modalError = true;
            $mensajeModal = "La orden no existe.";
        } else {
            $datosOrden = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($datosOrden['orden_estado'] == 1) {
                $modalFinalizado = true;
            }
        }
    }

        // FINALIZAR ORDEN SI ENVIO FORMULARIO
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['finalizar'])) {
            $ordenNum = $_POST['orden_numero'];
            $complejidad = $_POST['complejidad'];
            $km = $_POST['orden_kilometros'];
            $comentario = $_POST['orden_comentario'];

            // VERIFICAR QUE KILOMETRAJE SEA NÚMERO ENTERO POSITIVO
            if (!ctype_digit($km)) {
                $modalError = true;
                $mensajeModal = "El kilometraje debe ser un número entero positivo.";
            } else {
                $km = (int)$km; // Asegura tipo entero

                // VERIFICAR QUE KILOMETRAJE NO SEA MENOR AL ANTERIOR
                $stmt = $conexion->prepare(" SELECT orden_kilometros FROM orden_trabajo
                    WHERE orden_numero = :orden
                ");
                $stmt->execute(['orden' => $ordenNum]);
                $kmActual = (int)($stmt->fetchColumn() ?? 0);

                if ($km < $kmActual) {
                    $modalError = true;
                    $mensajeModal = "El kilometraje ingresado ($km) no puede ser menor al actual ($kmActual).";
                }
            }   
             
            // SOLO ACTUALIZA SI NO HUBO ERRORES
            if (!$modalError) {
                $stmt = $conexion->prepare("UPDATE orden_trabajo
                    SET complejidad = :comp, orden_kilometros = :km,
                        orden_comentario = :comentario, orden_estado = 1
                    WHERE orden_numero = :orden
                ");
                $stmt->execute([
                    'comp' => $complejidad,
                    'km' => $km,
                    'comentario' => $comentario,
                    'orden' => $ordenNum
                ]);
                $modalExito = true;
                
            }
        }

} catch (PDOException $e) {
    $modalError = true;
    $mensajeModal = "Error en la base de datos: " . $e->getMessage();
}
?>
<<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Orden Pendiente</title>
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
</head>
<body>
    <?php include("nav_mecanico.php"); ?>
    <br>

    <!-- MODAL ERROR -->
    <?php if ($modalError): ?>
    <dialog open>
        <p style="text-align:center;"><strong><?= htmlspecialchars($mensajeModal) ?></strong></p>
        <div style="text-align:center;"><button onclick="this.closest('dialog').close()">Cerrar</button></div>
    </dialog>

    <!-- MODAL FINALIZADO -->
    <?php elseif ($modalFinalizado): ?>
    <dialog open>
        <p style="text-align:center;"><strong>La orden ya fue finalizada.</strong></p>
        <div style="text-align:center;"><button onclick="this.closest('dialog').close()">Cerrar</button></div>
    </dialog>

    <!-- MODAL EXITO -->
    <?php elseif ($modalExito): ?>
    <dialog open>
        <p style="text-align:center;"><strong>Orden Nº <?= htmlspecialchars($ordenNum) ?> finalizada con éxito.</strong></p>
        <form method="get" action="mecanico.php" style="text-align:center;">
            <button type="submit">Volver</button>
        </form>
    </dialog>
    <?php endif; ?>

    <?php if (!empty($datosOrden) && !$modalFinalizado): ?>
    <section class="nueva_ord">
        <h2 class="recepcion_titulos">
            <?= htmlspecialchars($datosOrden['vehiculo_patente']) ?> - 
            <?= htmlspecialchars($datosOrden['vehiculo_marca']) ?> - 
            <?= htmlspecialchars($datosOrden['vehiculo_modelo']) ?> - 
            <?= htmlspecialchars($datosOrden['vehiculo_anio']) ?>
        </h2>

        <form method="post" class="form_orden">
            <input type="hidden" name="orden_numero" value="<?= htmlspecialchars($datosOrden['orden_numero']) ?>">

            <p><strong>Servicio:</strong> <?= htmlspecialchars($datosOrden['servicio_nombre']) ?></p>

            <label for="complejidad" class="compl_form">Complejidad</label>
            <select name="complejidad" id="complejidad" class="compl_form1">
                <option value="1" <?= $datosOrden['complejidad'] == 1 ? 'selected' : '' ?>>Baja (1)</option>
                <option value="2" <?= $datosOrden['complejidad'] == 2 ? 'selected' : '' ?>>Media (2)</option>
                <option value="3" <?= $datosOrden['complejidad'] == 3 ? 'selected' : '' ?>>Alta (3)</option>
            </select>

            <label for="orden_kilometros" class="kil_form">Kilometraje</label>
            <input type="text" name="orden_kilometros" id="orden_kilometros" class="kil_form1" value="<?= htmlspecialchars($datosOrden['orden_kilometros']) ?>" required>

            <label for="orden_comentario" class="serv_desc">Comentario</label>
            <textarea name="orden_comentario" id="orden_comentario" class="serv_desc1"><?= htmlspecialchars($datosOrden['orden_comentario']) ?></textarea>

            <input class="guardar_rec" type="submit" value="Finalizar" name="finalizar">
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
            <a href="exportar_pdf_orden.php?orden=<?= htmlspecialchars($datosOrden['orden_numero']) ?>" 
            target="_blank" class="imprimir_rec" style="text-align: center; text-decoration: none; line-height: 34.67px;">
                🖨️ Imprimir
            </a>            
            </div>
        </form>
    </section>
    <?php endif; ?>
    <br>
    <?php include("piedepagina.php"); ?>
    <script src="control_inactividad.js"></script>
</body>
</html>