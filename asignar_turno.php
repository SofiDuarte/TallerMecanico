<?php
session_start();
require_once 'conexion_base.php';
require_once 'verificar_sesion_empleado.php';

// Incluir PHPMailer
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// VARIABLES PARA MODALES
$modalTurnoOK = false;
$modalTurnoError = false;
$modalFaltanDatos = false;

// Datos recibidos desde turnos.php
$fecha = $_POST['fecha'] ?? '';
$hora = $_POST['hora'] ?? '';

// Al enviar formulario de asignar turno
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['asignar'])) {
    $cliente_dni = $_POST['cliente_dni'] ?? '';
    $patente = $_POST['vehiculo_patente'] ?? '';
    $servicio = $_POST['servicio_codigo'] ?? '';
    $mecanico = $_POST['mecanico_dni'] ?? '';
    $km = $_POST['orden_kilometros'] ?? '';
    $comentario = $_POST['orden_comentario'] ?? '';

    if (empty($cliente_dni) || empty($patente) || empty($servicio) || empty($mecanico) || empty($km)) {
        $modalFaltanDatos = true;
    } else {
        try {
            $conexion->beginTransaction();

            // Obtener nuevo numero de orden
            $stmtMax = $conexion->query("SELECT MAX(orden_numero) FROM ordenes");
            $nuevoNumero = $stmtMax->fetchColumn() + 1;

            // Insertar orden
            $stmtOrden = $conexion->prepare("INSERT INTO ordenes (orden_numero, orden_fecha, vehiculo_patente) VALUES (?, ?, ?)");
            $stmtOrden->execute([$nuevoNumero, $fecha, $patente]);

            // Insertar turno
            $stmtTurno = $conexion->prepare("INSERT INTO turnos (turno_fecha, turno_hora, turno_estado, turno_comentario, mecanico_DNI, cliente_DNI, vehiculo_patente) VALUES (?, ?, 'pendiente', ?, ?, ?, ?)");
            $stmtTurno->execute([$fecha, $hora, $comentario, $mecanico, $cliente_dni, $patente]);
            $turno_id = $conexion->lastInsertId();

            // Insertar orden_trabajo
            $stmtTrabajo = $conexion->prepare("INSERT INTO orden_trabajo (orden_numero, orden_kilometros, orden_comentario, turno_id, servicio_codigo, complejidad, mecanico_DNI) VALUES (?, ?, ?, ?, ?, 1, ?)");
            $stmtTrabajo->execute([$nuevoNumero, $km, $comentario, $turno_id, $servicio, $mecanico]);

            // ENVIAR MAIL DE CONFIRMACIÓN
            $queryMail = $conexion->prepare("
                SELECT c.cliente_nombre, c.cliente_email, 
                       v.vehiculo_marca, v.vehiculo_modelo, 
                       s.servicio_nombre, s.servicio_descripcion,
                       t.turno_fecha, t.turno_hora
                FROM turnos t
                JOIN clientes c ON t.cliente_DNI = c.cliente_DNI
                JOIN vehiculos v ON t.vehiculo_patente = v.vehiculo_patente
                JOIN orden_trabajo ot ON t.turno_id = ot.turno_id
                JOIN servicios s ON ot.servicio_codigo = s.servicio_codigo
                WHERE t.turno_id = :turno_id
            ");
            $queryMail->execute([':turno_id' => $turno_id]);
            $datos = $queryMail->fetch(PDO::FETCH_ASSOC);
            if (!$datos) {
                echo "<pre>NO SE ENCONTRARON DATOS PARA EL EMAIL</pre>";
                exit();
            } else {
                echo "<pre>DATOS DEL MAIL:</pre>";
                print_r($datos); // Mostrá que vino bien cliente_email, nombre, etc.
            }
            if ($datos) {
                try {
                    $mail = new PHPMailer(true);
                    $mail->SMTPDebug = 2;
                    $mail->Debugoutput = 'html';
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com';
                    $mail->SMTPAuth = true;
                    $mail->Username = 'wasportaller@gmail.com';
                    $mail->Password = 'kyozoppabnumingu'; // App password
                    $mail->SMTPSecure = 'tls';
                    $mail->Port = 587;

                    $mail->setFrom('wasportaller@gmail.com', 'WA SPORT');
                    $mail->addAddress($datos['cliente_email'], $datos['cliente_nombre']);
                    $mail->isHTML(true);
                    $mail->Subject = 'Confirmación de turno asignado - WA SPORT';
                    $mail->Body = "
                        <h2>Hola {$datos['cliente_nombre']},</h2>
                        <p>Te confirmamos que tu turno fue asignado con éxito.</p>
                        <p><strong>Fecha:</strong> {$datos['turno_fecha']}<br>
                        <strong>Hora:</strong> {$datos['turno_hora']}<br>
                        <strong>Vehículo:</strong> {$datos['vehiculo_marca']} {$datos['vehiculo_modelo']}<br>
                        <strong>Servicio:</strong> {$datos['servicio_nombre']}<br>
                        <strong>Descripción:</strong> {$datos['servicio_descripcion']}</p>
                        <p><em>Por favor, concurrí 10 minutos antes del horario establecido.</em></p>
                        <br>
                        <p>Gracias por elegirnos.<br>Equipo de <strong>WA SPORT</strong>.</p>
                    ";

                    try {
                        $mail->send();
                        echo "<p><strong>MAIL ENVIADO CON ÉXITO</strong></p>";
                    } catch (Exception $e) {
                        echo "<p><strong>Error al enviar correo:</strong> {$mail->ErrorInfo}</p>";
                    }
                } catch (Exception $e) {
                    // El turno se guarda igual aunque falle el envío de mail
                    error_log("Error al enviar email: " . $mail->ErrorInfo);
                }
            }

            $conexion->commit();
            $modalTurnoOK = true;
        } catch (PDOException $e) {
            $conexion->rollBack();
            $modalTurnoError = true;
        }
    }
}

// Traer datos para desplegables
$servicios = $conexion->query("SELECT servicio_codigo, servicio_nombre FROM servicios")->fetchAll(PDO::FETCH_ASSOC);
$mecanicos = $conexion->query("SELECT empleado_DNI, empleado_nombre FROM empleados WHERE empleado_roll = 'mecanico'")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Asignar Turno</title>
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
</head>

<!-- MODALES -->
<?php if ($modalTurnoOK): ?>
<dialog open>
    <p style="text-align:center;"><strong>Turno registrado con éxito</strong></p>
    <div style="text-align:center;">
        <form method="post" action="turnos.php">
            <button type="submit">Aceptar</button>
        </form>
    </div>
</dialog>
<?php endif; ?>

<?php if ($modalTurnoError): ?>
<dialog open>
    <p style="text-align:center;"><strong>Error al registrar el turno</strong></p>
    <div style="text-align:center;">
        <form method="post" action="turnos.php">
            <button type="submit">Volver</button>
        </form>
    </div>
</dialog>
<?php endif; ?>

<?php if ($modalFaltanDatos): ?>
<dialog open>
    <p style="text-align:center;"><strong>Debe completar todos los campos obligatorios</strong></p>
    <div style="text-align:center;">
        <form method="post" action="turnos.php">
            <button type="submit">Volver</button>
        </form>
    </div>
</dialog>
<?php endif; ?>

<body>
<?php include("nav_rec.php"); ?>
<section class="turno_asig">
     <img class="turno-asig_img" src="fondos/mecanico_fond2.jpg" alt="">

    <form method="post" class="turnos_form_asig">
        <h2>Asignar nuevo turno</h2>   
        <input type="hidden" name="fecha" value="<?= htmlspecialchars($fecha) ?>">
        <input type="hidden" name="hora" value="<?= htmlspecialchars($hora) ?>">

        <label for="cliente_dni">DNI Cliente</label>
        <input type="text" name="cliente_dni" id="cliente_dni" required>

        <label for="vehiculo_patente">Patente Vehículo</label>
        <input type="text" name="vehiculo_patente" id="vehiculo_patente" required>

        <label for="servicio_codigo">Servicio</label>
        <select name="servicio_codigo" id="servicio_codigo" required>
            <option value="">Seleccione</option>
            <?php foreach ($servicios as $s): ?>
                <option value="<?= $s['servicio_codigo'] ?>"><?= $s['servicio_nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="mecanico_dni">Mecánico</label>
        <select name="mecanico_dni" id="mecanico_dni" required>
            <option value="">Seleccione</option>
            <?php foreach ($mecanicos as $m): ?>
                <option value="<?= $m['empleado_DNI'] ?>"><?= $m['empleado_nombre'] ?></option>
            <?php endforeach; ?>
        </select>

        <label for="orden_kilometros">Kilometraje</label>
        <input type="number" name="orden_kilometros" id="orden_kilometros" required>

        <label for="orden_comentario">Comentario</label>
        <textarea name="orden_comentario" id="orden_comentario" class="turnos_area"></textarea>
        <div class="boton-turno_asig">
            <input  type="submit" name="asignar" value="Guardar Turno">
            <br>
            <a href="turnos.php" >Cancelar</a>
        </div>
    </form>
     <img class="turno-asig_img" src="fondos/mecanico_fond2.jpg" alt="">

</section>
<?php include("piedepagina.php"); ?>
<script src="control_inactividad.js"></script>
</body>
</html>
