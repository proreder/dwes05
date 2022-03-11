<?php

$reserva= new class(){
            public $user;
            public $zona;
            public $fecha;
            public $tramo;
        };
        
$reserva->tramo=new class(){
            public $horaInincio;
            public $horafin;
        };

