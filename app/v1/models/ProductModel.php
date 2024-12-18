<?php

class ProductModel implements JsonSerializable
{
    protected int $id;
    protected string $nombre;
    protected string $stock_actual;
    protected string $stock_minimo;

    public function __construct(int $id, string $nombre, string $stock_actual, string $stock_minimo)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->stock_actual = $stock_actual;
        $this->stock_minimo = $stock_minimo;
    }

    /**
     * implementar el jsonSerialize
     * @return array 
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'stock_actual' => $this->stock_actual,
            'stock_minimo' => $this->stock_minimo
        ];
    }

    // Getters & Setters

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getStockActual()
    {
        return $this->stock_actual;
    }

    public function getStockMinimo()
    {
        return $this->stock_minimo;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setStockActual($stock_actual)
    {
        $this->stock_actual = $stock_actual;
    }

    public function setStockMinimo($stock_minimo)
    {
        $this->stock_minimo = $stock_minimo;
    }
}
