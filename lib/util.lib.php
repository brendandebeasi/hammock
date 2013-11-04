<?

/*UTIL LIBRARY*/

class Util {
	
	static private $_i;
	
	//create a singleton
	static public function singleton() {
        return isset(self::$_i) ? self::$_i : self::$_i = new self();
    }

    function __construct() {
            //do something here
    }
    
    public function sanitize($input) {
	    $clean = strip_tags($input);
	    return $clean;
    }
	
		
	//Encryption Method
	public function encrypt($data) {
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_256, ENC_KEY, $data, MCRYPT_MODE_ECB);
	}
	
	//Decryption Method
	public function decrypt($data) {
		return mcrypt_decrypt(MCRYPT_RIJNDAEL_256, ENC_KEY, $data, MCRYPT_MODE_ECB);
	} 
	
	function base64url_encode($data) { 
	  return rtrim(strtr(base64_encode($data), '+/', '-_'), '='); 
	} 
	
	function base64url_decode($data) { 
	  return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT)); 
	}
	

	function asc2hex ($temp) {
	    $len = strlen($temp);
        $data='';
	    for ($i=0; $i<$len; $i++) $data.=sprintf("%02x",ord(substr($temp,$i,1)));
	    return $data;
	}
	
	function hex2asc($temp) {
	    $len = strlen($temp);
        $data ='';
	    for ($i=0;$i<$len;$i+=2) $data.=chr(hexdec(substr($temp,$i,2)));
	    return $data;
	}
	
	

	
}