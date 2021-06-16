<?php namespace App\Controllers;

use App\Models\FranquiaModel;
use CodeIgniter\RESTful\ResourceController;

class Franquia extends ResourceController{

    //Metodo Select Franquia. 
    public function index()
    {
        $modal = new FranquiaModel();
        $data = $modal->findAll();
        
        return $this->respond($data);
    }

    //Metodo Insert Franquia.
    public function create()
    {
        $model = new FranquiaModel();
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

    //Metodo Update Franquia.
    public function update($id = null)
    {
        $model = new FranquiaModel();
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

    //Metodo Delete Franquia.
    public function delete($id = null)
    {
        $model = new FranquiaModel();
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