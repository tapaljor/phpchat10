<?php

/**
 * Description of class
 *
 * @author tcrc
 */

require_once CLASSES . 'class.inter.php';

class user extends inter {

    	private $array = array();
	private $table = 'login';//accessible on in this class, no other classes or PHP outside
	private $sql = false;
	private $status = false;
	private $num_rows = false;

	 /**
	     * 
	     * @return array of the key value paired departments
	     *  Key being the primary key of the department table
	     *  Value being the name of the department
	     * @return boolean false, if no result found
	 */

	function check_login($username = '', $password = '') {

		require_once CLASSES . 'class.db.php';
		require_once CLASSES . 'class.log.php';
		require_once CLASSES . 'class.user.php';
		require_once CLASSES . 'class.utility.php';

		$db = new database();
		$log = new log($db);
		$user = new user($db);

		$this->sql = "SELECT id, username, password, salt FROM `$this->table` WHERE username = '$username'";
		$this->array = $this->result($this->sql);
		foreach($this->array as $rows) {

			if ( $username === $rows["username"] && $rows["password"] === md5(md5($password).$rows["salt"])) {

				$_SESSION["AdminCHATP"] = $username;
				$_SESSION["idCHATP"] = $rows["id"];
				$_SESSION["idhashCHATP"] = md5($rows["id"].md5($_SESSION["tsa_gong"]));

				$_data = array( 
					'id' => $rows["id"],
					'status' => 2
					);
				$user->update($_data);

				$loga = array(
					'which' => 'login',
					'time' => utility::gettime(),
					'logouttime' => 0,
					'user' => $username
					);
				$log->add($loga);

				$this->status = true;
			} 
		}
		return $this->status;
	}
    	function get($conditions = '') {

		$this->sql = "SELECT *, $this->table.id FROM `$this->table` $conditions";
		$this->array= $this->result($this->sql);//calling parent inter method result which handles rest

		return $this->array;
    	}
    	function get_some_fields($fields, $conditions = '') {

		$this->sql = "SELECT $fields FROM `$this->table` $conditions";
		$this->array= $this->result($this->sql);//calling parent inter method result which handles rest

		return $this->array;
    	}
	function delete_file() {

		return $this->status = $this->db->delete_file($this->table, $_SESSION["idCHATP"]);
	}
    	function get_users_username($conditions = '') {

		$this->sql = "SELECT username FROM `$this->table` $conditions";
		$this->users = $this->result($this->sql);//calling parent inter method result which handles rest

		return $this->users;
    	}
	function get_num_rows($conditions = '') {

		$this->sql = "SELECT * FROM $this->table $conditions";
		$this->num_rows = $this->db->get_num_rows($this->sql);
		return $this->num_rows;
	}

    	function add( $array = array() ) {

		return $this->db->insert($this->table, $array);
    	}
	function update($array = array() ) {

		return $this->db->update($this->table, $array);
	}
	function match_hash($hash = '') {

		return $this->db->match_hash($this->table, $hash);
	}
}
