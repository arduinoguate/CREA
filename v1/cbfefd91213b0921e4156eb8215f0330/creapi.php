<?php


require_once 'API.class.php';
include_once 'session.php';
include_once 'user.php';
include_once 'module.php';
include_once 'action.php';
include_once 'security/allow.php';

class CREAPI extends API
{
  protected $User;
	protected $session;
  protected $action;

  public function __construct($request, $origin) {
    parent::__construct($request);

    $this->session = new SESSION($this->endpoint);

    switch($this->endpoint){
      case 'user':
        $this->action = new USER($this->endpoint);
        break;
      case 'module':
        $this->action = new MODULE($this->endpoint);
        break;
      case 'action':
        $this->action = new ACTION($this->endpoint);
        break;
      default:
        break;
    }

  //Bearer token validation, maybe
  }

  /**
   * /v1/module
   */
  protected function module(){
    if ($this->session->validate_bearer_token($_SERVER['HTTP_Authorization'])){
      switch ($this->method) {
        case 'GET':
          if (allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
            switch($this->verb){
              case 'actions':
                $this->action->actions();
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'form':
                $this->action->form();
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              default:
                $id = null;
                if (isset($this->verb) && (trim($this->verb) != '')){
                  $id = $this->verb;
                }
                if (isset($_GET['show']) && (!allow::is_allowed($this->session->session_scopes, allow::MODERATE()))){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                if (!isset($this->args[0])){
                  $this->args[0]="";
                }
                switch($this->args[0]){
                  case 'actions':
                    $this->action = new ACTION('action');
                    $this->action->show_actions($id, $_GET, $this->session->username);
                    $this->response_code = $this->action->response['http_code'];
                    return $this->action->response;
                    break;
                  default:
                    $this->action->show($id, $_GET, $this->session->username);
                    $this->response_code = $this->action->response['http_code'];
                    return $this->action->response;
                  break;
                }
                break;
            }
          }else{
            $this->response_code = '401';
            return allow::denied($this->session->session_scopes);
          }
          break;
        case 'POST':
          if (allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
            $this->action->create($_POST, $this->session->username);
            $this->response_code = $this->action->response['http_code'];
            return $this->action->response;
          }else{
            $this->response_code = '401';
            return allow::denied($this->session->session_scopes);
          }
          break;
        case 'PUT':
          if (isset($this->verb) && isset($this->args[0]) && (trim($this->args[0]) != '')){
            switch($this->args[0]){
              case 'register-action':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                parse_str($this->file,$post_vars);
                $this->action = new ACTION('action');
                $this->action->create($this->verb, $post_vars, $this->session->username);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'execute-action':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                parse_str($this->file,$post_vars);
                $this->action = new ACTION('action');
                $this->action->execute($this->verb, $post_vars, $this->session->username);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'request-status':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                parse_str($this->file,$post_vars);
                $this->action = new ACTION('action');
                $this->action->status($this->verb, $post_vars, $this->session->username);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'disable':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->disable($this->args[0], $_GET);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'enable':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->enable($this->args[0], $_GET);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              default:
                $this->response_code = '404';
                return $this::return_message('Acción Invalida, metodo no encontrado: '.$this->args[1],'error');
                break;
            }
          }else{
            $this->response_code = '401';
            return $this::return_message('URL Invalido','error');
          }
          break;
        case 'DELETE':
          if (isset($this->verb) && (trim($this->verb) != '')){
            switch($this->args[0]){
              case 'actions':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                parse_str($this->file,$post_vars);
                $this->action = new ACTION('action');
                $this->action->delete($this->verb, $post_vars, $this->session->session_token, $_GET);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              default:
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->delete($this->verb);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
            }
          }else{
            $this->response_code = '401';
            return $this::return_message('URL Invalido','error');
          }
          break;
        default:
          $this->response_code = '401';
          return $this::return_message('Método Invalido','error');
          break;
      }
    }else{
      $this->response_code = $this->session->response['code'];
      return $this->session->response;
    }
  }

  /**
  * /v1/user
  */
  protected function user(){
    if ($this->session->validate_bearer_token($_SERVER['HTTP_Authorization'])){
      switch ($this->method) {
        case 'GET':
          if (allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
            switch($this->verb){
              case 'form':
                $this->action->form();
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              default:
                $id = null;
                if (isset($this->verb) && (trim($this->verb) != '')){
                  $id = trim($this->verb);
                  if (($id != $this->session->username) && (!allow::is_allowed($this->session->session_scopes, allow::MODERATE()))){
                    $this->response_code = '401';
                    return allow::denied($this->session->session_scopes);
                  }
                }else{
                  if (!allow::is_allowed($this->session->session_scopes, allow::ADMINISTRATOR())){
                    $this->response_code = '401';
                    return allow::denied($this->session->session_scopes);
                  }
                }
                if (isset($_GET['show']) && (!allow::is_allowed($this->session->session_scopes, allow::MODERATE()))){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                if (!isset($this->args[0])){
                  $this->args[0]="";
                }
                switch($this->args[0]){
                  case 'api': //quejas/:id/api
                    $this->action->show_api($id);
                    $this->response_code = $this->action->response['http_code'];
                    return $this->action->response;
                    break;
                  default: //quejas/:id
                    $this->action->show($id, $_GET);
                    $this->add_header($this->action->pagination_link);
                    $this->response_code = $this->action->response['http_code'];
                    return $this->action->response;
                    break;
                }
                break;
            }
          }else{
            $this->response_code = '401';
            return allow::denied($this->session->session_scopes);
          }
          break;
        case 'POST':
          if (allow::is_allowed($this->session->session_scopes, allow::MODERATE())){
            $this->action->create($_POST, $this->session->session_token);
            $this->response_code = $this->action->response['http_code'];
            return $this->action->response;
          }else{
            $this->response_code = '401';
            return allow::denied($this->session->session_scopes);
          }
          break;
        case 'PUT':
          if (isset($this->verb) && isset($this->args[0]) && (trim($this->args[0]) != '')){
            switch($this->args[0]){
              case 'upload':
                if (($this->verb != $this->session->username) && !allow::is_allowed($this->session->session_scopes, allow::ADMINISTRATOR())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->upload($this->verb, $this->file);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'enable':
                if (!allow::is_allowed($this->session->session_scopes, allow::MODERATE())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->enable($this->verb);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'update':
                if (!allow::is_allowed($this->session->session_scopes, allow::PUBLISH())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                parse_str($this->file,$post_vars);
                $this->action->update($this->verb, $post_vars, $this->session->username);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              case 'disable':
                if (!allow::is_allowed($this->session->session_scopes, allow::VALIDATE())){
                  $this->response_code = '401';
                  return allow::denied($this->session->session_scopes);
                }
                $this->action->disable($this->verb);
                $this->response_code = $this->action->response['http_code'];
                return $this->action->response;
                break;
              default:
                $this->response_code = '404';
                return $this::return_message('Acción Invalida, metodo no encontrado: '.$this->args[1],'error');
                break;
            }
          }else{
            $this->response_code = '401';
            return $this::return_message('URL Invalido','error');
          }
          break;
        case 'DELETE':
          if (isset($this->verb) && (trim($this->verb) != '')){
            $id = $this->verb;
            if (!allow::is_allowed($this->session->session_scopes, allow::ADMINISTRATOR())){
              $this->response_code = '401';
              return allow::denied($this->session->session_scopes);
            }
            $this->action->delete($id);
            $this->response_code = $this->action->response['http_code'];
            return $this->action->response;
          }else{
            $this->response_code = '401';
            return $this::return_message('URL Invalido','error');
          }
          break;
        default:
          $this->response_code = '401';
          return $this::return_message('Método Invalido','error');
          break;
      }
    }else{
      $this->response_code = $this->session->response['code'];
      return $this->session->response;
    }
  }

  protected function action_type(){
    switch ($this->method) {
      case 'GET':
        $this->action = new ACTION('action');
        $this->action->show_action_type();
        $this->response_code = $this->action->response['http_code'];
        return $this->action->response;
        break;
      case 'POST':
      default:
        $this->response_code = 405;
        return "Invalid action method";
      break;
    }
  }

  protected function module_type(){
    switch ($this->method) {
      case 'GET':
        $this->action = new ACTION('action');
        $this->action->show_module_type();
        $this->response_code = $this->action->response['http_code'];
        return $this->action->response;
        break;
      case 'POST':
      default:
        $this->response_code = 405;
        return "Invalid action method";
      break;
    }
  }

  protected function session(){
    switch ($this->method) {
     case 'POST':
    	 $this->session->validate_basic_token($_SERVER['HTTP_Authorization'], $_POST, $this->method);
    	 $this->response_code = $this->session->response['code'];
       return $this->session->response;
    	 break;
     default:
    	 $this->response_code = 405;
    	 return "Invalid action method";
    	 break;
    }
  }

  protected function aw(){
    switch ($this->method) {
      case 'GET':
        if (trim($this->verb) != ''){
          if ($this->session->validate_basic_token($_SERVER['HTTP_Authorization'], $_POST, $this->method)){
            $this->action = new ACTION('action');
            $this->action->api_what($this->verb, $_GET);
            $this->response_code = $this->action->response['http_code'];
            return $this->action->response['message'];
          }
          $this->response_code = $this->session->response['code'];
          return $this->session->response['message'];
        }else{
          $this->response_code = '404';
          return '<>';
        }
        break;
      default:
    	  $this->response_code = 405;
    	  return "Invalid action method";
    	  break;
    }
  }

  protected function welcome() {
    if ($this->method == 'GET') {
      return "WELCOME TO CREA API - BIENVENIDO AL API DE CREA";
    } else {
      return "Invalid Method";
    }
  }
}

?>
