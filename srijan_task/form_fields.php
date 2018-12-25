<?php
/**
 * Creation of Form fields
 */

//use Srijan\EncryptFactory;

if (isset($_POST['word_enc']) && $_POST['word_enc'] != "") {
	
	$encrypt = EncryptFactory::create();
	$data = array();
	$data['encrypt'] = $encrypt->makeEncrypt($_POST['word_enc']);
	$data['decrypt'] = $encrypt->makeDecrypt($data['encrypt']);
    echo json_encode($data);exit;
}



class BaseProb
{
  	private $ivlen;
  	private $iv;
  	private $key;
  	private $cipher;
  	private $original_plaintext;
	
	public function __construct()
    {
        $this->ivlen = openssl_cipher_iv_length("AES-128-CBC");
        $this->iv = openssl_random_pseudo_bytes($this->ivlen);
        $this->key = base64_encode(openssl_random_pseudo_bytes(32));
        $this->cipher = "AES-128-CBC";
    }
	public function makeEncrypt($text_to_encrypted)
    {
		$ciphertext_raw = openssl_encrypt($text_to_encrypted, $this->cipher, $this->key, $options=OPENSSL_RAW_DATA, $this->iv);
		$hmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
		$ciphertext = base64_encode( $this->iv.$hmac.$ciphertext_raw );
		return $ciphertext;
    }
	public function makeDecrypt($ciphertext) {
		$c = base64_decode($ciphertext);
		$iv = substr($c, 0, $this->ivlen);
		$hmac = substr($c, $this->ivlen, $sha2len=32);
		$ciphertext_raw = substr($c, $this->ivlen+$sha2len);
		$original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $this->key, $options=OPENSSL_RAW_DATA, $this->iv);
		$calcmac = hash_hmac('sha256', $ciphertext_raw, $this->key, $as_binary=true);
		if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
		{
		return $original_plaintext."\n";
		}
	}
}

class EncryptFactory
{
    public static function create()
    {
        return new BaseProb();
    }
}
?>