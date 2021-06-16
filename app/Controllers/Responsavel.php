<?php namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ResponsavelModel;
 
class Responsavel extends ResourceController
{
    use ResponseTrait;
    // lista todos responsaveis
    public function index()
    {
        $model = new ResponsavelModel();
        $data = $model->findAll();
        return $this->respond($data);
    }
 
    // lista um responsavels
    public function show($id = null)
    {
        $model = new ResponsavelModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if($data){
            return $this->respond($data);
        }
        
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);        
    }
 
    // adiciona um responsavels
    public function create()
    {
        $model = new ResponsavelModel();
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
    
    // atualiza um responsavels
    public function update($id = null)
    {
        $model = new ResponsavelModel();
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
 
    // deleta um responsavels
    public function delete($id = null)
    {
        $model = new ResponsavelModel();
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