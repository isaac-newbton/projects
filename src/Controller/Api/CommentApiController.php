<?php
namespace App\Controller\Api;

use App\Entity\Comment;
use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentApiController extends AbstractController {

	/**
	 * @Route("/api/v1/comment/create", methods={"POST"})
	 */
	public function createComment(Request $request, TaskRepository $taskRepository){
		$data = json_decode($request);
		if (!$this->getUser()) return new JsonResponse(['error' => 'user must be authenticated']);
		if (!$data->content) return new JsonResponse(['error' => 'content is a required']);
		if (!$data->taskUuid) return new JsonResponse(['error' => 'task uuid is required']);

		if (!$task = $taskRepository->findOneByEncodedUuid($data->taskUuid)) return new JsonResponse(['error' => 'task not found']);

		$em = $this->getDoctrine()->getManager();
		$comment = new Comment();
		$comment->setTask($task);
		$comment->setUser($this->getUser());
		$comment->setContent($data->content);

		$em->persist($comment);
		$em->merge($task);
		$em->flush();

		return new JsonResponse(['success'], 200);
	}

	/**
	 * @Route("/api/v1/task/comments", methods={"POST"})
	 */
	public function taskComments(){
		return new JsonResponse("TODO: return all comments for specified task");
	}

	/**
	 * @Route("/api/v1/comment/create", methods={"POST"})
	 */
	public function deleteComment(){
		return new JsonResponse("TODO: delete comment");
	}
}