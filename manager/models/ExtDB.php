<?php
/**************************************************************************
etoFork Content Management System
Copyright (c) 2011 All Rights Reserved
John Carlson - <johncarlson21@gmail.com>
Extended Database Class - adds extra functionality to database calls
such as joins.

/**************************************************************************/

class ExtDB {

	public $_eto; // etomite class variable to be passed
	public $_dbSelect;
	public $_dbFromTable; // main select table
	public $_dbFromFields; // main select table fields
	public $_dbJoin;
	public $_dbJoinFields;
	public $_dbWhere; // query where statement
	public $_dbGroupBy; // group by var
	public $_dbOrder; // select order
	public $_dbLimit;	// query limit
	public $_dbSelectTotal = 0; // total count of rows for a query. usefull if you are using a pagination script and need the total amount of records but want to just get the limited amount of rows.
	public $_dbSelectTotalField = 'id'; // field used for the total select query
	public $_noDBSel = 0; // set to 0 if you don't want to use the db select in the from portion

	// load vars on creation
	function __construct(&$eto){
		$this->_eto = $eto;
	}
	
	// db function

	function select($table=null,$fields=array()){ // can only accept 1 table at a time with fields
		if($table==null){ return false; }
		if(is_array($table)){
			foreach($table as $key=>$val){ // key = table name, val = short name example: array('users'=>'u')
				$this->_dbFromTable[] = $key." as ".$val;
				if(empty($fields) || count($fields)<1){
					$this->_dbFromFields[] = $val.".*";
				}else{
					$fs = array();
					foreach($fields as $f){
						$pos = strpos($f,$val.".");
						if($pos === false){
							$fs[] = $val.".".$f;
						}else{
							$fs[] = $val;
						}
					}
					$this->_dbFromFields[] = implode(",",$fs);
				}
			}
		}else{
			$this->_dbFromTable[] = $table;
			
			if(empty($fields) || count($fields)<1){
				$this->_dbFromFields[] = "*";
			}else{
				$this->_dbFromFields[] = implode(",",$fields);
			}
		}
		return $this;
	}
	
	function leftJoin($join=null,$on='',$fields=array()){ // example leftJoin(array('table_name'=>'t'),'table1.id=table_name.t_id',array('name','id','order'))
		if(empty($join) || $join==null || empty($on)){ return $this; }
		if(is_array($join)){
			foreach($join as $key=>$val){
				$this->_dbJoin[] = "LEFT JOIN ".$this->_eto->db.$key." as ".$val." ON ".$on;
				if(is_array($fields)){
					$fs = array();
					foreach($fields as $f){
						$fs[] = $val.".".$f;
					}
					$this->_dbJoinFields[] = implode(",",$fs);
				}else{
					$this->_dbJoinFields[] = $val.".*";
				}
			}// end foreach
		}else{
			$this->_dbJoin[] = "LEFT JOIN ".$this->_eto->db.$join." ON ".$on;
			if(is_array($fields)){
				$this->_dbJoinFields[] = implode(",",$fields);
			}else{
				$this->_dbJoinFields[] = "*";
			}
		}
		$this->_dbFromFields[] = implode(",",$this->_dbJoinFields);
		return $this;
	}
	
	function where($where=array()){
		if(!empty($where)){
			if(is_array($where)){
				$this->_dbWhere += $where;
			}else{
				$this->_dbWhere[] = $where;
			}
		}
		return $this;
	}
	
	function groupby($groupby){
		if(!empty($groupby)){
			$this->_dbGroupBy = $groupby;
		}
		return $this;
	}
	
	function order($order=array()){
		if(!empty($order)){
			if(is_array($order)){
				$this->_dbOrder += $order;
			}else{
				$this->_dbOrder[] = $order;
			}
		}
		return $this;
	}
	
	function limit($limit=''){
		if(!empty($limit)){
			$this->_dbLimit = $limit;
		}
		return $this;
	}
	
	function setTotalCountField($field="id"){
		$this->_dbSelectTotalField = $field;
		return $this;
	}
	
