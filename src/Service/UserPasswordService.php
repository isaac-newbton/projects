<?php
namespace App\Service;

use App\Doctrine\UuidEncoder;
use Ramsey\Uuid\Uuid;

class UserPasswordService{

	protected const PASSWORD_MIN_LENGTH = 7;
	protected const PASSWORD_MIN_UPPERCASE = 1;
	protected const PASSWORD_MIN_LOWERCASE = 1;

	protected $uuidEncoder;

	public function __construct(UuidEncoder $encoder)
	{
		$this->uuidEncoder = $encoder;
	}

	public function makeRandomPassword(){
		$specialChars = '*-;|@!_$?';
		$uuid = Uuid::uuid4();
		$encoded = $this->uuidEncoder->encode($uuid);
		for($i = 0; $i < rand(1, strlen($encoded)); $i++){
			$index = rand(0, strlen($encoded) - 1);
			$encoded[$index] = $specialChars[rand(0, strlen($specialChars) - 1)];
		}
		return $encoded;
	}

	public function passwordIsValid(string $password){
		$problems = $this->problemsWithPassword($password);
		return empty($problems);
	}

	public function problemsWithPassword(string $password){
		$problems = [];

		if(strlen($password)<self::PASSWORD_MIN_LENGTH) $problems[] = 'password must have at least ' . self::PASSWORD_MIN_LENGTH . ' character(s)';
		if(0<self::PASSWORD_MIN_UPPERCASE){
			$uppercase_matches = [];
			preg_match('/([A-Z])/', $password, $uppercase_matches);
			if(empty($uppercase_matches) || !isset($uppercase_matches[1]) || strlen($uppercase_matches[1])<self::PASSWORD_MIN_UPPERCASE) $problems[] = 'password must have at least ' . self::PASSWORD_MIN_UPPERCASE . ' upper-case character(s)';
		}
		if(0<self::PASSWORD_MIN_LOWERCASE){
			$lowercase_matches = [];
			preg_match('/([a-z])/', $password, $lowercase_matches);
			if(empty($lowercase_matches) || !isset($lowercase_matches[1]) || strlen($lowercase_matches[1])<self::PASSWORD_MIN_LOWERCASE) $problems[] = 'password must have at least ' . self::PASSWORD_MIN_LOWERCASE . ' lower-case character(s)';
		}

		return $problems;
	}

}