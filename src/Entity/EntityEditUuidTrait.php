<?php
namespace App\Entity;

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
}