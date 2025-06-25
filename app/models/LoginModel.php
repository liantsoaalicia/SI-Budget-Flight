<?php
namespace app\models;
use Flight;

class LoginModel extends AbstractDolibarrModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function checkPassword($password, $hash) {
        return (
            password_verify($password, $hash) ||
            md5($password) === $hash ||
            sha1($password) === $hash 
        );
    }

    public function login($loginUser, $password) {
        try {
            $loginUser = trim($loginUser);
            $sql = "
                SELECT u.rowid, u.login, u.firstname, u.lastname, u.pass_crypted, 
                d.rowid as departement_id, d.nom as departement_nom FROM llx_user u
                LEFT JOIN llx_user_departement ud ON u.rowid = ud.fk_user
                LEFT JOIN llx_departement d ON ud.fk_departement = d.rowid
                WHERE BINARY u.login = ? AND u.statut = 1
            ";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $loginUser, \PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(\PDO::FETCH_ASSOC);
            // verifier le mdp
            if(!$this->checkPassword($password, $user['pass_crypted'])) {
                return ['success' => false, 'message' => 'Mot de passe incorrect'];
            }

            return [
                'success' => true,
                'user' => ['user_id' => $user['rowid'], 'login_user' => $user['login'], 'nom_user' => $user['firstname'].''.$user['lastname']],
                'departement' => ['departement_id' => $user['departement_id'], 'departement_nom' => $user['departement_nom']]
            ];

        } catch(\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de connexion'];
        }
    }

     public function clientLogin($email, $password) {
        try {
            $email = trim($email);
            
            // Recherche du client dans la table des tiers (thirdparties)
            $sql = "
                SELECT s.rowid, s.nom, s.email, s.pass_crypted, s.code_client
                FROM llx_societe s
                WHERE s.email = ? AND s.client = 1 AND s.status = 1
            ";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(1, $email, \PDO::PARAM_STR);
            $stmt->execute();
            $client = $stmt->fetch(\PDO::FETCH_ASSOC);
            
            if (!$client) {
                return ['success' => false, 'message' => 'Client introuvable'];
            }
            
            // Si pas de mot de passe défini, utiliser l'email comme mot de passe temporaire
            // ou implémenter votre logique de vérification
            $storedPassword = $client['pass_crypted'] ?: md5($email);
            
            if (!$this->checkPassword($password, $storedPassword)) {
                return ['success' => false, 'message' => 'Mot de passe incorrect'];
            }

            return [
                'success' => true,
                'client' => [
                    'client_id' => $client['rowid'],
                    'client_nom' => $client['nom'],
                    'client_email' => $client['email'],
                    'code_client' => $client['code_client']
                ]
            ];

        } catch(\PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de connexion: ' . $e->getMessage()];
        }
    }
}