<?php namespace App\Models;
  
use CodeIgniter\Model;
  
class CargoModel extends Model
{
    protected $table = 'cargo';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome'];
    protected $validationRules    = [
        'nome' => 'required|max_length[100]'      
    ];
}