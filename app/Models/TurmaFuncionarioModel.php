<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class TurmaFuncionarioModel extends Model
{
    protected $table = 'turma_funcionario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['fk_turma_id','fk_funcionario_id'];
    
    public function getAllTurmaFuncionario(){
        return $this->join('funcionario', 'turma_funcionario.fk_funcionario_id = funcionario.id')
            ->join('turma', 'turma_funcionario.fk_turma_id = turma.id');
    }
}