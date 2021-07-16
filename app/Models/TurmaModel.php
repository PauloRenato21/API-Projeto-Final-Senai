<?php namespace App\Models;

use CodeIgniter\Model;

class TurmaModel extends Model{

    protected $table = 'turma';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nome','turno','horario_inicial','horario_termino','fk_categoria_id','fk_franquias_id'];

    protected $validationRules = [
        'nome' => 'required|max_length[50]',
        'turno' => 'required|max_length[50]',
        'horario_inicial' => 'required',
        'horario_termino' => 'required',
        'fk_categoria_id' => 'required',
        'fk_franquias_id' => 'required',
    ];
}