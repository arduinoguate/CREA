<?php
/* SIMPLE ORM CORE
 * Developed by OSCAR LECHE and DIEGO VASQUEZ
 * V.2.0
 * DESCRIPTION: This is the simple ORM core code, here is where all the methods usable for querying a database table lays
*/

include_once("DataBaseManager.php");

class DBManager extends DataBaseManager{
	
	const SELF = '_self';
	// atributos heredados
	// $db
	public $columns = array();
	public $db_name;
	public $err_data;
	public $count;
	public $the_key = array();
	public $foreign_relations = array();
	public $foreign_keys = array();
	public $pages;
	public $affected_rows;
	private $ipp = 0;
	private $pagination = false;
	var $fetched = false;
	public $columns_defs = array();
	var $connection;
	private $recursive;
	
	// constructor
	function __construct($connection, $db_name, $db_columns, $key, $foreigns = null, $ipp = 25){
		parent::__construct($connection);
		$this->db_name = $db_name;
		$this->foreign_relations = $foreigns;
		$this->columns_defs = $db_columns;
		$this->the_key = $key;
		$this->err_data = "";
		$this->connection = $connection;
		$this->ipp = $ipp;
		$this->recursive = true;
		foreach ($db_columns as $columnname)
			$this->columns[$columnname] = "NULL";
		if (!is_null($foreigns)){
			foreach ($foreigns as $relation => $v) {
				$this->foreign_keys[] = $relation;
			}
		}
		$this->count = 0;
	}
	
	function set_pagination($value){	
		$this->pagination = $value;
	}
	
	function set_recursivity($value){	
		$this->recursive = $value;
	}
	
	function set_ipp($value){	
		$this->ipp = $value;
	}
    
    function get_columns(){
        return $this->columns_defs;
    }
	
