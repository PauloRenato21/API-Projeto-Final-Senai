<?php namespace App\Controllers;

use App\Helpers\Validacao;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\FuncionarioModel;
use App\Models\FranquiaModel;
use App\Models\CargoModel;

use App\Models\TurmaFuncionarioModel;
 
class Funcionario extends ResourceController
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

    // lista todos Funcionario
    public function index()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FuncionarioModel();
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
 
    // lista um Funcionario
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FuncionarioModel();
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
 
    // adiciona um Funcionario
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FuncionarioModel();
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
    
    // atualiza um Funcionario
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FuncionarioModel();
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
 
    // deleta um Funcionario
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FuncionarioModel();
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

    public function funcionarioPdf(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $funcionarioModel = new FuncionarioModel();
                $cargoModel = new CargoModel();
                $franquiaModel = new FranquiaModel();
                $turmafuncionarioModel = new TurmaFuncionarioModel();
                
                $datafuncionario = $funcionarioModel->findAll();
                $datacargo = $cargoModel->findAll();
                $datafranquia = $franquiaModel->findAll();
                $allDataTurmaFuncionario = $turmafuncionarioModel->getAllTurmaFuncionario()->findAll();
                
                $resultado = [];
                
                foreach ($datafuncionario as $funcionario){
                    $funcionario['cargo'] = array();
                    $funcionario['franquia'] = array();
                    $funcionario['turma'] = array();
                
                    foreach ($datacargo as $cl){
                        if ($funcionario['fk_cargo_id'] == $cl['id']){
                
                            $funcionariocargo['id'] = $cl['id'];
                            $funcionariocargo['nome'] = $cl['nome'];
                
                            array_push($funcionario['cargo'], $funcionariocargo);
                        }
                    }

                    foreach($datafranquia as $franquia){
                        if($funcionario['fk_franquias_id'] == $franquia['id']){
                            $funcionariofranquia['nome'] = $franquia['nome'];
                            $funcionariofranquia['cnpj'] = $franquia['cnpj'];
                            $funcionariofranquia['rua'] = $franquia['endereco_rua'];
                            $funcionariofranquia['numero'] = $franquia['endereco_numero'];
                            $funcionariofranquia['cep'] = $franquia['endereco_CEP'];
                            $funcionariofranquia['estado'] = $franquia['estado'];
                            $funcionariofranquia['cidade'] = $franquia['cidade'];
                            $funcionariofranquia['telefone'] = $franquia['telefone'];
                            $funcionariofranquia['email'] = $franquia['email'];

                            array_push($funcionario['franquia'],$funcionariofranquia);
                        }
                    }

                    foreach($allDataTurmaFuncionario as $tf){
                        if($funcionario['id'] == $tf['fk_funcionario_id']){
                            $funcionarioturma['id'] = $tf['fk_turma_id'];
                            $funcionarioturma['nome'] = $tf['nome'];
                            $funcionarioturma['turno'] = $tf['turno'];
                            $funcionarioturma['horario_inicial'] = $tf['horario_inicial'];
                            $funcionarioturma['horario_termino'] = $tf['horario_termino'];

                            array_push($funcionario['turma'], $funcionarioturma);
                        }
                    }
                
                    array_push($resultado, $funcionario);
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

    public function funcionariocargofranquia(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $funcionarioModel = new FuncionarioModel();
                $cargoModel = new CargoModel();
                $franquiaModel = new FranquiaModel();
                
                $datafuncionario = $funcionarioModel->findAll();
                $datacargo = $cargoModel->findAll();
                $datafranquia = $franquiaModel->findAll();
                
                $resultado = [];
                
                foreach ($datafuncionario as $funcionario){
                    $funcionario['cargo'] = array();
                    $funcionario['franquia'] = array();

                    foreach ($datacargo as $cl){
                        if ($funcionario['fk_cargo_id'] == $cl['id']){
                
                            $funcionariocargo['id'] = $cl['id'];
                            $funcionariocargo['nome'] = $cl['nome'];
                
                            array_push($funcionario['cargo'], $funcionariocargo);
                        }
                    }
                
                    foreach ($datafranquia as $franquia){
                        if ($funcionario['fk_franquias_id'] == $franquia['id']){
                    
                            $funcionariofranquia['id'] = $franquia['id'];
                            $funcionariofranquia['nome'] = $franquia['nome'];
                    
                            array_push($funcionario['franquia'], $funcionariofranquia);
                        }
                    }
                
                    array_push($resultado, $funcionario);
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