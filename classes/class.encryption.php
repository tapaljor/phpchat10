<?php
class encryption {

	private $secret_key = false;
	private $secret_iv = 'IOH$@696#DFGkhdil@#DD))&';
	private $key = false;
	private $iv = false;
	private $output = false;
	private $encrypt_method = "AES-256-CBC";
	
	function encrypt( $string = '', $secret_key = '') {

		$this->secret_key = $secret_key;
		$this->key = hash( 'sha256', $this->secret_key );
		$this->iv = substr( hash( 'sha256', $this->secret_iv ), 0, 16 );
	
		$this->output = base64_encode( openssl_encrypt( $string, $this->encrypt_method, $this->key, 0, $this->iv ) );
		return $this->output;
	}
	function decrypt( $string = '', $secret_key = '') {

		$this->secret_key = $secret_key;
		$this->key = hash( 'sha256', $this->secret_key );
		$this->iv = substr( hash( 'sha256', $this->secret_iv ), 0, 16 );
	
		$this->output = openssl_decrypt( base64_decode( $string ), $this->encrypt_method, $this->key, 0, $this->iv );
		return $this->output;
	}
}
