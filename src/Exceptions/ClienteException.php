<?php

namespace LKnijnik\Asaas\Exceptions;

class ClienteException
{

  public static function invalidClient()
  {
    return array('error' => 'Os dados obrigatórios são Nome e Cpf\Cnpj. Os dados fornecidos para o cadastro do cliente não são válidos.');
  }
}
