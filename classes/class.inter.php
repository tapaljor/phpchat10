<?php

/**
 * Description of class
 *
 * @author tcrc
 */

class inter {

	protected $db = false;
	private $array = array();

	function __construct($db) {
		$this->db = $db;
	}	

	 /**
	     * 
	     * @return array of the key value paired departments
	     *  Key being the primary key of the department table
	     *  Value being the name of the department
	     * @return boolean false, if no result found
	 */

    	protected function result($sql = '' ) {

		$this->db->select($sql);
		$this->array = $this->db->get_results();

		return $this->array;
    	}
}
