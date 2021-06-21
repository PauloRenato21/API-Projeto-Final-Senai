<?php namespace App\Models;

use CodeIgniter\Model;

class FranquiaModel extends Model{
    protected $table = 'franquias';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','cnpj','endereco_rua','endereco_bairro','estado','cidade','telefone', 'email', 'fk_clube_futebol_id'];

    protected $validationRules = [
        'nome' => 'required|max_length[100]',
        'cnpj' => 'required|min_length[18]|max_length[18]|is_natural',
        'endereco_rua' => 'required|max_length[50]',
        'endereco_numero' => 'required|max_length[5]',
        'endereco_CEP' => 'required|max_length[10]',
        'estado' => 'required|max_length[50]',
        'cidade' => 'required|max_length[50]',
        'telefone' => 'required|max_length[10]|is_natural',
        'fk_clube_futebol_id' => 'required|is_natural'
    ];
}