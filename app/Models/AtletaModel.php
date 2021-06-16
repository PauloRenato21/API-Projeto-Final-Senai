<?php namespace App\Models;

use CodeIgniter\Model;

class AtletaModel extends Model{
    protected $table = 'atleta';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','cpf','dt_nascimento','endereco_rua','endereco_bairro','endereco_CEP','naturalidade','problema_saude','alergia','medicamento','telefone','email','fk_turma_id','fk_Responsavel_id'];
    
    protected $validationRules = [
        'nome' => 'required',
        'cpf' => 'required|min_length[14]|max_length[14]',
        'dt_nascimento' => 'required|valid_date[Y/m/d]',
        'endereco_rua' => 'required|max_length[50]',
        'endereco_bairro' => 'required|max_length[50]',
        'endereco_CEP' => 'required|max_length[10]',
        'naturalidade' => 'required|max_length[50]',
        'telefone' => 'required|is_natural',
        'fk_turma_id' => 'required|is_natural',
        'fk_Responsavel' => 'required|is_natural'
    ];
}