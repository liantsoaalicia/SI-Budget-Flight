<?php
namespace app\models;
use Flight;
use PDO;

class ClientModel extends AbstractDolibarrModel {

    public function createClient($contactData) {
        return $this->makeRequest('POST', 'thirdparties', $contactData);
    }

    public function deleteClient($id) {
        return $this->makeRequest('DELETE', 'thirdparties/' . $id);
    }

    public function getClient($id) {
        return $this->makeRequest('GET', 'thirdparties/' . $id);
    }

    public function updateClient($id, $contactData) {
        return $this->makeRequest('PUT', 'thirdparties/' . $id, $contactData);
    }
}
