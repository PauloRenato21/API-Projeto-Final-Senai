<?php namespace App\Controllers;

use App\Models\CategoriaModel;
use App\Models\FranquiaModel;
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

    //Metodo que tras informações relacionadas com uma Turma: 
    //categoria, franquia.
    public function turmaCategoriaFranquia(){
        $modelTurma = new TurmaModel();
        $modelCategoria = new CategoriaModel();
        $modelFranquia = new FranquiaModel();

        $dataTurma = $modelTurma->findAll();
        $dataCategoria = $modelCategoria->findAll();
        $dataFranquia = $modelFranquia->findAll();

        $resultado = [];

        foreach($dataTurma as $turma){
            $turma['categoria'] = array();
            $turma['franquia'] = array();

            foreach($dataCategoria as $categoria){
                if($turma['fk_categoria_id'] == $categoria['id']){
                    $turmaCategoria['nome'] = $categoria['nome'];

                    array_push($turma['categoria'],$turmaCategoria);
                }
            }

            foreach($dataFranquia as $franquia){
                if($turma['fk_franquias_id'] == $franquia['id']){
                    $turmaFranquia['nome'] = $franquia['nome'];
                    $turmaFranquia['cnpj'] = $franquia['cnpj'];
                    $turmaFranquia['rua'] = $franquia['endereco_rua'];
                    $turmaFranquia['numero'] = $franquia['endereco_numero'];
                    $turmaFranquia['cep'] = $franquia['endereco_CEP'];
                    $turmaFranquia['estado'] = $franquia['estado'];
                    $turmaFranquia['cidade'] = $franquia['cidade'];
                    $turmaFranquia['telefone'] = $franquia['telefone'];
                    $turmaFranquia['email'] = $franquia['email'];

                    array_push($turma['franquia'],$turmaFranquia);
                }
            }
            array_push($resultado,$turma);
        }
        return $this->respond($resultado);
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