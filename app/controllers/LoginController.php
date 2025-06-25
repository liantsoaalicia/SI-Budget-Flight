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
     public function clientLogin() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['client_email'];
            $mdp = $_POST['client_password'];

            $result = Flight::LoginModel()->clientLogin($email, $mdp);
            if($result['success']) {
                $_SESSION['client_id'] = $result['client']['client_id'];
                $_SESSION['client_nom'] = $result['client']['client_nom'];
                $_SESSION['client_email'] = $result['client']['client_email'];
                $_SESSION['user_type'] = 'client'; // Identifier le type d'utilisateur
                
                // Rediriger vers l'interface client
                Flight::render('template.php', [
                    'pageTitle' => 'Espace Client',
                    'view' => 'client/dashboard',
                    'currentPage' => 'client_dashboard',
                    'sidebarCollapsed' => false
                ]);
            } else {
                // Gérer l'erreur de connexion
                Flight::render('pages/login/form', [
                    'error' => $result['message']
                ]);
            }
        }
    }

    public function logout() {
        session_destroy();
        Flight::redirect('/');
    }
}