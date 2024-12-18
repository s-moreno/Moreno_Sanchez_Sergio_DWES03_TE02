<?php

class ErrorController
{
    /**
     * Método que mostrará el json para las páginas con el error http 404.
     * Si existe ID, devolverá un mensaje personalizado.
     * @param int|null $id ID del producto
     * @return void 
     */
    public function error404(int $id = null): void
    {
        http_response_code(404);

        $mensaje = $id
            ? 'El producto con el Id ' . $id . ' no se encuentra en la Base de Datos.'
            : 'Error 404. Página no encontrada.';

        echo json_encode([
            'status' => 'error',
            'code' => 404,
            'message' => $mensaje
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Método que mostrará el json para las páginas con el error http 500.
     * Si existe un $mensaje, devolvera un error personalizado.
     * @param string|null $mensaje
     * @return void 
     */
    public function error500(string $mensaje = null): void
    {
        http_response_code(500);

        $mensaje ??= 'Error del servidor. Vuelve a intentarlo más tarde.';

        echo json_encode([
            'status' => 'error',
            'code' => 500,
            'message' => $mensaje
        ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    public function customError(int $code = 500, string $mensaje): void
    {
        http_response_code($code);

        echo json_encode([
            'status' => 'error',
            'code' => $code,
            'message' => $mensaje
        ]);
    }
}
