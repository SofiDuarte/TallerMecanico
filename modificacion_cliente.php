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
    <section class="modif_cli">
        <img class="modif_img1" src="fondos/hola.usuario.jpg" alt="">
        
        <section class="modificar_cliente">
            <h2>Hola Usuario</h2>
            <h3>Modificar Datos</h3> 
            <table>
                <tr>
                    <th>DNI</th>
                    <td class="datos_unicos">Armar php de base (No se editan)</td>
                </tr>
                <tr>
                    <th>NOMBRE</th>
                    <td class="datos_unicos">Armar php de base (No se editan)</td>
                </tr>
                <tr>
                    <th>Direccion</th>
                    <td class="datos_modificados" contenteditable="true"></td>
                </tr>
                <tr>
                    <th>Localidad</th>
                    <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
                </tr>
                <tr>
                    <th>Telefono</th>
                    <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
                </tr>
                <tr>
                    <th>E-Mail</th>
                    <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
                </tr>
                <tr>
                    <th>Contraseña</th>
                    <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
                </tr>
            </table>
            <div>
                <input class="guardar_mod" type="submit" value="Guardar" name="guardar_rec">
                <input class="solicitar_mod" type="submit" value="Solicitar Turno" name="solicitar">
            </div>

        </section>

        <img class="modif_img2" src="fondos/hola.usuario.jpg" alt="">

    </section>
    <br>

    <?php 
        include("piedepagina.php");
    ?>

 </body>
 </html>
 
