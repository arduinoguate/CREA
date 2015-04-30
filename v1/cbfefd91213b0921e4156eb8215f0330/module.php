<?php

include_once 'config/config.php';

class MODULE extends GCConfig
{
	public $pagination_link = "";
	private static $private_fields = array('id', 'endpoint', 'sample', 'scopes', 'internal');
	
    public function __construct($request) {
		parent::__construct($request);
		$this->response = array();
		$this->action = 'module';
	}
    
	//Public Methods
	public function form($action=''){
    	if ($action == '')
			$action = $this->action;
        $q_list = $this->api_form->fetch(" endpoint LIKE '$action' ");
        if (count($q_list) > 0){
            $this->response['code'] = 0;
			$this->response['fields'] = array();
            
			$i=0;
			foreach ($q_list as $q_item) {
				$this->response['fields'][$i] = array();
                foreach ($q_item->columns as $k => $colval) {
                    if (!in_array($k, $this::$private_fields)){
                        $this->response['fields'][$i][$k] = $q_item->columns[$k];
                    }
                }
				$i++;
			}
			$this->response['http_code'] = 200;
		}else{
            $this->response['type'] = 'error';
			$this->response['request'] = $_POST;
			$this->response['message'] = 'Fields definition error';
			$this->response['code'] = 2;
			$this->response['http_code'] = 500;
		}
    }
	
    public function show($eid=null, $params=array(), $token){
    	$verified = " enabled is TRUE ";
		
		if (isset($params['show'])){
			$opts = explode(',', $params['show']);
			if (in_array('disabled', $opts))
				$verified = " enabled is FALSE ";
		}
		
		$q_list = array();
		
        if (is_null($eid)){
			$q_list = $this->modulo_asoc->fetch("idusuario = '$token'");
		}else{
			if ($this->modulo_asoc->fetch_id(array('idusuario'=>$token, 'modulo_id'=>$eid)))
				$q_list[] = $this->modulo_asoc;
		}
		
        if (count($q_list) > 0){
        	$this->response['code'] = 0;
			$this->response['modulos'] = array();
			foreach ($q_list as $q_item) {
				$mid = $q_item->columns['modulo_id']['id'];
				if ($this->modulo->fetch_id(array("id" => $mid), null, true, "$verified")){
	        		$this->response['modulos'][] = $this->modulo->columns;
	        	}
			}
			$this->response['http_code'] = 200;
		}else{
            $this->response['type'] = 'error';
			$this->response['message'] = 'Cannot retrieve data';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}
    }

