<?php namespace App\Controllers;

use App\Models\ClubeModel;
use App\Models\FranquiaModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Exception;

class Clube extends ResourceController{

    private function getKey(){
        return 'TI2004M-03';
    }

    //Metedo Select Clube.
    public function index()
    {
        try {
            $token = $this->request->getHeader("Authorization")->getValue();
            $decoded = JWT::decode($token, $this->getKey(), array("HS256"));

            if ($decoded) {
                $model = new ClubeModel();
                $data = $model->findAll();

                return $this->respond($data);
            }
        } catch (Exception $ex) {
            return $this->failUnauthorized("Acesso negado! Exception: " . $ex);
        }
    }

    //Metodo que tras informações relacionadas a um Clube:
    //franquias.
    public function clubeFranquia(){
        $modelClube = new ClubeModel();
        $modelFranquia = new FranquiaModel();

        $dataClube = $modelClube->findAll();
        $dataFranquia = $modelFranquia->findAll();

        $resultado = [];

        foreach($dataClube as $clube){
            $clube['franquias'] = array();

            foreach($dataFranquia as $franquia){
                if($clube['id'] == $franquia['fk_clube_futebol_id']){
                    $clubeFranquia['id'] = $franquia['id'];
                    $clubeFranquia['nome'] = $franquia['nome'];
                    $clubeFranquia['cnpj'] = $franquia['cnpj'];
                    $clubeFranquia['endereco_rua'] = $franquia['endereco_rua'];
                    $clubeFranquia['endereco_numero'] = $franquia['endereco_numero'];
                    $clubeFranquia['endereco_bairro'] = $franquia['endereco_bairro'];
                    $clubeFranquia['endereco_CEP'] = $franquia['endereco_CEP'];
                    $clubeFranquia['estado'] = $franquia['estado'];
                    $clubeFranquia['cidade'] = $franquia['cidade'];
                    $clubeFranquia['telefone'] = $franquia['telefone'];
                    $clubeFranquia['email'] = $franquia['email'];
                    $clubeFranquia['id_clube'] = $franquia['fk_clube_futebol_id'];

                    array_push($clube['franquias'],$clubeFranquia);
                }
            }

            array_push($resultado, $clube);
        }

        return $this->respond($resultado);
    }

    //Metedo Insert Clube.
    public function create()
    {
        $model = new ClubeModel();
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
        $model = new ClubeModel();
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
        $model = new ClubeModel();
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