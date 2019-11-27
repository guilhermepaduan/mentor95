<?php

include './controller/userController.php';

$user = new UserController;

print_r($user->insert([
  'name' => 'Bruno Dolenc',
  'age' => 28,
  'email' => 'brdo23423@gmail.com',
  'password' => 'dolenck',
  'mentor' => 'NO',
  'target' => 'NOVO'
]));
