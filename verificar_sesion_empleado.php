<?php
session_start();

if (!isset($_SESSION['empleado_dni'])) {
    header("Location: login.php");
    exit();
}
?>