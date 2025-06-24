<?php
namespace app\models;
use Flight;
use PDO;

class ClientModel extends AbstractDolibarrModel {

    public function createClient($clientData) {
        return $this->makeRequest('POST', 'thirdparties', $clientData);
    }

    public function deleteClient($id) {
        return $this->makeRequest('DELETE', 'thirdparties/' . $id);
    }

    public function getClient($id) {
        return $this->makeRequest('GET', 'thirdparties/' . $id);
    }

    public function updateClient($id, $clientData) {
        return $this->makeRequest('PUT', 'thirdparties/' . $id, $clientData);
    }

    public function listClients($filters = []) {
        $endpoint = 'thirdparties';
        if (!empty($filters)) {
            $endpoint .= '?' . http_build_query($filters);
        }
        return $this->makeRequest('GET', $endpoint);
    }
}
