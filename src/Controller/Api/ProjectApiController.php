<?php
namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectApiController extends AbstractController {
	/**
	 * @Route("/api/v1/project/create")
	 */
	public function createProject(Request $request, UuidEncoder $encoder){
		$name = $request->get("name");
		$dueDate = $request->get("dueDate");

		if (!$name) return new JsonResponse(["error" => "project name is required"], 418); // LOL

		$em = $this->getDoctrine()->getManager();
		
		$project = new Project();
		$project->setName($name);
		if($dueDate) $project->setDueDate(new \DateTime($dueDate));


		$em->persist($project);
		$em->flush();
		$em->refresh($project);

		return new JsonResponse([
			"name" => $project->getName(),
			"uuid" => $project->getUuid(),
			"viewUuid" => $project->getViewUuid(),
			"editUuid" => $project->getEditUuid(),
			"encodedUuid" => $encoder->encode($project->getUuid()),
			"encodedViewUuid" => $encoder->encode($project->getViewUuid()),
			"encodedEditUuid" => $encoder->encode($project->getEditUuid())
		], 200);

	}
	/**
	 * @Route("/api/v1/project/view", methods={"POST"})
	 */
	public function viewProject(Request $request, ProjectRepository $projectRepository, UuidEncoder $encoder){
		$data = json_decode($request->getContent());
		if (!$encodedUuid = $data->encodedUuid) return new JsonResponse("encodedUuid is required");

		$decodedUuid = $encoder->decode($encodedUuid);
		$project = $projectRepository->findOneBy(["viewUuid" => $decodedUuid]) ?? $projectRepository->findOneBy(["editUuid" => $decodedUuid]);

		if ($project){
			$permission = $project->getEditUuid() == $decodedUuid ? 'edit' : 'view';
			return new JsonResponse([
				"name" => $project->getName(),
				"dueDate" => $project->getDueDate(),
				$permission => true
			]);
		}
		return new JsonResponse(["error" => "project matching encoded uuid not found"]);
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