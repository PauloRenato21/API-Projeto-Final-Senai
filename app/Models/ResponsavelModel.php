<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class ResponsavelModel extends Model
{
    protected $table = 'responsavel';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','cpf', 'grau_parentesco', 'profissao', 'local_trabalho', 'telefone', 'email'];
    protected $validationRules    = [
        'nome'     => 'required|max_length[45]',
        'cpf'         => 'required|decimal',
        'grau_parentesco'         => 'required|max_length[45]',
        'profissao'         => 'required|max_length[45]', 
        'local_trabalho'     => 'required|max_length[45]',
        'telefone'         => 'required|decimal',
        'email'         => 'required|max_length[45]'      
    ];
}