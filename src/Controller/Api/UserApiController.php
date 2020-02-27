<?php
namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserPasswordService;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserApiController extends AbstractController{

	/**
	 * @Route("/api/v1/user/create", methods={"POST"})
	 */
	public function create(Request $request, UserRepository $userRepository, UserPasswordService $userPasswordService, UserPasswordEncoderInterface $passwordEncoder, UuidEncoder $uuidEncoder){
		$email = $request->request->get('email');
		$mobileNumber = $request->request->get('mobileNumber');
		$username = $request->request->get('username');
		$password = $request->request->get('password');
		if(!$email && !$mobileNumber) return new JsonResponse(['error'=>'email or number is required'], 400);
		if ($userRepository->findOneBy(['username' => $username])) return new JsonResponse(['error' => 'uername is taken'], 400);
		if(!$password) return new JsonResponse(['error'=>'password is required'], 400);

		if(
			($email && $userRepository->findOneBy(['email'=>$email]))
			|| ($mobileNumber && $userRepository->findOneBy(['mobileNumber'=>$mobileNumber]))
		){
			return new JsonResponse(['error'=>'an account already exists'], 401);
		}

		if(!$userPasswordService->passwordIsValid($password)){
			return new JsonResponse(['error'=>'password invalid', 'problems'=>$userPasswordService->problemsWithPassword($password)]);
		}

		$user = new User();
		$user->setEmail($email ?? null);
		$user->setMobileNumber($mobileNumber ?? null);
		$user->setUsername($username);
		$user->setPassword($passwordEncoder->encodePassword($user, $password));

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		return new JsonResponse([
			'user'=>[
				'encodedUuid'=>$uuidEncoder->encode($user->getUuid()),
				'email'=>$user->getEmail(),
				'mobileNumber'=>$user->getMobileNumber(),
				'verified'=>$user->isVerified()
			]
		], 200);
	}

	/**
	 * @Route("/api/v1/user/verify/{encodedUuid}/{type}/{value}", methods={"GET"})
	 */
	public function verify(string $encodedUuid, string $type, string $value, Request $request, UserRepository $userRepository){
		/**
		 * @var User
		 */
		if(!$user = $userRepository->findOneByEncodedUuid($encodedUuid)){
			return new JsonResponse(['error'=>'user not found'], 404);
		}
		$em = $this->getDoctrine()->getManager();
		switch($type){
			case 'email':
				if($value!=$user->getEmail()){
					return new JsonResponse(['verified'=>false], 409);
				}else{
					if($user->getEmailVerified()){
						return new JsonResponse(['verified'=>true], 304);
					}else{
						$user->setEmailVerified(true);
						$em->persist($user);
						$em->flush();
					}
				}
			break;
			case 'mobile':
				if($value!=$user->getMobileNumber()){
					return new JsonResponse(['verified'=>false], 409);
				}else{
					if($user->getMobileNumberVerified()){
						return new JsonResponse(['verified'=>true], 304);
					}else{
						$user->setMobileNumberVerified(true);
						$em->persist($user);
						$em->flush();
					}
				}
			break;
			default:
				return new JsonResponse(['error'=>'invalid verification method'], 400);
		}
		return new JsonResponse(['verified'=>$user->isVerified()], 200);
	}
}