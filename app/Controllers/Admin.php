<?php namespace App\Controllers;

use App\Helpers\Validacao;
use App\Models\AdminModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;

class Admin extends ResourceController
{   
    use ResponseTrait;

    protected $key;

    public function __construct()
    {
        $this->key = new Validacao();
    }

    //Metedo Select Admin.
	public function index()
    {
        if($this->request->getHeader("Authorization")){
            if($this->key->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new AdminModel();
                $data = $model->findAll();
                return $this->respond($data);
    
            } else{
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else{
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }

    public function login(){
        $modalAdmin = new AdminModel();

        $data = $modalAdmin->where('user', $this->request->getVar('user'))->first();

        if(!empty($data)){
            if($this->request->getVar('password') == $data['password']){

                $key = $this->key->getKey();

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

    //Metodo show que tras um dado especifico pelo id dele Admin.
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->key->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new AdminModel();
                $data = $model->getWhere(['id' => $id])->getResult();

                if($data){
                    return $this->respond($data);
                }

                return $this->failNotFound('Nenhum dado encontrado com id '.$id);
    
            } else{
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else{
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }

    //Metedo Insert Admin.
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->key->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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
    
            } else{
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else{
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }

    //Metedo Update Admin.
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->key->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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
    
            } else{
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else{
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }
    
    //Metedo Delete Admin.
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->key->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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
    
            } else{
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else{
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }
}
