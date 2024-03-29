<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{$titulo}</title>
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
        {if $eliminado}
             <h2 class="verde">El registro se ha eliminado correctamente</h2>
             <br>
             <a href="ejercicio5.php">Volver</a>
        {else}
            <div class="form">
             <form action="ejercicio5.php" method="post">
                <fieldset><!-- comment -->
                    <legend>Formulario de eliminación de reserva</legend>
                        <label for="zona_id">
                            ID de zona:
                            <input type="text" name="zona_id" id="zona_id"/>*
                        </label> 
                        <label for="fecha">
                            Fecha (dd-mm-aaaa):
                            <input type="text" name="fecha" id="fecha"/>*
                        </label>
                        <label for="horaInicio">
                            Hora de inicio (hh:mm):
                            <input type="text" name="horaInicio" id="horaInicio"/>*
                        </label>

                </fieldset>
                 <span class="rojo">* Campos obligatorios</span>
                 <br/><br/>
                <input type="submit" name="enviar" value="Enviar"/>
            </form>
            </div>
        {/if}
    </body>
</html>

