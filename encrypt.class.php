<?php
	class encrypt {
		protected $_cipher = "AES-128-CBC",
							$_key,
							$_ivlen,
							$_iv,
							$_options = 0,
							$_salt = 'i010.com';

		public function __construct($privateKey) {
			
			if(!function_exists('openssl_encrypt') || !function_exists('openssl_decrypt')) {
				die('Encrypt function not exists');
			}

			$this->_key = decbin(ord($privateKey));
			$this->_ivlen = openssl_cipher_iv_length($this->_cipher);
			$this->_iv = openssl_random_pseudo_bytes($this->_ivlen);
			if (!in_array($this->_cipher, openssl_get_cipher_methods())) {
				die('Encrypt method not exists');
			}
		}

		public function put($string) {
			return $ciphertext = $this->_iv . openssl_encrypt($string . $this->_salt, $this->_cipher, $this->_key, $this->_options, $this->_iv);
		}
		
		public function get($encrypted) {
			$iv = substr($encrypted, 0, $this->_ivlen);
			$encrypted = substr($encrypted, $this->_ivlen);
			return substr(openssl_decrypt($encrypted, $this->_cipher, $this->_key, $this->_options, $iv),0, -strlen($this->_salt));			
		}
	}
	
  //HOW TO USE
	$encrypt = new encrypt('private_key');
	
	echo 'Value to be stored to database: '. $string = $encrypt->put('michael@i010.com');
	echo '<br><br>';
	echo 'Length of encrypted = '.strlen($string).'<br>';
	echo '<br>';
	echo 'Decrypted:' . $encrypt->get($string);
?>
