<?php

use app\controllers\DolibarrController;
use app\controllers\LoginController;
use app\controllers\ClientController;
use app\controllers\AgentController;

use app\controllers\TicketController;

use flight\Engine;
use flight\net\Router;
// require_once 'controllers/VehiculeController';
//use Flight;

/** 
 * @var Router $router 
 * @var Engine $app
 */
/*$router->get('/', function() use ($app) {
	$Welcome_Controller = new WelcomeController($app);
	$app->render('welcome', [ 'message' => 'It works!!' ]);
});*/

$DolibarrController = new DolibarrController();
$LoginController = new LoginController();
$ClientController = new ClientController();
$AgentController = new AgentController();
$TicketController = new TicketController();

Flight::route('', [$LoginController, 'redirectLogin'] );
Flight::route('POST /user/login', [$LoginController, 'login'] );
Flight::route('/client/redirect', [$ClientController, 'redirectClient']);
Flight::route('POST /client/ajout', [$ClientController, 'ajout'] );
Flight::route('POST /ticket/ajout', [$TicketController, 'ajout'] );
Flight::route('/ticket/redirect', [$TicketController, 'redirectForm'] );
Flight::route('/ticket/ajout', [$TicketController, 'redirectForm'] );
Flight::route('POST /ticket/assign/', [$TicketController, 'assignAgentToTicket']);
Flight::route('/ticket/assign', [$TicketController, 'showAssignmentList']);

Flight::route('/agent/form', [$AgentController, 'showAddForm']);
Flight::route('POST /agent/add', [$AgentController, 'ajout']);


//Flight::start();
//$router->get('/', \app\controllers\WelcomeController::class.'->home'); 

// $router->get('/hello-world/@name', function($name) {
// 	echo '<h1>Hello world! Oh hey '.$name.'!</h1>';
// });

// $router->group('/api', function() use ($router, $app) {
// 	$Api_Example_Controller = new ApiExampleController($app);
// 	$router->get('/users', [ $Api_Example_Controller, 'getUsers' ]);
// 	$router->get('/users/@id:[0-9]', [ $Api_Example_Controller, 'getUser' ]);
// 	$router->post('/users/@id:[0-9]', [ $Api_Example_Controller, 'updateUser' ]);
// });