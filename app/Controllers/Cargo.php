<?php namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\CargoModel;
use App\Models\FuncionarioModel;
 
class Cargo extends ResourceController
{
    use ResponseTrait;
    // lista todos responsaveis
    public function index()
    {
        $model = new CargoModel();
        $data = $model->findAll();
        return $this->respond($data);
    }
 
    // lista um Cargos
    public function show($id = null)
    {
        $model = new CargoModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if($data){
            return $this->respond($data);
        }
        
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);        
    }
 
    // adiciona um Cargos
    public function create()
    {
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
    }
    
    // atualiza um Cargos
    public function update($id = null)
    {
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
        }
 
    // deleta um Cargos
    public function delete($id = null)
    {
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
    }

    public function cargofuncionario(){
        $funcionarioModel = new FuncionarioModel();
        $cargoModel = new CargoModel();
        
        $datafuncionario = $funcionarioModel->findAll();
        $datacargo = $cargoModel->findAll();
        
        $resultado = [];
        
        foreach ($datafuncionario as $funcionario){
            $funcionario['cargo'] = array();
        
            foreach ($datacargo as $cl){
                if ($funcionario['fk_cargo_id'] == $cl['id']){
        
                    $funcionariocargo['id'] = $cl['id'];
                    $funcionariocargo['nome'] = $cl['nome'];
        
                    array_push($funcionario['cargo'], $funcionariocargo);
                }
            }
        
            array_push($resultado, $funcionario);
        }
        
        return $this->respond($resultado);
    }
 
}