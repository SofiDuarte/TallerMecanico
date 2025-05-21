<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="estilopagina.css?v=<?= time() ?>"> 
    <title>Document</title>
</head>
<body>

    <?php 
        include("navegador.php");
    ?>
    <br><br><br><br>
    <section class="form_consulta">
        <div class="imagen_cons1">
            <img src="iconos/form_empleados.png" alt="">
        </div>
        <h2 class="consulta">Consultas</h2>
        <form action="" class="consulta">

            <div class="cons_historial">
                <label for="historial">PATENTE DEL VEHICULO</label>
                <h3>Historial del vehiculo</h3>
                <input type="text" name="historial" id="historial">
            </div>
            <br>
            <div class="cons_trabajo">
                <label for="trabajos_realizar">ORDEN DE NÚMERO</label>
                <h3>Trabajos a realizar</h3>
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