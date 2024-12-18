# API Rest simple en PHP
Código que simula una API Rest de stock de productos en un almacén.
Es capaz de realizar operaciones CRUD así como obtener los productos que están bajo el mínimo establecido.

## Configuración en localhost

La tarea está realizada siguiendo este path o dirección:

`http://localhost/projects/DWES03_TE02/`

Si se está usando XAMPP, guardar el contenido en: `C:\xampp\htdocs\projects\DWES03_TE02`

### Configuración hosts:
+  En __Windows__: `C:\Windows\System32\drivers\etc\hosts` añadir DNS personalizado. Por ejemplo `127.0.0.1 dwes03.test`

### Configuración del vhost:
+ En __Windows__ (con XAMPP), editar el archivo `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

    ```apache
    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs"
        ServerName localhost
    </VirtualHost>

    <VirtualHost *:80>
        DocumentRoot "C:/xampp/htdocs/projects/DWES03_TE02/public"
        ServerName dwes03.test
    </VirtualHost>
    ```

    Teniendo en cuenta que el `DocumentRoot` tiene que apuntar a la carpeta public del proyecto.

## Rutas (end poinsts)

| Method | Description                  | Request                          |
| ------ | ---------------------------- |----------------------------------|
| GET    | Get all products             | http://../api/v1/products        |
| GET    | Get product by Id            | http://../api/v1/products/{{id}} |
| GET    | Get products under min stock | http://../api/v1/products/min    |
| POST   | Create product               | http://../api/v1/products        |
| PUT    | Update product by Id         | http://../api/v1/products/{{id}} |
| PATCH  | Partial update product by Id | http://../api/v1/products/{{id}} |
| DELETE | Delete product by Id         | http://../api/v1/products/{{id}} |