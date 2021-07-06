<?php namespace App\Controllers;

use App\Helpers\Validacao;
use App\Models\ClubeModel;
use App\Models\FranquiaModel;
use CodeIgniter\RESTful\ResourceController;

class Clube extends ResourceController{

    public $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
        header('Access-Control-Allow-Origin: *');
    }
    
    //Metedo Select Clube.
    public function index()
    {   
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new ClubeModel();
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

    //Metodo que tras informações relacionadas a um Clube:
    //franquias.
    public function clubeFranquia(){

        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metodo show que tras um dado especifico pelo id dele Clube.
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new ClubeModel();
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

    //Metedo Insert Clube.
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metedo Update Clube.
    public function update($id = null)
    {

        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metedo Delete Clube.
    public function delete($id = null)
    {

        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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