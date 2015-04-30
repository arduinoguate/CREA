<?php

include_once 'config/config.php';

class ASSET extends GCConfig
{
	public $pagination_link = "";
	private static $private_fields = array('id', 'endpoint', 'sample', 'scopes', 'internal');
	
    public function __construct($request) {
		parent::__construct($request);
		$this->response = array();
		$this->action = 'assets';
	}
    
	//Public Methods
    public function show($qid=null, $params=array(), $id=null){
    	$this->set_pagination($this->asset, $params);
		
		if ($this->validate_queja($qid)){
			$banned = "FALSE";
			$flagged = "FALSE";
			
			if (isset($params['show'])){
				$opts = explode(',', $params['show']);
				if (in_array('banned', $opts))
					$banned = "TRUE";
				if (in_array('flagged', $opts))
					$flagged = "TRUE";
			}
			
			$q_list = array();
			
			if (is_null($id))
				if (is_null($qid)){
					$q_list = $this->asset->fetch(" banned is $banned AND flagged is $flagged ",false,array('id'),false,$this->page);
				}else
					$q_list = $this->asset->fetch("id_queja = $qid AND banned is $banned AND flagged is $flagged ",false,array('id'),false,$this->page);
			else{
				if ($this->asset->fetch_id(array('id'=>$id),null,true," banned is $banned AND flagged is $flagged "))
					$q_list[] = $this->asset;
			}
			
	        if (count($q_list) > 0){
	        	$this->paginate($this->asset);
	            
				$this->response['code'] = 0;
				$this->response['archivo'] = array();
				foreach ($q_list as $q_item) {
					$this->response['archivo'][] = $q_item->columns;
				}
				$this->response['http_code'] = 200;
			}else{
	            $this->response['type'] = 'error';
				$this->response['request'] = $_POST;
				$this->response['message'] = 'Cannot retrieve data';
				$this->response['code'] = 2;
				$this->response['http_code'] = 422;
			}
		}
    }
    
