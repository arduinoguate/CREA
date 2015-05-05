<?php
/*********************************************
 * 
 *  Modificaciones:
 * Diego Vásquez -- levantar excepciones al detectar error de mysql -- 23/10/2009
 * Oscar Leche -- Refactoring -- 01/04/2014
 * 
 *********************************************/
 
// metodos gen&#65533;ricos para la base de datos

class DataBase{
	var $host; // Host name 
	var $username; // Mysql username 
	var $password; // Mysql password 
	var $db_name; // Database name 
	var $link;

	// constructor
	function DataBase($_host, $_username, $_password, $_db_name){
		$this->host = $_host;
		$this->username = $_username;
		$this->password = $_password;
		$this->db_name = $_db_name;
		
		// Connect to server and select databse.
		$this->link = mysqli_connect("$_host", "$_username", "$_password", "$_db_name")or die("cannot connect"); 
	}
	
	function Execute($sql){
		$result = mysqli_query($this->link, $sql);
		
		// si existe un error se levanta una excepci&#65533;n
		if($result === FALSE){
			throw new Exception(mysqli_errno($this->link) . ": " . mysqli_error($this->link));
		}
		return $result;
	}
	
	// valida informaci&#65533;n que se adjuntar&#65533; a un SQL para prevenir el SQL Injection
	function CheckSQL($param){
		$param = stripslashes($param);
		$param = mysqli_real_escape_string($this->link, $param);
		return $param;
	}
	
	function BeginTransaction(){
		mysqli_autocommit($this->link, FALSE);
		mysqli_query($this->link, "START TRANSACTION ISOLATION LEVEL SERIALIZATION;");
	}
	
	function Commit(){
		mysqli_commit($this->link);
		mysqli_autocommit($this->link, TRUE);
	}
	
	function Rollback(){
		mysqli_rollback($this->link);
		mysqli_autocommit($this->link, TRUE);
	}
	
	function Close(){
		//mysqli_close($this->link);
	}
}
?>