<?php namespace App\Controllers;
 
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use App\Models\ResponsavelModel;
use App\Models\AtletaModel;
use App\Models\TurmaModel;
 
class Responsavel extends ResourceController
{
    use ResponseTrait;
    // lista todos responsaveis
    public function index()
    {
        $model = new ResponsavelModel();
        $data = $model->findAll();
        return $this->respond($data);
    }
 
    // lista um responsavels
    public function show($id = null)
    {
        $model = new ResponsavelModel();
        $data = $model->getWhere(['id' => $id])->getResult();

        if($data){
            return $this->respond($data);
        }
        
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);        
    }
 
    // adiciona um responsavels
    public function create()
    {
        $model = new ResponsavelModel();
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
    
    // atualiza um responsavels
    public function update($id = null)
    {
        $model = new ResponsavelModel();
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
 
    // deleta um responsavels
    public function delete($id = null)
    {
        $model = new ResponsavelModel();
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

    public function responsavelatletaturma(){
        $responsavelModel = new ResponsavelModel();
        $atletaModel = new AtletaModel();
        $turmaModel = new TurmaModel();
        
        $dataresponsavel = $responsavelModel->findAll();
        $dataatleta = $atletaModel->findAll();
        $dataturma = $turmaModel->findAll();
        
        $resultado = [];
        
        foreach ($dataresponsavel as $responsavel){
            $responsavel['atleta'] = array();
            $responsavel['turma'] = array();
        
            foreach ($dataatleta as $at){
                if ($responsavel['id'] == $at['fk_Responsavel_id']){
        
                    $responsavelatleta['id'] = $at['id'];
                    $responsavelatleta['nome'] = $at['nome'];
                    $responsavelatleta['cpf'] = $at['cpf'];
                    $responsavelatleta['dt_nascimento'] = $at['dt_nascimento'];
                    $responsavelatleta['endereco_rua'] = $at['endereco_rua'];
                    $responsavelatleta['endereco_numero'] = $at['endereco_numero'];
                    $responsavelatleta['endereco_bairro'] = $at['endereco_bairro'];
                    $responsavelatleta['endereco_CEP'] = $at['endereco_CEP'];
                    $responsavelatleta['naturalidade'] = $at['naturalidade'];
                    $responsavelatleta['problema_saude'] = $at['problema_saude'];
                    $responsavelatleta['alergia'] = $at['alergia'];
                    $responsavelatleta['medicamento'] = $at['medicamento'];
                    $responsavelatleta['telefone'] = $at['telefone'];
                    $responsavelatleta['email'] = $at['email'];
                    $id_atleta = $at['fk_turma_id'];
                    array_push($responsavel['atleta'], $responsavelatleta);
                }
            }

            
                
            
                foreach ($dataturma as $tur){
                    if ($id_atleta == $tur['id']){
    
                        $atletaturma['id'] = $tur['id'];
                        $atletaturma['nome'] = $tur['nome'];
                        $atletaturma['turno'] = $tur['turno'];
                        $atletaturma['horario_inicial'] = $tur['horario_inicial'];
                        $atletaturma['horario_termino'] = $tur['horario_termino'];
            
                        array_push($responsavel['turma'], $atletaturma);
                    }
                }
                array_push($resultado, $responsavel);
            
            
        }

        return $this->respond($resultado);
    }
 
}