<?php 
namespace app\controllers;
// use '../vendor/autoload.php';
use app\models\DolibarrModel;
use Flight;

class DolibarrController {

    public function __construct() {
        
    }

    public function dolibarr() {
        $dolibarr = new DolibarrModel();
        $result = $dolibarr->getStatus();

        if (isset($result['error'])) {
            Flight::render('table', ['error' => $result['error']]);
        } else {
            Flight::render('table', ['data' => $result['data']['success']]);
        }
    }
    
}