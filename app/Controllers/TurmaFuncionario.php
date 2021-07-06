<?php namespace App\Controllers;

use App\Helpers\Validacao;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\TurmafuncionarioModel;
use App\Models\FuncionarioModel;
 
class TurmaFuncionario extends ResourceController
{
    use ResponseTrait;

    public $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
        header('Access-Control-Allow-Origin: *');
    }

    // lista todos TurmaFuncionario
    public function index()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new TurmaFuncionarioModel();
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
 
    // lista um TurmaFuncionario
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new TurmaFuncionarioModel();
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
 
    // adiciona um TurmaFuncionario
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new TurmaFuncionarioModel();
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
    
    // atualiza um TurmaFuncionario
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new TurmaFuncionarioModel();
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
 
    // deleta um TurmaFuncionario
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new TurmaFuncionarioModel();
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

    public function funcionarioTurma(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $FuncionarioModel = new FuncionarioModel();
                $model = new TurmaFuncionarioModel();
                
                $dataFuncionario = $FuncionarioModel->findAll();
                
                $allDataTurmaFuncionario = $model->getAllTurmaFuncionario()->findAll();
                
                $resultado = [];
                
                foreach ($dataFuncionario as $funcionario){
                    $funcionario['turma'] = array();
                
                    foreach ($allDataTurmaFuncionario as $tf){
                        if ($funcionario['id'] == $tf['fk_funcionario_id']){
                    
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
 
}