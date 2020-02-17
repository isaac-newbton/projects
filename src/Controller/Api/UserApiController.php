<?php
namespace App\Controller\Api;

use App\Repository\UserRepository;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserApiController extends AbstractController{

	/**@
	 * Route("/api/v1/user/create", methods={"POST"})
	 */
	public function create(Request $request, UserRepository $userRepository){
		$data = json_decode($request->getContent());
		if(!$data->email && !$data->mobileNumber) return new JsonResponse(['error'=>'email or number is required'], 400);
		if(!$data->password) return new JsonResponse(['error'=>'password is required'], 400);

		if(
			($data->email && $userRepository->findOneBy(['email'=>$data->email]))
			|| ($data->mobileNumber && $userRepository->findOneBy(['mobileNumber'=>$data->mobileNumber]))
		){
			return new JsonResponse(['error'=>'an account already exists'], 401);
		}
	}
}