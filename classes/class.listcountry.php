<?php

/**
 * Description of class
 *
 * @author tcrc
 */

require_once CLASSES . 'class.inter.php';

class listcountry extends inter {

    	private $array = array();
	private $table = 'listcountry';
	private $sql = false;
	private $status = false;

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
	function delete( $conditions = '') {

		return $this->db->delete($this->table, $conditions);
	}
	function match_hash($idh = '') {

		return $this->db->match_hash($this->table, $idh);
	}
}
