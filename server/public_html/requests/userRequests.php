<?php

include './services/validates.php';

class UserRequests {

  protected $validates;

  public function __construct() {
    $this->validates = new Validates();
  }

  final public function validate($fields) {

    $errors = array();

    if (empty($fields['name']) || $this->validates->isNull($fields['name'])) {
      array_push($errors, 'Por favor preencha seu nome.');
    }

    if (empty($fields['age']) || $this->validates->isNull($fields['age'])) {
      array_push($errors, 'Por favor preencha sua idade.');
    }

    if (empty($fields['email']) || $this->validates->isNull($fields['email'])) {
      array_push($errors, 'Por favor preencha seu e-mail.');
    }
    else {
      if (!$this->validates->isEmail($fields['email'])) {
        array_push($errors, 'Por favor preencha um e-mail válido.');
      }
    }

    if (empty($fields['password']) || $this->validates->isNull($fields['password'])) {
      array_push($errors, 'Por favor preencha sua senha.');
    }

    return $errors;
  }
}

?>
