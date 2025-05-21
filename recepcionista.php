<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="estilopagina.css"> 
    <title>Document</title>
</head>
<body>

    <?php 
        include("navegador.php");
    ?>
    <br>
    <a href="http://localhost/tallermecanico/registro_cliente.php" class="registro_recepcionista">Nuevo Cliente</a>
    <section class="nuevo_cli"> 
        <br>
        <h2 class="recepcion_titulos">NUEVO VEHICULO</h2>
        <br>
        <form action="" class="form_recp_clinuv">
            <label  for="dni_cli" class="form_dni">DNI Cliente</label>
            <input type="text" name="dni_cli" id="dni_cli" class="form_dni1">
            <br><br><br>
            <label for="patente" class="form_patetente">Patente</label>
            <input type="text" name="patente" id="patente" class="form_patetente1">

            <label for="marca" class="form_marca">Marca</label>
            <input type="text" name="marca" id="marca" class="form_marca1">

            <label for="modelo" class="form_modelo">Modelo</label>
            <input type="text" name="modelo" id="modelo" class="form_modelo1">

            <label for="año" class="form_año">Año</label>
            <input type="number" name="año" id="año" class="form_año1" >

            <label for="motor" class="form_motor">Motor</label>
            <input type="text" name="motor" id="motor" class="form_motor1">

            <label for="color" class="form_color">Color</label>
            <input type="text" name="color" id="color" class="form_color1">
            
            <input class="guardar_rec" type="submit" value="Guardar" name="guardar_rec">
        </form>
    </section>
 <br>
    <section class="nueva_ord">
        <br>
        <h2 class="recepcion_titulos">NUEVA ORDEN</h2>
        <br>
        <form action="" class="form_orden">
            <label  for="ord_num" class="ord_form">Orden Número</label>
            <input type="number" name="ord_num" id="ord_num" class="ord_form1">
            <label for="veh_pat" class="patente_form">Vehiculo Patente</label>
            <input type="text" name="veh_pat" id="veh_pat" class="patente_form1">
            <label for="fecha" class="fecha_form">Fecha</label>
            <input type="datetime" name="fecha" class="fecha_form1">
            <label for="serv_cod" class="serv_form">Servicio Codigo</label>
            <input type="text" name="serv_cod" id="serv_cod" class="serv_form1">
            <label for="complejidad" class="compl_form">Complejidad</label>
            <input type="number" name="complejidad" id="complejidad" class="compl_form1">
            <label for="kilometraje" class="kil_form">Kilometraje</label>
            <input type="text" name="kilometraje" id="kilometraje" class="kil_form1">

            <input class="guardar_rec" type="submit" value="Guardar" name="guardar_rec">
            
        </form>
    </section>
<br>
    <article class="busqueda_cli">
        <br>
        <h2 class="recepcion_titulos"> BUSCAR</h2>
        <br>
        <form action="" class="form_busqueda">
            <label for="dni" class="form2_dni">DNI</label>
            <input type="text" name="dni" id="dni" class="form2_dni2">

            <label for="vehiculo" class="form2_veh">Vehiculo Patente </label>
            <input type="text" name="vehiculo" id="vehiculo" class="form2_veh2">

            <label for="n_orden" class="form2_orden">Número de orden</label>
            <input type="text" name="n_orden" id="n_orden" class="form2_orden2" >
            

            <input class="buscar_rec" type="submit" value="Buscar" name="buscar_rec" >

        </form>
    </article>

    <br>

    <?php 
        include("piedepagina.php");
    ?>
</body>
</html>