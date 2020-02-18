<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentApiController extends AbstractController {

	/**
	 * @Route("/api/v1/comment/create", methods={"POST"})
	 */
	public function createComment(){
		return new JsonResponse("TODO: create comment and add to a specifed task");
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