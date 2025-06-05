<?php
$modalVehiculoNoRegistrado = false;
$modalOrdenNoExiste = false;
$modalOrdenFinalizada = false;
$modalVehiculoNoEncontrado = false;
$modalOrdenNoEncontrada = false;
$modalOrdenFinalizada = false;

try {
    $pdo = new PDO("mysql:host=localhost;dbname=bdd_taller_mecanico_mysql", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['buscar_rec'])) {
        $patente = strtoupper(trim($_GET['historial'] ?? ''));
        $ordenNum = trim($_GET['trabajos_realizar'] ?? '');

        if (!empty($patente)) {
            $stmt = $pdo->prepare("SELECT * FROM vehiculos WHERE vehiculo_patente = :patente");
            $stmt->execute(['patente' => $patente]);

            if ($stmt->rowCount() > 0) {
                header("Location: historico_vehiculo.php?patente=" . urlencode($patente));
                exit();
            } else {
                $modalVehiculoNoRegistrado = true;
            }
        } elseif (!empty($ordenNum)) {
            $stmt = $pdo->prepare("
                SELECT ot.orden_estado
                FROM orden_trabajo ot
                JOIN ordenes o ON o.orden_numero = ot.orden_numero
                WHERE o.orden_numero = :orden
                LIMIT 1
            ");
            $stmt->execute(['orden' => $ordenNum]);

            if ($stmt->rowCount() === 0) {
                $modalOrdenNoExiste = true;
            } else {
                $estado = $stmt->fetchColumn();
                if ($estado == 1) {
                    $modalOrdenFinalizada = true;
                } else {
                    header("Location: ordenes_pendientes.php?orden=" . urlencode($ordenNum));
                    exit();
                }
            }
        }
    }
} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>"> 
    <title>Document</title>
</head>

<!-- MODAL VEHICULO NO REGISTRADO -->
<?php if ($modalVehiculoNoRegistrado): ?>
<dialog open id="modal_vehiculo_no_registrado">
    <p style="text-align:center;"><strong>Vehículo no registrado</strong></p>
    <div style="text-align:center;">
        <button onclick="document.getElementById('modal_vehiculo_no_registrado').close()">Cerrar</button>
    </div>
</dialog>
<?php endif; ?>

<!-- MODAL ORDEN NO EXISTE -->
<?php if ($modalOrdenNoExiste): ?>
<dialog open id="modal_orden_inexistente">
    <p style="text-align:center;"><strong>Orden inexistente</strong></p>
    <div style="text-align:center;">
        <button onclick="document.getElementById('modal_orden_inexistente').close()">Cerrar</button>
    </div>
</dialog>
<?php endif; ?>

<!-- MODAL ORDEN FINALIZADA -->
<?php if ($modalOrdenFinalizada): ?>
<dialog open id="modal_orden_finalizada">
    <p style="text-align:center;"><strong>La orden ya fue finalizada</strong></p>
    <div style="text-align:center;">
        <button onclick="document.getElementById('modal_orden_finalizada').close()">Cerrar</button>
    </div>
</dialog>
<?php endif; ?>

<body>

    <?php 
        include("nav_mecanico.php");
    ?>
    <br><br><br><br>
    <section class="form_consulta">
        <div class="imagen_cons1">
            <img src="iconos/form_empleados.png" alt="">
        </div>
        <h2 class="consulta">Consultas</h2>
        <form action="" class="consulta">

            <div class="cons_historial">
                <label for="historial">HISTORIAL DEL VEHICULO</label>
                <h3>Patente del vehiculo</h3>
                <input type="text" name="historial" id="historial">
            </div>
            <br>
            <div class="cons_trabajo">
                <label for="trabajos_realizar">TRABAJOS A REALIZAR</label>
                <h3>Numero de Orden</h3>
                <input type="text" name="trabajos_realizar" id="trabajos_realizar">
            </div>
            <br>
            <input class="buscar_mec" type="submit" value="Buscar" name="buscar_rec" class="form2_orden2">
        </form>
        <div class="imagen_cons2">
            <img src="iconos/form_empleados.png" alt="">
        </div>
    </section>

    <br><br><br><br><br>
    



    <?php 
        include("piedepagina.php");
    ?>

</body>
</html>