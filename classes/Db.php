<?php

namespace Application;

class Db
{

	/**
	 * DB PDO Connector
	 *
	 * @var \Phalcon\Db\Adapter\Pdo
	 */
	protected $pdo;

	protected $result;

	protected $fields = array();

	public $debug = false;

	public $pdoError = '';

	public function __construct($module = null)
	{
			if (is_null($module)) $module = APPLICATION_MODULE;
			
			include_once 'config.inc.php';
			
			$shared = defined('CONNECT_SHARED') ? CONNECT_SHARED : true;
			
			$conn = new \Phalcon\Db\Adapter\Pdo\Mysql($conexoes[$module]);
			
			$this->pdo = $conn;
	
	}

	public function query($query, $bindParams = null, $bindTypes = null, $backtrace = 0)
	{

		try {
			
			$this->result = $this->pdo->query($query, $bindParams, $bindTypes);
			
		} catch (\Exception $e) {
			
			$trace = debug_backtrace();
			
			//echo $query;
			//var_dump($trace);
			
			$this->pdoError = "<div>
								<p><b>Erro na query:</b><br>{$query}</p>
								<p><b>Mensagem do PDO:</b><br>" . $e->getMessage() . "</p>
								<p><b>Backtrace:</b><br>{$trace[$backtrace]['file']}({$trace[$backtrace]['line']})</p>
							</div>";
			
			if ($this->debug) {
				
				echo $this->pdoError;
				
				$error = null;
			} else {
				
				$error = $e->getMessage() . "; \n{$trace[$backtrace]['file']}({$trace[$backtrace]['line']});";
				
				//error_log(json_encode($trace));
			}
			
			throw new \Exception($error);
		}
	
	}

	public function insert($table, $values, $replace = false)
	{

		/*
		if ($this->fields) $values = $this->filter($values, $this->fields);
		
		$sql = $this->insertQuery($table, $values, $replace);
		
		return $this->query($sql, null, null, 1);
		*/
		
		$this->insertBind($table, $values, $replace);
	
	}	
	
	public function insertBind($table, $values, $replace = false)
	{
	
		try {
	
			if ($this->fields) $values = $this->filter($values, $this->fields);
	
			$sql = $this->insertBindQuery($table, $values, $replace);
			$queryPrepare = $this->pdo->prepare($sql);
				
			$bindValues = array();
			foreach ($values as $key => $value) $bindValues[':'.$key] = $value;
				
			$queryPrepare->execute($bindValues);
	
		} catch (\Exception $e) {
	
			$trace = debug_backtrace();
			$error = $e->getMessage() . "; \n{$trace[1]['file']}({$trace[1]['line']});";
	
			throw new \Exception($error);
	
		}
	
	}	

	public function insertQuery($table, $values, $replace = false)
	{

		$syntax = $replace ? 'REPLACE' : 'INSERT';
		
		$sql_values = '';
		
		$sql = "{$syntax} INTO {$table} (";
		
		$sql .= implode(', ', array_keys($values));
		$sql .= ") VALUES (";
		
		foreach ($values as $val) {
			if ($sql_values != '') $sql_values .= ', ';
			if (is_null($val))
				$sql_values .= 'NULL ';
			else $sql_values .= "'" . $val . "'";
		}
		$sql .= $sql_values . ');';
		
		return $sql;
	
	}
	
	public function insertBindQuery($table, $values, $replace = false)
	{
	
		$syntax = $replace ? 'REPLACE' : 'INSERT';
	
		$sql = "{$syntax} INTO {$table} (" . implode(', ', array_keys($values)) . ') ';
	
		$sql .= ' VALUES (:' . implode(', :', array_keys($values)) . ');';
	
		return $sql;
	
	}	
	
	function placeholders($text, $count=0, $separator=","){
		$result = array();
		if($count > 0){
			for($x=0; $x<$count; $x++){
				$result[] = $text;
			}
		}
	
		return implode($separator, $result);
	}
	
