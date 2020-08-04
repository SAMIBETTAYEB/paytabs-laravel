<?php 

namespace Damas\Paytabs;

define("TESTING", "https://localhost:8888/paytabs/apiv2/index");
define("AUTHENTICATION", "https://www.paytabs.com/apiv2/validate_secret_key");
define("PAYPAGE_URL", "https://www.paytabs.com/apiv2/create_pay_page");
define("VERIFY_URL", "https://www.paytabs.com/apiv2/verify_payment");

class Paytabs {

	protected $merchant_email;
	protected $secret_key;

	public function __construct($merchant_email, $secret_key)
	{
		$this->merchant_email = $merchant_email;
		$this->secret_key = $secret_key;
	}

	public function authentication(){
		$obj = json_decode($this->runPost(AUTHENTICATION, array("merchant_email"=> $this->merchant_email, "secret_key"=>  $this->secret_key)));
        if (is_object($obj) and $obj->result == "valid") {
            return true;
        }
        return false;
	}

	public function create_pay_page($values) {
		$values['merchant_email'] = $this->merchant_email;
		$values['secret_key'] = $this->secret_key;
		$values['ip_customer'] = $_SERVER['REMOTE_ADDR'];
		$values['ip_merchant'] = isset($_SERVER['SERVER_ADDR'])? $_SERVER['SERVER_ADDR'] : '::1';
		return json_decode($this->runPost(PAYPAGE_URL, $values));
	}

	public function send_request(){
		$values['ip_customer'] = $_SERVER['REMOTE_ADDR'];
		$values['ip_merchant'] = isset($_SERVER['SERVER_ADDR'])? $_SERVER['SERVER_ADDR'] : '::1';
		return json_decode($this->runPost(TESTING, $values));
	}


	public function verify_payment($payment_reference){
		$values['merchant_email'] = $this->merchant_email;
		$values['secret_key'] = $this->secret_key;
		$values['payment_reference'] = $payment_reference;
		return json_decode($this->runPost(VERIFY_URL, $values));
	}

	protected function runPost($url, $fields) {
		$fields_string = "";
		foreach ($fields as $key => $value) {
			$fields_string .= $key . '=' . $value . '&';
		}
		rtrim($fields_string, '&');
		$ch = curl_init();
		$ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

		$ip_address = array(
			"REMOTE_ADDR" => $ip,
			"HTTP_X_FORWARDED_FOR" => $ip
		);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $ip_address);
		curl_setopt($ch, CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_REFERER, 1);

		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}
