<?php

class DataBaseManager
{
	public $db;
	
	public function __construct($connection){
		//$this->db = new DataBase($server, $user, $password, $database);
		$this->db = $connection;
	}
	
	public function __destruct(){
		$this->db->Close();
	}
	
    public function BeginTransaction(){
    	$this->db->BeginTransaction();
    }
    
    public function Commit(){
		$this->db->Commit();
    }
    
    public function RollBack(){
       	$this->db->RollBack();
    }
	
	function GetType($var)
    {
        if (is_array($var)) return "array";
        if (is_bool($var)) return "boolean";
        if (is_float($var)) return "float";
        if (is_int($var)) return "integer";
        if (is_null($var)) return "NULL";
        if (is_numeric($var)) return "numeric";
        if (is_object($var)) return "object";
        if (is_resource($var)) return "resource";
        if (is_string($var)) return "string";
        return "unknown";
    }
}
?>
