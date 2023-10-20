<?php
class Lista {
    private $titulo;
    private $items = [];

    // Constructor
    public function __construct($titulo) {
        $this->titulo = $titulo;
    }

    // Método para agregar un elemento a la lista
    public function agregarElemento($elemento) {
        $this->items[] = $elemento;
    }

    // Método para obtener la lista completa
    public function obtenerLista() {
        return $this->items;
    }
}
?>