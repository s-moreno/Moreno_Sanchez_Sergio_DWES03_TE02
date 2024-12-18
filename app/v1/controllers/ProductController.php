<?php

class ProductController
{
    /**
     * Método que mostrara el json con todos los productos.
     * En caso de error mostrará el json con el tipo de error.
     * @return void 
     */
    public function getAll(): void
    {
        $productService = new ProductService();
        $allProducts = $productService->getAll();

        if ($allProducts !== false) {
            $this->json_response('OK', 200, $allProducts);
        } else {
            $error = new ErrorController();
            $error->error500();
        }
    }

    /**
     * Método que mostrara el json con la información de un producto según su ID.
     * En caso de error mostrará el json con el tipo de error.
     * 
     * @param int $id ID del producto
     * @return void 
     */
    public function getById(int $id): void
    {
        try {
            $productService = new ProductService();
            $product = $productService->getById($id);

            if ($product !== false) {
                $this->json_response('OK', 200, $product);
            } else {
                $error = new ErrorController();
                $error->error404($id);
            }
            return;
        } catch (Exception $e) {
            $error = new ErrorController();
            $error->error500($e->getMessage());
        }
    }

    /**
     * Método que mostrará el json del nuevo producto creado.
     * En caso de error mostrará el json con el tipo de error.
     * @param string $data JSON con el nombre, stock_actual y stock_mínimo
     * @return void 
     */
    public function create(string $data): void
    {
        try {
            // Validar el estructura correcta del json recibido a través de $data
            $product_data = $this->decode_validate_json($data);

            // Validar los campos y valores enviados del nuevo producto.
            $this->validate_fields($product_data);

            $productService = new ProductService();
            $created_product = $productService->create($product_data);

            if ($created_product !== false) {
                $this->json_response('Created', 201, $created_product);
            } else {
                throw new Exception("No se puede crear el producto. Vuelve a intentarlo más tarde.", 500);
            }
            return;
        } catch (Exception $e) {
            $error = new ErrorController();
            $error->customError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método que mostrara el json con la información de un producto
     * parcialmente actualizado según su ID y los datos datos.
     * En caso de error mostrará el json con el tipo de error.
     * @param int $id ID del producto
     * @param string $data JSON con el nombre?, stock_actual? y/o stock_mínimo?
     * @return void 
     */
    public function partialUpdate(int $id, string $data): void
    {
        try {
            // Validar la estructura correcta del json recibido a través de $data
            $product_data = $this->decode_validate_json($data);

            // Validar los campos y valores enviados del nuevo producto.
            $this->validate_partial_fields($product_data);

            $productService = new ProductService();
            $updated_product = $productService->update($id, $product_data);

            if (!empty($updated_product)) {
                $this->json_response('OK', 200, $updated_product);
            } else {
                $error = new ErrorController();
                $error->error404($id);
            }
            return;
        } catch (Exception $e) {
            $error = new ErrorController();
            $error->customError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método que mostrara el json con la información de un producto
     * actualizado según su ID y los datos datos.
     * En caso de error mostrará el json con el tipo de error.
     * @param int $id ID del producto
     * @param string $data JSON con el nombre, stock_actual y stock_mínimo
     * @return void 
     */
    public function update(int $id, string $data): void
    {
        try {
            // Validar la estructura correcta del json recibido a través de $data
            $product_data = $this->decode_validate_json($data);

            // Validar los campos y valores enviados del nuevo producto.
            $this->validate_fields($product_data);

            $productService = new ProductService();
            $updated_product = $productService->update($id, $product_data);

            if (!empty($updated_product)) {
                $this->json_response('OK', 200, $updated_product);
            } else {
                $error = new ErrorController();
                $error->error404($id);
            }
            return;
        } catch (Exception $e) {
            $error = new ErrorController();
            $error->customError($e->getCode(), $e->getMessage());
        }
    }

    /**
     * Método que mostrara el json con la información de un producto
     * eliminado según su ID.
     * En caso de error mostrará el json con el tipo de error.
     * @param int $id ID del producto
     * @return void 
     */
    public function delete(int $id): void
    {
        try {
            $productService = new ProductService();
            $deleted_product = $productService->delete($id);

            if ($deleted_product) {
                $this->json_response('OK', 200, $deleted_product);
            } else {
                $error = new ErrorController();
                $error->error404($id);
            }
            return;
        } catch (Exception $e) {
            $error = new ErrorController();
            $error->error500($e->getMessage());
        }
    }

    /**
     * Método que mostrará el json con los productos que estén por debajo de su stock mínimo definido.
     * @return void 
     */
    public function underMin(): void
    {
        $productService = new ProductService();
        $under_min = $productService->getUnderMin();

        if ($under_min !== false) {
            $this->json_response('OK', 200, $under_min);
        } else {
            $error = new ErrorController();
            $error->error500();
        }
    }

    /**
     * Muestra por pantalla el json con la respuesta. Será un estado, código de estado, y los datos devueltos.
     * @param string $status
     * @param int $code 
     * @param mixed $data
     * @return void 
     */
    public function json_response(string $status, int $code, mixed $data): void
    {
        http_response_code($code);
        echo json_encode(
            [
                'status' => $status,
                'code' => $code,
                'data' => $data
            ],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );
    }

    /**
     * Decodifica y valida que el json sea correcto. Devuelve el json en formato array.
     * @param string $json 
     * @return array json en formato array
     * @throws InvalidArgumentException 
     */
    private function decode_validate_json(string $json): array
    {
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidArgumentException("Invalid JSON: " . json_last_error_msg(), 400);
        }

        return $data;
    }

    /**
     * Valida que todos los campos de los datos del producto sean válidos y que su tipado sea el correcto.
     * @param array $data [nombre, stock_actual, stock_mínimo]
     * @return void 
     * @throws InvalidArgumentException 
     */
    private function validate_fields(array $data): void
    {

        // validar que no hay más campos que los requeridos:
        $campos_requeridos = ['nombre', 'stock_actual', 'stock_minimo'];
        $campos_obtenidos = array_keys($data);
        foreach ($campos_obtenidos as $campo) {
            if (!in_array($campo, $campos_requeridos)) {
                throw new InvalidArgumentException("Campos no válidos.", 400);
            }
        }

        // Validar que no falta ningún campo requerido:
        if (!isset($data['nombre'], $data['stock_actual'], $data['stock_minimo'])) {
            throw new InvalidArgumentException("Faltan campos requeridos.", 400);
        }

        // Validar que los valores son los esperados
        if (
            (!is_string($data['nombre']) || empty($data['nombre']))
            || (!is_int($data['stock_actual']) || $data['stock_actual'] < 0)
            || (!is_int($data['stock_minimo']) || $data['stock_minimo'] < 0)
        ) {
            throw new InvalidArgumentException("Se esperaban valores diferentes.", 400);
        }
    }

    /**
     * Valida que alguno de los campos de los datos del producto sean válidos y que su tipado sea el correcto.
     * @param array $data [nombre?, stock_actual?, stock_mínimo?]
     * @return void 
     * @throws InvalidArgumentException 
     */
    private function validate_partial_fields(array $data): void
    {
        // Validar que el array no está vacío (hay algún campo)
        if (empty($data)) {
            throw new InvalidArgumentException("Se necesita mínimo un campo para actualizar.", 400);
        }

        // validar que no hay más campos que los requeridos:
        $campos_requeridos = ['nombre', 'stock_actual', 'stock_minimo'];
        $campos_obtenidos = array_keys($data);
        foreach ($campos_obtenidos as $campo) {
            if (!in_array($campo, $campos_requeridos)) {
                throw new InvalidArgumentException("Campos no válidos.", 400);
            }
        }

        // validar que no se quiere realizar una validación completa
        if (count($data) === 3) {
            throw new InvalidArgumentException("Para una actualización completa del producto, usar el método PUT.", 405);
        }

        // Validar que los valores son los esperados
        if ((isset($data['nombre']) && (!is_string($data['nombre']) || empty($data['nombre'])))
            || (isset($data['stock_actual']) && (!is_int($data['stock_actual']) || $data['stock_actual'] < 0))
            || (isset($data['stock_minimo']) && (!is_int($data['stock_minimo']) || $data['stock_minimo'] < 0))
        ) {
            throw new InvalidArgumentException("Se esperaban valores diferentes.", 400);
        }
    }
}