	function insertMultiple2($table, $datafields, $data){
	
		//$datafields = array('fielda', 'fieldb', ... );
		//$data[] = array('fielda' => 'value', 'fieldb' => 'value' ....);
		//$data[] = array('fielda' => 'value', 'fieldb' => 'value' ....);		
		
		$insert_values = array();
		foreach($data as $d){
			$question_marks[] = '('  . $this->placeholders('?', sizeof($d)) . ')';
			$insert_values = array_merge($insert_values, array_values($d));
		}
	
		$sql = "INSERT INTO $table (" . implode(",", $datafields ) . ") VALUES " . implode(',', $question_marks);
	
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute($insert_values);
		
// 		try {
// 			$stmt->execute($insert_values);
// 		} catch (PDOException $e){
// 			echo $e->getMessage();
// 		}
	
	}
	
	function insertMultiple($tableName, $columnNames, $data){
		
		//Will contain SQL snippets.
		$rowsSQL = array();
		
		//Will contain the values that we need to bind.
		$toBind = array();
		
		//Loop through our $data array.
		foreach($data as $arrayIndex => $row){
			$params = array();
			foreach($row as $columnName => $columnValue){
				$param = ":" . $columnNames[$columnName] . $arrayIndex;
				$params[] = $param;
				$toBind[$param] = $columnValue;
			}
			$rowsSQL[] = "(" . implode(", ", $params) . ")";
		}
		
		//Construct our SQL statement
		$sql = "INSERT INTO `$tableName` (" . implode(", ", $columnNames) . ") VALUES " . implode(", ", $rowsSQL);
		
		//Prepare our PDO statement.
		$pdoStatement = $this->pdo->prepare($sql);
		
		//Bind our values.
		foreach($toBind as $param => $val){
			$pdoStatement->bindValue($param, $val);
		}
		
		//Execute our statement (i.e. insert the data).
		return $pdoStatement->execute();		
		
	}

	public function delete($table, $conditions)
	{

		if ($this->fields) $values = $this->filter($values, $this->fields);
		
		$condition = $this->conditions($conditions);
		
		$sql = "DELETE FROM {$table} WHERE $condition";
		
		$this->query($sql, null, null, 1);
		
		return $this->affectedRows();
	
	}

	public function update($table, $values, $conditions)
	{

// 		if ($this->fields) $values = $this->filter($values, $this->fields);
		
// 		$sql = $this->updateQuery($table, $values, $conditions);
		
// 		$this->query($sql, null, null, 1);
		
// 		return $this->affectedRows();

		$this->updateBind($table, $values, $conditions);
	
	}
	
	public function updateBind($table, $values, $conditions)
	{
	
		try {
	
			if ($this->fields) $values = $this->filter($values, $this->fields);
	
			$sql = $this->updateBindQuery($table, $values, $conditions);
			$queryPrepare = $this->pdo->prepare($sql);
				
			$bindValues = array();
			foreach ($values as $key => $value) $bindValues[':'.$key] = $value;
				
			$queryPrepare->execute($bindValues);
	
		} catch (\Exception $e) {
	
			$trace = debug_backtrace();
			$error = $e->getMessage() . "; \n{$trace[1]['file']}({$trace[1]['line']});";
	
			throw new \Exception($error);
	
		}
	
	}	

	public function updateQuery($table, $values, $conditions)
	{
		

		$condition = $this->conditions($conditions);
		
		$update = '';
		foreach ($values as $field => $value) {
			if ($update) $update .= ', ';
			if (! is_null($value)) {
				$update .= $field . " = '" . $value . "' ";
			} else {
				$update .= $field . " = null ";
			}
		}
		$sql = "UPDATE $table SET $update WHERE $condition";
		
		return $sql;
	
	}
	
	public function updateBindQuery($table, $values, $conditions)
	{
	
		$condition = $this->conditions($conditions);
		
		$update = '';
		foreach (array_keys($values) as $field) {
			if ($update) $update .= ', ';
			$update .= "{$field}=:$field "; 
			
		}
		
		$sql = "UPDATE $table SET $update WHERE $condition";
	
		return $sql;
	
	}

	public function fetch($query = null, $bindParams = null, $bindTypes = null)
	{

		if ($query) $this->query($query, $bindParams, $bindTypes, 1);
		
		return $this->result ? $this->result->fetch(\PDO::FETCH_OBJ) : array();
	
	}

	public function fetchArray($query = null, $bindParams = null, $bindTypes = null)
	{

		if ($query) $this->query($query, $bindParams, $bindTypes, 1);
		
		$result = $this->result->fetchArray();
		
		return $result ? (array) $result : array();
	
	}

