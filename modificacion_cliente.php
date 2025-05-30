<?php
session_start();

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// VARIABLES DE MODAL
$modalGuardadoExito = false;
$modalTurnoExito = false;
$modalErrorMail = false;

// PERMITIR OBTENER EL DNI DESDE GET o SESSION
if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
} elseif (isset($_SESSION['cliente_dni'])) {
    $dni = $_SESSION['cliente_dni'];
} else {
    header("Location: login.php");
    exit();
}

$mensaje = "";

$pdo = new PDO("mysql:host=localhost;dbname=bdd_taller_mecanico_mysql", "root", "");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$stmt = $pdo->prepare("SELECT * FROM clientes WHERE cliente_DNI = :dni");
$stmt->execute(['dni' => $dni]);
$cliente = $stmt->fetch();

// GUARDAR CAMBIOS
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['guardar_rec'])) {
    $direccion = $_POST['direccion'];
    $localidad = $_POST['localidad'];
    $telefono  = $_POST['telefono'];
    $correo    = $_POST['correo'];
    $clave     = $_POST['clave'];

    $update = $pdo->prepare("UPDATE clientes SET cliente_direccion = :dir, cliente_localidad = :loc, cliente_telefono = :tel, cliente_email = :mail, cliente_contrasena = :clave WHERE cliente_DNI = :dni");
    $update->execute([
        'dir'   => $direccion,
        'loc'   => $localidad,
        'tel'   => $telefono,
        'mail'  => $correo,
        'clave' => $clave,
        'dni'   => $dni
    ]);

    $modalGuardadoExito = true;
}

// SOLICITAR TURNO
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['solicitar'])) {
    $clienteCorreo = $cliente['cliente_email'];
    $clienteNombre = $cliente['cliente_nombre'];

    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->Port       = 587;
        $mail->SMTPAuth   = true;
        $mail->Username   = 'wasportaller@gmail.com';
        $mail->Password   = 'kyozoppabnumingu';
        $mail->SMTPSecure = 'tls';

        // MAIL AL CLIENTE
        $mail->setFrom('wasportaller@gmail.com', 'WA SPORT');
        $mail->addAddress($clienteCorreo);
        $mail->Subject = 'Solicitud de turno recibida';
        $mail->Body    = 'Hemos recibido su solicitud de turno. A la brevedad será atendido.';
        $mail->send();

        // MAIL AL TALLER
        $mail->clearAddresses();
        $mail->addAddress('wasportaller@gmail.com');
        $mail->Subject = 'Nuevo turno solicitado';
        $mail->Body    = "El cliente $clienteNombre (DNI: $dni) ha solicitado un turno.";
        $mail->send();

        $modalTurnoExito = true;
    } catch (Exception $e) {
        $modalErrorMail = true;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
    <title>Modificar Cliente</title>
</head>
<body>
<?php include("navegador.php"); ?>
<br>
<section class="modif_cli">
    <img class="modif_img1" src="fondos/hola.usuario.jpg" alt="">

    <section class="modificar_cliente">
        <h2>Hola <?= htmlspecialchars($cliente['cliente_nombre']) ?></h2>
        <h3>Modificar Datos</h3>
        <form method="post">
            <table>
                <tr>
                    <th>DNI</th>
                    <td class="datos_unicos"><?= htmlspecialchars($cliente['cliente_DNI']) ?></td>
                </tr>
                <tr>
                    <th>NOMBRE</th>
                    <td class="datos_unicos"><?= htmlspecialchars($cliente['cliente_nombre']) ?></td>
                </tr>
                <tr>
                    <th>Dirección</th>
                    <td><input type="text" class="datos_modificados" name="direccion" value="<?= htmlspecialchars($cliente['cliente_direccion']) ?>"></td>
                </tr>
                <tr>
                    <th>Localidad</th>
                    <td><input type="text" class="datos_modificados" name="localidad" value="<?= htmlspecialchars($cliente['cliente_localidad']) ?>"></td>
                </tr>
                <tr>
                    <th>Teléfono</th>
                    <td><input type="text" class="datos_modificados" name="telefono" value="<?= htmlspecialchars($cliente['cliente_telefono']) ?>"></td>
                </tr>
                <tr>
                    <th>E-Mail</th>
                    <td><input type="email" class="datos_modificados" name="correo" value="<?= htmlspecialchars($cliente['cliente_email']) ?>"></td>
                </tr>
                <tr>
                    <th>Contraseña</th>
                    <td><input type="text" class="datos_modificados" name="clave" value="<?= htmlspecialchars($cliente['cliente_contrasena']) ?>"></td>
                </tr>
            </table>
            <div>
                <input class="guardar_mod" type="submit" value="Guardar" name="guardar_rec">
                <input class="solicitar_mod" type="submit" value="Solicitar Turno" name="solicitar">
            </div>
        </form>
    </section>

    <img class="modif_img2" src="fondos/hola.usuario.jpg" alt="">
</section>

<!-- MODAL DATOS GUARDADOS -->
<?php if ($modalGuardadoExito): ?>
<dialog open>
    <p><strong>Datos guardados con éxito.</strong></p>
    <form method="get" action="modificacion_cliente.php">
        <button type="submit">Aceptar</button>
    </form>
</dialog>
<?php endif; ?>

<!-- MODAL TURNO SOLICITADO -->
<?php if ($modalTurnoExito): ?>
<dialog open>
    <p><strong>Turno solicitado con éxito.</strong></p>
    <form method="get" action="modificacion_cliente.php">
        <button type="submit">Aceptar</button>
    </form>
</dialog>
<?php endif; ?>

<!-- MODAL ERROR ENVIO DE CORREO -->
<?php if ($modalErrorMail): ?>
<dialog open>
    <p><strong>Error al enviar el correo.</strong></p>
    <form method="get" action="modificacion_cliente.php">
        <button type="submit">Volver</button>
    </form>
</dialog>
<?php endif; ?>

<br>
<?php include("piedepagina.php"); ?>
</body>
</html>