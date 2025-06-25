<?php

use app\controllers\DolibarrController;
use app\controllers\LoginController;
use app\controllers\ClientController;
use app\controllers\AgentController;
use app\controllers\MessageController;
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
$messageController = new MessageController();

// Routes de connexion
Flight::route('', [$LoginController, 'redirectLogin']);
Flight::route('POST /user/login', [$LoginController, 'login']);
Flight::route('POST /client/login', [$LoginController, 'clientLogin']); // Nouvelle route pour login client
Flight::route('/logout', [$LoginController, 'logout']); // Route de déconnexion

// Routes client
Flight::route('/client/redirect', [$ClientController, 'redirectClient']);
Flight::route('POST /client/ajout', [$ClientController, 'ajout']);

Flight::route('POST /ticket/ajout', [$TicketController, 'ajout'] );
Flight::route('/ticket/redirect', [$TicketController, 'redirectForm'] );
Flight::route('/ticket/ajout', [$TicketController, 'redirectForm'] );
Flight::route('POST /ticket/assign/', [$TicketController, 'assignAgentToTicket']);
Flight::route('/ticket/assign', [$TicketController, 'showAssignmentList']);

Flight::route('/ticket/list', [$TicketController, 'listAllWithStatusForm']);
Flight::route('POST /ticket/status/update', [$TicketController, 'updateStatus']);

Flight::route('/agent/form', [$AgentController, 'showAddForm']);
Flight::route('POST /agent/add', [$AgentController, 'ajout']);

// Routes pour les discussions agent
Flight::route('GET /ticket/@id/discussion', function($id) use ($messageController) {
    $messageController->showAgentDiscussion($id);
});

Flight::route('GET /message/agent/@id', function($id) use ($messageController) {
    $messageController->showAgentDiscussion($id);
});

// Routes pour les discussions client
Flight::route('GET /ticket/@id/client', function($id) use ($messageController) {
    $messageController->showClientDiscussion($id);
});

Flight::route('GET /message/client/@id', function($id) use ($messageController) {
    $messageController->showClientDiscussion($id);
});

// Routes pour l'envoi de messages
Flight::route('POST /message/send/agent', function() use ($messageController) {
    $messageController->sendAgentMessage();
});

Flight::route('POST /message/send/client', function() use ($messageController) {
    $messageController->sendClientMessage();
});

// API pour récupérer les nouveaux messages (AJAX)
Flight::route('GET /message/api/new/@ticketId/@lastMessageId', function($ticketId, $lastMessageId) use ($messageController) {
    $messageController->getNewMessages($ticketId, $lastMessageId);
});

// Route pour marquer comme lu
Flight::route('POST /message/mark-read/@ticketId', function($ticketId) use ($messageController) {
    $messageController->markTicketAsRead($ticketId);
});

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