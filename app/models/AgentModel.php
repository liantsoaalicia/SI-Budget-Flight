<?php
namespace app\models;
use Flight;
use PDO;

class AgentModel extends AbstractDolibarrModel {


    public function createAgent($userData)
    {
        return $this->makeRequest('POST', 'users', $userData);
    }

    public function deleteAgent($id)
    {
        return $this->makeRequest('DELETE', 'users/' . $id);
    }


    public function getAgent($id)
    {
        return $this->makeRequest('GET', 'users/' . $id);
    }


    public function updateAgent($id, $userData)
    {
        return $this->makeRequest('PUT', 'users/' . $id, $userData);
    }

  
    public function listAgents($query = '')
    {
        $endpoint = 'users';
        if (!empty($query)) {
            $endpoint .= '?' . $query;
        }
        return $this->makeRequest('GET', $endpoint);
    }
}
