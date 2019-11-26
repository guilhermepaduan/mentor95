<?php

include './model/userModel.php';
include './services/helpers.php';
include './requests/userRequests.php';

class UserController {

  protected $user;
  protected $helpers;
  protected $requests;

  public function __construct() {
    $this->user = new UserModel();
    $this->helpers = new Helpers();
    $this->userRequests = new UserRequests();
  }

  public function insert($fields) {

    $validate = $this->userRequests->validate($fields);

    if (count($validate) > 0) return ['success' => false, 'messages' => $validate];

    $get = $this->user->getByEmail($fields['email']);

    if (count($get) > 0) return ['success' => false, 'messages' => ['Esse e-mail já está cadastrado.']];

    $fields['password'] = $this->helpers->password($fields['password']);
    $fields['created_at'] = date("Y-m-d H:i:s");

    $saveUser = $this->user->insert($fields);

    if ($saveUser) {
      return ['success' => true, 'messages' => ['Usuário cadastrado com sucesso.'], 'user_id' => $saveUser];
    }
    else {

      print_r($saveUser);
      return ['success' => false, 'messages' => ['Não foi possível cadastrar o usuário.']];
    }
  }
}

?>
