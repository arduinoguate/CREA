<?php
	include 'DB/DBManager.php';
	include 'DB/DataBase.class.php';

	class GCConfig{
		private $server;
		private $db_user;
		private $db_pass;
		private $db_database;

    protected $ipp;
		protected $fbappid;
		protected $fbsecret;

		protected $action;

		private $connection;

    //controller vars
    public $pagination_link = "";
    public $page;
    public $per_page;

        //API vars
		protected $scopes;
		protected $user;
		protected $api_client;
		protected $api_client_scopes;
		protected $api_token;
    protected $api_form;
  	protected $api_field_type;
		protected $api_user_asoc;

    //user vars

    //modulo vars
    protected $modulo;
		protected $tipo_modulo;
		protected $modulo_asoc;

		//accion vars
		protected $actions;
		protected $tipo_action;
		protected $tipo_respuesta;

		//response information
		public $err;
		public $response;

    public function __construct($request){

			$config = parse_ini_file("config.ini");

			$this->server = $config['server'];
			$this->db_user = $config['db_user'];
			$this->db_pass = $config['db_pass'];
			$this->db_database = $config['database'];

	    $this->app_secret = $config['app_secret'];

			$this->fbappid = $config['fbapp'];
			$this->fbsecret = $config['fbsecret'];
	    $this->ipp = $config['ipp'];

			$this->connection = new DataBase($this->server, $this->db_user, $this->db_pass, $this->db_database);

			$col_asset_type = array('id', 'name', 'format', 'max_size', 'max_dimensions', 'mime', 'type');
			$key_asset_type = array('id');
	    $this->asset_type = new DBManager($this->connection, 'asset_type', $col_asset_type, $key_asset_type);

	    $col_ftype = array('id', 'name', 'regex');
	  	$key_ftype = array('id');
			$this->api_field_type = new DBManager($this->connection, 'api_field_type', $col_ftype, $key_ftype);

	    $col_aform = array('id', 'endpoint', 'field', 'id_type', 'sample', 'internal', 'required', 'scopes');
			$key_aform = array('id');
			$foreign_aform = array('id_type' => array('api_field_type','id', $this->api_field_type));
			$this->api_form = new DBManager($this->connection, 'api_form', $col_aform, $key_aform, $foreign_aform);

			$col_scopes = array('name', 'level', 'priority');
			$key_scopes = array('name');
			$this->scopes = new DBManager($this->connection, 'api_scopes', $col_scopes, $key_scopes);

			$col_usuario = array('idusuario', 'nombre', 'apellido', 'email', 'fb_account', 'path_avatar', 'password', 'enabled', 'created_at', 'updated_at');
			$key_usuario = array('idusuario');
			$this->user = new DBManager($this->connection, 'usuario', $col_usuario, $key_usuario);

			$col_api = array('client_id', 'client_secret', 'email', 'user_id', 'created_at', 'updated_at', 'enabled', 'asoc');
			$key_api = array('client_id');
			$foreign_api = array('user_id' => array('usuario','idusuario', $this->user));
			$this->api_client = new DBManager($this->connection, 'api_users', $col_api, $key_api, $foreign_api);

			$col_api_scopes = array('id_scope', 'id_client');
			$key_api_scopes = array('id_scope', 'id_client');
			$foreign_api_scopes = array('id_client' => array('api_users','client_id', $this->api_client), 'id_scope' => array('api_scopes','name', $this->scopes));
			$this->api_client_scopes = new DBManager($this->connection, 'api_scope_users', $col_api_scopes, $key_api_scopes, $foreign_api_scopes);

			$col_token = array('id', 'token', 'created_at', 'expires', 'enabled', 'client_id', 'updated_at', 'scopes', 'timestamp');
			$key_token = array('id');
			$foreign_token = array('client_id' => array('api_users','client_id', $this->api_client));
			$this->api_token = new DBManager($this->connection, 'api_tokens', $col_token, $key_token, $foreign_token);

			$col_token = array('client_id', 'id_usuario');
			$key_token = array('client_id', 'id_usuario');
			$foreign_token = array('client_id' => array('api_users','client_id', $this->api_client),
				'id_usuario' => array('usuario','idusuario', $this->user));
			$this->api_user_asoc = new DBManager($this->connection, 'api_user_asoc', $col_token, $key_token, $foreign_token);

			//Types will be loaded always
			$col_tmod = array('idtipo_modulo', 'nombre', 'base_name', 'url_libreria', 'url_doc', 'url_img', 'descripcion');
			$key_tmod = array('idtipo_modulo');
			$this->tipo_modulo = new DBManager($this->connection, 'tipo_modulo', $col_tmod, $key_tmod);

			$col_tact = array('idtipo_action', 'nombre', 'comando', 'default_value', 'id_type', 'read_only', 'options');
			$key_tact = array('idtipo_action');
			$foreign_tact = array('id_type' => array('api_field_type','id', $this->api_field_type));
			$this->tipo_action = new DBManager($this->connection, 'tipo_action', $col_tact, $key_tact, $foreign_tact);

			$col_tres = array('id', 'nombre', 'detalle', 'enabled', 'created_at');
			$key_tres = array('id');
			$this->tipo_respuesta = new DBManager($this->connection, 'tipo_respuesta', $col_tres, $key_tres);


			switch ($request) {
				case 'module':
					$col_mod = array('id', 'nombre', 'tipo_modulo', 'estado', 'last_response', 'created_at', 'updated_at', 'enabled');
					$key_mod = array('id');
					$foreign_mod = array('tipo_modulo' => array('tipo_modulo','idtipo_modulo', $this->tipo_modulo));
					$this->modulo = new DBManager($this->connection, 'modulo', $col_mod, $key_mod, $foreign_mod);

					$col_ma = array('idusuario', 'modulo_id');
					$key_ma= array('idusuario', 'modulo_id');
					$foreign_ma = array('idusuario' => array('usuario','idusuario', $this->user),
						'modulo_id' => array('modulo','id', $this->modulo));
					$this->modulo_asoc = new DBManager($this->connection, 'modulo_asoc', $col_ma, $key_ma, $foreign_ma);
          break;

				case 'action':
					$col_mod = array('id', 'nombre', 'tipo_modulo', 'estado', 'last_response', 'created_at', 'updated_at', 'enabled');
					$key_mod = array('id');
					$foreign_mod = array('tipo_modulo' => array('tipo_modulo','idtipo_modulo', $this->tipo_modulo));
					$this->modulo = new DBManager($this->connection, 'modulo', $col_mod, $key_mod, $foreign_mod);

          $col_ma = array('idusuario', 'modulo_id');
  				$key_ma= array('idusuario', 'modulo_id');
					$foreign_ma = array('idusuario' => array('usuario','idusuario', $this->user),
						'modulo_id' => array('modulo','id', $this->modulo));
					$this->modulo_asoc = new DBManager($this->connection, 'modulo_asoc', $col_ma, $key_ma, $foreign_ma);

					$col_act = array('id', 'nombre', 'tipo_accion', 'comando', 'ultimo_valor', 'input', 'modulo_id', 'enabled', 'created_at', 'updated_at', 'tipo_respuesta');
					$key_act= array('id');
					$foreign_act = array('tipo_accion' => array('tipo_action','idtipo_action', $this->tipo_action),
						'modulo_id' => array('modulo','id', $this->modulo),
						'tipo_respuesta' => array('tipo_respuesta','id', $this->tipo_respuesta));
					$this->actions = new DBManager($this->connection, 'actions', $col_act, $key_act, $foreign_act);
					break;
                default:
					break;
			}

		}

		//Private Methods
		private function filter_gets($ignored = array()){
			$query = "";
			$count = 0;
			foreach ($_GET as $key => $value) {
				if (!in_array($key, $ignored)){
					$query .= $key.'='.$value;
				}
				if ($count > 0){
					$query .= "&";
				}
				$count++;
			}

			return $query;
		}

		//Protected methods
        protected function paginate($class){
            if ($class->pages > 1){
                $this->pagination_link = "Link: ";
				if (($this->page+1) > 1){
					$this->pagination_link .= "<".$_SERVER['SCRIPT_URI']."?page=".$this->page;
					$rest = $this->filter_gets(array('page','request'));
					if ($rest != "")
						$this->pagination_link .= "&$rest";
					$this->pagination_link .= '>; rel="prev",';
				}
				if (($this->page+1) < $class->pages){
					$this->pagination_link .= "<".$_SERVER['SCRIPT_URI']."?page=".($this->page+2);
					$rest = $this->filter_gets(array('page','request'));
					if ($rest != "")
						$this->pagination_link .= "&$rest";
					$this->pagination_link .= '>; rel="next",';
				}
				if (($this->page+1) <= $class->pages){
					$this->pagination_link .= "<".$_SERVER['SCRIPT_URI']."?page=1";
					$rest = $this->filter_gets(array('page','request'));
					if ($rest != "")
						$this->pagination_link .= "&$rest";
					$this->pagination_link .= '>; rel="first",';
				}
				if (($this->page+1) >= 1){
					$this->pagination_link .= "<".$_SERVER['SCRIPT_URI']."?page=".$class->pages;
					$rest = $this->filter_gets(array('page','request'));
					if ($rest != "")
						$this->pagination_link .= "&$rest";
					$this->pagination_link .= '>; rel="last",';
				}
        	}
        }

        protected function set_pagination(&$class, $params){
            $this->per_page = (isset($params['per_page']) && trim($params['per_page']) != '')?$params['per_page']:$this->ipp;
        	$this->page = (isset($params['page']) && trim($params['page']) != '')?$params['page']:1;
    		if ($this->page <= 0)
    			$this->page = 0;
    		else {
    			$this->page -= 1;
    		}
    		$class->set_ipp($this->per_page);
        }

		protected function validate_fields($fields, $endpoint){
			$available = array();
			$rvalue = true;
			$this->response['message'] = array();
			if (is_array($fields)){
				foreach ($fields as $k => $field) {
					$available[] = $k;
					if (trim($field) == ''){
						$rvalue = false;
						$message = array();
						$message['field'] = $k;
						$message['message'] = 'Is empty';
						$this->response['message'][] = $message;
						$this->response['code'] = 2;
						$this->response['http_code'] = 422;
					}
				}
			}
			$q_list = $this->api_form->fetch(" endpoint LIKE '$endpoint' ");
			if (count($q_list) > 0){
	            $i = 0;
				foreach ($q_list as $q_item) {
					$i++;
					if (in_array($q_item->columns['field'], $available)){
						//regex validation
						if ( !preg_match( $q_item->columns['id_type']['regex'], $fields[$q_item->columns['field']] ) ) {
							$rvalue = false;
							$message = array();
							$message['field'] = $q_item->columns['field'];
							$message['message'] = 'Do not match validation type: '.$q_item->columns['id_type']['name'];
							$message['format'] = $q_item->columns['id_type']['regex'];
							$this->response['message'][] = $message;
							$this->response['code'] = 2;
							$this->response['http_code'] = 422;
						}
					}else{
						if ($q_item->columns['required']){
							$rvalue = false;
							$message = array();
							$message['field'] = $q_item->columns['field'];
							$message['message'] = 'Is required';
							$this->response['message'][] = $message;
							$this->response['code'] = 2;
							$this->response['http_code'] = 422;
						}
					}
				}
			}else{
				$this->response['request'] = $_POST;
				$this->response['message'] = 'Fields definition error';
				$this->response['code'] = 2;
				$this->response['http_code'] = 500;
				return false;
			}
			return $rvalue;
		}
	}

?>
