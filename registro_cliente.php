<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="estilopagina.css" > 
    <title>Document</title>
</head>
<body>
    <?php 
        include("navegador.php");
    ?>

    <br><br>


    <section class="pagina_registro">
        <img src="fondos/registro_cli.jpg" alt="" class="img_registro1">
        <section class="registro">
            
            <h2>Hola Usuario</h2>
            <h3>Ingrese sus datos</h3>

            <form action="" class="form_registro" >
                <label  for="dni_registro" class="">DNI</label>
                <br><br>
                <input type="text" name="dni_registro" id="dni_registro" class="">
                <br><br>
                <label for="nombre_registro" class="" >Nombre</label>
                <br><br>
                <input type="text" name="nombre_registro" id="nombre_registro" class="">
                <br><br>
                <label for="direcc_registro">Direccion</label>
                <br><br>
                <input type="text" name="direcc_registro" id="direcc_registro">
                <br><br>
                <label for="loc_registro"> Localidad</label>
                <br><br>
                <input type="text" name="loc_registro" id="loc_registro">
                <br><br>
                <label for="tel_registro">Telefono</label>
                <br><br>
                <input type="tel" name="tel_registro" id="tel_registro">
                <br><br>
                <label for="email_registro">E-Mail</label>
                <br><br>
                <input type="email" name="email_registro" id="email_registro">
                <br><br>
                <label for="contra_registro">Contraseña</label>
                <br><br>
                <input type="password" name="contra_registro" id="contra_registro">
            </form>

            <div class="bot_registro">
                <button class="boton_registro" value="Registrar" name="buscar_rec" >Registrar</button>
            </div>

        </section>
        <img src="fondos/registro_cli.jpg" alt="" class="img_registro2">

    </section>    

<br><br>

    <?php 
        include("piedepagina.php");
    ?>
</body>
</html>