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
                        <input type="text" name="zona_id" id="zona_id"/>*
                    </label> 
                    <label for="user_id">
                        ID de usuario:
                        <input type="text" name="user_id" id="user_id"/>*
                    </label>    
                    <label for="fecha">
                        Fecha (dd-mm-aaaa):
                        <input type="text" name="fecha" id="fecha"/>*
                    </label>
            </fieldset>
            <fieldset>
                <label for="horaInicio">
                    Hora de inicio (hh:mm):
                    <input type="text" name="horaInicio" id="horaInicio"/>*
                </label>
                <label for="horaFin">
                    Hora de fin (hh:mm):
                    <input type="text" name="horaFin" id="horaFin"/>*
                </label>
                
            </fieldset>
             <span class="rojo">* Campos obligatorios</span>
             <br/><br/>
            <input type="submit" name="enviar" value="Enviar"/>
        </form>
        </div>
    </body>
</html>
