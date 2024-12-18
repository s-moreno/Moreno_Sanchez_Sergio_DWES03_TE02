<?php

class ProductService
{
    // definimos una constante para la ruta del json con los datos 
    protected const JSON_DATA_PATH = '../data/products.json';

    /**
     * Devuelve un array con todos los productos de nuestra base de datos.
     * False en caso de error.
     * @return array|false 
     */
    public function getAll(): array|false
    {
        $json_data = file_get_contents(self::JSON_DATA_PATH);

        if ($json_data) {
            $array_data = json_decode($json_data, true);
            $products = [];
            foreach ($array_data as $data) {
                $product = new ProductModel(
                    $data['id'],
                    $data['nombre'],
                    $data['stock_actual'],
                    $data['stock_minimo']
                );
                $products[] = $product;
            }
            return $products;
        }
        return false;
    }

    /**
     * Devuelve el producto de la base de datos que coincide con el
     * ID dado. Si no existe el ID, devuelve false
     * @param int $id ID del producto
     * @return ProducModel|false 
     */
    public function getById(int $id): ProductModel|false
    {
        $products = $this->getAll();

        foreach ($products as $product) {
            if ($product->getId() === $id) {
                return $product;
            }
        }

        return false;
    }

    /**
     * Devuelve un array con los productos de la base de datos que se encuentren
     * por debajo de su stock mínimo establecido.
     * Devuelve false si no puede procesar la BD.
     * @return array|false 
     */
    public function getUnderMin(): array|false
    {
        $products = $this->getAll();

        if ($products) {
            $under_min = [];
            foreach ($products as $product) {
                if ($product->getStockActual() < $product->getStockMinimo()) {
                    $under_min[] = $product;
                }
            }
            return $under_min;
        }
        return false;
    }


    /**
     * Crea un producto nuevo que lo incluye a la BD y lo duevelve.
     * Devuelve false si no puede procesar la BD.
     * @param array $data [nombre, stock_actual, stock_minimo]
     * @return ProductModel|false 
     */
    public function create(array $data): ProductModel|false
    {
        // obtener los productos del archivo json
        $products = $this->getAll();

        // obtener el id a asignar:
        // si no hay elementos, asignamos el 1
        $id = !empty($products) ? end($products)->getId() + 1 : 1;

        // nuevo producto con el id:
        $new_product = new ProductModel(
            $id,
            $data['nombre'],
            $data['stock_actual'],
            $data['stock_minimo']
        );

        // añadimos el nuevo producto al array de productos:
        $products[] = $new_product;

        // finalmente sobreescrimos el archivo json, con el listado de productos actualizado
        $products_json = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        // escribir el nuevo json y validar que ha sido posible. En caso de éxito, devolvemos el nuevo producto
        if (file_put_contents(self::JSON_DATA_PATH, $products_json, LOCK_EX)) {
            return $new_product;
        }

        return false;
    }

    /**
     * Actualiza un producto de la BD completa o parcialmente y devuelve el producto actualizado.
     * Devuelve false si no puede procesar la solicitud.
     * @param int $id ID del producto
     * @param array $data [nombre?, stock_actual?, stock_minimo?]
     * @return ProductModel|false 
     */
    public function update(int $id, array $data): ProductModel|false
    {
        // Comprobar el producto bajo su id existe
        if (!empty($this->getById($id))) {
            $products = $this->getAll();

            foreach ($products as $product) {
                if ($product->getId() === $id) {
                    $product->setNombre($data['nombre'] ?? $product->getNombre());
                    $product->setStockActual($data['stock_actual'] ?? $product->getStockActual());
                    $product->setStockMinimo($data['stock_minimo'] ?? $product->getStockMinimo());

                    $products_json = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    if (file_put_contents(self::JSON_DATA_PATH, $products_json, LOCK_EX)) {
                        return $product;
                    }
                    break;
                }
            }
        }
        return false;
    }

    /**
     * Elimina un producto de la base de datos.
     * Develve el producto eliminado si hay éxito, o false si no se puede procesar.
     * @param int $id 
     * @return ProductModel|false 
     */
    public function delete(int $id): ProductModel|false
    {
        $products = $this->getAll();

        $delete_product = $this->getById($id);

        foreach ($products as $index => $product) {
            if ($product->getId() === $id) {
                // eliminar el producto del array
                unset($products[$index]);
                // reindexar el array para mantener índices consecutivos
                $products = array_values($products);

                $products_json = json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

                // escribir el nuevo json y validar que ha sido posible. En caso de éxito, devolvemos el producto eliminado
                if (file_put_contents(self::JSON_DATA_PATH, $products_json, LOCK_EX)) {
                    return $delete_product;
                }

                break;
            }
        }
        return false;
    }
}
