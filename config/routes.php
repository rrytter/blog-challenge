<?php

use App\Component\Routing\Route;

return [
    new Route('admin_index', '/admin', 'App\\Controller\\AdminController', 'index'),
    new Route('admin_create', '/admin/create', 'App\\Controller\\AdminController', 'create'),
    new Route('admin_create', '/admin/update/{id}', 'App\\Controller\\AdminController', 'update'),
    new Route('admin_delete', '/admin/delete/{id}', 'App\\Controller\\AdminController', 'delete'),
];