<?php
namespace App\Repository;

use App\Doctrine\UuidEncoder;

trait RepositoryEditUuidFinderTrait{
	/**
	 * @var UuidEncoder
	 */
	protected $uuidEncoder;

	public function findOneByEncodedUuid(string $encoded){
		return $this->findOneBy([
			'editUuid'=>$this->uuidEncoder->decode($encoded)
		]);
	}
}