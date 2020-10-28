<?php

use App\Component\Router\Route;
use App\Controller\AdminController;
use App\Controller\HomeController;
use App\Controller\PostController;

return [
    new Route('home', '#^/$#', [HomeController::class, 'index']),
    new Route('article', '#^/article(?:/(\d+))?$#', [PostController::class, 'index']),
    new Route('admin', '#^/admin$#', [AdminController::class, 'index']),
    new Route('admin_create', '#^/admin/create$#', [AdminController::class, 'create']),
    new Route('admin_update', '#^/admin/update/(\d+)$#', [AdminController::class, 'update']),
    new Route('admin_delete', '#^/admin/delete/(\d+)$#', [AdminController::class, 'delete'])
];