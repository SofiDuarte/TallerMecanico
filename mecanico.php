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
         <div class="encabezado">
        <head class="navegacion">
            
            
            <a  href="http://localhost/tallermecanico/inicio.php"><img class="login" src="iconos/WA_Sport.jpg" alt="Logotipo de WA Sport" ></a>
            <a class="nav" href="http://localhost/tallermecanico/nosotros.php">Nosotros</a>
            <a class="nav" href="http://localhost/tallermecanico/servicios.php">Servicios</a>
            <a class="nav" href="http://localhost/tallermecanico/contacto.php">Contacto</a>
            <a class="nav" href="http://localhost/tallermecanico/login.php">Login</a>
            
        </head>
    </div>
    <?php 
        include("navegador.php");
    ?>
    <br>
    <section >
        <h2>Consultas</h2>
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
            <input class="buscar_mec" type="submit" value="Buscar" name="buscar_rec" class="form2_orden2"><!--MISMO BOTON QUE RECEPCIONISTA -->
        </form>
    </section>
    <section >
        <h2>Actualizar</h2>
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




    <?php 
        include("piedepagina.php");
    ?>
     <footer class="PieDePagina"> 
        <h4 class="redes">Redes</h4>
    <div class="iconos-redes">
        <link rel="stylesheet" href=""><img class= "iconos" src="iconos/face.webp" alt="icono de Facebook con link a la pagina">
        <link rel="stylesheet" href=""><img class= "iconos"src="iconos/inst.png" alt="icono de Instragram con link a la pagina">
        <link rel="stylesheet" href=""><img class= "iconoswpp" src="iconos/wpp.png" alt="icono de Whatsapp con link a la pagina">

       

    </div>
        <a class="direc" href="https://www.google.com.ar/maps/place/WAsport/@-34.6485696,-58.5412355,17z/data=!3m1!4b1!4m6!3m5!1s0x95bcc94f14768b4b:0x569b8550c525f535!8m2!3d-34.6485696!4d-58.5386606!16s%2Fg%2F11h7z1d_5t?hl=es&entry=ttu&g_ep=EgoyMDI1MDQzMC4xIKXMDSoJLDEwMjExNDUzSAFQAw%3D%3D" >Paso 1418, Ramos Mejía, Provincia de Buenos Aires</a>
    </footer>

</body>
</html>