<?php

namespace App\Controllers;

use App\Helpers\Validacao;
use App\Models\AtletaModel;
use App\Models\CategoriaModel;
use App\Models\FranquiaModel;
use App\Models\TurmaModel;
use CodeIgniter\RESTful\ResourceController;

class Turma extends ResourceController
{

    public $validacao;

    public function __construct()
    {
        $this->validacao = new Validacao();
    }

    //Metedo Select Turma.
    public function index()
    {
        if ($this->request->getHeader("Authorization")) {
            if ($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())) {

                $model = new TurmaModel();
                $data = $model->findAll();

                return $this->respond($data);
            } else {
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else {
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }

    //Metodo que tras informações relacionadas com uma Turma: 
    //categoria, franquia.
    public function turmaCategoriaFranquia()
    {
        if ($this->request->getHeader("Authorization")) {
            if ($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())) {

                $modelTurma = new TurmaModel();
                $modelCategoria = new CategoriaModel();
                $modelFranquia = new FranquiaModel();

                $dataTurma = $modelTurma->findAll();
                $dataCategoria = $modelCategoria->findAll();
                $dataFranquia = $modelFranquia->findAll();

                $resultado = [];

                foreach ($dataTurma as $turma) {
                    $turma['categoria'] = array();
                    $turma['franquia'] = array();

                    foreach ($dataCategoria as $categoria) {
                        if ($turma['fk_categoria_id'] == $categoria['id']) {
                            $turmaCategoria['nome'] = $categoria['nome'];

                            array_push($turma['categoria'], $turmaCategoria);
                        }
                    }

                    foreach ($dataFranquia as $franquia) {
                        if ($turma['fk_franquias_id'] == $franquia['id']) {
                            $turmaFranquia['nome'] = $franquia['nome'];
                            $turmaFranquia['cnpj'] = $franquia['cnpj'];
                            $turmaFranquia['rua'] = $franquia['endereco_rua'];
                            $turmaFranquia['numero'] = $franquia['endereco_numero'];
                            $turmaFranquia['cep'] = $franquia['endereco_CEP'];
                            $turmaFranquia['estado'] = $franquia['estado'];
                            $turmaFranquia['cidade'] = $franquia['cidade'];
                            $turmaFranquia['telefone'] = $franquia['telefone'];
                            $turmaFranquia['email'] = $franquia['email'];

                            array_push($turma['franquia'], $turmaFranquia);
                        }
                    }
                    array_push($resultado, $turma);
                }

                return $this->respond($resultado);
            } else {
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else {
            $erro = [
                'Erro' => 'Authorization|Token não encontrado'
            ];
            return $this->respond($erro);
        }
    }

    public function turmaPdf()
    {
        if ($this->request->getHeader("Authorization")) {
            if ($this->validacao->validacaoToken($this->request->getHeader("Authorization")->getValue())) {

                $modelTurma = new TurmaModel();
                $modelCategoria = new CategoriaModel();
                $modelFranquia = new FranquiaModel();
                $modelAtleta = new AtletaModel();

                $dataTurma = $modelTurma->findAll();
                $dataCategoria = $modelCategoria->findAll();
                $dataFranquia = $modelFranquia->findAll();
                $dataAtleta = $modelAtleta->findAll();

                $resultado = [];

                foreach ($dataTurma as $turma) {
                    $turma['categoria'] = array();
                    $turma['franquia'] = array();
                    $turma['atleta'] = array();
                    $idTurma = $turma['id'];

                    foreach ($dataCategoria as $categoria) {
                        if ($turma['fk_categoria_id'] == $categoria['id']) {
                            $turmaCategoria['nome'] = $categoria['nome'];

                            array_push($turma['categoria'], $turmaCategoria);
                        }
                    }

                    foreach ($dataFranquia as $franquia) {
                        if ($turma['fk_franquias_id'] == $franquia['id']) {
                            $turmaFranquia['nome'] = $franquia['nome'];
                            $turmaFranquia['cnpj'] = $franquia['cnpj'];
                            $turmaFranquia['rua'] = $franquia['endereco_rua'];
                            $turmaFranquia['numero'] = $franquia['endereco_numero'];
                            $turmaFranquia['cep'] = $franquia['endereco_CEP'];
                            $turmaFranquia['estado'] = $franquia['estado'];
                            $turmaFranquia['cidade'] = $franquia['cidade'];
                            $turmaFranquia['telefone'] = $franquia['telefone'];
                            $turmaFranquia['email'] = $franquia['email'];

                            array_push($turma['franquia'], $turmaFranquia);
                        }
                    }

                    foreach ($dataAtleta as $atleta) {
                        if ($idTurma == $atleta['fk_turma_id']) {
                            $turmaAtleta['nome'] = $atleta['nome'];
                            $turmaAtleta['cpf'] = $atleta['cpf'];
                            $turmaAtleta['dt_nascimento'] = $atleta['dt_nascimento'];
                            $turmaAtleta['rua'] = $atleta['endereco_rua'];
                            $turmaAtleta['numero'] = $atleta['endereco_numero'];
                            $turmaAtleta['bairro'] = $atleta['endereco_bairro'];
                            $turmaAtleta['cep'] = $atleta['endereco_CEP'];
                            $turmaAtleta['naturalidade'] = $atleta['naturalidade'];
                            $turmaAtleta['problema_saude'] = $atleta['problema_saude'];
                            $turmaAtleta['alergia'] = $atleta['alergia'];
                            $turmaAtleta['medicamento'] = $atleta['medicamento'];
                            $turmaAtleta['telefone'] = $atleta['telefone'];
                            $turmaAtleta['email'] = $atleta['email'];

                            array_push($turma['atleta'], $turmaAtleta);
                        }
                    }

                    array_push($resultado, $turma);
                }

                return $this->respond($resultado);

            } else {
                $erro = [
                    'Erro' => 'Token Inválido'
                ];
                return $this->respond($erro);
            }
        } else {
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

                $model = new TurmaModel();
                $data = $this->request->getJSON();

                if ($model->insert($data)) {
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

                $model = new TurmaModel();
                $data = $this->request->getJSON();

                if ($model->update($id, $data)) {
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

                $model = new TurmaModel();
                $data = $model->find($id);

                if ($data) {
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

                return $this->failNotFound('Nenhum dado encontrado com id ' . $id);
    
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
