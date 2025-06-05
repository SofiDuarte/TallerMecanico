<?php
// PERMITIR ORTENER EL DNI DESDE GET o SESSION
if (isset($_GET['dni'])) {
    $dni = $_GET['dni'];
} elseif (isset($_SESSION['cliente_dni'])) {
    $dni = $_SESSION['cliente_dni'];
} else {
    header("Location: login.php");
    exit();
}
