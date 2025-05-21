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

<!-- orden_numero, servicio_codigo, complejidad, orden_kilometros -->
    <section class="ordenes">

        <br><br>
        <h2>Ordenes</h2>
        <br><br>
        <table>
            <tr>
                <th>ORDEN NÚMERO</th>
                <th>SERVICIO CODIGO</th>
                <th>COMPLEJIDAD</th>
                <th>KILOMETRAJE</th>
            </tr>
            <tr>
               <td class="datos_unicos2">Armar php de base (No se editan)</td>
               <td class="datos_unicos2">Armar php de base (No se editan)</td>
               <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
               <td class="datos_modificados" contenteditable="true">Armar php de base (Se editan)</td>
            </tr>
        </table>
        <br><br>
        <form action="">
            <label for="descripcion" class="desc_ord">Descripcion/Observacion</label>
            <textarea  class="desc_ord1" name="descripcion" id="descripcion" cols="" rows=""></textarea>
            <br><br>
            <div >
                <input class="finalizar_ord" type="submit" value="Finalizar" name="buscar_rec" >
            </div>
        </form>

    </section>


    <?php 
        include("piedepagina.php");
    ?>

</body>
</html>