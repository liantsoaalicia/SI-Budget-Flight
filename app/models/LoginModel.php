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

        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Erreur de connexion'];
        }
    }
}