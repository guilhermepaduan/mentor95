<?php


	Class PDO_Manager {

		private $database;
		private $user;
		private $password;
		private $host;
		private $customFields = false;
		private $bindFields = array();
		private $bindWhere = array();
		private $tableUsed  = false;
		private $conditionQuery = array();
		private $conditionProcess = false;
		private $orderbyQuery = array();
		private $orderbyProcess = false;
		private $groupbyQuery = array();
		private $groupbyProcess = false;
		private $limit_query = false;
		private $fields_query = array();
		private $connectPDO;

		/**
		* Connect data base
		**/
		public function __construct($host, $database, $user, $password){
			$this->host = $host;
			$this->database = $database;
			$this->user = $user;
			$this->password = $password;
  			$this->connect();
		}


		/**
		* configure data base
		**/
		private function connect(){
			try{
		        $this->connectPDO = new PDO("mysql:host=".$this->host.";dbname=".$this->database.";charset=latin1", $this->user, $this->password, array( PDO::ATTR_PERSISTENT => TRUE , PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8" ) );
		        $this->connectPDO->setAttribute(
		        									PDO::ATTR_ERRMODE,
		        									PDO::ERRMODE_EXCEPTION
		        								);
		    }catch(PDOException $e){
		        echo $e->getMessage();
		    }
		}


		/**
		* Create alert messages
		* @param string $msgs : error code
		* @return error alert
		**/
	    private function msgs($msgs){
    		$alerts = false;
			switch($msgs){
				case 'not_table': $alerts = "Error: This table does not exist! <br>"; break;
				case 'condition_null': $alerts = "Error: Create a condition! use: ->condition('WHERE id = 1'); <br>"; break;
				case 'table_null': $alerts = "Error: Select a table  use: ->table('tabela'); <br>"; break;
				case 'parameter_null': $alerts = "Error: Enter the parameters  use: ->parameter( array['field']='value'; ); <br>"; break;
			}
			return $alerts;
		}


		/**
		* Treatment of variables and anti sql injection function
		**/
	    public function checkVar($var){
    		$initVar = addslashes($var);
			$initVar = strip_tags($initVar);

    		$badsql  = "from|select|insert|delete|where|truncate table|drop table|show tables|#|*|--";
			$array = explode("|", $badsql);

			foreach ($array as $value) {
				$initVar = str_ireplace($value,"", $initVar);
			}
			return $initVar;
		}


		/**
		* Checks if there are any runtime error on QUERY
		*
		* @param array $error : error returned PDO class
		* @return string : error alert
		*
		**/
		public function errorPDO($error){
			if($error[0]=='00000' && $error[2]==NULL){
				return true;
			}else{
				echo 'Error code: '.$error[0].'<br>';
				echo 'Error message: '.$error[2].'<br>';
			}
		}


		/**
		* Closes the connection with the bank data. Important: use whenever you complete a request
		**/
		public function close(){
			$this->customFields = false;
            $this->bindFields = array();
            $this->bindWhere = array();
            $this->tableUsed  = false;
            $this->conditionQuery = array();
            $this->conditionProcess = false;
            $this->orderbyQuery = array();
            $this->orderbyProcess = false;
            $this->groupbyQuery = array();
            $this->groupbyProcess = false;
            $this->limit_query = false;
		}



		/**
		* Treatment received as parameter array
		*
		* @param array $fields : fields that will be used in the execution of the query
		*
		**/
	    public function parameter($fields = false){
	    	if($fields!=false){
		    	//variveis de uso interno
		    	$return = false;
		    	$fields_index = array();
		    	$count_array = 0;

		    	//captura os indices -> nome dos campos na tabela
				foreach ($fields as $key => $value) {
					$fields_index[] = $key;
				}
				//trata a variavel de saída e deixa pronta para o SQL
				foreach ($fields_index as $value) {
					if($return==false){
						$comma = '';
					}else{
						$comma = ', ';
					}
					$return .= $comma.$value."=:".$value."";
					$this->bindFields[$count_array]['field'] =  $value;
					$this->bindFields[$count_array]['value'] =  $this->checkVar($fields[$value]);
					$count_array++;
				}
				//retorna os campos tratados e pronto para o SQL
				$this->customFields = $return;
			}else{
				$this->customFields = false;
			}
			return $this;
	    }

		/**
		* Sets and checks the selected table exists
		*
		* @param string $table : name table
		*
		**/
	    public function table($table = false){
	    	if($table!=false){
	    		$verify_table = $this->sqlquery("SELECT count(*) FROM ".$table." LIMIT 0,1");
		    	if(!$verify_table){
		    		$this->tableUsed = false;
		    		echo $this->msgs('not_table');
		    	}else{
		    		$this->tableUsed = $table;
		    	}
		    }else{
		    	$this->tableUsed = false;
		    }
		    return $this;
	    }


	    /**
		* Defines a condition for query
		*
		* @param string $field : field that will be compared
		* @param string $operator : comparison operator
		* @param string $value : value that will be compared
		* @param string $where_two : if the second condition use usar OR or AND
		*
		**/
	    public function condition($field, $operator, $value, $where_two = false){
	    	if($field!='' and $operator!=''){
	       		$condition_temp = array($field, $operator, $value, $where_two);
		    	array_push($this->conditionQuery, $condition_temp);
		    }else{
		    	$this->conditionQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all conditions
		**/
	    public function processCondition(){
	    	if($this->conditionQuery!=false){
	    		$condition_ini = "WHERE ";
	    		$count_cont = 0;
	    		$count_array = 0;
	    		foreach ($this->conditionQuery as $value) {
	    			if($value[3]!=false){
	    				$condition_ini .= $value[3].' ';
	    			}
	    			$condition_ini .= $value[0].' ';
	    			$condition_ini .= $value[1].' ';
	    			$condition_ini .= ":".$this->checkVar($value[0].$count_array)." ";

	    			$count_cont++;

	    			$this->bindWhere[$count_array]['field'] = $value[0].$count_array;
	    			$this->bindWhere[$count_array]['value'] = $this->checkVar($value[2]);
	    			$count_array++;
	    		}
	    		$this->conditionProcess = $condition_ini;
	    	}else{
	    		$this->conditionProcess = false;
	    	}
	    }


		/**
		* Defines a sort condition
		*
		* @param string $field : field to be ordained
		* @param string $operator : ordering operator ASC / DESC
		*
		**/
	    public function orderby($field = false, $operator = false){
	    	if($field!=false and $operator!=false){
	       		$order_by_temp = array($field, $operator);
		    	array_push($this->orderbyQuery, $order_by_temp);
		    }else{
		    	$this->orderbyQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all ordinations
		**/
	    public function processOrderby(){
	    	if($this->orderbyQuery!=false){
	    		$ordeby_ini = "ORDER BY ";
	    		$count_cont = 0;
	    		foreach ($this->orderbyQuery as $value) {
	    			if($count_cont>0){
	    				$ordeby_ini .= ', ';
	    			}
	    			$ordeby_ini .= $value[0].' ';
	    			$ordeby_ini .= $value[1].' ';
	    			$count_cont++;
	    		}
	    		$this->orderbyProcess = $ordeby_ini;
	    	}else{
	    		$this->orderbyProcess = false;
	    	}
	    }



	    /**
		* Defines a grouping condition
		*
		* @param string $field : field to be ordained
		*
		**/
	    public function groupby($field = false){
	    	if($field!=false){
	       		$group_by_temp = array($field);
		    	array_push($this->groupbyQuery, $group_by_temp);
		    }else{
		    	$this->groupbyQuery = false;
		    }
		    return $this;
	    }

	    /**
		* Processes all grouping condition
		**/
	    public function processGroupby(){
	    	if($this->groupbyQuery!=false){
	    		$groupby_ini = "GROUP BY ";
	    		$count_cont = 0;
	    		foreach ($this->groupbyQuery as $value) {
	    			if($count_cont>0){
	    				$groupby_ini .= ', ';
	    			}
	    			$groupby_ini .= $value[0].' ';
	    			$count_cont++;
	    		}
	    		$this->groupbyProcess = $groupby_ini;
	    	}else{
	    		$this->groupbyProcess = false;
	    	}
	    }



		/**
		* Defines the limits
		*
		* @param int $ini : initial limit
		* @param int $end : final limit
		*
		**/
	    public function limit($ini = 0, $end = 0){
	    	if($ini>=0 and $end>=0){
	       		$this->limit_query = "LIMIT ".$ini.",".$end;
		    }else{
		    	$this->limit_query = false;
		    }
		    return $this;
	    }


		/**
		* Makes the registration in the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return string : last id insert
		*
		**/
	    public function insert($showQuery = false){
	    	if($this->customFields!=false){
	    		if($this->tableUsed!=false){

	    			$query = "INSERT INTO ".$this->tableUsed." SET ".$this->customFields;

	    			if($showQuery=='echo'){
		    				echo $query;
		    			}

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindFields as $key => $value) {

	    					$sql->bindValue(':'.$value['field'],$value['value']);


	    			}

	    			try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					echo 'Function Class: '.$trace[1]['function'].'<br>';
    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
    				}

	    			$error = $sql->errorInfo();

	    			if($this->errorPDO($error) && $sql) {
	    				//retorna o id cadastrado
	    				return (int)$this->connectPDO->lastInsertId();;
	    			}else{
	    				return false;
	    			}

	    		}else{
	    			echo $this->msgs('table_null');
	    		}
	    	}else{
	    		echo $this->msgs('parameter_null');
	    	}
	    }



	    /**
		* It makes editing the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return boolean : true or false
		*
		**/
	    public function update($showQuery = false){
	    	$this->processCondition();
	    	if($this->customFields!=false){
	    		if($this->tableUsed!=false){
	    			if($this->conditionProcess!=false){

		    			$query = "UPDATE ".$this->tableUsed." SET ".$this->customFields ." ".$this->conditionProcess;

		    			if($showQuery=='echo'){
		    				echo $query;
		    			}

		    			$sql = $this->connectPDO->prepare($query);

		    			foreach ($this->bindWhere as $key => $value) {

	    					$sql->bindValue(':'.$value['field'],$value['value']);

	    				}

		    			foreach ($this->bindFields as $key => $value) {

	    					$sql->bindValue(':'.$value['field'],$value['value']);

	    				}

	    				try {
	    					$sql->execute();
	    				}catch (PDOException $Exception) {
	    					$trace = $Exception->getTrace();
	    					echo 'Function Class: '.$trace[1]['function'].'<br>';
	    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
	    				}

	    				$error = $sql->errorInfo();

		    			if($this->errorPDO($error) && $sql) {
		    				return true;
		    			}else{
		    				return false;
		    			}

		    		}else{
		    			echo $this->msgs('condition_null');
		    		}
	    		}else{
	    			echo $this->msgs('table_null');
	    		}
	    	}else{
	    		echo $this->msgs('parameter_null');
	    	}
	    }




	    /**
		* Delete an item in the database in the selected table with the ->table()
		*
		* @param string $showQuery : echo query create
		* @return boolean : true or false
		*
		**/
	    public function delete($showQuery = false){
    		$this->processCondition();
    		if($this->tableUsed!=false){
    			if($this->conditionProcess!=false){

	    			$query = "DELETE FROM ".$this->tableUsed." ".$this->conditionProcess;

	    			if($showQuery=='echo'){
	    				echo $query;
	    			}

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {

    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

	    			try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					echo 'Function Class: '.$trace[1]['function'].'<br>';
    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
    				}

	    			$error = $sql->errorInfo();

	    			if($this->errorPDO($error) && $sql) {
	    				return true;
	    			}else{
	    				return false;
	    			}


	    		}else{
	    			echo $this->msgs('condition_null');
	    		}
    		}else{
    			echo $this->msgs('table_null');
    		}
	    }



	    /**
		* Select the database in the selected table with the ->table()
		*
		* @param string $fields_select : fields that will be returned
		* @param string $showQuery : echo query create
		* @return object : selected rows
		*
		**/
	    public function ready($fields_select = false, $showQuery = false){
    		$this->processCondition();
    		$this->processOrderby();
    		$this->processGroupby();

    		if($this->tableUsed!=false){

    				if($fields_select==false){
	    				$fields_select = "*";
	    			}

	    			$this->fields_query = array();

	    			$query = "SELECT ".$fields_select." FROM ".$this->tableUsed." ".$this->conditionProcess." ".$this->groupbyProcess." ".$this->orderbyProcess." ".$this->limit_query;

	    			if($showQuery=='echo'){
	    				echo $query;
	    			}

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {

    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				foreach ($this->bindFields as $key => $value) {

    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					echo 'Function Class: '.$trace[1]['function'].'<br>';
    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
    				}

    				$error = $sql->errorInfo();

    				$result_query = $sql->fetchAll(PDO::FETCH_OBJ);

    				if($this->errorPDO($error) && $sql) {
	    			 	return $result_query;
	    			}else{
	    				return false;
	    			}

    		}else{
    			echo $this->msgs('table_null');
    		}
	    }

	    /**
		* QUERY SPECIFICATIONS
		*
		* @param string $query_init : query sql
		* @return object : result of consult
		*
		**/
	    public function sqlquery($query_init){

			$this->fields_query = array();

			try {

    			$sql = $this->connectPDO->query($query_init);

			    return $sql->fetchAll(PDO::FETCH_OBJ);

    		}catch (Exception $e) {

				if($this->connectPDO->errorCode()=='0000'){
					return true;
				}else{
					$this->errorPDO($this->connectPDO->errorInfo());
					return false;
				}
    		}

	    }



	    /**
		* account the outcome of the selection in the database in the selected table
		*
		* @param string $fields_select : fields that will be returned
		* @return int : query count
		*
		**/
	    public function count($fields_select = false){
    		$this->processCondition();
    		$this->processOrderby();

    		if($this->tableUsed!=false){

    				if($fields_select==false){
	    				$fields_select = "*";
	    			}

	    			$this->fields_query = array();

	    			$query = "SELECT ".$fields_select." FROM ".$this->tableUsed." ".$this->conditionProcess." ".$this->orderbyProcess." ".$this->limit_query;

	    			$sql = $this->connectPDO->prepare($query);

	    			foreach ($this->bindWhere as $key => $value) {

    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				foreach ($this->bindFields as $key => $value) {

    					$sql->bindValue(':'.$value['field'],$value['value']);

    				}

    				try {
    					$sql->execute();
    				}catch (PDOException $Exception) {
    					$trace = $Exception->getTrace();
    					echo 'Function Class: '.$trace[1]['function'].'<br>';
    					echo 'Function PDO: '.$trace[0]['function'].'<br>';
    				}


    				$error = $sql->errorInfo();

    				$count_query = $sql->rowCount();


	    			if($this->errorPDO($error) && $sql) {
	    			 	return $count_query;
	    			}else{
	    				return false;
	    			}

    		}else{
    			echo $this->msgs('table_null');
    		}
	    }



	    /**
		* account the results of the query specifies the database
		*
		* @return int : specific query count
		*
		**/
	    public function count_query(){
	    	$count = 0;
	    	foreach ($this->fields_query as $key => $value) {
	    		$count++;
	    	}
	    	return $count;
	    }

	}

?>
