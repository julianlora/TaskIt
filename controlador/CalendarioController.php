<?php

class CalendarioController {
    private $conexion;
    private $eventos = [];

    public function __construct($conexion) {
        $this->conexion = $conexion;
        // Buscar y guardar todas las fechas de finalizacion
        $resultado = mysqli_query($this->conexion, "SELECT * from listas WHERE fecha_finalizacion IS NOT NULL");
        $cant = mysqli_num_rows($resultado);
        while($evento = mysqli_fetch_array($resultado)){
            $this->eventos[] = $evento;
        }
    }

    public function mostrarCalendario(){
        $hoy = date('Y-m-d');
        $mesActual = date('n');  // Obtiene el número de mes actual (1-12)
        $añoActual = date('Y');  // Obtiene el año actual
        $cantidadDias = cal_days_in_month(CAL_GREGORIAN, $mesActual, $añoActual);
        $cantidadDiasMesAnterior = cal_days_in_month(CAL_GREGORIAN, $mesActual-1, $añoActual);
        $nombreMes = date('F');
        $primerDiaDelMes = date('w', strtotime(date('Y-m-01')));
        $numeroDiaActual = date('2', strtotime($hoy)); // 0 - 6 (domingo a sabado)

        echo"
        <main class='ventanaprincipal calendario'><br>
        <h1>$nombreMes, $añoActual</h1><br>
        <div class='calendario-trello'>
            <div class='encabezado-dia'>Domingo</div>
            <div class='encabezado-dia'>Lunes</div>
            <div class='encabezado-dia'>Martes</div>
            <div class='encabezado-dia'>Miércoles</div>
            <div class='encabezado-dia'>Jueves</div>
            <div class='encabezado-dia'>Viernes</div>
            <div class='encabezado-dia'>Sábado</div><br>";
        for($i=0;$i<35;$i++){
            if ($i < $primerDiaDelMes){
                $dia = $cantidadDiasMesAnterior - ($primerDiaDelMes + 1);
                $fecha_tarjeta = date('Y-m-d', strtotime(date('Y-m-01') . " -$primerDiaDelMes days"));
                $clase = 'dia extra';
            } elseif ($i + 1 > $cantidadDias){
                $dia = $i + 1 - $cantidadDias;
                $fecha_tarjeta = date('Y-m-d', strtotime(date('Y-m-01') . " +$i days"));
                $clase = 'dia extra';
            } else {
                $dia = $i + 1;
                $fecha_tarjeta = date('Y-m-d', strtotime(date('Y-m-01') . " +$i days"));
                if ($fecha_tarjeta == $hoy){
                    $clase = 'hoy dia';
                } else {
                    $clase = 'dia';
                }
                
            }
            echo "
            <div class='$clase'>
                <h3>$dia</h3>";
            $this->buscarEvento($fecha_tarjeta);
            echo"
            </div>";
        }
        echo'
        </div>
        </main>';
    }

    public function buscarEvento($fecha_tarjeta){
        foreach($this->eventos as $evento){
            if($evento['fecha_finalizacion'] == $fecha_tarjeta){
                echo "Finaliza el proyecto: ".$evento['titulo'];
            }
        }
    }
}