<?php
namespace App\Controller\Api;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ProjectApiController {
	/**
	 * @Route("/api/v1/project/create")
	 */
	public function createProject(){
		return new JsonResponse("TODO: create project");
	}
	/**
	 * @Route("/api/v1/project/view")
	 */
	public function viewProject(){
		return new JsonResponse("TODO: return project");
	}
	/**
	 * @Route("/api/v1/project/update")
	 */
	public function updateProject(){
		return new JsonResponse("TODO: update project");
	}
	/**
	 * @Route("/api/v1/project/delete")
	 */
	public function deleteProject(){
		return new JsonResponse("TODO: delete project");
	}
}