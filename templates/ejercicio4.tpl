<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>titulo de la página</title>
        <style>
            label { 
                display: block;
            }
            .rojo {
                color: red;
            }
            .verde {
                color: green;
            }
        </style>
    </head>

    <body>
        <h3>{$titulo}</h3>
<form action="ejercicio4.php" method="post">
    <label for="zona_id">
        ID de zona:
        <input type="text" name="zona_id" id="zona_id">
    </label>    
    <label for="fecha_actual">
        Fecha (dd/mm/aaaa):
        <input type="text" name="fecha_actual" id="fecha_actual">
    </label>
    <label for="inicio_actual">
        Hora de inicio actual (hh:mm):
        <input type="text" name="inicio_actual" id="inicio_actual">
    </label>
    <label for="nuevo_inicio">
        Nueva hora de inicio (hh:mm):
        <input type="text" name="nuevo_inicio" id="nuevo_inicio">
    </label>
    <label for="nuevo_fin">
        Nueva hora de fin (hh:mm):
        <input type="text" name="nuevo_fin" id="nuevo_fin">
    </label>
    <input type="submit" name="enviar" value="Enviar"/>
</form>
    </body>
</html>
