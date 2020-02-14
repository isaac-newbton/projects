<?php
namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use DateTimeZone;
use DateTime;
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

		// $decodedUuid = $encoder->decode($encodedUuid);
		$project = $projectRepository->findOneByEncodedEditUuid($encodedUuid) ?? $projectRepository->findOneByEncodedViewUuid($encodedUuid);

		if ($project){
			$permission = $project->getEditUuid() == $encoder->decode($encodedUuid) ? 'edit' : 'view';

			return new JsonResponse([
				"name" => $project->getName(),
				"dueDate" => ($project->getDueDate() ? $project->getDueDate()->format('Y-m-d') : null),
				"encodedUuid" => $encoder->encode($project->getUuid()),
				"encodedEditUuid" => $encoder->encode($project->getEditUuid()),
				$permission => true,
				"tasks" => array_map(function($task) use ($encoder){
					return [
						"name" => $task->getName(),
						"dueDate" => $task->getDueDate() ? $task->getDueDate()->format('y-m-d') : null,
						"encodedUuid" => $encoder->encode($task->getUuid()),
						"encodedViewUuid" => $encoder->encode($task->getViewUuid()),
						"encodedEditUuid" => $encoder->encode($task->getEditUuid()),
						"active" => !$task->getDeleted() ? true : false,
					];
				}, $project->getTasks()->getValues())
			]);
		}
		return new JsonResponse(["error" => "project matching encoded uuid not found"]);
	}
	/**
	 * @Route("/api/v1/project/update", methods={"POST"})
	 */
	public function updateProject(Request $request, ProjectRepository $projectRepository, UuidEncoder $encoder){
		$data = json_decode($request->getContent());
		if (!$data->project) return new JsonResponse("project is required");
		$project = $projectRepository->findOneByEncodedEditUuid($data->project->encodedEditUuid);

		if ($project){
			$em = $this->getDoctrine()->getManager();
			$project->setName($data->project->name);
			$project->setDueDate(new DateTime($data->project->dueDate, new DateTimeZone('AMERICA/NEW_YORK')));
			// TODO: update the tasks here
			$em->persist($project);
			$em->flush();
			$em->refresh($project); // do we need this?
			return new JsonResponse([
				"name" => $project->getName(),
				"dueDate" => $project->getDueDate()->format('Y-m-d'),
				"encodedUuid" => $encoder->encode($project->getUuid()),
				"encodedEditUuid" => $encoder->encode($project->getEditUuid()),
				"edit" => true, // this should always be edit if we have access to this endpoint
				"tasks" => array_map(function($task) use ($encoder){
					return [
						"name" => $task->getName(),
						"dueDate" => $task->getDueDate() ? $task->getDueDate()->format('y-m-d') : null,
						"encodedUuid" => $encoder->encode($task->getUuid()),
						"encodedViewUuid" => $encoder->encode($task->getViewUuid()),
						"encodedEditUuid" => $encoder->encode($task->getEditUuid()),
						"active" => !$task->getDeleted() ? true : false,
					];
				}, $project->getTasks()->getValues())
			]); 
		}
		return new JsonResponse("Project to be updated not found");
	}
	/**
	 * @Route("/api/v1/project/delete/{encoded}", methods={"DELETE"})
	 */
	public function deleteProject(string $encoded, ProjectRepository $projectRepository){
		/**
		 * @var Project
		 */
		if(($project = $projectRepository->findOneByEncodedEditUuid($encoded)) && (!$project->getDeleted())){
			$em = $this->getDoctrine()->getManager();
			$project->setDeleted(true);
			if($tasks = $project->getTasks()){
				foreach($tasks as $task){
					$task->setDeleted(true);
					$em->persist($task);
				}
			}
			$em->persist($project);
			$em->flush();
			return new JsonResponse([true], 200);
		}else{
			return new JsonResponse(["error"=>"Project not found"], 404);
		}
	}
}