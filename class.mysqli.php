<?php

/**
 * 14.02.2011 Jonas Bundgaard - MySQLi library
 *
 * rev.2 - 15.02.2012
 * rev.2.5 - 14.09.2019
 * rev 2.6 - 21.05.2024 (PHP 8.1 compatibility)
 *
 * This abstraction is obsolete. Consider using PDO instead.
 */

class mydb extends mysqli
{
	public function prepare($query) {
		return new stmt($this, $query);
	}
}

class stmt extends mysqli_stmt
{
	protected $row;

	public function __construct($link, $query)
	{
		parent::__construct($link, $query);
	}
		
	public function execute(?array $params = null): bool
	{
		$r = parent::execute();
			
		$meta = $this->result_metadata();
			
		$field_name = 'name';
		
		if ($meta) {
			while ($field = $meta->fetch_field()) {
				$params[] = &$this->row[$field->$field_name];
			}
		
			call_user_func_array(array($this, 'bind_result'), $params);
			
			$meta->close();
		}
		
		return $r;
	}
	
	public function execute_no_bind()
	{
		$r = parent::execute();
		
		return $r;
	}
	
	public function bind_results() 
	{
		$meta = $this->result_metadata();
			
		$field_name = 'name';
		
		if ($meta) {
			while ($field = $meta->fetch_field()) {
				$params[] = &$this->row[$field->$field_name];
			}
		
			call_user_func_array(array($this, 'bind_result'), $params);
			
			$meta->close();
		}
	}

	public function fetch()
	{
		$result = array();
		
		if (parent::fetch() != null) {
			foreach($this->row as $key => $val) {
				$c[$key] = $val;
			}

			return $c; 
		}
		else return false;

	}
}

class dbi
{
	private static $mysqli;
	public function __construct(){}
	
	public static function conn()
	{
		if(!isset(self::$mysqli) ) {		
			self::$mysqli = new mydb('localhost', DB_USERNAME, DB_PASSWORD, DB_DATABASE);
			self::$mysqli->set_charset('utf8');
		}
		
		return self::$mysqli;
	}
}
