<?php
namespace App\Repository;

use App\Doctrine\UuidEncoder;

trait RepositoryEditUuidFinderTrait{
	/**
	 * @var UuidEncoder
	 */
	protected $uuidEncoder;

	public function findOneByEncodedEditUuid(string $encoded){
		return $this->findOneBy([
			'editUuid'=>$this->uuidEncoder->decode($encoded)
		]);
	}
}