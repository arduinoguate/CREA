<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';

	class GCConfig{
		private $server;
		private $db_user;
		private $db_pass;
		private $db_database;

		private $connection;

		protected $country;

		public function __construct(){
			$config = parse_ini_file("config.ini");

			$this->server = $config['server'];
			$this->db_user = $config['db_user'];
			$this->db_pass = $config['db_pass'];
			$this->db_database = $config['database'];

			$this->connection = new DataBase($this->server, $this->db_user, $this->db_pass, $this->db_database);

			$col_con = array('id_pais', 'nombre', 'codigo', 'bandera', 'lang', 'crea_lang');
			$key_con = array('id_pais');
			$this->country = new DBManager($this->connection, 'country', $col_con, $key_con);
		}
	}
?>
