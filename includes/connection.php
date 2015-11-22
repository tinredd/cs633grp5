<?php
class Database extends mysqli {
	function __construct() {
		$this->connect(DB_HOST,DB_USER,DB_PASS,DB_NAME,DB_PORT);
	}

	function get_primary($table) {
		$sql="SHOW INDEX FROM $table WHERE Key_name='PRIMARY'";
		$row=$this->fetch_row($sql);

		return $row['Column_name'];
	}

	function get_fields($table) {
		$returnA=array();

		$sql="SHOW COLUMNS FROM $table";
		$rows=$this->fetch_rows($sql);

		foreach ($rows as $row) $returnA[]=$row['Field'];

		return $returnA;
	}

	function fetch_value($sql) {
		$query=$this->query($sql)->fetch_array();

		return $query[0];
	}

	function fetch_row($sql) {
		$query=$this->query($sql);
		$row=$query->fetch_assoc();

		return $row;
	}

	function fetch_rows($sql) {
		$returnA=array();

		$query=$this->query($sql);
		while ($row=$query->fetch_assoc()) $returnA[]=$row;

		return $returnA;
	}

	function fetch_by_primary($table,$id=0) {
		$sql="SELECT * FROM $table WHERE ".$this->get_primary($table)."=$id";

		$row=$this->fetch_row($sql);

		return $row;
	}

	function insert($table,$fieldsA=array()) {
		if (count($fieldsA)==0) { return 0; exit(); }

		$columnsA=$this->get_fields($table);

		$errors=0;
		foreach ($fieldsA as $key=>$value) if (!in_array($key, $columnsA)) $errors++;

		if ($errors>0) { return 0; exit(); }

		$updatesA=array(); foreach ($fieldsA as $key=>$value) $updatesA[]="$key=$value";
		$sql="INSERT INTO $table SET ".implode(',',$updatesA);

		$result=$this->query($sql);

		return $this->insert_id;
	}

	function update($table,$id=0,$fieldsA=array()) {
		if (count($fieldsA)==0) { return 0; exit(); }

		$columnsA=$this->get_fields($table);

		$errors=0;
		foreach ($fieldsA as $key=>$value) if (!in_array($key, $columnsA)) $errors++;

		if ($errors>0) { return 0; exit(); }

		$updatesA=array(); foreach ($fieldsA as $key=>$value) $updatesA[]="$key=$value";
		$sql="UPDATE $table SET ".implode(',',$updatesA)." WHERE ".$this->get_primary($table)."=$id";

		$result=$this->query($sql);
	}
}
?>