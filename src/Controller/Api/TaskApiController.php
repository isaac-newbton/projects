<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskApiController extends AbstractController {
	/**
	 * @Route("/api/v1/task/create")
	 */
	public function createTask(){
		return new JsonResponse('TODO: create task');
	}

	/**
	 * @Route("/api/v1/task/view")
	 */
	public function viewTask(){
		return new JsonResponse('TODO: view task');
	}

	/**
	 * @Route("/api/v1/task/update", methods={"POST"})
	 */
	public function updateTask(){
		return new JsonResponse('TODO: update task');
	}

	/**
	 * @Route("/api/v1/task/delete/{encoded}", methods={"DELETE"})
	 */
	public function deleteTask(){
			return new JsonResponse('TODO: delete task');
	}
}