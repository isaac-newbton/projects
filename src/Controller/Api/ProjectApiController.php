<?php
namespace App\Controller\Api;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectApiController extends AbstractController {
	/**
	 * @Route("/api/v1/project/create")
	 */
	public function createProject(Request $request){
		$name = $request->get("name");
		$dueDate = $request->get("dueDate");

		if (!$name) return new JsonResponse(["error" => "project name is required"], 418); // LOL

		$em = $this->getDoctrine()->getManager();
		
		$project = new Project();
		$project->setName($name);
		$project->setDueDate($dueDate);


		$em->persist($project);
		$em->flush();
		$em->refresh($project);

		return new JsonResponse([
			"id" => $project->getId(),
			"name" => $project->getName(),
			"uuid" => $project->getUuid(),
			"viewUuid" => $project->getViewUuid(),
			"editUuid" => $project->getEditUuid(),
		], 200);

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