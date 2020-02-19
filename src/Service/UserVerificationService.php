<?php
namespace App\Service;

use App\Doctrine\UuidEncoder;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Mailgun\Mailgun;
use Twig\Environment as TwigEnvironment;

class UserVerificationService{
	/**
	 * @var UserEmailService
	 */
	protected $userEmailService;

	/**
	 * @var EntityManagerInterface
	 */
	protected $entityManager;

	/**
	 * @var TwigEnvironment
	 */
	protected $twigEnvironment;

	public function __construct(UserEmailService $userEmailService, EntityManagerInterface $entityManager, TwigEnvironment $twigEnvironment){
		$this->userEmailService = $userEmailService;
		$this->entityManager = $entityManager;
		$this->twigEnvironment = $twigEnvironment;
	}

	public function sendVerification(User $user){
		$user->setEmailVerified(false);
		$this->entityManager->persist($user);
		$this->entityManager->flush();
		return $this->twigEnvironment->render('email/user/email_verification.html.twig', [
			'user'=>$user
		]);
	}

}