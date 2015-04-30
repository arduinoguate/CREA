<?php

include_once 'config/config.php';

class ACTION extends GCConfig {
	public $pagination_link = "";
	private static $private_fields = array('id', 'endpoint', 'sample', 'scopes', 'internal');

	public function __construct($request) {
		parent::__construct($request);
		$this->response = array();
		$this->action = 'action';
	}

	//Public Methods
	public function form($action = '') {
		if ($action == '')
			$action = $this->action;
		$q_list = $this->api_form->fetch(" endpoint LIKE '$action' ");
		if (count($q_list) > 0) {
			$this->response['code'] = 0;
			$this->response['fields'] = array();

			$i = 0;
			foreach ($q_list as $q_item) {
				$this->response['fields'][$i] = array();
				foreach ($q_item->columns as $k => $colval) {
					if (!in_array($k, $this::$private_fields)) {
						$this->response['fields'][$i][$k] = $q_item->columns[$k];
					}
				}
				$i++;
			}
			$this->response['http_code'] = 200;
		} else {
			$this->response['type'] = 'error';
			$this->response['request'] = $_POST;
			$this->response['message'] = 'Fields definition error';
			$this->response['code'] = 2;
			$this->response['http_code'] = 500;
		}
	}

	public function show($eid = null, $params = array(), $token, $aid) {
		$verified = " enabled is TRUE ";

		if (isset($params['show'])) {
			$opts = explode(',', $params['show']);
			if (in_array('disabled', $opts))
				$verified = " enabled is FALSE ";
		}

		$q_list = array();

		if (is_null($eid)) {
			$q_list = $this->actions->fetch("$verified");
		} else {
			$q_list = $this->actions->fetch("modulo_id = '$eid' AND $verified");
			/*if ($this->actions->fetch_id(array('id' => $eid), null, true, "$verified"))
				$q_list[] = $this->action;*/
		}

		if (count($q_list) > 0) {
			$this->response['code'] = 0;
			$this->response['acciones'] = array();
			foreach ($q_list as $q_item) {
				$this->response['acciones'][] = $q_item->columns;
			}
			$this->response['http_code'] = 200;
		} else {
			$this->response['type'] = 'error';
			$this->response['message'] = 'Cannot retrieve data';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}
	}

	public function show_actions($id, $params = array(), $token) {
		if ($this->validate_module_id($id, $token)) {
			$q_list = $this->actions->fetch("modulo_id = '$id'");

			if (count($q_list) > 0) {
				$this->response['code'] = 0;
				$this->response['acciones'] = array();
				foreach ($q_list as $q_item) {
					$this->response['acciones'][] = $q_item->columns;
				}
				$this->response['http_code'] = 200;
			} else {
				$this->response['type'] = 'error';
				$this->response['message'] = 'Cannot retrieve data';
				$this->response['code'] = 2;
				$this->response['http_code'] = 422;
			}
		}
	}
	
	public function show_action_type() {
		$q_list = $this->tipo_action->fetch();

		if (count($q_list) > 0) {
			$this->response['code'] = 0;
			$this->response['tipos'] = array();
			foreach ($q_list as $q_item) {
				$this->response['tipos'][] = $q_item->columns;
			}
			$this->response['http_code'] = 200;
		} else {
			$this->response['type'] = 'error';
			$this->response['message'] = 'Cannot retrieve data';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}
	}
	
	public function show_module_type() {
		$q_list = $this->tipo_modulo->fetch();

		if (count($q_list) > 0) {
			$this->response['code'] = 0;
			$this->response['modulos'] = array();
			foreach ($q_list as $q_item) {
				$this->response['modulos'][] = $q_item->columns;
			}
			$this->response['http_code'] = 200;
		} else {
			$this->response['type'] = 'error';
			$this->response['message'] = 'Cannot retrieve data';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}
	}

	public function delete($id = null, $params = array()) {
		if ($this->validate_fields($params, 'action/delete')) {
			if ($this->validate_action($params['action'], $id)) {
				if ($this->actions->fetch_id(array("id" => $params['action']))) {
					if (!$this->actions->delete()) {
						$this->response['type'] = 'error';
						$this->response['title'] = 'Delete Error';
						$this->response['message'] = 'No se pudo eliminar la data';
						$this->response['code'] = 1;
						$this->response['http_code'] = 422;
					} else {
						$this->response['message'] = 'Deleted';
						$this->response['http_code'] = 203;
						$this->response['code'] = 0;
					}
				} else {
					$this->response['type'] = 'error';
					$this->response['message'] = 'Cannot retrieve data';
					$this->response['code'] = 2;
					$this->response['http_code'] = 422;
				}
			}
		}
	}

