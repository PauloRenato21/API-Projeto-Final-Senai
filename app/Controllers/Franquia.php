<?php namespace App\Controllers;

use App\Models\ClubeModel;
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

    //Metodo que tras informações relacionadas com um Franquia: 
    //clube.
    public function franquiaClube(){
        $modalFranquia = new FranquiaModel();
        $modalClube = new ClubeModel();

        $dataFranquia = $modalFranquia->findAll();
        $dataClube = $modalClube->findAll();

        $resultado = [];

        foreach($dataFranquia as $franquia){
            $franquia['clube'] = array();

            foreach($dataClube as $clube){
                if($franquia['fk_clube_futebol_id'] == $clube['id']){
                    $franquiaClube['nome'] = $clube['nome'];
                    $franquiaClube['cnpj'] = $clube['cnpj'];

                    array_push($franquia['clube'],$franquiaClube);
                }
            }

            array_push($resultado,$franquia);
        }

        return $this->respond($resultado);
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