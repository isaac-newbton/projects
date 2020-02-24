<?php
namespace App\Controller;

use App\Doctrine\UuidEncoder;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

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
	 * @Route("/api/v1/login", methods={"POST"})
	 */
	public function apiLogin(Request $request, UuidEncoder $encoder)
    {
        $user = $this->getUser();
		if ($user){
			return $this->json([
				'username' => $user->getUsername(),
				'encodedUuid' => $encoder->encode($user->getUuid()),
				'roles' => $user->getRoles(),
			]);
		}
	
		return $this->json([
			'error' => 'user is not authenticated'
		]);
    }
	
	/**
     * @Route("/api/v1/logout", name="api_logout", methods={"GET"})
     */
    public function logout()
    {
		$this->get('security.token_storage')->setToken(null);
		$this->get('session')->invalidate();
		return new JsonResponse(['success'], 200);
	}
	
	/**
	 * @Route("/api/v1/auth", name="react_checker")
	 */
	public function reactSimpleAuth(UuidEncoder $encoder){
		$user = $this->getUser();

		if ($user){
			return $this->json([
				'username' => $user->getUsername(),
				'encodedUuid' => $encoder->encode($user->getUuid()),
				'roles' => $user->getRoles(),
			]);
		}

		return $this->json([
			'error' => 'user is not authenticated'
		]);
	}
}