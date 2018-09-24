<?php

/**
 * Description of class
 *
 * @author tcrc
 */

require_once CLASSES . 'class.inter.php';

class likedislike extends inter {

	private $table = 'likedislike';//accessible on in this class, no other classes or PHP outside
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

	function get_total_rows($conditions = '') {

		$this->sql = "SELECT * FROM $this->table $conditions";
		$this->num_rows = $this->db->get_num_rows($this->sql);
		return $this->num_rows;
	}
    	function add( $array = array() ) {

		return $this->db->insert($this->table, $array);
    	}
	function delete($conditions = '') {

		return $this->db->delete($this->table, $conditions);
	}
}
