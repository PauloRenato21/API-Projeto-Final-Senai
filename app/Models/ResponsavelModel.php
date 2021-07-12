<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class ResponsavelModel extends Model
{
    protected $table = 'responsavel';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','cpf', 'grau_parentesco', 'profissao', 'local_trabalho', 'telefone', 'email'];
    protected $validationRules    = [
        'nome' => 'required|max_length[100]',
        'cpf' => 'required|min_length[14]|max_length[14]',
        'grau_parentesco' => 'required|max_length[100]',
        'profissao' => 'required|max_length[50]', 
        'local_trabalho' => 'required|max_length[100]',
        'telefone' => 'required|decimal|max_length[50]',
        'email' => 'required|max_length[100]'      
    ];
}