	public function fetchAll($query = null, $bindParams = null, $bindTypes = null)
	{

		if ($query) $this->query($query, $bindParams, $bindTypes, 1);
		
		return $this->result ? $this->result->fetchAll(\PDO::FETCH_OBJ) : array();
	
	}

	public function fetchAllArray($query = null, $bindParams = null, $bindTypes = null)
	{

		if ($query) $this->query($query, $bindParams, $bindTypes, 1);
		
		return $this->result ? $this->result->fetchAll(\PDO::FETCH_ASSOC) : array();
	
	}

	public function fetchAllPair($query = null, $prefix = null)
	{

		if ($query) $this->query($query, null, null, 1);
		
		$records = array();
		
		if ($this->result) {
			
			while ($record = $this->result->fetch(\PDO::FETCH_NUM)) {
				
				$record = array_values( (array) $record);
				$index = $record[0];
				
				if ($prefix) $index = $prefix . $index;
				
				$records[$index] = $record[1];
			}
		}
		
		return $records;
	
	}

	public function numRows()
	{

		return $this->result ? $this->result->numRows() : null;
	
	}

	public function affectedRows()
	{

		return $this->pdo->affectedRows();
	
	}

	public function lastInsertId()
	{

		return $this->pdo->lastInsertId();
	
	}

	public function beginTransaction()
	{

		$result = $this->pdo->begin();
		
		register_shutdown_function(array(
			$this,
			"__shutdown_check"
		));
	
	}

	public function __shutdown_check()
	{

		if ($this->inTransaction()) {
			
			$this->rollback();
		}
	
	}

	public function inTransaction()
	{

		return $this->pdo->isUnderTransaction();
	
	}

	public function commit()
	{

		return $this->pdo->commit();
	
	}

	public function rollback()
	{

		return $this->pdo->rollback();
	
	}

	public static function filter($vars, $filter = null)
	{

		if (is_array($vars))
			foreach ($vars as $key => $value) {
				if (is_array($value)) {
					unset($vars[$key]);
					continue;
				}
				if (! is_null($value)) {
					//$vars[$key] = $this->escape($vars[$key]);
				}
				if (is_array($filter)) {
					if (! in_array($key, $filter)) {
						unset($vars[$key]);
					}
				}
			}
		else {
			if (! is_null($vars)) {
				$vars = $this->escape($vars);
			}
		}
		return $vars;
	
	}

	public function escape($string)
	{

		return $this->pdo->escapeString($string);
	
	}
	
	protected function conditions($conditions)
	{
		
		if (is_array($conditions)) {

			$condition = '';
			
			foreach ($conditions as $field => $value) {
				if ($condition) $condition .= 'AND ';
				if (! is_null($value)) {
					$condition .= $field . " = '" . $value . "' ";
				} else {
					$condition .= $field . " is null ";
				}
			}
		} else
			$condition = (string) $conditions;
		
		return $condition;
	
	}
	
	public function prepareExecute($query, $bindParams = array(), $bindTypes = array(), $backtrace = 0)
	{
	
		try {
				
			if (!$bindParams) $bindParams = array();
			if (!$bindTypes) $bindTypes = array();
				
			foreach ($bindParams as $k => $v) if (!isset($bindTypes[$k]))  $bindTypes[$k] =
	
			$statement = $this->pdo->prepare($query);
			$this->result = $this->pdo->executePrepared($statement, $bindParams, $bindTypes);
	
		} catch (\Exception $e) {
	
			$trace = debug_backtrace();
	
			//echo $query;
			//var_dump($trace);
	
			$this->pdoError = "<div>
			<p><b>Erro na query:</b><br>{$query}</p>
			<p><b>Mensagem do PDO:</b><br>" . $e->getMessage() . "</p>
			<p><b>Backtrace:</b><br>{$trace[$backtrace]['file']}({$trace[$backtrace]['line']})</p>
			</div>";
	
			if (true || $this->debug) {
	
			echo $this->pdoError;
	
			$error = null;
			} else {
	
			$error = $e->getMessage() . "; \n{$trace[$backtrace]['file']}({$trace[$backtrace]['line']});";
	
			//error_log(json_encode($trace));
			}
	
			throw new \Exception($error);
		}
	
	}
	
	public function close() {

	$this->pdo->close();

	}

	public function getConnecion() {

		return $this->pdo;		
		
	}
}