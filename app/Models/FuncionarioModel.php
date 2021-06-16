<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class FuncionarioModel extends Model
{
    protected $table = 'funcionario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','dt_nascimento', 'cpf', 'rg', 'naturalidade', 'endereco_rua', 'endereco_numero', 'endereco_bairro', 'telefone', 'email'];
    protected $validationRules    = [
        'nome'     => 'required|max_length[45]',
        'dt_nascimento'         => 'valid_date[Y/m/d]',
        'cpf'         => 'required|decimal',
        'rg'         => 'required|decimal', 
        'naturalidade'     => 'required|max_length[45]',
        'endereco_rua'         => 'required|max_length[45]',
        'endereco_numero'         => 'required|max_length[45]',
        'endereco_bairro'         => 'required|max_length[45]', 
        'telefone'     => 'required|decimal',
        'email'         => 'required|max_length[45]'      
    ];
}