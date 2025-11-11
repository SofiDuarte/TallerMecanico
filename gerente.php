<?php
require 'conexion_base.php';
require_once 'verificar_sesion_empleado.php';

// DNI DEL GERENTE LOGEADO
$mecDni = $_SESSION['empleado_DNI'] ?? $_SESSION['empleado_dni'] ?? null;

include 'nav_gerente.php';
?>
<div class="container">
    <h2>Panel del Gerente</h2>
    <hr>

    <!-- SECCIÓN PRODUCTOS -->
    <section>
        <h3>Productos</h3>

        <!-- GET para que se vean los filtros en la URL y se mantengan -->
        <form action="productos.php" method="GET" class="form-busqueda">
            <label>Código:</label>
            <input type="text" name="codigo" placeholder="Ej: LUB001">

            <label>Categoría:</label>
            <input type="text" name="categoria" placeholder="Ej: Lubricantes">

            <label>Descripción:</label>
            <input type="text" name="descripcion" placeholder="Buscar descripción">

            <label style="margin-left:10px;">
                <input type="checkbox" name="incluir_no_disponibles" value="1">
                Incluir no disponibles
            </label>

            <button type="submit" name="buscar">Buscar</button>
            <!-- Botón que abre el modal "Nuevo" en productos.php -->
            <button type="submit" name="nuevo" value="1">Nuevo</button>
        </form>
    </section>

    <hr>

    <!-- SECCIÓN ESTADÍSTICAS -->
    <section>
        <h3>Estadísticas</h3>
        <form action="estadisticas.php" method="GET" class="form-busqueda">
            <label>Tipo:</label>
            <select name="tipo">
                <option value="servicios">Servicios</option>
                <option value="ventas">Ventas</option>
            </select>

            <label>Desde:</label>
            <input type="date" name="desde">

            <label>Hasta:</label>
            <input type="date" name="hasta">

            <button type="submit" name="buscar_estadisticas">Buscar</button>
        </form>
    </section>
</div>

<style>
.container { width: 90%; margin: auto; padding: 20px; }
.form-busqueda input, .form-busqueda select { margin-right: 10px; padding: 5px; }
button { padding: 6px 12px; cursor: pointer; }
</style>
