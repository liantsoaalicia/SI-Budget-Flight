<?php 
namespace app\controllers;
use app\models\LoginModel;
use Flight;

class LoginController {

    public function __construct() {
        if(session_status() === PHP_SESSION_NONE)
        {
            session_start();
        }
    }

    public function redirectLogin() {
        Flight::render('pages/login/form');
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['login'];
            $mdp = $_POST['password'];

            $result = Flight::LoginModel()->login($nom, $mdp);
            if($result['success']) {
                $_SESSION['user_id'] = $result['user']['user_id'];
                $_SESSION['departement_id'] = $result['departement']['departement_id'];
                $_SESSION['departement_nom'] = $result['departement']['departement_nom'];
                
                Flight::render('template.php', [
                    'pageTitle' => 'Accueil',
                    'view' => 'accueil',
                    'currentPage' => 'accueil',
                    'sidebarCollapsed' => false 
                ]);
            }
        }
    }

}