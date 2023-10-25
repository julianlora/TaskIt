<?php

class CalendarioController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarCalendario(){
        echo'
        <div class="calendario-trello">
            <div class="dia">
                <h3>Octubre 1, 2023</h3>
                <!-- Contenido de eventos o tareas para este día -->
            </div>
            <div class="dia">
                <h3>Octubre 2, 2023</h3>
                <!-- Contenido de eventos o tareas para este día -->
            </div>
            <!-- Repite para cada día del mes -->
        </div>
        ';
    }
}