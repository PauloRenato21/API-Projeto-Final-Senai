<?php namespace App\Models;

use CodeIgniter\Model;

class ClubeModel extends Model{

    protected $table = 'clube_futebol';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','cnpj'];
    protected $validationRules = [
        'nome' => 'required|max_length[100]',
        'cnpj' => 'required|min_length[14]|max_length[14]'
    ];
}