<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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
	 * @Route("/admin/login", name="app_login")
	 */
	public function formLogin(AuthenticationUtils $authenticationUtils){
		$error = $authenticationUtils->getLastAuthenticationError();
		$lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('dev/login.html.twig', [
			'last_username'=>$lastUsername,
			'error'=>$error
		]);
	}
}