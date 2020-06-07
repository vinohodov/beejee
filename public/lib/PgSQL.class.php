<?php

namespace lib;

class PgSQL {

	public $dbh;
	public $fields = 0;
	public $rows = 0;

	private $sql = array();

	public function __construct($host, $port, $database, $user, $pass) {

		$this->dbh = @pg_connect("host=".$host." port=".$port." dbname=".$database." user=".$user." password=".$pass."");
		try {
			if(!$this->dbh) {
				throw new Exception('connect(): unable to establish database connection');
			}
		} catch (Exception $e) {
			self::error_string($e);
		}
		return $this->dbh;
	}

	public function close() {
		try {
			if(!@pg_free_result($this->dbh))
				throw new Exception('close(): unable to free database result');
			if(!@pg_close($this->dbh))
				throw new Exception('close(): unable to close connection to database');
		} catch (Exception $e) {
			self::error_string($e);
		}
	}

	public function select($qs) {
		$res = array(); 
		$i = 0;
		try {
			$query = @pg_query($this->dbh, $qs);
			if(!$query){
                echo ('select(): ' .$qs);
            } else {
                while($this->row = pg_fetch_assoc($query)) {
                    $res[$i] = $this->row;
                    $i++;
                }
            }
		} catch (Exception $e) {
            $res = array();
			self::error_string($e);
            return false;
		}
		$this->fields = pg_num_fields($query); 
		$this->rows = pg_num_rows($query); 
		return $res;
	}

	public function select_row($qs) {
		$res = array();
		$i = 0;
		try {
			$query = @pg_query($this->dbh, $qs);
			if(!$query) {
                throw new Exception('select_row(): ' .$qs);
            } else {
                while($this->row = pg_fetch_row($query)) {
                    $res[$i] = $this->row;
                    $i++;
                }
            }
		} catch (Exception $e) {
            $res = array();
			self::error_string($e);
            return false;
		}
		$this->fields = pg_num_fields($query);
		$this->rows = pg_num_rows($query);
		return $res;
	}

	public function select_simple($qs) { 
		$res = array(); 
		try {
			$query = @pg_query($this->dbh, $qs);
			if(!$query) {
                throw new Exception('select_simple(): ' .$qs);
            } else {
                $res = pg_fetch_assoc($query);
            }
		} catch (Exception $e) {
			$res = array();
			self::error_string($e);
			return false;
		}
		$this->fields = pg_num_fields($query); 
		$this->rows = pg_num_rows($query); 
		return $res;
	}

	public function sql_add($qs) {	
		array_push($this->sql, $qs);
	}

	public function clear_sql_arr() {			
		unset($this->sql);
		$this->sql = array();
	}

	public function transaction() {
		$ok = 1;
		self::begin();
		try {
			while ($sql = array_shift($this->sql)) {
				$query = @pg_query($this->dbh, $sql);
				$errors[] = pg_last_error($this->dbh);
				if(!$query){
					self::rollback();
					throw new Exception("transaction(): the transaction faild");
				}
			}
		} catch (Exception $e) {
			$err = implode('<br />', $errors);
			self::error_string($e,$err);
			return false;
		}
		self::commit();
		self::clear_sql_arr();
		return true;
	}

	public function begin() {
		return pg_query($this->dbh, 'BEGIN') ? true : false;
	}

	public function commit() {
		return pg_query($this->dbh, 'COMMIT') ? true : false;
	}

	public function rollback() {
		return pg_query($this->dbh, 'ROLLBACK') ? true : false;
	}

	public function prepare($qs, $stmt_name) {
		try {
			if(!@pg_connection_busy($this->dbh)) {
				try {
					if(!@pg_send_prepare($this->dbh, $stmt_name, $qs))
						throw new Exception ('prepare(): send_prepare failed');
					if(!@pg_get_result($this->dbh))
						throw new Exception ('prepare(): get_result failed');
				} catch (Exception $e) {
					throw $e;
				}
			} else {
				throw new Exception ('prepare(): connection busy ');
			}
		} catch (Exception $e) {
			self::error_string($e);
			return false;
		}
		return true;
	}

	public function execute($stmt_name, array $values) {
		try {
			if(!@pg_connection_busy($this->dbh)) {
				$res = @pg_execute($this->dbh, $stmt_name, $values);
				if(!$res) throw new Exception ('execute(): execute failed');
			} else {
				throw new Exception ('execute(): connection is busy ');
			}
		} catch (Exception $e) {
			self::error_string($e);
			return false;
		}
		return $res;
	}

	public function fetch($ressource_id) {
		$res = array();
		try {
			$res = @pg_fetch_all($ressource_id);
			if(!$res) throw new Exception ('fetch(): fetch failed');
		} catch (Exception $e) {
			self::error_string($e);
			return false;
		}
		return $res;
	}

	public function get_fields() {
		return $this->fields;
	}

	public function get_rows() {
		return $this->rows;
	}

	public function ping() {
		return (pg_ping($this->dbh)) ? true : false;
	}

	public function dbh() {
		return $this->dbh;
	}

	static public function quote($str) {
		return pg_escape_string(stripslashes($str));
	}

	private function error_string($e, $errors = null) {
		$errors = (!empty($errors)) ? $errors : pg_last_error($this->dbh);
		echo "<table cellpadding=\"4\" cellspacing=\"4\" width=\"98%\" 
					 style=\"border: 1px solid #990000;background-color:#eee;
					 margin:10px;\">
			  <tr>
				<td><b style=\"color:#990000;\"><u>db error:</u><b></td>
				<td>".$e->getMessage()."</td>
			  </tr>
			  <tr>
			  	<td><b style=\"color:#990000;\"><u>file:</u><b></td>
				<td>".$e->getFile()." [line ".$e->getLine()."]</td>
			  </tr>
			  <tr>
			  	<td><b style=\"color:#990000;\"><u>db message:</u><b></td>
				<td>".$errors."</td>
			  </tr>
			</table>";
	}
}

?>