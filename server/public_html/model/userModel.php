<?php

include './config/connectDB.php';

class UserModel {

  protected $db;
  protected $pdo;

  public function __construct() {
    $this->pdo = new dataBase();
    $this->db = $this->pdo->connect();
  }

  public function insert($fields) {

    $insert = $this->db->table("mt_user")
                    ->parameter($fields)
                    ->insert();

    $this->db->close();

    return $insert;
  }

  public function getByEmail($email) {

    $get = $this->db->table("mt_user")
                    ->condition("email", "=", $email)
                    ->ready('*');

    $this->db->close();

    return $get;
  }
}
