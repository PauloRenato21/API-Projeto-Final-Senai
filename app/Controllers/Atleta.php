<?php namespace App\Controllers;

use App\Models\AtletaModel;
use CodeIgniter\RESTful\ResourceController;

class Atleta extends ResourceController{

    //Metedo Select Atleta.
    public function index()
    {
        $modal = new AtletaModel();
        $data = $modal->findAll();

        return $this->respond($data);
    }

    //Metodo que tras informações relacionadas com um Atleta: 
    //turma, responsavel.
    public function atletaTurmaResponsavel(){
    
    }

    //Metodo Insert Atleta.
    public function create()
    {
        $model = new AtletaModel();
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

    //Metodo Update Atleta.
    public function update($id = null)
    {
        $model = new AtletaModel();
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

    //Metodo Delete Atleta.
    public function delete($id = null)
    {
        $model = new AtletaModel();
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