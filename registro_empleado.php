<?php
$mensajeRegistro = "";
$modalEmpleadoRegistrado = false;

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['registrar_empleado'])) {
    try {
        $pdo = new PDO("mysql:host=localhost;dbname=bdd_taller_mecanico_mysql;port=3307", "root", "");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $dni = $_POST['dni_empleado'];
        $nombre = $_POST['nombre_empleado'];
        $email = $_POST['email_empleado'];
        $rol = $_POST['rol_empleado'];
        $clave_original = $_POST['clave_empleado'];
        $clave = password_hash($clave_original, PASSWORD_DEFAULT);

        // Verificar si el DNI ya existe
        $check = $pdo->prepare("SELECT * FROM empleados WHERE empleado_DNI = :dni");
        $check->execute(['dni' => $dni]);

        if ($check->rowCount() > 0) {
            $mensajeRegistro = "Ya existe un empleado con ese DNI.";
        } else {
            $insert = $pdo->prepare("INSERT INTO empleados (empleado_DNI, empleado_nombre, empleado_email, empleado_roll, empleado_contrasena)
                VALUES (:dni, :nombre, :email, :rol, :clave)");

            $insert->execute([
                'dni' => $dni,
                'nombre' => $nombre,
                'email' => $email,
                'rol' => $rol,
                'clave' => $clave
            ]);

            $modalEmpleadoRegistrado = true;
            goto fin;
        }

    } catch (PDOException $e) {
        $mensajeRegistro = "Error de conexión: " . $e->getMessage();
    }
}
fin:
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro Empleado</title>
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>">
</head>

<!-- MODAL REGISTRO EXISTOSO -->
<?php if ($modalEmpleadoRegistrado): ?>
<dialog open id="modal_empleado_registrado">
    <p style="text-align:center;"><strong>Empleado registrado con éxito</strong></p>
    <div style="text-align:center;">
        <form method="get" action="login.php">
            <button type="submit">Ir a login</button>
        </form>
    </div>
</dialog>
<?php endif; ?>

<body>
<?php include("navegador.php"); ?>

<br><br>

<section class="pagina_registro">
    <img src="fondos/registro_cli.jpg" alt="" class="img_registro1">
    
    <section class="registro">
        <img class="logo_registro" src="iconos/form_empleados.png" alt="">
        <h3>Registro de Empleados</h3>

        <form method="post" class="form_registro">
            <label for="dni_empleado">DNI</label>
            <br><br>
            <input type="text" name="dni_empleado" id="dni_empleado" placeholder="Ej: 12345678" required>
            <br><br>
            <label for="nombre_empleado">Nombre</label>
            <br><br>
            <input type="text" name="nombre_empleado" id="nombre_empleado" placeholder="Ej: Ana Pérez" required>
            <br><br>
            <label for="email_empleado">Email</label>
            <br><br>
            <input type="email" name="email_empleado" id="email_empleado" placeholder="Ej: ana@mail.com" required>
            <br><br>
            <label for="rol_empleado">Rol</label>
            <br><br>
            <select name="rol_empleado" id="rol_empleado" required>
                <option value="">Seleccione un rol</option>
                <option value="recepcionista">Recepcionista</option>
                <option value="mecanico">Mecánico</option>
            </select>
            <br><br>
            <label for="clave_empleado">Contraseña</label>
            <br><br>
            <input type="password" name="clave_empleado" id="clave_empleado"
                placeholder="Ingrese una contraseña segura"
                minlength="8" maxlength="15"
                pattern="^(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,15}$"
                title="Debe tener entre 8 y 15 caracteres, al menos una mayúscula, un número y un símbolo"
                required>

            <div class="bot_registro">
                <button class="boton_registro" type="submit" name="registrar_empleado">Registrar</button>
            </div>
        </form>

        <?php if ($mensajeRegistro): ?>
            <br>
            <p style="color:red; text-align:center; font-weight:bold;"><?= $mensajeRegistro ?></p>
        <?php endif; ?>
    </section>

    <img src="fondos/registro_cli.jpg" alt="" class="img_registro2">
</section>

<br><br>
<?php include("piedepagina.php"); ?>
</body>
</html>
