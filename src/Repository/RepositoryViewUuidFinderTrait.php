<?php
namespace App\Repository;

use App\Doctrine\UuidEncoder;

trait RepositoryViewUuidFinderTrait{
	/**
	 * @var UuidEncoder
	 */
	protected $uuidEncoder;

	public function findOneByEncodedUuid(string $encoded){
		return $this->findOneBy([
			'viewUuid'=>$this->uuidEncoder->decode($encoded)
		]);
	}
}