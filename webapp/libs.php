<?php

/**
 * ejemplo: valida fecha y hora
 * var_dump(validateDate('28/02/2012', 'd/m/Y')); # true
 * var_dump(validateDate('30/02/2012', 'd/m/Y')); # false
 * validaDate('14:50', 'H:i')); # true
 * var_dump(validateDate('14:77', 'H:i')); # false
 * @param type $date fecha a validar
 * @param type $format  formato que tiene el tiempo
 * @return false si hay error o tiempo de entrada si esta bién
 */
function validaDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    $valor= $d && $d->format($format) == $date;
    return $valor== true ? $date : false;
}

