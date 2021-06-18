<?php namespace App\Helpers;

use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Exception;

class Validacao extends ResourceController{

    public function getKey(){
        return 'TI2004M-03';
    }

    public function validacaoToken($value){
        
        try{
            $token = $value;
            $decoded = JWT::decode($token, $this->getKey(), array("HS256"));

            if ($decoded) {
                return true;
            } else{
                return false;
            }
            
        } catch (Exception $ex) {
            return false;
        }
    }

}