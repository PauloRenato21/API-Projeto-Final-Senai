<?php namespace Tests\teste_validacao;

use App\Helpers\Validacao;
use PHPUnit\Framework\TestCase;

class ValidacaoTest extends TestCase{

    //Função que valida um token
    public function testVadicaoTokenCorreta(){

        $val = new Validacao();

        $esperado = true;

        $retorno = $val->validacaoToken('');

        return $this->assertEquals($esperado,$retorno);
    }
}