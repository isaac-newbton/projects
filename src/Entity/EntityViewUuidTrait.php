<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

trait EntityViewUuidTrait{

	/**
	 * @var UuidInterface
	 * @ORM\Column(type="uuid", unique=true)
	 */
	protected $viewUuid;

	public function getViewUuid(): UuidInterface{
		return $this->viewUuid;
	}

	public function setViewUuid(UuidInterface $uuid){
		$this->viewUuid = $uuid;
	}
}