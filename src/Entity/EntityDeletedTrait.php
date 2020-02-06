<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

trait EntityDeletedTrait{

	/**
	 * @var bool
	 * @ORM\Column(type="boolean", nullable=false, options={"default":false})
	 */
	protected $deleted;

	public function getDeleted(): bool{
		return $this->deleted;
	}

	public function setDeleted(bool $deleted){
		$this->deleted = $deleted;
	}
}