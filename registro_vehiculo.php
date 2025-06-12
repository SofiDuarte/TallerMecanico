<?php
session_start();
require_once 'conexion_base.php';
require_once 'verificar_sesion_cliente.php';

$modalVehiculoGuardado = false;

$dni = $_SESSION['cliente_dni'];

//OBTENER LOS DATOS DEL CLIENTE
$stmt = $conexion->prepare("SELECT cliente_nombre FROM clientes WHERE cliente_DNI = :dni");
$stmt->execute(['dni' => $dni]);
$cliente = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['guardar_vehiculo'])) {
    $patente = strtoupper(trim($_POST['patente']));
    $marca = $_POST['marca'];
    $modelo = $_POST['modelo'];
    $anio = $_POST['anio'];
    $km_actual = $_POST['km_actual'];
    $dni_cliente = $_SESSION['cliente_dni'];

    $insert = $conexion->prepare("INSERT INTO vehiculos (vehiculo_patente, vehiculo_marca, vehiculo_modelo, vehiculo_anio, vehiculo_km, cliente_DNI)
                                  VALUES (:pat, :marca, :modelo, :anio, :km, :dni)");
    $insert->execute([
        'pat'   => $patente,
        'marca' => $marca,
        'modelo'=> $modelo,
        'anio'  => $anio,
        'km'    => $km_actual,
        'dni'   => $dni_cliente
    ]);

    $modalVehiculoGuardado = true;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
    <title>Registrar Vehículo</title>
</head>
<body>
    <?php include("nav_cli.php"); ?>
    <br>
    <section class="modif_cli">
        <img class="modif_img1" src="fondos/hola.usuario.jpg" alt="">

        <section class="modificar_cliente">
            <h2><?= htmlspecialchars($cliente['cliente_nombre']) ?></h2>
            <h3>Registrar Nuevo Vehículo</h3>
            <form method="post">
                <table>
                    <tr>
                        <th>Patente</th>
                        <td><input type="text" class="datos_modificados" name="patente" required maxlength="10"></td>
                    </tr>
                    <tr>
                        <th>Marca</th>
                        <td><input type="text" class="datos_modificados" name="marca" required></td>
                    </tr>
                    <tr>
                        <th>Modelo</th>
                        <td><input type="text" class="datos_modificados" name="modelo" required></td>
                    </tr>
                    <tr>
                        <th>Año</th>
                        <td><input type="number" class="datos_modificados" name="anio" required min="1900" max="<?= date('Y') ?>"></td>
                    </tr>
                    <tr>
                        <th>Kilometraje</th>
                        <td><input type="number" class="datos_modificados" name="km_actual" required min="0"></td>
                    </tr>
                </table>
                <div class="bot_modf">
                    <input class="solicitar_mod" type="submit" name="guardar_vehiculo" value="Registrar Vehículo">
                    <a class="solicitar_mod" href="http://localhost/tallermecanico/modificacion_cliente.php">Volver</a>
                </div>
            </form>
        </section>

        <img class="modif_img2" src="fondos/hola.usuario.jpg" alt="">
    </section>

    <!-- MODAL VEHICULO GUARDADO -->
    <?php if ($modalVehiculoGuardado): ?>
    <dialog open>
        <p><strong>Vehículo registrado con éxito.</strong></p>
        <form method="get" action="vehiculo_cliente.php">
            <button type="submit">Aceptar</button>
        </form>
    </dialog>
    <?php endif; ?>

    <br>
    <?php include("piedepagina.php"); ?>
    <script src="control_inactividad.js"></script>
</body>
</html>