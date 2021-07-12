<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class FuncionarioModel extends Model
{
    protected $table = 'funcionario';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','dt_nascimento', 'cpf', 'rg', 'naturalidade', 'endereco_rua', 'endereco_numero', 'endereco_bairro', 'telefone', 'email','fk_franquias_id','fk_cargo_id'];
    protected $validationRules    = [
        'nome' => 'required|max_length[100]',
        'dt_nascimento' => 'valid_date[Y-m-d]',
        'cpf' => 'required|min_length[14]|max_length[14]',
        'rg' => 'required|decimal|max_length[10]', 
        'naturalidade' => 'required|max_length[50]',
        'endereco_rua' => 'required|max_length[50]',
        'endereco_numero' => 'required|max_length[5]',
        'endereco_bairro' => 'required|max_length[50]', 
        'telefone' => 'required|decimal',
        'email' => 'required|max_length[100]',
        'fk_franquias_id' => 'required|is_natural',
        'fk_cargo_id' => 'required|is_natural'      
    ];
}