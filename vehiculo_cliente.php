<?php
session_start();
require_once 'conexion_base.php';
require_once 'verificar_sesion_cliente.php';

$dni = $_SESSION['cliente_dni'];

// Obtener vehículos del cliente
$stmt = $conexion->prepare("SELECT * FROM vehiculos WHERE cliente_DNI = :dni");
$stmt->execute(['dni' => $dni]);
$vehiculos = $stmt->fetchAll(PDO::FETCH_ASSOC);
$sinVehiculos = count($vehiculos) === 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
    <title>Mis Vehículos</title>

    <style>
        table {
            width: 90%;
            margin: 0px 70px;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;

        }
        th, td {
            border: 1px solid #000;
            padding: 2px 10px; 
            text-align: center;
            font-family: 'AlumniSans_Light';
            font-size: 18px;
            /*height: 10px;*/
            line-height: 1.1; 
            vertical-align: middle;
        }
        th {
            background-color: #ddd;
            font-family: 'Big_Shoulders_Medium';
            font-size: 20px;
            /*height: 30px;*/
            padding: 2px 10px;
        }
        .boton_historial {
            background: none;
            border: none;
            font-size: 18px;
            cursor: pointer;
        }
    </style>

</head>
<body>
    <?php include("nav_cli.php"); ?>
    <br>
    <section class="modif_cli">
        <img class="modif_img1" src="fondos/hola.usuario.jpg" alt="">
        <section class="modificar_cliente">
            <h2>Mis Vehículos</h2>
            <?php if ($sinVehiculos): ?>
                <dialog open>
                    <p><strong>No tiene vehículos registrados.</strong></p>
                    <form method="get" action="modificacion_cliente.php">
                        <button type="submit">Volver</button>
                    </form>
                    <form method="get" action="registro_vehiculo.php">
                        <button type="submit">Registrar Vehículo</button>
                    </form>
                </dialog>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Patente</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Año</th>
                        <th>Kilometraje</th>
                        <th>Histórico</th>
                    </tr>
                    <?php foreach ($vehiculos as $vehiculo): ?>
                        <?php
                        // Último kilometraje del vehículo
                        $stmtKm = $conexion->prepare(" SELECT orden_kilometros 
                            FROM orden_trabajo ot
                            JOIN ordenes o ON ot.orden_numero = o.orden_numero
                            WHERE o.vehiculo_patente = :patente
                            ORDER BY o.orden_fecha DESC 
                            LIMIT 1
                        ");
                        $stmtKm->execute(['patente' => $vehiculo['vehiculo_patente']]);
                        $km = $stmtKm->fetchColumn();
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($vehiculo['vehiculo_patente']) ?></td>
                            <td><?= htmlspecialchars($vehiculo['vehiculo_marca']) ?></td>
                            <td><?= htmlspecialchars($vehiculo['vehiculo_modelo']) ?></td>
                            <td><?= htmlspecialchars($vehiculo['vehiculo_anio']) ?></td>
                            <td><?= $km ? htmlspecialchars($km) . ' km' : 'Sin datos' ?></td>
                            <td>
                                <a href="historico_vehiculo_cliente.php?patente=<?= urlencode($vehiculo['vehiculo_patente']) ?>" 
                                class="boton_historial" title="Ver histórico"> 🔍
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                <br>
                <div style="text-align:center;">
                    <a href="modificacion_cliente.php" class="solicitar_mod">Volver</a>
                </div>
            <?php endif; ?>
        </section>
        <img class="modif_img2" src="fondos/hola.usuario.jpg" alt="">
    </section>
    <br>
    <?php include("piedepagina.php"); ?>
    <script src="control_inactividad.js"></script>
</body>
</html>