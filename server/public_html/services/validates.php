<?php

class Validates {

  public function isEmail($email) {

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) return false;

		return true;
  }
  
  public function isNull($field) {

    if ($field == "" || $field == null || $field == " ") return true;

    return false;
  }
}
