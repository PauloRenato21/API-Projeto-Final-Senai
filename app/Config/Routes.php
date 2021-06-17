<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

$routes->get('/', 'Home::index');
$routes->resource('categoria',['placeholder' => '(:num)']);
$routes->resource('admin',['placeholder' => '(:num)']);
$routes->resource('franquia',['placeholder' => '(:num)']);
$routes->resource('clube',['placeholder' => '(:num)']);
$routes->get('clube/franquia','clube::clubeFranquia');

$routes->resource('responsavel',['placeholder' => '(:num)']);
$routes->resource('atleta',['placeholder' => '(:num)']);
$routes->resource('turma',['placeholder' => '(:num)']);
$routes->get('responsavel/rat','responsavel::responsavelatletaturma');

$routes->resource('cargo',['placeholder' => '(:num)']);
$routes->get('cargo/cf','cargo::cargofuncionario');

$routes->resource('funcionario',['placeholder' => '(:num)']);
$routes->get('funcionario/ffc','funcionario::funcionariocargofranquia');
$routes->get('funcionario/fct','funcionario::funcionariocargoturma');

$routes->resource('turmafuncionario', ['placeholder' => '(:num)']);
$routes->get('turmafuncionario/funcionario', 'turmafuncionario::funcionario');


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
