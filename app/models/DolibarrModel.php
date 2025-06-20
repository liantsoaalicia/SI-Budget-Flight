<?php

namespace app\models;

class DolibarrModel extends AbstractDolibarrModel
{
    public function getStatus() {
        $response = $this->makeRequest('GET', 'status');
        return $response;
    }
}