	/*
	 * Method: Fetch
	 * Executes a select based on the information of the model
	 *
	 * @query		the search query that will go as a Where, if empty "" the WHERE statement won't be added
	 * @custom		replaces all the automatic query with a custom query. It also returns the response with its column model and replaces all the column_defs
	 * @order		array countaining the columns that want to be ordered by
	 * @asc			use only with @order. Default ascending or ASC set as false changes it to DESC
	 * @page		use with pagination set as true. The page that needs to be queried
	 * */
	function fetch($query="", $custom=false, $order = null, $asc = true, $page=0){
		$this->err_data = "";
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= " ASC";
			}else{
				$order_text .= " DESC";
			}
		}
		
		if (!$custom){
			if ($query != ""){
				$query = " WHERE ".$query;
			}
			
			$sql = 'SELECT * FROM '.$this->db_name.''.$query.' '.$order_text;
			$this->count = $this->fixed_count('SELECT count(*) FROM '.$this->db_name.''.$query.' '.$order_text.';');
			if ($this->pagination){
				$this->pages = ceil($this->count / $this->ipp);
				$sql .= ' LIMIT '.($page * $this->ipp).','.$this->ipp.';';
			}else
				$sql .= ';';
		}else {
			$sql = $query;
		}
		
		$retorno = array();
		//echo '<b>'.$sql.'</b><br/>';
		try{
			$result = $this->db->Execute($sql);
			
			while ($row = mysqli_fetch_assoc($result)){
                if (!$custom){
				    $rowobj = new DBManager($this->connection, $this->db_name, $this->columns_defs, $this->the_key);
    				foreach($this->columns_defs as $definitions){
    					if (in_array($definitions, $this->foreign_keys)){
    						if ($this->foreign_relations[$definitions][2] == $this::SELF){
    							if (is_null($row[$definitions]))
    								$rowobj->columns[$definitions] = $row[$definitions];
    							else{
    								//can go to further entities or just stays in one level
    								if ($this->recursive){
    									$this->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
    									$rowobj->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;	
    								}else{
    									$rowobj->columns[$definitions] = $row[$definitions];
    								}
    							}
    						}else{
    							if ($this->recursive){
	    							$this->foreign_relations[$definitions][2]->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
	    							$rowobj->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;
								}else{
									$rowobj->columns[$definitions] = $row[$definitions];
								}
    						}
    					}else{
    						$rowobj->columns[$definitions] = $row[$definitions];	
    					}
    				}
				}else{
    			    $retorno[] = $row;       
				}
				$retorno[] = $rowobj;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $retorno;
	}

	function fetch_obj_in($objs, $cond="", $order = null, $asc = true, $page=0){
		$consulta = "";
		$this->err_data = "";
		
		$valid = false;
		$statements = array();
		$tables = array();
		$ftables = array();
		$fobjs = array();
		
		$base_letter = "A";
		$tables[] = $this->db_name." ".$base_letter;
		$base_table = $base_letter;
		
		foreach ($objs as $obj) {
			$base_letter++;
			$tables[] = $obj->db_name." ".$base_letter;
			foreach ($this->foreign_relations as $fkey => $value) {
				if ($value[0] == $obj->db_name){
					$fobjs[] = $fkey;
					$ftables[$fkey] = $obj->columns;
					if (in_array($value[1], $obj->the_key)){
						$statements[] = $base_letter.".".$value[1]."="
						.((($this->GetType($obj->columns[$value[1]]) == 'boolean' 
						|| $this->GetType($obj->columns[$value[1]]) == 'float' 
						|| $this->GetType($obj->columns[$value[1]]) == 'integer' 
						|| $this->GetType($obj->columns[$value[1]]) == 'numeric' 
						|| $this->GetType($obj->columns[$value[1]]) == 'NULL'))?'':"'")
							.(($this->GetType($obj->columns[$value[1]]) == 'NULL')?'NULL':$obj->columns[$value[1]])
						.((($this->GetType($obj->columns[$value[1]]) == 'boolean' 
						|| $this->GetType($obj->columns[$value[1]]) == 'float' 
						|| $this->GetType($obj->columns[$value[1]]) == 'integer' 
						|| $this->GetType($obj->columns[$value[1]]) == 'numeric' 
						|| $this->GetType($obj->columns[$value[1]]) == 'NULL'))?'':"'");
						$statements[] = $base_letter.".".$value[1]."=".$base_table.".".$fkey;
					}	
				}
			}
		}
		
		$tablestr = "";
		$count = 0;
		foreach ($tables as $table) {
			if ($count != 0)
				$tablestr .= ", ";
			$tablestr .= $table;
			$count++;
		}
		
		$joinstr = "";
		$count = 0;
		foreach ($statements as $statement) {
			if ($count != 0)
				$joinstr .= " AND ";
			$joinstr .= $statement;
			$count++;
		}
		
		if ($cond != ""){
			$joinstr .= " AND ".$cond;
		}
		
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= "ASC";
			}else{
				$order_text .= "DESC";
			}
		}
		
	    $sql = 'SELECT '.$base_table.'.* FROM '.$tablestr.' WHERE '.$joinstr.' '.$order_text;
		$this->count = $this->fixed_count('SELECT count('.$base_table.'.*) FROM '.$tablestr.' WHERE '.$joinstr.' '.$order_text.';');
		if ($this->pagination){
			$this->pages = ceil($this->count / $this->ipp);
			$sql .= ' LIMIT '.($page * $this->ipp).','.$this->ipp.';';
		}else
			$sql .= ';';
		
		$retorno = array();
		
		try{
			$result = $this->db->Execute($sql);
			
			while ($row = mysqli_fetch_assoc($result)){
				$rowobj = new DBManager($this->connection, $this->db_name, $this->columns_defs, $this->the_key);
				foreach($this->columns_defs as $definitions){
					if (in_array($definitions, $fobjs)){
						$rowobj->columns[$definitions] = $ftables[$definitions];
					}else{
						if (in_array($definitions, $this->foreign_keys)){
							if ($this->foreign_relations[$definitions][2] == $this::SELF){
								if (is_null($row[$definitions]))
									$rowobj->columns[$definitions] = $row[$definitions];
								else{
									//can go to further entities or just stays in one level
									if ($this->recursive){
										$this->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
										$rowobj->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;	
									}else{
										$rowobj->columns[$definitions] = $row[$definitions];
									}
								}
							}else{
								if ($this->recursive){
									$this->foreign_relations[$definitions][2]->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
									$rowobj->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;
								}else{
									$rowobj->columns[$definitions] = $row[$definitions];
								}
							}
						}else{
							$rowobj->columns[$definitions] = $row[$definitions];	
						}
							
					}
					
				}
				$retorno[] = $rowobj;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $retorno;
		
	}
	
	/*
	 * Method: fetch_id
	 * Executes a select based on the information of the model and per Keys
	 *
	 * @id			array containing the key columns that want to be used as query params column => value
	 * @order		array countaining the columns that want to be ordered by
	 * @asc			use only with @order. Default ascending or ASC set as false changes it to DESC
	 * @cond		the search query that will go as a Where, if empty "" the WHERE statement won't be added
	 * @page		use with pagination set as true. The page that needs to be queried
	 * */
	function fetch_id($id, $order = null, $asc = true, $cond = "", $page=0){
		$consulta = "";
		$this->err_data = "";
		
		$result = false;
		
		$key_names = "";
		$count = 0;
		
		if (count($id) > 0)
			foreach ($this->the_key as $keys) {
				
				if ($count > 0)
					$key_names .= ' AND ';
				
				$key_names .= $keys."="
					.((($this->GetType($id[$keys]) == 'boolean' 
					|| $this->GetType($id[$keys]) == 'float' 
					|| $this->GetType($id[$keys]) == 'integer' 
					|| $this->GetType($id[$keys]) == 'numeric' 
					|| $this->GetType($id[$keys]) == 'NULL'))?"":"'")
						.(($this->GetType($id[$keys]) == 'NULL')?'NULL':$id[$keys])
					.((($this->GetType($id[$keys]) == 'boolean' 
					|| $this->GetType($id[$keys]) == 'float' 
					|| $this->GetType($id[$keys]) == 'integer' 
					|| $this->GetType($id[$keys]) == 'numeric' 
					|| $this->GetType($id[$keys]) == 'NULL'))?"":"'");
				$count++;
				
			}
		
		
		
		if ($cond != ""){
			$key_names .= ($key_names != "")?" AND ":"";
			$key_names .= $cond;
		}
		
		$count = 0;
		$order_text = "";
		if (!is_null($order)){
			$order_text = " ORDER BY ";
			foreach ($order as $keys){
				if ($count > 0)
					$order_text .= ' , ';
				$order_text .= $keys;
				$count++;
			}
			if ($asc){
				$order_text .= "ASC";
			}else{
				$order_text .= "DESC";
			}
		}
		
	    $sql = 'SELECT * FROM '.$this->db_name.' WHERE '.$key_names.' '.$order_text;
		$this->count = $this->fixed_count('SELECT count(*) FROM '.$this->db_name.' WHERE '.$key_names.' '.$order_text.';');
		if ($this->pagination){
			$this->pages = ceil($this->count / $this->ipp);
			$sql .= ' LIMIT '.($page * $this->ipp).','.$this->ipp.';';
		}else
			$sql .= ';';

		try{
			if (is_null($id)){
				$this->err_data = "No id present";
				return FALSE;
			}
			else{
				//echo $sql.'<br/>';
				$result = $this->db->Execute($sql);
				
				if ($row = mysqli_fetch_assoc($result)){
					/*foreach($this->columns_defs as $definitions){
						$this->columns[$definitions] = $row[$definitions];
					}*/
                    foreach($this->columns_defs as $definitions){
						if (in_array($definitions, $this->foreign_keys)){
							if ($this->foreign_relations[$definitions][2] == $this::SELF){
								if (is_null($row[$definitions]))
									$this->columns[$definitions] = $row[$definitions];
								else{
									//can go to further entities or just stays in one level
									if ($this->recursive){
										$this->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
										$this->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;	
									}else{
										$this->columns[$definitions] = $row[$definitions];
									}
								}
							}else{
								if ($this->recursive){
									$this->foreign_relations[$definitions][2]->fetch_id(array($this->foreign_relations[$definitions][1]=>$row[$definitions]));
									$this->columns[$definitions] = $this->foreign_relations[$definitions][2]->columns;	
								}else{
									$this->columns[$definitions] = $row[$definitions];
								}
							}
						}else{
							$this->columns[$definitions] = $row[$definitions];	
						}
    				}
				}else
					return FALSE;
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return TRUE;
	}
	
	function fixed_count($cond = ""){
		$this->err_data = "";
		
		$result = false;
		
		$count = 0;
	    $sql = $cond;

		try{
			$result = $this->db->Execute($sql);
			
			if ($row = mysqli_fetch_array($result)){
				$count = $row[0];
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $count;
	}
	
	function count($id, $order = null, $asc = true, $cond = ""){
		$this->err_data = "";
		
		$result = false;
		
		$key_names = "";
		$count = 0;
		
		foreach ($this->the_key as $keys) {
			
			if ($count > 0)
				$key_names .= ' AND ';
			
			$key_names .= $keys."="
				.((($this->GetType($id[$keys]) == 'boolean' 
				|| $this->GetType($id[$keys]) == 'float' 
				|| $this->GetType($id[$keys]) == 'integer' 
				|| $this->GetType($id[$keys]) == 'numeric' 
				|| $this->GetType($id[$keys]) == 'NULL'))?"":"'")
					.(($this->GetType($id[$keys]) == 'NULL')?'NULL':$id[$keys])
				.((($this->GetType($id[$keys]) == 'boolean' 
				|| $this->GetType($id[$keys]) == 'float' 
				|| $this->GetType($id[$keys]) == 'integer' 
				|| $this->GetType($id[$keys]) == 'numeric' 
				|| $this->GetType($id[$keys]) == 'NULL'))?"":"'");
			$count++;
			
		}
		
		if ($cond != ""){
			if ($count > 0)
				$key_names .= ' AND ';
			$key_names .= $cond;
		}
		
		$count = 0;
	    $sql = 'SELECT count(*) FROM '.$this->db_name.' WHERE '.$key_names.' '.$order_text.';';

		try{
			$result = $this->db->Execute($sql);
			
			if ($row = mysqli_fetch_array($result)){
				$count = $row[0];
			}
		}
		catch(Exception $ex){
			// si existe un error se deshace la transacci&#65533;n
			//throw new Exception("(RecuperarCuentas) " . $ex->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		return $count;
	}
	
	function delete(){
		$this->err_data = "";
		
		$key_names = "";
		$count = 0;
		foreach ($this->the_key as $keys) {
			if ($count > 0)
				$key_names .= ' AND ';
			$key_names .= 'a.'.$keys.'='
				.((($this->GetType($this->columns[$keys]) == 'boolean' 
				|| $this->GetType($this->columns[$keys]) == 'float' 
				|| $this->GetType($this->columns[$keys]) == 'integer' 
				|| $this->GetType($this->columns[$keys]) == 'numeric' 
				|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'")
					.(($this->GetType($this->columns[$keys]) == 'NULL')?'NULL':$this->columns[$keys])
				.((($this->GetType($this->columns[$keys]) == 'boolean' 
				|| $this->GetType($this->columns[$keys]) == 'float' 
				|| $this->GetType($this->columns[$keys]) == 'integer' 
				|| $this->GetType($this->columns[$keys]) == 'numeric' 
				|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'");
			$count++;
		}
		
		$sql = "DELETE a FROM ".$this->db_name." a WHERE ".$key_names.";";
		
		try{
			$this->BeginTransaction();
			
			$this->db->Execute($sql);
			
			$this->Commit();	
		}
		catch(Exception $ex){
			$this->RollBack();
			//throw new Exception("DELETE) " . $e->getMessage());
			$this->err_data = $ex->getMessage();
			return FALSE;
		}
		
		return TRUE;
	}
	
	function update($conditions = null, $set = array(), $from = array()){
		$query = "";
		try{
			$this->BeginTransaction();
			
			if (empty($set))
				$query = $this->assemble_query(true);
			else{
				foreach ($set as $key => $value) {
					if ($count > 0)
						$query .= ', ';
					$query .= $key.'='
						.((($this->GetType($value) == 'boolean' 
						|| $this->GetType($value) == 'float' 
						|| $this->GetType($value) == 'integer' 
						|| $this->GetType($value) == 'numeric' 
						|| $this->GetType($value) == 'NULL'))?'':"'")
							.(($this->GetType($value) == 'NULL')?'NULL':$value)
						.((($this->GetType($value) == 'boolean' 
						|| $this->GetType($value) == 'float' 
						|| $this->GetType($value) == 'integer' 
						|| $this->GetType($value) == 'numeric' 
						|| $this->GetType($value) == 'NULL'))?'':"'");
				}
			}
			
			$key_names = "";
			if (empty($from)){
				$count = 0;
				foreach ($this->the_key as $keys) {
					if ($count > 0)
						$key_names .= ' AND ';
					$key_names .= $keys.'='
						.((($this->GetType($this->columns[$keys]) == 'boolean' 
						|| $this->GetType($this->columns[$keys]) == 'float' 
						|| $this->GetType($this->columns[$keys]) == 'integer' 
						|| $this->GetType($this->columns[$keys]) == 'numeric' 
						|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'")
							.(($this->GetType($this->columns[$keys]) == 'NULL')?'NULL':$this->columns[$keys])
						.((($this->GetType($this->columns[$keys]) == 'boolean' 
						|| $this->GetType($this->columns[$keys]) == 'float' 
						|| $this->GetType($this->columns[$keys]) == 'integer' 
						|| $this->GetType($this->columns[$keys]) == 'numeric' 
						|| $this->GetType($this->columns[$keys]) == 'NULL'))?'':"'");
					$count++;
				}	
			}else{
				foreach ($from as $key => $value) {
					if ($count > 0)
						$key_names .= ' AND ';
					$key_names .= $key.'='
						.((($this->GetType($value) == 'boolean' 
						|| $this->GetType($value) == 'float' 
						|| $this->GetType($value) == 'integer' 
						|| $this->GetType($value) == 'numeric' 
						|| $this->GetType($value) == 'NULL'))?'':"'")
							.(($this->GetType($value) == 'NULL')?'NULL':$value)
						.((($this->GetType($value) == 'boolean' 
						|| $this->GetType($value) == 'float' 
						|| $this->GetType($value) == 'integer' 
						|| $this->GetType($value) == 'numeric' 
						|| $this->GetType($value) == 'NULL'))?'':"'");
				}
			}
			
			$sql = "UPDATE ".$this->db_name." SET ".$query." WHERE ".$key_names;
			
			if (!is_null($conditions)){
				$sql .= ' AND '.$conditions;
			}
			
            //echo $sql;
            
			$result = $this->db->Execute($sql);
			
			$this->Commit();
		}catch(Exception $e)
		{
			$this->RollBack();
			$this->err_data = $e->getMessage();
			return FALSE;
			//throw new Exception("(UPDATE) " . $e->getMessage());
		}
		return TRUE;
				
	}

	function insert(){
		$query = "";
		$result = FALSE;
		
		try{
			$this->BeginTransaction();
			
			$query = $this->assemble_query();
			
			$sql = "INSERT INTO ".$this->db_name." VALUES ( ".$query." ) ";
			
			$this->db->Execute($sql);
			$result = mysqli_insert_id($this->db->link);
			
			$this->Commit();	
		}catch(Exception $e)
		{
			$this->RollBack();
			$this->err_data = $e->getMessage();
			return FALSE;
		}
		return $result;
	}

	private function assemble_query($is_insert = false){
		$query = "";
		$count = 0;
		
		foreach ($this->columns as $key => $value) {
			if ($count > 0)
				$query .= ', ';
			$query .= ($is_insert?$key.'=':"")
				.((($this->GetType($value) == 'boolean' 
				|| $this->GetType($value) == 'float' 
				|| $this->GetType($value) == 'integer' 
				|| $this->GetType($value) == 'numeric' 
				|| $this->GetType($value) == 'NULL'))?'':"'")
					.(($this->GetType($value) == 'NULL')?'NULL':$value)
				.((($this->GetType($value) == 'boolean' 
				|| $this->GetType($value) == 'float' 
				|| $this->GetType($value) == 'integer' 
				|| $this->GetType($value) == 'numeric' 
				|| $this->GetType($value) == 'NULL'))?'':"'");
			$count++;
		}
		
		return $query;
	}	
	
}
?>