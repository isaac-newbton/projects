<?php
namespace App\Controller\Api;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ProjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TaskApiController extends AbstractController {
	/**
	 * @Route("/api/v1/task/create", methods={"POST"})
	 */
	public function createTask(Request $request, ProjectRepository $projectRepository){
		$data = json_decode($request->getContent());
		if (!$data->name) return new JsonResponse(['error' => 'Task name is required']);
		if (!$data->projectUuid) return new JsonResponse(['error' => 'Parent project UUID is required']);
		if (!$project = $projectRepository->findOneByEncodedUuid($data->projectUuid)) return new JsonResponse(['error' => 'Project not found for supplied UUID']);

		$em = $this->getDoctrine()->getManager();

		$task = new Task();
		$task->setProject($project);
		$task->setName($data->name);
		$task->setDueDate($data->dueDate ?? null);

		$em->persist($task);
		$em->flush($task);


		// adding a task should return all of the tasks in the event that someone else might have added some!
		return new JsonResponse(['success', 200]);
		// return new JsonResponse(
		// 	["tasks" => array_map(function($task){
		// 		return [
		// 			"name" => $task->getName(),
		// 			"dueDate" => $task->getDueDate() ? $task->getDueDate()->format('y-m-d') : null,
		// 			"uuid" => $task->getUuid(),
		// 		];
		// 	}, $project->getTasks()->getValues())
		// 	]
		// );
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