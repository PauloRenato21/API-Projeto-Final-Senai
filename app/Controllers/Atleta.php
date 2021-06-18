<?php namespace App\Controllers;

use App\Models\AtletaModel;
use App\Models\ClubeModel;
use App\Models\FranquiaModel;
use App\Models\ResponsavelModel;
use App\Models\TurmaModel;
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
        $modelAtleta = new AtletaModel();
        $modelTurma = new TurmaModel();
        $modelResponsavel = new ResponsavelModel();
        
        $dataAtleta = $modelAtleta->findAll();
        $dataTurma = $modelTurma->findAll();
        $dataResponsavel = $modelResponsavel->findAll();

        $resultado = [];

        foreach($dataAtleta as $atleta){
            $atleta['turma'] = array();
            $atleta['responsavel'] = array();

            foreach($dataTurma as $turma){
                if($atleta['fk_turma_id'] == $turma['id']){
                    $atletaTurma['nome'] = $turma['nome'];
                    $atletaTurma['turno'] = $turma['turno'];
                    $atletaTurma['horario_inicial'] = $turma['horario_inicial'];
                    $atletaTurma['horario_termino'] = $turma['horario_termino'];

                    array_push($atleta['turma'],$atletaTurma);
                }
            }

            foreach($dataResponsavel as $responsavel){
                if($atleta['fk_responsavel'] == $responsavel['id']){
                    $atletaRespon['nome'] = $responsavel['nome'];
                    $atletaRespon['cpf'] = $responsavel['cpf'];
                    $atletaRespon['grau_parentesco'] = $responsavel['grau_parentesco'];
                    $atletaRespon['profissao'] = $responsavel['profissao'];
                    $atletaRespon['local_trabalho'] = $responsavel['local_trabalho'];
                    $atletaRespon['telefone'] = $responsavel['telefone'];
                    $atletaRespon['email'] = $responsavel['email'];

                    array_push($atleta['responsavel'],$atletaRespon);
                }
            }
            array_push($resultado,$atleta);
        }

        return $this->respond($resultado);
    }

    //Metodo informações do relatorio PDF Atleta;
    public function atletaPdf(){
        $modelAtleta = new AtletaModel();
        $modelTurma = new TurmaModel();
        $modelResponsavel = new ResponsavelModel();
        $modalFranquia = new FranquiaModel();
        $modalClube = new ClubeModel();
        
        $dataAtleta = $modelAtleta->findAll();
        $dataTurma = $modelTurma->findAll();
        $dataResponsavel = $modelResponsavel->findAll();
        $dataFranquia = $modalFranquia->findAll();
        $dataClube = $modalClube->findAll();

        $resultado = [];

        foreach($dataAtleta as $atleta){
            $atleta['turma'] = array();
            $atleta['responsavel'] = array();
            $atleta['franquia'] = array();
            $atleta['clube'] = array();

            foreach($dataTurma as $turma){
                if($atleta['fk_turma_id'] == $turma['id']){
                    $atletaTurma['nome'] = $turma['nome'];
                    $atletaTurma['turno'] = $turma['turno'];
                    $atletaTurma['horario_inicial'] = $turma['horario_inicial'];
                    $atletaTurma['horario_termino'] = $turma['horario_termino'];
                    $fkFranquia = $turma['fk_franquias_id'];
                    array_push($atleta['turma'],$atletaTurma);
                }
            }

            foreach($dataFranquia as $franquia){
                if($fkFranquia == $franquia['id']){
                    $atletaFranquia['nome'] = $franquia['nome'];
                    $atletaFranquia['cnpj'] = $franquia['cnpj'];
                    $atletaFranquia['rua'] = $franquia['endereco_rua'];
                    $atletaFranquia['numero'] = $franquia['endereco_numero'];
                    $atletaFranquia['bairro'] = $franquia['endereco_bairro'];
                    $atletaFranquia['cep'] = $franquia['endereco_CEP'];
                    $atletaFranquia['estado'] = $franquia['estado'];
                    $atletaFranquia['cidade'] = $franquia['cidade'];
                    $atletaFranquia['telefone'] = $franquia['telefone'];
                    $atletaFranquia['email'] = $franquia['email'];
                    $fkClube = $franquia['fk_clube_futebol_id'];

                    array_push($atleta['franquia'],$atletaFranquia);
                }
            }

            foreach($dataClube as $clube){
                if($fkClube == $clube['id']){
                    $atletaClube['nome'] = $clube['nome'];
                    $atletaClube['cnpj'] = $clube['cnpj'];

                    array_push($atleta['clube'],$atletaClube);
                }
            }

            foreach($dataResponsavel as $responsavel){
                if($atleta['fk_responsavel'] == $responsavel['id']){
                    $atletaRespon['nome'] = $responsavel['nome'];
                    $atletaRespon['cpf'] = $responsavel['cpf'];
                    $atletaRespon['grau_parentesco'] = $responsavel['grau_parentesco'];
                    $atletaRespon['profissao'] = $responsavel['profissao'];
                    $atletaRespon['local_trabalho'] = $responsavel['local_trabalho'];
                    $atletaRespon['telefone'] = $responsavel['telefone'];
                    $atletaRespon['email'] = $responsavel['email'];

                    array_push($atleta['responsavel'],$atletaRespon);
                }
            }
            array_push($resultado,$atleta);
        }

        return $this->respond($resultado);

    }

    //Metodo show que tras um dado especifico pelo id dele Atleta.
    public function show($id = null)
    {
        $model = new AtletaModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if($data){
            return $this->respond($data);
        }

        return $this->failNotFound('Nenhum dado encontrado com id '.$id);
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