	public function create($id, $params = array(), $token) {
		if ($this->validate_fields($params, 'module/:code/register-action')) {
			if ($this->validate_module_id($id, $token)) {
				if ($this->validate_action_name($params['nombre'], $id)) {
					$this->response['type'] = 'error';
					$this->response['title'] = 'Accion';
					$this->response['message'] = 'Ya existe una acción con ese nombre';
					$this->response['code'] = 2;
					$this->response['http_code'] = 422;
				}else{
					$this->response = array();
					$this->actions->columns['id'] = null;
					$this->actions->columns['nombre'] = $params['nombre'];
					$this->actions->columns['tipo_accion'] = $params['tipo-accion'];
					$this->actions->columns['comando'] = $params['comando'];
					$this->actions->columns['ultimo_valor'] = "";
					$this->actions->columns['input'] = $params['input'];
					$this->actions->columns['modulo_id'] = $id;
					$this->actions->columns['enabled'] = 1;
					$this->actions->columns['created_at'] = date("Y-m-d H:i:s");
					$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");
	
					$id = $this->actions->insert();
					if (is_int($id)) {
						if ($this->actions->fetch_id(array('id' => $id))) {
							$this->response['acciones'] = $this->actions->columns;
							$this->response['http_code'] = 200;
							$this->response['code'] = 0;
						} else {
							$this->response['type'] = 'error';
							$this->response['title'] = 'Mostrar acciones';
							$this->response['message'] = 'Se ha producido el siguiente error: ' . $this->modulo->err_data;
							$this->response['code'] = 1;
							$this->response['http_code'] = 500;
						}
					} else {
						$this->response['type'] = 'error';
						$this->response['title'] = 'Crear acciones';
						$this->response['message'] = 'Se ha producido el siguiente error: ' . $this->modulo->err_data;
						$this->response['code'] = 1;
						$this->response['http_code'] = 422;
					}
				}
			}
		}
	}

	public function update($id, $params = array(), $token, $get_params) {
		if ($this->validate_fields($params, 'action/:id/update')) {
			if ($this->validate_action($id, $params['modulo'])) {
				if ($this->actions->fetch_id(array("id" => $id))) {
					$this->actions->columns['nombre'] = (isset($params['nombre'])) ? $params['nombre'] : $this->actions->columns['nombre'];
					$this->actions->columns['tipo_accion'] = (isset($params['tipo-accion'])) ? $params['tipo-accion'] : $this->actions->columns['tipo_accion']['idtipo_action'];
					$this->actions->columns['comando'] = (isset($params['comando'])) ? $params['comando'] : $this->actions->columns['comando'];
					$this->actions->columns['input'] = (isset($params['input'])) ? $params['input'] : $this->actions->columns['input'];
					$this->actions->columns['modulo_id'] = $this->actions->columns['modulo_id']['id'];
					$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");

					if (!$this->actions->update()) {
						$this->response['type'] = 'error';
						$this->response['title'] = 'Update Error';
						$this->response['message'] = 'No se pudo actualizar la data';
						$this->response['code'] = $this->actions->err_data;
						$this->response['http_code'] = 422;
					} else {
						if ($this->actions->fetch_id(array('id' => $id))) {
							$this->response['entidad'] = $this->actions->columns;
							$this->response['http_code'] = 202;
							$this->response['code'] = 0;
						} else {
							$this->response['type'] = 'error';
							$this->response['title'] = 'Mostrar entidad';
							$this->response['message'] = 'Se ha producido el siguiente error: ' . $this->actions->err_data;
							$this->response['code'] = 1;
							$this->response['http_code'] = 500;
						}
					}
				}
			}
		}
	}

