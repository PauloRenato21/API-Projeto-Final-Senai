<?php namespace App\Controllers;

use App\Helpers\Validacao;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CategoriaModel;
 
class Categoria extends ResourceController
{
    use ResponseTrait;

    public $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, POST, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Max-Age: 86400");
        header("Access-Control-Allow-Credentials: true");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    // lista todos responsaveis
    public function index()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CategoriaModel();
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
 
    // lista um Categorias
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CategoriaModel();
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
 
    // adiciona um Categorias
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CategoriaModel();
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
    
    // atualiza um Categorias
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CategoriaModel();
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
 
    // deleta um Categorias
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CategoriaModel();
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