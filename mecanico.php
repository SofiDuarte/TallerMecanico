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
    <br><br>
    <section >
        <h2 class="consulta">Consultas</h2>
        <form action="" class="form_consulta">
            <div class="cons_ordenes">
                <label for="ordenes" >PATENTE DEL VEHICULO</label>
                <h3 >Consulta las ordenes</h3>
                <input  type="text" name="ordenes" id="ordenes">
            </div>

            <div class="cons_historial">
                <label for="historial">PATENTE DEL VEHICULO</label>
                <h3>Historial del vehiculo</h3>
                <input type="text" name="historial" id="historial">
            </div>
            <div class="cons_trabajo">
                <label for="trabajos_realizar">ORDEN DE NÚMERO</label>
                <h3>Trabajos a realizar</h3>
                <input type="text" name="trabajos_realizar" id="trabajos_realizar">
            </div>
            <input class="buscar_mec" type="submit" value="Buscar" name="buscar_rec" class="form2_orden2">
    </section>
    <section>
        <h2 class="actualizar" >Actualizar</h2>
        <form action="" class="form_actualizacion">
            <div class="kilometraje"> 
                <h3>Kilometraje</h3>
                <br>
                <label for="n_orden">Número de orden</label>
                <br>
                <input type="text" name="n_orden" id="n_orden">
                <br>
                <label for="n_kilometraje">Nuevo kilometraje</label>
                <br>
                <input type="text" name="n_kilometraje" id="n_kilometraje">
            </div>
            <div class="complejidad">
                <h3>Complejidad</h3>
                <label for="n_orden">Número de orden</label>
                <br>
                <input type="text" name="n_orden" id="n_orden">
                <br>
                <label for="n_kilometraje">Nuevo kilometraje</label>
                <br>
                <input type="text" name="n_kilometraje" id="n_kilometraje">
            </div>
        </form>
        <div class="guardar_mec">
            <input class="guardar_mec1"  type="submit" value="Guardar" name="guardar_rec">
        </div>
    </section>
    <br><br><br>



    <?php 
        include("piedepagina.php");
    ?>
    

</body>
</html>