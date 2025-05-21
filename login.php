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
    
    <section class="fondo_login">
        <div class="formu_login">

             <div class="formu_empleados"> 
                <img src="iconos/form_empleados.png" alt="">
                <h2>EMPLEADOS</h2>
                <br> <br>
                <form action="">
                    <label for="us">Usuario</label>
                    <br><br>
                    <input type="text" id="us">
                    <br><br><br><br>
                    <label for="password">Contraña</label>
                    <br><br>
                    <input type="password" name="" id="password">
                    <br><br>
                    <button class="ingreso1" id="">Ingresar</button>
                    <br><br>
                    <button class="olvido" id="Olvide mi contraseña">Olvide mi contraseña</button>
                </form>
            </div>


            <div class="formu_clientes"> 
                <img src="iconos/form_clientes.png" alt="">
                <h2>CLIENTES</h2>
                <br><br>
                <form action="" class="clientes" >
                    <label for="us">Usuario</label>
                    <br><br>
                    <input type="text" id="us">
                    <br><br><br><br>
                    <label for="password">Contraña</label>
                    <br><br>
                    <input type="password" name="" id="password">
                    <br><br><br><br>

                    <button class="ingreso2" id="">Ingresar</button>
                    <br><br>
                    <button class="olvido"  id="Olvide mi contraseña">Olvide mi contraseña</button>
                    <br><br>
                    <button class="registro2" id="">Registrame</button>
                </form>
            </div>
   

    </section>

    

        

    <?php 
        include("piedepagina.php");
    ?>
     

</body>
</html>