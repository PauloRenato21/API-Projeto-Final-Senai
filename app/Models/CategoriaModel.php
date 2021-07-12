<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class CategoriaModel extends Model
{
    protected $table = 'categoria';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome'];
    protected $validationRules    = [
        'nome' => 'required|max_length[50]' 
    ];
}