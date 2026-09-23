<?php

declare(strict_types=1);

use App\Controllers\HomeController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', [HomeController::class, 'index'], ['as' => 'home']);

// Módulo: Residentes
$routes->group('residentes', static function ($routes) {
    $routes->get('', 'ResidentesController::index', ['as' => 'residentes.index']);
    $routes->get('novo', 'ResidentesController::novo', ['as' => 'residentes.novo']);
    $routes->post('criar', 'ResidentesController::criar', ['as' => 'residentes.criar']);
    $routes->get('detalhes/(:num)', 'ResidentesController::detalhes/$1', ['as' => 'residentes.detalhes']);
    $routes->get('editar/(:num)', 'ResidentesController::editar/$1', ['as' => 'residentes.editar']);
    $routes->post('atualizar/(:num)', 'ResidentesController::atualizar/$1', ['as' => 'residentes.atualizar']);
    $routes->post('excluir/(:num)', 'ResidentesController::excluir/$1', ['as' => 'residentes.excluir']);
    $routes->post('toggle-status/(:num)', 'ResidentesController::toggleStatus/$1', ['as' => 'residentes.toggleStatus']);
    $routes->get('usuario/(:num)/novo', 'ResidentesController::novoUsuario/$1', ['as' => 'residentes.novoUsuario']);
    $routes->post('usuario/(:num)/criar', 'ResidentesController::criarUsuario/$1', ['as' => 'residentes.criarUsuario']);
    $routes->post('usuario/(:num)/toggle-acesso', 'ResidentesController::toggleAcessoUsuario/$1', ['as' => 'residentes.toggleAcessoUsuario']);
});

// Módulo: Áreas Comuns
$routes->group('areas', static function ($routes) {
    $routes->get('', 'AreasController::index', ['as' => 'areas.index']);
    $routes->get('novo', 'AreasController::novo', ['as' => 'areas.novo']);
    $routes->post('criar', 'AreasController::criar', ['as' => 'areas.criar']);
    $routes->get('editar/(:num)', 'AreasController::editar/$1', ['as' => 'areas.editar']);
    $routes->post('atualizar/(:num)', 'AreasController::atualizar/$1', ['as' => 'areas.atualizar']);
    $routes->post('excluir/(:num)', 'AreasController::excluir/$1', ['as' => 'areas.excluir']);
    $routes->post('toggle-status/(:num)', 'AreasController::toggleStatus/$1', ['as' => 'areas.toggleStatus']);
});

// Módulo: Reservas
$routes->group('reservas', static function ($routes) {
    $routes->get('', 'ReservasController::index', ['as' => 'reservas.index']);
    $routes->get('novo', 'ReservasController::novo', ['as' => 'reservas.novo']);
    $routes->post('criar', 'ReservasController::criar', ['as' => 'reservas.criar']);
    $routes->get('detalhes/(:num)', 'ReservasController::detalhes/$1', ['as' => 'reservas.detalhes']);
    $routes->post('cancelar/(:num)', 'ReservasController::cancelar/$1', ['as' => 'reservas.cancelar']);
    $routes->post('confirmar/(:num)', 'ReservasController::confirmar/$1', ['as' => 'reservas.confirmar']);
});

// Módulo: Cobranças
$routes->group('cobrancas', static function ($routes) {
    $routes->get('', 'CobrancasController::index', ['as' => 'cobrancas.index']);
    $routes->get('detalhes/(:num)', 'CobrancasController::detalhes/$1', ['as' => 'cobrancas.detalhes']);
    $routes->post('pagar/(:num)', 'CobrancasController::pagar/$1', ['as' => 'cobrancas.pagar']);
});

service('auth')->routes($routes);
