<?php namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\CategoriaModel;
use App\Models\AtletaModel;
use App\Models\TurmaModel;
use App\Models\ResponsavelModel;
use App\Models\FranquiaModel;
use App\Models\ClubeModel;

class AtletaSearch extends ResourceController{

    //Metodo que pega o buscador e pesquisa a existência dele no BD;
    public function index()
    {
        $modelAtleta = new AtletaModel();
        $modelResponsavel = new ResponsavelModel();

        $dataResponsavel = $modelResponsavel->where('nome', $this->request->getVar('buscador'))->first();

        if($dataResponsavel){

            return $this->serchAtleta($dataResponsavel);

        } else{
            $dataAtleta = $modelAtleta->where('nome', $this->request->getVar('buscador'))->first();

            if($dataAtleta){

               return $this->serchAtleta($dataAtleta);

            } else{
                $erro = [
                    'Erro' => 'Buscador não encontrado no Banco de Dados'
                ];
    
                return $this->respond($erro);
            }
        }
    }

    //Metodo que realiza a pesquisa e a existência do atleta no BD e a relação do
    //atleta com o buscador;
    public function serchAtleta($data){
        $modelAtleta = new AtletaModel();

        $dataAtleta = $modelAtleta->where('nome', $this->request->getVar('atleta'))->first();

        if(empty($dataAtleta)){
            $erro = [
                'Erro' => 'Atleta não encontrado no Banco de Dados'
            ];

            return $this->respond($erro);

        }else{
            if($data['id'] == $dataAtleta['fk_Responsavel_id']){

                return $this->usuarioSearchAtleta($dataAtleta['id']);
    
            } elseif($data['id'] == $dataAtleta['id']){
    
                return $this->usuarioSearchAtleta($dataAtleta['id']);  
    
            } else{
    
                $erro = [
                    'Erro' => 'Buscador não compativel ou sem relação o atleta pesquisado'
                ];
    
                return $this->respond($erro);
            }
        }
    }

    //Metodo que relaciona as informações do atleta e retorna ela;
    public function usuarioSearchAtleta($id = null){
        $modelAtleta = new AtletaModel();
        $modelTurma = new TurmaModel();
        $modelCategoria = new CategoriaModel();
        $modelResponsavel = new ResponsavelModel();
        $modelFranquia = new FranquiaModel();
        $modelClube = new ClubeModel();

        $dataTurma = $modelTurma->findAll();
        $dataCategoria = $modelCategoria->findAll();
        $dataResponsavel = $modelResponsavel->findAll();
        $dataFranquia = $modelFranquia->findAll();
        $dataClube = $modelClube->findAll();

        $dataAtleta = array();

        $dataAtleta['atleta'] = $modelAtleta->getWhere(['id' => $id])->getResult();
        $dataTurma = $modelTurma->getWhere(['id' => $dataAtleta['atleta'][0]->fk_turma_id])->getResult();
        $dataCategoria = $modelCategoria->getWhere(['id' => $dataTurma[0]->fk_categoria_id])->getResult();
        $dataResponsavel = $modelResponsavel->getWhere(['id' => $dataAtleta['atleta'][0]->fk_Responsavel_id])->getResult();
        $dataFranquia = $modelFranquia->getWhere(['id' => $dataTurma[0]->fk_franquias_id])->getResult();
        $dataClube = $modelClube->getWhere(['id' => $dataFranquia[0]->fk_clube_futebol_id])->getResult();

        $dataAtleta['turma'] = array();
        $dataAtleta['categoria'] = array();
        $dataAtleta['responsavel'] = array();
        $dataAtleta['franquia'] = array();
        $dataAtleta['clube'] = array();
        

        $dataAtleta['turma'] = $dataTurma;
        $dataAtleta['categoria'] = $dataCategoria;
        $dataAtleta['responsavel'] = $dataResponsavel;
        $dataAtleta['franquia'] = $dataFranquia;
        $dataAtleta['clube'] = $dataClube;

        if($dataAtleta){
            return $this->respond($dataAtleta);
        }
    
        return $this->failNotFound('Nenhum dado encontrado com id '.$id);
    }

}

?>