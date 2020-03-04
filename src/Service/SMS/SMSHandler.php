<?php

namespace App\Service\SMS;

use Exception;
use Twilio\Rest\Client;

class SMSHandler
{
	private $sid;
	private $token;
	private $twilio;
	public function __construct()
	{
		$this->sid = "AC14206911c468bddc76512412bcba268b";
		$this->token = "5a9216504a108098eb45324b58679fec";
		$this->twilio = new Client($this->sid, $this->token);
	}
	public function sendSMS($recipient, string $message)
	{
		try {
			$response = $this->twilio->messages
				->create(
					$recipient, // to
					array(
						"body" => $message,
						"from" => "+19097428570"
					)
				);
		} catch (Exception $e) {
			$response = $e;
		}
		return $response;
	}
}
