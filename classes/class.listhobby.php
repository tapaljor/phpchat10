<?php

/**
 * Description of class
 *
 * @author tcrc
 */

require_once CLASSES . 'class.inter.php';

class listhobby extends inter {

    	private $array = array();
	private $table = 'listhobby';
	private $sql = false;
	private $status = false;
	private $rows = false;

	 /**
	     * 
	     * @return array of the key value paired departments
	     *  Key being the primary key of the department table
	     *  Value being the name of the department
	     * @return boolean false, if no result found
	 */

    	function get($conditions = '') {

		$this->sql = "SELECT DISTINCT * FROM `$this->table` $conditions";
		$this->array = $this->result($this->sql);//calling parent inter method result which handles rest

		return $this->array;
    	}
    	function add( $_data = array() ) {

		return $this->db->insert($this->table, $_data);
    	}
	function update( $array = array() ) {

		return $this->db->update($this->table, $array);
	}
	function delete_file($id = '') {

		return $this->db->delete_file($this->table, $id);
	}
	function delete( $conditions = '') {

		return $this->db->delete($this->table, $conditions);
	}
	function load_more_query($from = '') {

		$this->sql = "SELECT * FROM `$this->table` ORDER BY id DESC LIMIT $from, 5";
		$this->array = $this->result($this->sql);

		return $this->array;
	}
	function match_hash($idh = '') {
		return $this->db->match_hash($this->table, $idh);
	}
}
