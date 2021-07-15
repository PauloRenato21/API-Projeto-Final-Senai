<?php namespace App\Controllers;

use App\Helpers\Validacao;
use App\Models\AtletaModel;
use App\Models\CargoModel;
use App\Models\CategoriaModel;
use App\Models\ClubeModel;
use App\Models\FranquiaModel;
use App\Models\FuncionarioModel;
use App\Models\TurmaModel;
use CodeIgniter\RESTful\ResourceController;

class Franquia extends ResourceController{

    public $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
        header('Access-Control-Allow-Origin: http://localhost:3000');
        header('Access-Control-Allow-Methods: GET, POST, PUT, PATCH, POST, DELETE, OPTIONS');
        header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
        header("Access-Control-Max-Age: 86400");
        header("Access-Control-Allow-Credentials: true");
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method == "OPTIONS") {
            die();
        }
    }

    //Metodo Select Franquia. 
    public function index()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $modal = new FranquiaModel();
                $data = $modal->findAll();
                
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

    //Metodo que tras informações relacionadas com um Franquia: 
    //clube.
    public function franquiaClube(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metodo do relatorio das informações da franquia;
    public function franquiaPdf(){
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $modalFranquia = new FranquiaModel();
                $modalClube = new ClubeModel();
                $modalFuncionario = new FuncionarioModel();
                $modalCargo = new CargoModel();
                $modalTurma = new TurmaModel();
                $modalCategoria = new CategoriaModel();
                $modalAtleta = new AtletaModel();

                $dataFranquia = $modalFranquia->findAll();
                $dataClube = $modalClube->findAll();
                $dataFuncionario = $modalFuncionario->findAll();
                $dataCargo = $modalCargo->findAll();
                $dataTurma = $modalTurma->findAll();
                $dataCategoria = $modalCategoria->findAll();
                $dataAtleta = $modalAtleta->findAll();

                $resultado = [];

                foreach($dataFranquia as $franquia){
                    $franquia["clube"] = array();
                    $franquia["funcionarios"] = array();
                    $franquia["cargo"] = array();
                    $franquia["turma"] = array();
                    $franquia["categoria"] = array();
                    $franquia["atleta"] = array();

                    foreach($dataClube as $clube){
                        if($franquia['fk_clube_futebol_id'] == $clube['id']){
                            $franquiaClube['nome'] = $clube['nome'];
                            $franquiaClube['cnpj'] = $clube['cnpj'];

                            array_push($franquia['clube'],$franquiaClube);
                        }
                    }

                    foreach($dataFuncionario as $funcionario){
                        if($franquia['id'] == $funcionario['fk_franquias_id']){
                            $franquiaFuncionario['nome'] = $funcionario['nome'];
                            $franquiaFuncionario['dt_nascimento'] = $funcionario['dt_nascimento'];
                            $franquiaFuncionario['cpf'] = $funcionario['cpf'];
                            $franquiaFuncionario['rg'] = $funcionario['rg'];
                            $franquiaFuncionario['naturalidade'] = $funcionario['naturalidade'];
                            $franquiaFuncionario['rua'] = $funcionario['endereco_rua'];
                            $franquiaFuncionario['numero'] = $funcionario['endereco_numero'];
                            $franquiaFuncionario['bairro'] = $funcionario['endereco_bairro'];
                            $franquiaFuncionario['telefone'] = $funcionario['telefone'];
                            $franquiaFuncionario['email'] = $funcionario['email'];
                            
                            $fkFranquia = $funcionario['fk_franquias_id'];
                            $fkCargo = $funcionario['fk_cargo_id'];

                            array_push($franquia['funcionarios'],$franquiaFuncionario);
                        }
                    }

                    foreach($dataCargo as $cargo){
                        if($franquia['id'] == $fkFranquia){
                            if($fkCargo == $cargo['id']){

                                $franquiaCargo['nome'] = $cargo['nome'];
    
                                array_push($franquia['cargo'],$franquiaCargo);
                            }
                        }
                    }

                    foreach($dataTurma as $turma){
                        if($franquia['id'] == $turma['fk_franquias_id']){
                            $franquiaTurma['nome'] = $turma['nome'];
                            $franquiaTurma['turno'] = $turma['turno'];
                            $franquiaTurma['horario_inicial'] = $turma['horario_inicial'];
                            $franquiaTurma['horario_termino'] = $turma['horario_termino'];
                            $fkCategoria = $turma['fk_categoria_id'];
                            $idTurma = $turma['id'];

                            array_push($franquia['turma'],$franquiaTurma);
                        }
                    }

                    foreach($dataCategoria as $categoria){
                        if($fkCategoria == $categoria['id']){
                            $franquiaCategoria['nome'] = $categoria['nome'];

                            array_push($franquia['categoria'],$franquiaCategoria);
                        }
                    }

                    foreach($dataAtleta as $atleta){
                        if($idTurma == $atleta['fk_turma_id']){
                            $franquiaAtleta['nome'] = $atleta['nome'];
                            $franquiaAtleta['cpf'] = $atleta['cpf'];
                            $franquiaAtleta['dt_nascimento'] = $atleta['dt_nascimento'];
                            $franquiaAtleta['rua'] = $atleta['endereco_rua'];
                            $franquiaAtleta['numero'] = $atleta['endereco_numero'];
                            $franquiaAtleta['bairro'] = $atleta['endereco_bairro'];
                            $franquiaAtleta['cep'] = $atleta['endereco_CEP'];
                            $franquiaAtleta['naturalidade'] = $atleta['naturalidade'];
                            $franquiaAtleta['problema_saude'] = $atleta['problema_saude'];
                            $franquiaAtleta['alergia'] = $atleta['alergia'];
                            $franquiaAtleta['medicamento'] = $atleta['medicamento'];
                            $franquiaAtleta['telefone'] = $atleta['telefone'];
                            $franquiaAtleta['email'] = $atleta['email'];

                            array_push($franquia['atleta'],$franquiaAtleta);
                        }
                    }

                    array_push($resultado,$franquia);
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

    //Metodo show que tras um dado especifico pelo id dele Franquia.
    public function show($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

                $model = new FranquiaModel();
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
    
    //Metodo Insert Franquia.
    public function create()
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metodo Update Franquia.
    public function update($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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

    //Metodo Delete Franquia.
    public function delete($id = null)
    {
        if($this->request->getHeader("Authorization")){
            if($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())){

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