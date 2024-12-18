<?php

// Definir las rutas de la API Rest

$router->add(
    '/api/v1/products',
    'GET',
    array(
        'controller' => 'ProductController',
        'action' => 'getAll'
    )
);

$router->add(
    '/api/v1/products/{id}',
    'GET',
    array(
        'controller' => 'ProductController',
        'action' => 'getById'
    )
);

$router->add(
    '/api/v1/products',
    'POST',
    array(
        'controller' => 'ProductController',
        'action' => 'create'
    )
);

$router->add(
    '/api/v1/products/{id}',
    'PUT',
    array(
        'controller' => 'ProductController',
        'action' => 'update'
    )
);

$router->add(
    '/api/v1/products/{id}',
    'PATCH',
    array(
        'controller' => 'ProductController',
        'action' => 'partialUpdate'
    )
);

$router->add(
    '/api/v1/products/{id}',
    'DELETE',
    array(
        'controller' => 'ProductController',
        'action' => 'delete'
    )
);

$router->add(
    '/api/v1/products/min',
    'GET',
    array(
        'controller' => 'ProductController',
        'action' => 'underMin'
    )
);
