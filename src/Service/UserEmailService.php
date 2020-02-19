<?php
namespace App\Service;

use App\Entity\User;
use Mailgun\Mailgun;

class UserEmailService{
	/**
	 * @var Mailgun
	 */
	protected $mailgun;

	/**
	 * @var string
	 */
	protected $mailgunDomain;

	/**
	 * @var string
	 */
	protected $appName;

	public function __construct(string $mailgunApiKey, string $mailgunDomain, string $appName){
		$this->mailgunDomain = $mailgunDomain;
		$this->mailgun = Mailgun::create($mailgunApiKey);
		$this->appName = $appName;
	}

	public function emailUser(User $user, $params){
		if(!$email = $user->getEmail()){
			throw new \Exception('user has no email');
		}
		$params['to'] = "$this->appName user <$email>";
		return $this->send($params);
	}

	public function send($params){
		$sent = $this->mailgun->messages()->send($this->mailgunDomain, $params);
		return $sent;
	}

}