    //Public Methods
    public function delete($id=null, $params=array()){
        if ($this->validate_asset($id, $params)){
        	if ($this->asset->fetch_id( array("id" => $id) )){
                if ($this->remove_asset($this->asset->columns['path'])){
        		    if (!$this->asset->delete()){
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

	public function create($id, $params, $token, $gets_params){
		if ($this->validate_fields($gets_params,'quejas/:cc/upload')){
			$ip_origen = md5($gets_params['ip_origen']);
	        if ($this->validate_queja($id, $gets_params)){
	        	//Base file type detection
	        	$file_info = new finfo(FILEINFO_MIME);  // object oriented approach!
				$mime_type = $file_info->buffer($params);  // e.g. gives "image/jpeg"
				$mime_type = explode(';', $mime_type);
				
				if ($this->prepare_asset($mime_type[0], $id, $filepath, $filename, $tip)){
					$this->asset->columns['id'] = null;
		            $this->asset->columns['path'] = stripslashes($filename);
		            $this->asset->columns['id_queja'] = $id;
					$this->asset->columns['id_asset'] = $tip->columns['id'];
		            $this->asset->columns['fecha'] = date("Y-m-d H:i:s");
		            $this->asset->columns['ip_origen'] = $ip_origen;
		            $this->asset->columns['id_usuario'] = "";
					if ($tip->columns['type'] == "image")
		            	$this->asset->columns['flagged'] = 1;
					else
		            	$this->asset->columns['flagged'] = 0;
					
		        	if (!$this->validate_bot($id)){
						$this->asset->columns['banned'] = 0;
	                    $id = $this->asset->insert();
	                    if (is_int($id)){
	                    	file_put_contents($filepath, $params);
	                        if ($this->asset->fetch_id(array('id' => $id))){
	                            $this->response['archivo'] = $this->asset->columns;
	                    		$this->response['http_code'] = 200;
	            				$this->response['code'] = 0;	    
	                        }else{
	                            $this->response['type'] = 'error';
	                            $this->response['title'] = 'Crear asset';
	                            $this->response['message'] = 'Se ha producido el siguiente error: '.$this->asset->err_data;
	                    		$this->response['code'] = 1;
	                			$this->response['http_code'] = 500;
	                        }
	                    }else{
	                        $this->response['type'] = 'error';
	                        $this->response['title'] = 'Crear asset';
	                        $this->response['message'] = 'Se ha producido el siguiente error: '.$this->asset->err_data;
	            			$this->response['code'] = 1;
	            			$this->response['http_code'] = 422;
	                    }
					}	
				}
	        	
	        }
        }
	}

	public function ban($id, $params = array()){
    	if ($this->validate_asset($id, $params)){
    		if ($this->asset->fetch_id( array("id" => $id) )){
    		    $this->asset->columns['id_queja'] = $this->asset->columns['id_queja']['idqueja'];
                $this->asset->columns['banned'] = 1;
                if (!$this->asset->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Ban Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['queja'] = $this->asset->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
        
    }

	public function unban($id, $params = array()){
    	if ($this->validate_asset($id, $params)){
    		if ($this->asset->fetch_id( array("id" => $id) )){
    		    $this->asset->columns['id_queja'] = $this->asset->columns['id_queja']['idqueja'];
                $this->asset->columns['banned'] = 0;
                if (!$this->asset->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Ban Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['queja'] = $this->asset->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
        
    }
	
	public function flag($id, $params = array()){
    	if ($this->validate_asset($id, $params)){
    		if ($this->asset->fetch_id( array("id" => $id) )){
    		    $this->asset->columns['id_queja'] = $this->asset->columns['id_queja']['idqueja'];
                $this->asset->columns['flagged'] = 1;
                if (!$this->asset->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Flag Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['queja'] = $this->asset->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
        
    }
	
	public function unflag($id, $params = array()){
    	if ($this->validate_asset($id, $params)){
    		if ($this->asset->fetch_id( array("id" => $id) )){
    		    $this->asset->columns['id_queja'] = $this->asset->columns['id_queja']['idqueja'];
                $this->asset->columns['flagged'] = 0;
                if (!$this->asset->update()){
                    $this->response['type'] = 'error';
                    $this->response['title'] = 'Flag Error';
                    $this->response['message'] = 'No se pudo actualizar la data';
        			$this->response['code'] = 1;
        			$this->response['http_code'] = 422;
                }else{
                	$this->response['queja'] = $this->asset->columns;
            		$this->response['http_code'] = 202;
    				$this->response['code'] = 0;	    
                }
			}
    	}
        
    }
	
	//Private Methods
	private function validate_bot($idq){
		$validation = false;
		
        $q_list = $this->asset->fetch(" id_queja = $idq AND fecha >= DATE_SUB(NOW(),INTERVAL 1 HOUR ) ");
    	
		if (count($q_list) > 10){
			$validation = true;
            $this->response['type'] = 'alert';
            $this->response['title'] = 'Múltiples comentarios';
            $this->response['message'] = 'Se han detectado múltiples archivos a esta queja en poco tiempo. El este archivo no se guardara';
			$this->response['code'] = 4;
			$this->response['http_code'] = 422; 
		}
        
		return $validation;	
	}
    
    private function remove_asset($url){
        $filepath = str_replace("http://assets.quejamatic.com/",$_SERVER['DOCUMENT_ROOT'].'/quejamatic_assets/',$url);
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
				if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/quejamatic_assets/'.$id)) {
				    mkdir($_SERVER['DOCUMENT_ROOT'].'/quejamatic_assets/'.$id, 0777, true);
				}
				$filepath = $_SERVER['DOCUMENT_ROOT'].'/quejamatic_assets/'.$id.'/'.time().$q_list[0]->columns['format'];
				$filename = "http://assets.quejamatic.com/".$id."/".time().$q_list[0]->columns['format'];
				$tip = $q_list[0];
			}else{
	            $this->response['type'] = 'alert';
	            $this->response['title'] = 'Archivo Invalido';
	            $this->response['message'] = 'El archivo no es valido, por favor intente un archivo valido (jpg, png, bmp, gif, mp3, ogg, wav)';
				$this->response['code'] = 6;
				$this->response['http_code'] = 422;
			}	
		}else{
			$this->response['type'] = 'error';
            $this->response['title'] = 'Formato Invalido';
            $this->response['message'] = 'El formato no es valido, por favor intente un archivo valido (jpg, png, bmp, gif, mp3, ogg, wav)';
			$this->response['code'] = 6;
			$this->response['http_code'] = 422;
		}
		
		return $validation;
	}
	
	private function validate_queja($idqueja, $params=array()){
    	$banned = "FALSE";
		$flagged = "FALSE";
		
		if (!is_null($idqueja)){
			if (isset($params['show'])){
				$opts = explode(',', $params['show']);
				if (in_array('banned', $opts))
					$banned = "TRUE";
				if (in_array('flagged', $opts))
					$flagged = "TRUE";
			}
			
	        $validation = $this->queja->fetch_id(array('idqueja'=>$idqueja), null, true, " banned is $banned AND flagged is $flagged ");
	        
	        if (!$validation){
	            $this->response['type'] = 'error';
	            $this->response['title'] = 'No existe';
	            $this->response['message'] = 'La queja no existe';
	    		$this->response['code'] = 2;
				$this->response['http_code'] = 422;
	        }
		}else{
			return true;
		}
        
        return $validation;
    } 
	
	private function validate_asset($id, $params=array()){
    	$banned = "FALSE";
		$flagged = "FALSE";
		
		if (isset($params['show'])){
			$opts = explode(',', $params['show']);
			if (in_array('banned', $opts))
				$banned = "TRUE";
			if (in_array('flagged', $opts))
				$flagged = "TRUE";
		}
		
        $validation = $this->asset->fetch_id(array('id'=>$id), null, true, " banned is $banned AND flagged is $flagged ");
        
        if (!$validation){
            $this->response['type'] = 'error';
            $this->response['title'] = 'No existe';
            $this->response['message'] = 'El asset no existe';
    		$this->response['code'] = 2;
			$this->response['http_code'] = 422;
        }
        
        return $validation;
	
    }  
}
?>