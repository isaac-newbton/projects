<?php
namespace App\Twig;

use App\Doctrine\UuidEncoder;
use Ramsey\Uuid\UuidInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class UuidExtension extends AbstractExtension{
	/**
	 * @var UuidEncoder
	 */
	protected $uuidEncoder;

	public function __construct(UuidEncoder $encoder)
	{
		$this->uuidEncoder = $encoder;
	}

	public function getFilters()
	{
		return [
			new TwigFilter('encode_uuid', [$this, 'encodeUuid'])
		];
	}

	public function encodeUuid(UuidInterface $uuid){
		return $this->uuidEncoder->encode($uuid);
	}
}