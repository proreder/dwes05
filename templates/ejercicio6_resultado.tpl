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
            
            
            .color_cabecera {
                            background-color : #009879;
                            color : white;
                            
                         }
            .tabla       {
                            border-collapse: collapse;
                            border-width: 1px;
                            font-size: 1em;
                            border-color: black;
                            border-style: inset;
                          }
            table td{
                padding: 5px;
                border-style: inset;
                border-color: black;
                border-width: 1px;
            }
            table th{
                
                border-style: inset;
                
                border-width: 1px;
            }
                        
            .id {
                width: 2em;
                height: 2em;
                }
            .nombre{
                width: 10em;
            }
            .des{
                width: 12em;
            }
            .emp{
                width: 16em;
            }
            .man{
                width: 30em;
                margin-right: 20px;
                margin-left: 20px;
            }
            .tel{
                width: 10em;
            }
            .ope{
                width: 6em;
            }
            input{
                font-size: .75em;
                margin: 3px;
                cursor: pointer;
            }
           
        </style>
    </head>

    <body>
        <h3>{$titulo}</h3>
       
        <div class="form">
         {*print_r($reservas)*}  
            <table class="tabla">
                    <tbody>
                            <tr class="color_cabecera">
                                <td colspan="3"><span class="man"><b>Fecha:</b> {$reservas->fecha}</span><span class="man"><b>Zona:</b> {$reservas->zona}</span</td>
                            </tr>
                            <tr><td>Hora de inicio</td><td>Hora fin</td><td>Usuario</td></tr>
                                {if empty($errores)}
                                    {foreach $reservas->reservas->tramo as $tramo}

                                        <tr style="background: {cycle values='lightblue,azure'}">
                                            <td>{$tramo->horaInicio}</td>
                                            <td>{$tramo->horaFin}</td>
                                            <td>{$tramo->user}</td>


                                        </tr>
                                        {foreachelse}  
                                    {/foreach}
                                {/if}
                            
                    </tbody>
            </table>
            {if !empty($errores)}
            
            <h3>Errores encontrados:</h3>
            <ul>
               {foreach from=$errores item=$error}
                   <li>{$error}</li>
                {/foreach}
            </ul>
        {/if}                
          <a href="ejercicio6.php">Volver</a>                
        </div>
    </body>
</html>

