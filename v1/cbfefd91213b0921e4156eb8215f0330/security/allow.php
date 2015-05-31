<?php

class allow {
	private static $PUBLISH = array('module-owner', 'administrator', 'site-visitor', 'publish');
  private static $COMMENT = array('administrator', 'site-visitor', 'publish');
	private static $SEARCH = array('administrator', 'site-visitor');
	private static $REMOVE = array('administrator', 'moderator');
	private static $VALIDATE = array('administrator', 'moderator');
	private static $MODERATE = array('administrator', 'moderator');
	private static $RESOLVE = array('administrator', 'owner');
	private static $MANAGER = array('administrator', 'manager');
  private static $ADMINISTRATOR = array('administrator');

	public static function MANAGER(){
  	return self::$MANAGER;
	}

  public static function ADMINISTRATOR(){
  	return self::$ADMINISTRATOR;
	}

	public static function PUBLISH(){
		return self::$PUBLISH;
	}

	public static function RESOLVE(){
		return self::$RESOLVE;
	}

	public static function COMMENT(){
		return self::$COMMENT;
	}

	public static function SEARCH(){
		return self::$SEARCH;
	}

	public static function REMOVE(){
		return self::$REMOVE;
	}

	public static function VALIDATE(){
		return self::$VALIDATE;
	}

	public static function MODERATE(){
		return self::$MODERATE;
	}

	public static function is_allowed($scopes, $allow){
		$set = explode(',', $scopes);
		$r_values = true;
		foreach ($set as $value) {
			$value = trim($value);
			$r_values = ($r_values && in_array($value, $allow));
		}
		return $r_values;
	}

	public static function denied($scopes){
		$response = array();
		$response['type'] = 'error';
		$response['message'] = 'Cannot allow action under scopes: '.$scopes;
		return $response;
	}
}

?>
