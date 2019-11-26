<?php

error_reporting(1);

include './services/pdomanager.class.php';

class dataBase {


	final public static function connect() {

    $dbConnect = new PDO_Manager('sql220.main-hosting.eu','u177345686_mentor','u177345686_mentor','mentor!@2019');
    return $dbConnect;
	}
}