	public function execute($id, $params = array(), $token) {
		if ($this->validate_module_id($id, $token)) {
			if ($this->validate_fields($params, 'module/:id/execute-action')) {
				if ($this->validate_action($params['action'], $id)){
					if ($this->actions->fetch_id(array("id" => $params['action']))) {
						$this->actions->columns['ultimo_valor'] = $params['value'];
						$this->actions->columns['modulo_id'] = $this->actions->columns['modulo_id']['id'];
						$this->actions->columns['tipo_accion'] = $this->actions->columns['tipo_accion']['idtipo_action'];
						$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");
						
						if (!$this->actions->update()) {
							$this->response['type'] = 'error';
							$this->response['title'] = 'Execute Error';
							$this->response['message'] = 'No se pudo actualizar la data';
							$this->response['code'] = 1;
							$this->response['http_code'] = 422;
						} else {
							if ($this->modulo->fetch_id(array("id" => $id))) {
								$this->modulo->columns['estado'] = "ACCION";
								$this->modulo->columns['tipo_modulo'] = $this->modulo->columns['tipo_modulo']['idtipo_modulo'];
								$this->modulo->columns['updated_at'] = date("Y-m-d H:i:s");
								
								if (!$this->modulo->update()) {
									$this->response['type'] = 'error';
									$this->response['title'] = 'Execute asociation Error';
									$this->response['message'] = 'No se pudo actualizar la data';
									$this->response['code'] = 1;
									$this->response['http_code'] = 422;
								} else {
									$this->response['message'] = 'OK';
									$this->response['http_code'] = 202;
									$this->response['code'] = 0;
								}
							}
						}
					}
				}
			}
		}
	}

	public function status($id, $params = array(), $token) {
		if ($this->validate_module_id($id, $token)) {
			if ($this->modulo->fetch_id(array("id" => $id))) {
				$this->modulo->columns['estado'] = "STATUS";
				$this->modulo->columns['tipo_modulo'] = $this->modulo->columns['tipo_modulo']['idtipo_modulo'];
				$this->modulo->columns['updated_at'] = date("Y-m-d H:i:s");
				
				if (!$this->modulo->update()) {
					$this->response['type'] = 'error';
					$this->response['title'] = 'Execute asociation Error';
					$this->response['message'] = 'No se pudo actualizar la data';
					$this->response['code'] = 1;
					$this->response['http_code'] = 422;
				} else {
					$this->response['message'] = 'OK';
					$this->response['http_code'] = 202;
					$this->response['code'] = 0;
				}
			}
		}
	}

	public function api_what($mid, $params = array()) {
	    if ($this->validate_module_id_only($mid)){
            if ($this->modulo->fetch_id(array("id" => $mid))) {
            	$status = 'IDLE';
            	$this->actions->set_pagination(true);
				$this->actions->set_ipp(1);
				$acciones = $this->actions->fetch("modulo_id = '$mid' AND TRIM(ultimo_valor) <> '' ");
				
				$this->response['message'] = "<NA>";
				$this->response['http_code'] = 200;
				
				foreach ($acciones as $accion) {
					$this->response['message'] = "<".$accion->columns['comando']."|".$accion->columns['ultimo_valor'].">";
					if ($this->actions->fetch_id(array("id" => $accion->columns['id']))) {
						$status = 'OPERATED';
						
						$this->actions->columns['ultimo_valor'] = "";
						$this->actions->columns['modulo_id'] = $this->actions->columns['modulo_id']['id'];
						$this->actions->columns['tipo_accion'] = $this->actions->columns['tipo_accion']['idtipo_action'];
						$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");
						
						if (!$this->actions->update()) {
							$this->response['message'] = '<UPD_ERR>';
							$this->response['http_code'] = 422;
						}
					}else{
						$this->response['message'] = '<ERR>';
						$this->response['http_code'] = 500;
					}
				}
				
            	if (isset($params['q']) && $params['q'] != ''){
            		$this->modulo->columns['estado'] = "REPLIED";
					$this->modulo->columns['last_response'] = $params['q'];
            	}else{
            		$this->modulo->columns['estado'] = $status;
            	}
				
				$this->modulo->columns['tipo_modulo'] = $this->modulo->columns['tipo_modulo']['idtipo_modulo'];
				$this->modulo->columns['updated_at'] = date("Y-m-d H:i:s");
				
				if (!$this->modulo->update()) {
					$this->response['message'] = '<UPD_M_ERR>';
					$this->response['http_code'] = 422;
				}  
            }else{
            	$this->response['message'] = '<MOD_ERR>';
				$this->response['http_code'] = 422;
            }
	    }	
	}

