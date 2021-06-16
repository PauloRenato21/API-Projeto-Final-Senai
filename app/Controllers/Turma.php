<?php namespace App\Controllers;

use App\Models\TurmaModel;
use CodeIgniter\RESTful\ResourceController;

class Turma extends ResourceController{
    
    //Metedo Select Turma.
    public function index()
    {
        $model = new TurmaModel();
        $data = $model->findAll();

        return $this->respond($data);
    }

    //Metedo Insert Clube.
    public function create()
    {
        $model = new TurmaModel();
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

    //Metedo Update Clube.
    public function update($id = null)
    {
        $model = new TurmaModel();
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

    //Metedo Delete Clube.
    public function delete($id = null)
    {
        $model = new TurmaModel();
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