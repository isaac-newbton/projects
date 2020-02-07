<?php
namespace App\Entity;

use App\Doctrine\UuidEncoder;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

trait EntityEditUuidTrait{

	/**
	 * @var UuidInterface
	 * @ORM\Column(type="uuid", unique=true)
	 */
	protected $editUuid;

	public function getEditUuid(): UuidInterface{
		return $this->editUuid;
	}

	public function setEditUuid(UuidInterface $uuid){
		$this->editUuid = $uuid;
	}

	public function getEncodedEditUuid(){
		$encoder = new UuidEncoder();
		return $encoder->encode($this->editUuid);
	}
}