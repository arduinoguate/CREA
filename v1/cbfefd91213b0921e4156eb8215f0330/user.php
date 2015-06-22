<?php

include_once 'config/config.php';

class USER extends GCConfig
{
	public $pagination_link = "";
	private static $private_fields = array('id', 'endpoint', 'sample', 'scopes', 'internal');
    public function __construct($request) {
		parent::__construct($request);
		$this->response = array();
		$this->action = 'user';
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

  public function show($eid=null, $params=array()){
  	$this->set_pagination($this->user, $params);

		$verified = " enabled is TRUE ";

		if (isset($params['show'])){
			$opts = explode(',', $params['show']);
			if (in_array('disabled', $opts))
				$verified = " enabled is FALSE ";
		}

		$q_list = array();

		if (is_null($eid)){
			$q_list = $this->user->fetch("$verified",false,array('idusuario'),false,$this->page);
		}else{
			if ($this->user->fetch_id(array('idusuario'=>$eid),null,true,"$verified"))
				$q_list[] = $this->user;
		}

    if (count($q_list) > 0){
    	$this->paginate($this->user);

			$this->response['code'] = 0;
			$this->response['usuarios'] = array();
			foreach ($q_list as $q_item) {
				$this->response['usuarios'][] = $q_item->columns;
			}
			$this->response['http_code'] = 200;
		}else{
      $this->response['type'] = 'error';
			$this->response['message'] = 'Cannot retrieve data';
			$this->response['code'] = 2;
			$this->response['http_code'] = 422;
		}
  }

	public function show_api($id, $params=array()){
		if ($this->validate_user($id)){
    	$q_list = $this->api_user_asoc->fetch("id_usuario = '$id'");

			if (count($q_list) > 0){
				$this->response['code'] = 0;
				$this->response['credenciales'] = array();
				foreach ($q_list as $q_item) {
					if ($this->api_client->fetch_id(array('client_id'=>$q_item->columns['client_id']['client_id']),null,true," asoc = 0 ")){
						$this->response['credenciales'] = $this->api_client->columns;
						$s_list = $this->api_client_scopes->fetch("id_client = '".$q_item->columns['client_id']['client_id']."'");
						foreach ($s_list as $s_item) {
							$this->response['credenciales']['scopes'][] = $s_item->columns['id_scope'];
						}
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
  }

  public function delete($id=null, $params=array()){
    if ($this->validate_user($id)){
  		if ($this->user->fetch_id( array("idusuario" => $id) )){
        if ($this->remove_asset($this->user->columns['path_avatar'])){
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
    	if ($this->validate_user($params['username'])){
				$this->response['type'] = 'error';
        $this->response['title'] = 'Crear usuario';
        $this->response['message'] = 'El usuario ya existe';
  			$this->response['code'] = 5;
  			$this->response['http_code'] = 422;
			}else{
				$this->response = array();
				$password = md5($params['password']);
      	$this->user->columns['idusuario'] = strtolower($params['username']);
        $this->user->columns['nombre'] = $params['nombre'];
				$this->user->columns['apellido'] = (isset($params['apellido']))?$params['apellido']:"";
				$this->user->columns['email'] = $params['email'];
        $this->user->columns['fb_account'] = "";
				$this->user->columns['path_avatar'] = "";
        $this->user->columns['password'] = $password;
        $this->user->columns['enabled'] = 1;
				$this->user->columns['created_at'] = date("Y-m-d H:i:s");
				$this->user->columns['updated_at'] = date("Y-m-d H:i:s");

  	    $id = $this->user->insert();
        if (is_int($id)){
      		$client = '';
        	if ($this->validate_api($params['username'], $params['email'], $client, 0)){
        		if ($this->validate_association($params['username'], $client) && $this->validate_association($params['username'], '13a3b20fa41aa80636cffe064abd3e07')){
		    	    if ($this->user->fetch_id(array('idusuario' => $params['username']))){
                $this->response['entidad'] = $this->user->columns;
            		$this->response['http_code'] = 200;
		    				$this->response['code'] = 0;
              }else{
                $this->response['type'] = 'error';
                $this->response['title'] = 'Mostrar usuario';
                $this->response['message'] = 'Se ha producido el siguiente error: '.$this->user->err_data;
            		$this->response['code'] = 1;
	        			$this->response['http_code'] = 500;
              }
						}
					}
        }else{
          $this->response['type'] = 'error';
          $this->response['title'] = 'Crear usuario';
          $this->response['message'] = 'Se ha producido el siguiente error: '.$this->user->err_data;
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
        	$password = (isset($params['password']))?md5($params['password']):$this->user->columns['password'];
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
          	if ($this->user->fetch_id(array('idusuario' => $id))){
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

	public function upload($id, $params){
		if ($this->validate_user($id)){
    	//Base file type detection
    	$file_info = new finfo(FILEINFO_MIME);  // object oriented approach!
			$mime_type = $file_info->buffer($params);  // e.g. gives "image/jpeg"
			$mime_type = explode(';', $mime_type);
			if ($this->user->fetch_id( array("idusuario" => $id) )){
				if ($this->prepare_asset($mime_type[0], $id, $filepath, $filename, $tip)){
          $this->user->columns['path_avatar'] = stripslashes($filename);

					if ($tip->columns['type'] == "image"){
            if (!$this->user->update()){
            	$this->response['type'] = 'error';
              $this->response['title'] = 'Crear asset';
              $this->response['message'] = 'Se ha producido el siguiente error: '.$this->user->err_data;
        			$this->response['code'] = 1;
  						$this->response['http_code'] = 422;
            }else{
              file_put_contents($filepath, $params);
              if ($this->user->fetch_id(array('idusuario' => $id))){
                $this->response['entidad'] = $this->user->columns;
            		$this->response['http_code'] = 200;
        				$this->response['code'] = 0;
              }else{
                $this->response['type'] = 'error';
                $this->response['title'] = 'Crear asset';
                $this->response['message'] = 'Se ha producido el siguiente error: '.$this->user->err_data;
            		$this->response['code'] = 1;
      					$this->response['http_code'] = 500;
              }
            }
					}else{
						$this->response['type'] = 'error';
            $this->response['title'] = 'Upload asset';
            $this->response['message'] = 'El archivo no es valido, por favor intente un archivo valido (jpg, png, bmp, gif)';
						$this->response['code'] = 1;
    				$this->response['http_code'] = 422;
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

	public function validate_association($userid, $token){
		$this->api_user_asoc->columns['client_id'] = $token;
		$this->api_user_asoc->columns['id_usuario'] = $userid;

    $id = $this->api_user_asoc->insert();
    if (is_int($id)){
      if ($this->api_user_asoc->fetch_id(array('client_id' => $token, 'id_usuario' => $userid))){
        return true;
      }else{
        $this->response['type'] = 'error';
        $this->response['title'] = 'Error de Asociacion';
        $this->response['message'] = 'Se ha producido el siguiente error: '.$this->entidad->err_data;
  			$this->response['code'] = 1;
  			$this->response['http_code'] = 500;
				return false;
      }
    }else{
      $this->response['type'] = 'error';
      $this->response['title'] = 'Crear asociacion';
    	$this->response['message'] = 'Se ha producido el siguiente error: '.$this->entidad->err_data;
			$this->response['code'] = 1;
			$this->response['http_code'] = 422;
			return false;
    }
  }

	public function validate_api($userid, $email, &$client, $asoc = 1){
		$client = md5($userid.$email.date("Y-m-d H:i:s"));
		$secret = md5($client.'SECRETKEY');
  	$this->api_client->columns['client_id'] = $client;
    $this->api_client->columns['client_secret'] = $secret;
		$this->api_client->columns['email'] = $email;
		$this->api_client->columns['user_id'] = $userid;
    $this->api_client->columns['enabled'] = 1;
		$this->api_client->columns['asoc'] = $asoc;
		$this->api_client->columns['created_at'] = date("Y-m-d H:i:s");;
		$this->api_client->columns['updated_at'] = date("Y-m-d H:i:s");;

    $id = $this->api_client->insert();
    if (is_int($id)){
      if ($this->api_client->fetch_id(array('client_id' => $client))){
      	$this->api_client_scopes->columns['id_client'] = $client;
				$this->api_client_scopes->columns['id_scope'] = 'module-owner';
				$idx = $this->api_client_scopes->insert();
        if (is_int($idx)){
          if ($this->api_client_scopes->fetch_id(array('id_client' => $client,'id_scope'=>'module-owner'))){
          	return true;
          }else{
            $this->response['type'] = 'error';
            $this->response['title'] = 'Error de Asociacion scope';
            $this->response['message'] = 'Se ha producido el siguiente error: '.$this->api_client_scopes->err_data;
    				$this->response['code'] = 1;
	    			$this->response['http_code'] = 500;
						return false;
          }
        }else{
          $this->response['type'] = 'error';
          $this->response['title'] = 'Crear asociacion scope';
          $this->response['message'] = 'Se ha producido el siguiente error: '.$this->api_client_scopes->err_data;
					$this->response['code'] = 1;
					$this->response['http_code'] = 422;
					return false;
        }
      }else{
        $this->response['type'] = 'error';
        $this->response['title'] = 'Error de token';
        $this->response['message'] = 'Se ha producido el siguiente error: '.$this->api_client->err_data;
    		$this->response['code'] = 1;
  			$this->response['http_code'] = 500;
				return false;
      }
    }else{
      $this->response['type'] = 'error';
      $this->response['title'] = 'Crear Token';
      $this->response['message'] = 'Se ha producido el siguiente error: '.$this->api_client->err_data;
			$this->response['code'] = 1;
			$this->response['http_code'] = 422;
			return false;
    }
  }

	private function remove_asset($url){
    $filepath = str_replace("http://assets.arduinogt.com/user/",$_SERVER['DOCUMENT_ROOT'].'/arduinogt_assets/user/',$url);
    if (unlink($filepath)){
      return true;
    }else{
			$this->response['type'] = 'error';
			$this->response['title'] = 'Eliminar Archivo';
			$this->response['message'] = 'El archivo no ha podido ser eliminado)';
			$this->response['code'] = 7;
			$this->response['http_code'] = 500;
      return false;
    }
  }

	private function prepare_asset($mime, $id, &$filepath, &$filename, &$tip){
		$validation = false;

		if (!is_null($mime) && trim($mime) != ''){
			$q_list = $this->asset_type->fetch(" mime LIKE '%$mime%' ");

			if (count($q_list) > 0){
				$validation = true;
				if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/arduinogt_assets/user/'.$id)) {
			    mkdir($_SERVER['DOCUMENT_ROOT'].'/arduinogt_assets/user/'.$id, 0777, true);
				}
				$filepath = $_SERVER['DOCUMENT_ROOT'].'/arduinogt_assets/user/'.$id.'/'.time().$q_list[0]->columns['format'];
				$filename = "http://assets.arduinogt.com/user/".$id."/".time().$q_list[0]->columns['format'];
				$tip = $q_list[0];
			}else{
        $this->response['type'] = 'alert';
        $this->response['title'] = 'Archivo Invalido';
        $this->response['message'] = 'El archivo no es valido, por favor intente un archivo valido (jpg, png, bmp, gif)';
				$this->response['code'] = 6;
				$this->response['http_code'] = 422;
			}
		}else{
			$this->response['type'] = 'error';
      $this->response['title'] = 'Formato Invalido';
      $this->response['message'] = 'El formato no es valido, por favor intente un archivo valido (jpg, png, bmp, gif)';
			$this->response['code'] = 6;
			$this->response['http_code'] = 422;
		}

		return $validation;
	}
}
?>
