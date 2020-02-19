<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class SecurityController extends AbstractController{
	/**
	 * @Route("/login", name="login", methods={"POST"})
	 */
	public function login(Request $request){
		$user = $this->getUser();

		return $this->json([
			'username'=>$user->getUsername(),
			'roles'=>$user->getRoles()
		]);
	}

	/**
	 * @Route("/api/v1/auth", name="react_checker")
	 */
	public function reactSimpleAuth(AuthorizationCheckerInterface $auth){
		// TODO: this should return something unique that cant be spoofed on the client side
		return new JsonResponse($auth->isGranted('IS_AUTHENTICATED_FULLY'));
	}
}