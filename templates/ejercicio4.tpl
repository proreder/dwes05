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
            .form{
                width: 400px;
            }
        </style>
    </head>

    <body>
        <h3>{$titulo}</h3>
        {if !empty($errores)}
            <h3 class="rojo">Errores encontrados</h3>
                <ul>
                    {foreach from=$errores item=$error}
                        <li class="rojo">{$error}</li>
                    {/foreach}
                </ul>
        {/if}
        <div class="form">
         <form action="ejercicio4.php" method="post">
            <fieldset><!-- comment -->
                <legend>Formulario de reserva</legend>
                    <label for="zona_id">
                        ID de zona:
                        <input type="text" name="zona_id" id="zona_id">
                    </label> 
                    <label for="user_id">
                        ID de usuario:
                        <input type="text" name="user_id" id="user_id">
                    </label>    
                    <label for="fecha_actual">
                        Fecha (dd-mm-aaaa):
                        <input type="text" name="fecha_actual" id="fecha_actual">
                    </label>
            </fieldset>
            <fieldset>
                <label for="hora_inicio">
                    Hora de inicio (hh:mm):
                    <input type="text" name="hora_inicio" id="hora_inicio">
                </label>
                <label for="nuevo_inicio">
                    Hora de fin (hh:mm):
                    <input type="text" name="hora_fin" id="hora_fin">
                </label>
                
            </fieldset>
             <br>
            <input type="submit" name="enviar" value="Enviar"/>
        </form>
        </div>
    </body>
</html>