    public function delete($id=null, $params=array()){
        if ($this->validate_user($id)){
    		if ($this->modulo->fetch_id( array("idusuario" => $id) )){
    		    if (!$this->entidad->delete()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Delete Error';
                    $this->response['message'] = 'No se pudo eliminar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['message'] = 'Deleted';
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
        	}else{
                $this->response['type'] = 'error';
    			$this->response['request'] = $_POST;
				$this->response['message'] = 'Cannot retrieve data';
				$this->response['code'] = 2;
				$this->response['http_code'] = 422;
        	}
        }
    }

	public function create($params = array(), $token){
        if ($this->validate_fields($params,$this->action)){
        	if ($this->validate_module($params['nombre'], $token)){
        		$this->response['type'] = 'error';
                $this->response['title'] = 'Crear dispositivo';
                $this->response['message'] = 'El dispositivo ya existe';
    			$this->response['code'] = 5;
    			$this->response['http_code'] = 422;
        	}else
			{
				$this->response = array();
				$mid = md5(time());
				$this->modulo->columns['id'] = $mid;
	            $this->modulo->columns['nombre'] = $params['nombre'];
				$this->modulo->columns['tipo_modulo'] = $params['tipo'];
				$this->modulo->columns['estado'] = "CREATED";
	            $this->modulo->columns['enabled'] = 1;
	            $this->modulo->columns['last_response'] = "";
				$this->modulo->columns['created_at'] = date("Y-m-d H:i:s");
				$this->modulo->columns['updated_at'] = date("Y-m-d H:i:s");
	            
	    	    $id = $this->modulo->insert();
	            if (is_int($id)){
	            	if ($this->validate_association($mid, $token)){
			    	    if ($this->modulo->fetch_id(array('id' => $mid))){
		                    $this->response['entidad'] = $this->modulo->columns;
		            		$this->response['http_code'] = 200;
		    				$this->response['code'] = 0;	    
		                }else{
		                    $this->response['type'] = 'error';
		                    $this->response['title'] = 'Mostrar dispositivo';
		                    $this->response['message'] = 'Se ha producido el siguiente error: '.$this->modulo->err_data;
		            		$this->response['code'] = 1;
		        			$this->response['http_code'] = 500;
		                }
					}
	            }else{
	                $this->response['type'] = 'error';
	                $this->response['title'] = 'Crear dispositivo';
	                $this->response['message'] = 'Se ha producido el siguiente error: '.$this->modulo->err_data;
	    			$this->response['code'] = 1;
	    			$this->response['http_code'] = 422;
	            }
            }
        }
    }

	public function update($id, $params = array(), $token, $get_params){
		if ($this->validate_fields($params,'user/:id/update')){
			if ($this->validate_user($id)){
		        if ($this->user->fetch_id( array("idusuario" => $id) )){
		        	$password = (isset($params['password']))?$params['password']:$this->user->columns['password'];
		            $this->user->columns['nombre'] = (isset($params['nombre']))?$params['nombre']:$this->user->columns['nombre'];
		            $this->user->columns['apellido'] = (isset($params['apellido']))?$params['apellido']:$this->user->columns['apellido'];
		            $this->user->columns['email'] = (isset($params['email']))?$params['email']:$this->user->columns['email'];
		            $this->user->columns['password'] = $password;
					$this->user->columns['updated_at'] = date("Y-m-d H:i:s");
		            
	    		    if (!$this->user->update()){
	                    $this->response['type'] = 'error';
	                    $this->response['title'] = 'Update Error';
	                    $this->response['message'] = 'No se pudo actualizar la data';
	        			$this->response['code'] = $this->user->err_data;
	        			$this->response['http_code'] = 422;
	                }else{
	                	if ($this->entidad->fetch_id(array('identidad' => $id))){
	                        $this->response['entidad'] = $this->user->columns;
	                		$this->response['http_code'] = 202;
	        				$this->response['code'] = 0;	    
	                    }else{
	                        $this->response['type'] = 'error';
	                        $this->response['title'] = 'Mostrar entidad';
	                        $this->response['message'] = 'Se ha producido el siguiente error: '.$this->user->err_data;
	                		$this->response['code'] = 1;
	            			$this->response['http_code'] = 500;
	                    }
	                }
		        }
	        }
        }
    }
	
	public function enable($id, $params = array()){
		if ($this->validate_user($id)){
    		if ($this->user->fetch_id( array("idusuario" => $id) )){
    		    $this->user->columns['enabled'] = 1;
                if (!$this->user->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Validate Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['entidad'] = $this->user->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
    }
	
	public function disable($id, $params = array()){
		if ($this->validate_user($id)){
    		if ($this->user->fetch_id( array("idusuario" => $id) )){
    		    $this->user->columns['enabled'] = 0;
                if (!$this->user->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Validate Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['entidad'] = $this->user->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
    }
	
	//Private Methods
	private function validate_module($module, $token){
		
		$validation = false;
		
        $result = $this->modulo->fetch("nombre = '$module'");
        if (count($result) <= 0){
            $this->response['type'] = 'error';
            $this->response['title'] = 'Usuario';
            $this->response['message'] = 'El dispositivo no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
        }else{
        	foreach ($result as $mod) {
				if ($this->modulo_asoc->fetch_id(array('idusuario'=>$token, 'modulo_id'=>$mod->columns['id']))){
	        		return true;
	        	}	
			}
        }
        
        return $validation;
    }
	
	private function validate_user($user){
        $validation = $this->user->fetch_id(array('idusuario'=>$user));
        
        if (!$validation){
            $this->response['type'] = 'error';
            $this->response['title'] = 'Usuario';
            $this->response['message'] = 'El usuario no existe';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
        }
        
        return $validation;
    }	
	
	public function validate_association($modulo, $id){
		$this->modulo_asoc->columns['idusuario'] = $id;
        $this->modulo_asoc->columns['modulo_id'] = $modulo;
	    
	    $id = $this->modulo_asoc->insert();
        if (is_int($id)){
            if ($this->modulo_asoc->fetch_id(array('idusuario' => $id, 'modulo_id' => $modulo))){
                return true;	    
            }else{
                $this->response['type'] = 'error';
                $this->response['title'] = 'Error de Asociacion';
                $this->response['message'] = 'Se ha producido el siguiente error: '.$this->modulo_asoc->err_data;
        		$this->response['code'] = 1;
    			$this->response['http_code'] = 500;
				return false;
            }
        }else{
            $this->response['type'] = 'error';
            $this->response['title'] = 'Crear asociacion';
            $this->response['message'] = 'Se ha producido el siguiente error: '.$this->modulo_asoc->err_data;
			$this->response['code'] = 1;
			$this->response['http_code'] = 422;
			return false;
        }
    }
}
?>