	public function enable($id, $params = array()) {
		if ($this->validate_action($id)) {
			if ($this->actions->fetch_id(array("id" => $id))) {
				$this->actions->columns['enabled'] = 1;
				$this->actions->columns['modulo_id'] = $this->actions->columns['modulo_id']['id'];
				$this->actions->columns['tipo_accion'] = $this->actions->columns['tipo_accion']['idtipo_action'];
				$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");
				
				if (!$this->actions->update()) {
					$this->response['type'] = 'error';
					$this->response['title'] = 'Validate Error';
					$this->response['message'] = 'No se pudo actualizar la data';
					$this->response['code'] = 1;
					$this->response['http_code'] = 422;
				} else {
					$this->response['entidad'] = $this->actions->columns;
					$this->response['http_code'] = 202;
					$this->response['code'] = 0;
				}
			}
		}
	}

	public function disable($id, $params = array()) {
		if ($this->validate_action($id)) {
			if ($this->actions->fetch_id(array("id" => $id))) {
				$this->actions->columns['enabled'] = 0;
				$this->actions->columns['modulo_id'] = $this->actions->columns['modulo_id']['id'];
				$this->actions->columns['tipo_accion'] = $this->actions->columns['tipo_accion']['idtipo_action'];
				$this->actions->columns['updated_at'] = date("Y-m-d H:i:s");
				
				if (!$this->actions->update()) {
					$this->response['type'] = 'error';
					$this->response['title'] = 'Validate Error';
					$this->response['message'] = 'No se pudo actualizar la data';
					$this->response['code'] = 1;
					$this->response['http_code'] = 422;
				} else {
					$this->response['entidad'] = $this->actions->columns;
					$this->response['http_code'] = 202;
					$this->response['code'] = 0;
				}
			}
		}
	}

	//Private Methods
	private function validate_module($module, $token) {

		$validation = false;

		$result = $this->modulo->fetch("nombre = '$module'");
		if (count($result) <= 0) {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Modulo';
			$this->response['message'] = 'El dispositivo no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		} else {
			foreach ($result as $mod) {
				if ($this->modulo_asoc->fetch_id(array('idusuario' => $token, 'modulo_id' => $mod->columns['id']))) {
					return true;
				}
			}
		}

		return $validation;
	}

	private function validate_module_id($id, $token) {

		$validation = false;

		$result = $this->modulo->fetch("id = '$id'");
		if (count($result) <= 0) {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Modulo-i';
			$this->response['message'] = 'El dispositivo no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		} else {
			foreach ($result as $mod) {
				if ($this->modulo_asoc->fetch_id(array('idusuario' => $token, 'modulo_id' => $mod->columns['id']))) {
					return true;
				}
			}
		}

		return $validation;
	}
    
    private function validate_module_id_only($id) {

    	$validation = false;

		$result = $this->modulo->fetch("id = '$id'");
		if (count($result) <= 0) {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Modulo-i';
			$this->response['message'] = 'El dispositivo no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		} else {
			$validation = true;
		}

		return $validation;
	}

	private function validate_action($action, $module) {
		$validation = $this->actions->fetch_id(array('id' => $action), null, true, "modulo_id = '$module'");

		if (!$validation) {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Accion';
			$this->response['message'] = 'La accion no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}

		return $validation;
	}
	
	private function validate_action_name($action, $module) {
		$validation = false;
		
		$result = $this->actions->fetch("nombre = '$action' AND modulo_id = '$module'");
		$validation = (count($result)>0);
	
		if (!$validation) {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Accion';
			$this->response['message'] = 'La accion no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}

		return $validation;
	}

	public function validate_association($modulo, $id) {
		$this->modulo_asoc->columns['idusuario'] = $id;
		$this->modulo_asoc->columns['modulo_id'] = $modulo;

		$id = $this->modulo_asoc->insert();
		if (is_int($id)) {
			if ($this->modulo_asoc->fetch_id(array('idusuario' => $id, 'modulo_id' => $modulo))) {
				return true;
			} else {
				$this->response['type'] = 'error';
				$this->response['title'] = 'Error de Asociacion';
				$this->response['message'] = 'Se ha producido el siguiente error: ' . $this->modulo_asoc->err_data;
				$this->response['code'] = 1;
				$this->response['http_code'] = 500;
				return false;
			}
		} else {
			$this->response['type'] = 'error';
			$this->response['title'] = 'Crear asociacion';
			$this->response['message'] = 'Se ha producido el siguiente error: ' . $this->modulo_asoc->err_data;
			$this->response['code'] = 1;
			$this->response['http_code'] = 422;
			return false;
		}
	}

}
?>