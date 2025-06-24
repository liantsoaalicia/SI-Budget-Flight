<?php 
namespace app\controllers;

use app\models\AgentModel;
use Flight;

class AgentController {

    private $agentModel;

    public function __construct() {
        $this->agentModel = new AgentModel();
    }

    
    public function showAddForm() {
        Flight::render('template.php', [
            'pageTitle' => 'Ajout agent',
            'view' => 'agent/form', 
            'currentPage' => 'ajout_agent',
            'sidebarCollapsed' => false 
        ]);
    }

    public function ajout() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = [
                'login'     => $_POST['login'],
                'lastname'  => $_POST['lastname'],
                'firstname' => $_POST['firstname'],
                'email'     => $_POST['email'],
                'pass'      => $_POST['password'],
                'admin'     => isset($_POST['admin']) ? 1 : 0,
                'employee'  => 1
            ];

            $result = $this->agentModel->createAgent($user);

            // Optional: handle errors if $result['status'] !== 200 or 201
            if ($result['status'] === 200 || $result['status'] === 201) {
                Flight::redirect('/agent/add'); // success redirect
            } else {
                Flight::render('template.php', [
                    'pageTitle' => 'Ajout agent - Erreur',
                    'view' => 'agent/form',
                    'error' => $result['data'] ?? 'Erreur inconnue',
                    'currentPage' => 'ajout_agent',
                    'sidebarCollapsed' => false 
                ]);
            }
        }
    }
}
