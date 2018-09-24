<?php

class database {

	private $con = false;
	private $sql = false;
	private $status = false;
	private $result = array();
	private $id = false;
	private $num_rows = false;

    	function __construct() {
       	 	$this->con = mysqli_connect(LOCALHOST, USERNAME, PASSWORD, DBNAME);
    	}
	function delete_file($table = '', $id = '') {
	
		$this->sql = "SELECT image FROM `$table` WHERE id = $id";
	        $this->select($this->sql);
	        $this->result = $this->get_results();
	
	        foreach ($this->result as $rows) {
	
	            $delete_file = $_SERVER["DOCUMENT_ROOT"] . "/phpchat10/upload/$rows[image]";
	            unlink($delete_file);
	        }
	}
    	function delete($table = '', $id = '') {

        	$this->sql = "DELETE FROM `$table` WHERE id = $id";
		$result = mysqli_query($this->con, "SELECT * FROM `$table` WHERE id = $id");
		while($rows1 = mysqli_fetch_assoc($result)) {
			$what = json_encode($rows1);
		}
		
		//Creating for delete log
		$nowstamp = $this->get_current_timestamp();
		$this->sql = "INSERT INTO `log`(time, tablewa, cmd, cmd_user, whoseid, what) VALUES ('$nowstamp', '$table', '2', '$_SESSION[AdminTEACHING]', 
		'$id', '$what')";
		$this->query($this->sql, "execute");
		//LOG creating complete

