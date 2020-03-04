<?php

namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\User;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use App\Service\Email\EmailServiceHandler;
use App\Service\SMS\SMSHandler;
use App\Service\UserPasswordService;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserApiController extends AbstractController
{

	/**
	 * @Route("/api/v1/user/invite")
	 */
	public function userInvite(Request $request, SMSHandler $sms, EmailServiceHandler $emailServiceHandler)
	{
		$data = json_decode($request->getContent());
		if (!$data->data->type) return new JsonResponse(['error' => 'invite type is required (tel/email)']);
		if (!$data->data->input) return new JsonResponse(['error' => 'invite input is required (mobile number/email address)']);

		$url = "http://{$_SERVER['SERVER_NAME']}/signup";
		$message = "<h1>You have been invited to the projects app.</h1>";
		$message .= "<p>Click <a href='{$url}'>here</a> to sign up!</p>";

		if ($data->task) {
			$url .= "?task={$data->task->encodedUuid}";
		}

		if ($data->data->type == 'tel') {
			$response = $sms->sendSMS($data->data->input, "$url");
			return new JsonResponse(['success', 'msg' => 'SMS sent'], 200);
		}

		if ($data->data->type == 'email') {
			$response = $emailServiceHandler->sendEmail([$data->data->input], null, null, "You've been invited!", "invite.html.twig", ['message' => $message]);
			return new JsonResponse(['success', 'msg' => 'email sent'], 200);
		}

		// we didnt do anything
		return new JsonResponse(['error' => 'Other error, message not sent'], 500);
	}

	/**
	 * @Route("/api/v1/user/create", methods={"POST"})
	 */
	public function create(Request $request, UserRepository $userRepository, UserPasswordService $userPasswordService, UserPasswordEncoderInterface $passwordEncoder, UuidEncoder $uuidEncoder, TaskRepository $taskRepository)
	{
		$email = trim($request->request->get('email'));
		$mobileNumber = trim($request->request->get('mobileNumber'));
		$displayName = trim($request->request->get('displayName'));
		$password = trim($request->request->get('password'));
		$encodedTaskUuid = trim($request->request->get('encodedTaskUuid'));

		if ((!$email || $email == '') && (!$mobileNumber || $mobileNumber == '')) return new JsonResponse(['error' => 'email or number is required'], 400);
		if (!$displayName || $displayName == '') return new JsonResponse(['error' => 'display name is required'], 400);
		if ($userRepository->findOneBy(['displayName' => $displayName])) return new JsonResponse(['error' => 'uername is taken'], 400);
		if (!$password || trim($password) === '') return new JsonResponse(['error' => 'password is required'], 400);

		if (
			($email && $userRepository->findOneBy(['email' => $email]))
			|| ($mobileNumber && $userRepository->findOneBy(['mobileNumber' => $mobileNumber]))
		) {
			return new JsonResponse(['error' => 'an account already exists'], 401);
		}

		if (!$userPasswordService->passwordIsValid($password)) {
			return new JsonResponse(['error' => 'password invalid', 'problems' => $userPasswordService->problemsWithPassword($password)]);
		}

		$user = new User();
		$email !== '' ?? $user->setEmail($email);
		$mobileNumber !== '' ?? $user->setEmail($email);
		$user->setDisplayName($displayName);
		$user->setPassword($passwordEncoder->encodePassword($user, $password));

		$em = $this->getDoctrine()->getManager();
		$em->persist($user);
		$em->flush();

		// assign them to a task if we have an encoded task uuid stored in their browser and nobody is already assigned to that task
		if ($encodedTaskUuid && $task = $taskRepository->findOneByEncodedUuid($encodedTaskUuid)) {
			if (!$task->getAssignedUser()) {
				$task->setAssignedUser($user);
				$em->persist($task);
				$em->flush();
			}
		}

		return new JsonResponse([
			'user' => [
				'encodedUuid' => $uuidEncoder->encode($user->getUuid()),
				'email' => $user->getEmail(),
				'mobileNumber' => $user->getMobileNumber(),
				'verified' => $user->isVerified(),
			]
		], 200);
	}

	/**
	 * @Route("/api/v1/user/verify/{encodedUuid}/{type}/{value}", methods={"GET"})
	 */
	public function verify(string $encodedUuid, string $type, string $value, Request $request, UserRepository $userRepository)
	{
		/**
		 * @var User
		 */
		if (!$user = $userRepository->findOneByEncodedUuid($encodedUuid)) {
			return new JsonResponse(['error' => 'user not found'], 404);
		}
		$em = $this->getDoctrine()->getManager();
		switch ($type) {
			case 'email':
				if ($value != $user->getEmail()) {
					return new JsonResponse(['verified' => false], 409);
				} else {
					if ($user->getEmailVerified()) {
						return new JsonResponse(['verified' => true], 304);
					} else {
						$user->setEmailVerified(true);
						$em->persist($user);
						$em->flush();
					}
				}
				break;
			case 'mobile':
				if ($value != $user->getMobileNumber()) {
					return new JsonResponse(['verified' => false], 409);
				} else {
					if ($user->getMobileNumberVerified()) {
						return new JsonResponse(['verified' => true], 304);
					} else {
						$user->setMobileNumberVerified(true);
						$em->persist($user);
						$em->flush();
					}
				}
				break;
			default:
				return new JsonResponse(['error' => 'invalid verification method'], 400);
		}
		return new JsonResponse(['verified' => $user->isVerified()], 200);
	}

	/**
	 * @Route("/api/v1/user/search", methods={"POST"})
	 */
	public function userSearch(Request $request, UserRepository $userRepository)
	{
		$data = json_decode($request->getContent());
		if (!$data->searchValue) return new JsonResponse(['error' => 'search value is required']);

		return new JsonResponse(array_map(function ($user) {
			return [
				'encodedUuid' => UuidEncoder::encode($user->getUuid()),
				'displayName' => $user->getDisplayName(),
				'email' => $user->getEmail(),
				'mobileNumber' => $user->getMobileNumber(),
			];
		}, $userRepository->search($data->searchValue)));
	}
}
