<?php namespace App\Controllers;

use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Exception;

class Admin extends ResourceController
{
    use ResponseTrait;

    //Metedo Select Admin.
	public function index()
    {
        $model = new AdminModel();
        $data = $model->findAll();
        return $this->respond($data);
    }

    private function getKey(){
        return 'TI2004M-03';
    }

    public function login(){
        $modalAdmin = new AdminModel();

        $data = $modalAdmin->where('user', $this->request->getVar('user'))->first();

        if(!empty($data)){
            if($this->request->getVar('password') == $data['password']){

                $key = $this->getKey();

                $iat = time(); // retorna em timestamp
                $nbf = $iat + 10;
                $exp = $iat + 3600;

                $payload = array(
                    "iss" => "api_escola-futebol",
                    "aud" => "diversos_app",
                    "iat" => $iat, // issued at
                    "nbf" => $nbf, //not before in seconds
                    "exp" => $exp // expire time in seconds
                );

                $token = JWT::encode($payload, $key);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'messages' => 'Usuário logado com sucesso',
                    'data' => [
                        'token' => $token
                    ]
                ];
                return $this->respondCreated($response);

            } else {

                $response = [
                    'status' => 500,
                    'error' => true,
                    'messages' => 'Login inválido',
                    'data' => []
                ];
                return $this->respondCreated($response);
            }

        } else {
            $response = [
                'status' => 500,
                'error' => true,
                'messages' => 'Login inválido',
                'data' => []
            ];
            return $this->respondCreated($response);
        }

    }

    //Metedo Insert Admin.
    public function create()
    {
        $model = new AdminModel();
        $data = $this->request->getJSON();

        if($model->insert($data)){
            $response = [
                'status'   => 201,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados salvos'
                ]
            ];
            return $this->respondCreated($response);
        }

        return $this->fail($model->errors());
    }

    //Metedo Update Admin.
    public function update($id = null)
    {
        $model = new AdminModel();
        $data = $this->request->getJSON();
        
        if($model->update($id, $data)){
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados atualizados'
                    ]
                ];
                return $this->respond($response);
        };

        return $this->fail($model->errors());
    }
    
    //Metedo Delete Admin.
    public function delete($id = null)
    {
        $model = new AdminModel();
        $data = $model->find($id);
        
        if($data){
            $model->delete($id);
            $response = [
                'status'   => 200,
                'error'    => null,
                'messages' => [
                    'success' => 'Dados removidos'
                ]
            ];
            return $this->respondDeleted($response);
        }
        
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);        
    }
}
