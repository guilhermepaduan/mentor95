<?php

class Helpers {

	public function password($password) {

    return hash('sha256', $password);
  }
}