	function fetchAll(){ // fetch all rows
		$sql = $this->_dbSelect;
		$result = $this->_eto->dbQuery($sql);
		if($result && count($result)>0){
			// put result into an array
			$rArr = array();
			while($r = @mysql_fetch_assoc($result)){
				$rArr[] = $r;
			}
			return $rArr;
		}
		return false;
	}
	
	function fetch(){ // fetch rows
		$sql = $this->_dbSelect;
		
		$result = $this->_eto->dbQuery($sql);
		
		if($result && count($result)>0){
			$r = @mysql_fetch_assoc($result);
			return $r;
		}
		return false;
  }
  
  function create($total=false){ // set $total to true if you want to get a total of rows without the limit clause
		// need to build the select statement here
		if(empty($this->_dbFromTable) || empty($this->_dbFromFields)){ return false; }
		
		// begin select and add fields to select
		$sql = "SELECT "; // main select
		if(is_array($this->_dbFromFields)){
			$sql .= implode(",",$this->_dbFromFields)." ";
		}else{
			$sql .= $this->_dbFromFields." ";
		}
		$sql2 = "SELECT ".$this->_dbSelectTotalField." "; // select used for total
		// add main select table
		if(is_array($this->_dbFromTable)){
			$tbls = array();
			foreach($this->_dbFromTable as $t){
				if($this->_noDBSel==1){
					$tbls[] = $t;
				}else{
					$tbls[] = $this->_eto->db.$t;
				}
			}
			$sql .= "FROM ".implode(",",$tbls);
			$sql2 .= "FROM ".implode(",",$tbls);
		}else{
			if($this->_noDBSel==1){
				$sql .= "FROM ".$this->_dbFromTable;
				$sql2 .= "FROM ".$this->_dbFromTable;
			}else{
				$sql .= "FROM ".$this->_eto->db.$this->_dbFromTable;
				$sql2 .= "FROM ".$this->_eto->db.$this->_dbFromTable;
			}
		}
		
		// add join if there is one
		if(!empty($this->_dbJoin) && count($this->_dbJoin)>0){
			$sql .= " ".implode(" ",$this->_dbJoin);
			$sql2 .= " ".implode(" ",$this->_dbJoin);
		}
		
		if(!empty($this->_dbWhere) && count($this->_dbWhere)>0){
			$sql .= " WHERE ".implode(" AND ",$this->_dbWhere);
			$sql2 .= " WHERE ".implode(" AND ",$this->_dbWhere);
		}
		
		if(!empty($this->_dbGroupBy)){
			$sql .= " GROUP BY ".$this->_dbGroupBy;
			$sql2 .= " GROUP BY ".$this->_dbGroupBy;
		}
		
		if(!empty($this->_dbOrder)){
			$sql .= " ORDER BY ".implode(",",$this->_dbOrder);
		}
		
		if(!empty($this->_dbLimit)){
			$sql .= " LIMIT ".$this->_dbLimit;
		}
		
		if($total){ // set the total for the query
			$res = $this->_eto->dbQuery($sql2);
			$this->_dbSelectTotal = $this->_eto->recordCount($res);
		}
		
		$this->_dbSelect = $sql;
		return $this;
  }
  
  function __toString(){
  	return $this->_dbSelect;
  }
  
  // copy all sections of 1 db instance to a new one. to speed up some processes
  function __duplicate($db){
  	$this->_dbFromFields = $db->_dbFromFields;
	$this->_dbFromTable = $db->_dbFromTable;
	$this->_dbJoin = $db->_dbJoin;
	$this->_dbWhere = $db->_dbWhere;
	$this->_dbGroupBy = $db->_dbGroupBy;
	$this->_dbOrder = $db->_dbOrder;
	$this->_dbLimit = $db->_dbLimit;
	return $this;
  }
  
  // copy all sections of 1 db instance to a new one minus the limit function
  // can also be done just by using the normal __duplicate function and then setting the limit to empty
  function __duplicateNoLimit($db){
  	$this->_dbFromFields = $db->_dbFromFields;
	$this->_dbFromTable = $db->_dbFromTable;
	$this->_dbJoin = $db->_dbJoin;
	$this->_dbWhere = $db->_dbWhere;
	$this->_dbGroupBy = $db->_dbGroupBy;
	$this->_dbOrder = $db->_dbOrder;
	return $this;
  }

}// end class


?>
