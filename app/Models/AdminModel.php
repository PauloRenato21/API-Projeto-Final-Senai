<?php namespace App\Models;

use CodeIgniter\Model;

class AdminModel extends Model{
    protected $table = 'admin';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','user','password'];
    protected $validationRules = [
        'nome' => 'required|max_length[100]',
        'user' => 'required|max_length[100]',
        'password' => 'required|max_length[50]'  
    ];
}