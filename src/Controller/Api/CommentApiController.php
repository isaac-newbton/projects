<?php
namespace App\Controller\Api;

use App\Entity\Comment;
use App\Repository\TaskRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentApiController extends AbstractController {

	/**
	 * @Route("/api/v1/comment/create", methods={"POST"})
	 */
	public function createComment(Request $request, TaskRepository $taskRepository){
		$data = json_decode($request->getContent());
		// if (!$this->getUser()) return new JsonResponse(['error' => 'user must be authenticated']);
		if (!$data->content) return new JsonResponse(['error' => 'content is a required']);
		if (!$data->taskUuid) return new JsonResponse(['error' => 'task uuid is required']);

		if (!$task = $taskRepository->findOneByEncodedUuid($data->taskUuid)) return new JsonResponse(['error' => 'task not found']);

		$em = $this->getDoctrine()->getManager();
		$comment = new Comment();
		$comment->setTask($task);
		$comment->setUser($this->getUser());
		$comment->setTimestamp(new DateTime());
		$comment->setContent($data->content);

		$em->persist($comment);
		$em->merge($task);
		$em->flush();

		return new JsonResponse(['success'], 200);
	}

	/**
	 * @Route("/api/v1/comment/delete", methods={"POST"})
	 */
	public function deleteComment(){
		return new JsonResponse("TODO: delete comment");
	}
}