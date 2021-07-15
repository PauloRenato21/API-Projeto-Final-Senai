<?php namespace App\Controllers;

use App\Helpers\Validacao;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CargoModel;
use App\Models\FuncionarioModel;
 
class Cargo extends ResourceController
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

                $model = new CargoModel();
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
 
    // lista um Cargos
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CargoModel();
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
 
    // adiciona um Cargos
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CargoModel();
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
    
    // atualiza um Cargos
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CargoModel();
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
 
    // deleta um Cargos
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new CargoModel();
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

    public function cargoPdf(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $funcionarioModel = new FuncionarioModel();
                $cargoModel = new CargoModel();
                
                $datafuncionario = $funcionarioModel->findAll();
                $datacargo = $cargoModel->findAll();
                
                $resultado = [];
                
                foreach ($datacargo as $cargo){
                    $cargo['funcionario'] = array();
                
                    foreach ($datafuncionario as $funcionario){
                        if ($cargo['id'] == $funcionario['fk_cargo_id']){
                
                            $cargoFuncionario['nome'] = $funcionario['nome'];
                            $cargoFuncionario['dt_nascimento'] = $funcionario['dt_nascimento'];
                            $cargoFuncionario['cpf'] = $funcionario['cpf'];
                            $cargoFuncionario['rg'] = $funcionario['rg'];
                            $cargoFuncionario['naturalidade'] = $funcionario['naturalidade'];
                            $cargoFuncionario['rua'] = $funcionario['endereco_rua'];
                            $cargoFuncionario['numero'] = $funcionario['endereco_numero'];
                            $cargoFuncionario['bairro'] = $funcionario['endereco_bairro'];
                            $cargoFuncionario['telefone'] = $funcionario['telefone'];
                            $cargoFuncionario['email'] = $funcionario['email'];
                
                            array_push($cargo['funcionario'], $cargoFuncionario);
                        }
                    }
                
                    array_push($resultado, $cargo);
                }
                
                return $this->respond($resultado);
    
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