        	$this->status = $this->query($this->sql, "execute");
        	return $this->status;
    	}

    	function select($sql = '') {

       		$this->query($sql, "select");
    	}

    	function get_results() {

       		$array = array();
        	while ($rows = mysqli_fetch_assoc($this->resource)) {
            		$array[] = $rows;
        	}
        	return $array;
    	}
    	function get_num_rows($sql =  '') {
		
		$this->num_rows = $this->query($sql, "num_rows");
        	return $this->num_rows;
    	}

    	function update($table = '', $_data = array() ) {

		//keeping logs of update
		$what = $this->get_array_difference($_data, $_SESSION["previousARRAY"]);
		$nowstamp = $this->get_current_timestamp();
		$this->sql = "INSERT INTO `log`(time, tablewa, cmd, cmd_user, whoseid, what) VALUES ('$nowstamp', '$table', '1', '$_SESSION[AdminCHATP]', 
			'$_data[id]', '$what')";
		$this->query($this->sql, "execute");
		//LOG creating complete

		$this->sql = $this->create_update_sql($table, $_data);
        	return $this->status = $this->query($this->sql, "execute");
    	}
	function execute_only($sql = '') {

		return $this->status = $this->query($sql, "execute");
	}

    	function insert($table = '', $_data = array()) {

       		$this->sql = $this->create_insert_sql($table, $_data);
        	$this->status = $this->query($this->sql, "execute");

        	return $this->status;
    	}
    	private function query($sql = '', $type = '') {

        	switch ($type) {

            		case 'execute':
                		return $this->status = mysqli_query($this->con, $sql);
                		break;
            		case 'select':
                		$this->resource = mysqli_query($this->con, $sql);
                		break;
			case 'num_rows':
				$this->resource = mysqli_query($this->con, $sql);
				$this->num_rows = mysqli_num_rows($this->resource);
				return $this->num_rows;
				break;
        	}
    	}

    	private function create_insert_sql($table = '', $_data = array()) {

        	$_data = $this->clean_array($_data);

        	$this->status = $fields = $values = '';
        	$count = 0;

        	foreach ($_data as $field => $value) {

	                if ($count == 0) {
	                 	$fields .= "`$field`";
	                    	$values .= "'$value'";
	                    	$count++;
	                } else {
	               		$fields .= ", `$field`";
	                	$values .= ", '$value'";
	            	}
        	}
        	$this->sql = "INSERT INTO `$table` ($fields) VALUES ($values)";

        	return $this->sql;
    	}
	private function create_update_sql($table = '', $_data = array()) {

		$this->sql = false;//Resetting private propery $sql to null
		$count = 0;
		foreach($_data as $field => $value) {
			
			if( $field != 'id' && $field != 'idh') {

				if ( $count == 0) {
					$this->sql .= " $field = '$value'";
					$count++;
				} else {
					$this->sql .= ", $field = '$value'";
				}
			}
		}
		$this->sql = "UPDATE `$table` SET $this->sql WHERE id = $_data[id]";
		return $this->sql;
	}
    	function upload_image() {

	        $uploaddir = $_SERVER['DOCUMENT_ROOT'] . "/phpchat10/upload/";
	        $filename = basename($_FILES['image']['name']);

	        if ($filename != '') {
	
	            $filesize = $_FILES["image"]["size"];
	            $this->check_extension($filename);
	            $this->check_file_size($filesize);

		    /*Checking file type, extension can be anything, but file contents are dangerous */
		    $file_type = getimagesize($_FILES["image"]["tmp_name"]);
		    $this->check_file_type($file_type["mime"]);
                    /* ## end of file type checking */
		
	            $filename = $this->rename_file($filename);
	
	            $uploadfile = $uploaddir . $filename;
	            if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadfile)) {
	                return $filename;
	            } else {
	                die('<div id="file_error">Image upload failed</div>');
	            }
	        }
	        return $filename;
    	}
    	function rename_file($filename) {

	        if ($filename != '') {
	            $filename = md5($filename . utility::gettime());
	        }
	        return $filename;
    	}

    	private function check_file_type($file_type) {

        	if (!preg_match("/jpeg|JPEG|jpg|JPG|png|PNG|gif|GIF/", $file_type)) {
			die('<div id="file_error">Apologies, please put proper image file</div>');
		}
    	}
    	private function check_file_size($filesize) {

	        if ($filesize / 1000000 > 1) {
	            die('<div id="file_error">File size exceeded. Please upload photo less than 1MB.</div>');
	        }
    	}

    	private function check_extension($filename) {

        	if (!preg_match("/.(jpeg|JPEG|jpg|JPG|png|PNG|gif|GIF)$/i", $filename)) {
            		die('<div id="file_error">File extension error</div>');
        	}
    	}

    	function match_hash($table_name, $idh) {

	        $this->sql = "SELECT id FROM `$table_name`";
	        $this->select($this->sql);
	        $this->result = $this->get_results();
	
	        foreach ($this->result as $rows) {

	            if ($idh === md5($rows["id"].md5($_SESSION["tsa_gong"])) ) {

	            	$this->id = $rows["id"];
			break;
	            }
	        }
		//Checking whether id is numeric or not, as it shouldn't be other than alphnumeric 
		utility::is_numeric($this->id);
	        return $this->id;
    	}

    	function clean_array($array) {

	        //As name suggests it cleans every array include POST & GET
	
	        foreach ($array as $key => $value) {

	        	$cleaned_value = mysqli_real_escape_string($this->con, $value);
		       	$cleaned_value = trim($cleaned_value);
		        $cleaned_value = str_replace(';', '', $cleaned_value);
		        $cleaned_value = str_replace('>', '', $cleaned_value);
		        $cleaned_value = str_replace('<', '', $cleaned_value);
		        $cleaned_value = str_replace('(', '', $cleaned_value);
		        $cleaned_value = str_replace(')', '', $cleaned_value);
		        $cleaned_value = str_replace('=', '', $cleaned_value);
		        $cleaned_value = htmlspecialchars($cleaned_value);//it encodes some codes that are not suppose to enter
		        $array[$key] = $cleaned_value;
	        }
	        return $array;
    	}
	private function get_array_difference($array = array(), $array2 = array()) {
		
		$diffa = array_diff($array, $array2);
		unset($diffa["enteredby"]);
		unset($diffa["registerdate"]);
		unset($diffa["idh"]);
		return $name  = json_encode($diffa);
	}
	private function get_current_timestamp() {

		$this->nowstamp = strtotime(date('Y-m-d H:i:s'));
		return $this->nowstamp;
	}
	function close_db() {

		mysqli_close($this->con);
	}
}

