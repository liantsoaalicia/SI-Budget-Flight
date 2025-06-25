<?php 
namespace app\controllers;
use app\models\ClientModel;
use Flight;

class ClientController {

    private $clientModel;

    public function __construct() {
        $this->clientModel = new ClientModel();
    }

    public function redirectClient() {
        Flight::render('template.php', [
            'pageTitle' => 'Ajout client',
            'view' => 'client/form',
            'currentPage' => 'ajout_client',
            'sidebarCollapsed' => false,
        ]);
    }
    
    public function ajout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $client = [
                'name' => $_POST['nom'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address'],
                'zip' => $_POST['zip']
            ];
            $result = $this->clientModel->createClient($client);
            Flight::redirect('/client/redirect');
        }
    }   
}