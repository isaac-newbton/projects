<?php
namespace App\Controller\Api;

<<<<<<< HEAD
use App\Doctrine\UuidEncoder;
use App\Repository\TaskRepository;
=======
use App\Entity\Project;
use App\Entity\Task;
use App\Repository\ProjectRepository;
>>>>>>> master
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\Project;
use App\Repository\ProjectRepository;

class TaskApiController extends AbstractController {
	/**
	 * @Route("/api/v1/task/create", methods={"POST"})
	 */
<<<<<<< HEAD
	public function createTask(Request $request, UuidEncoder $encoder, ProjectRepository $projectRepository){
		$name = $request->get('name');
		$dueDate = $request->get('dueDate');
		$projectEncodedUuid = $request->get('project');
		if(!$name) return new JsonResponse(['error'=>'name is required'], 418);
		if(!$projectEncodedUuid) return new JsonResponse(['error'=>'project is required'], 418);
		/**
		 * @var Project|null
		 */
		$project = $projectRepository->findOneByEncodedEditUuid($projectEncodedUuid);
		if(!$project) return new JsonResponse(['error'=>'project not found'], 418);
		$em = $this->getDoctrine()->getManager();
		$task = new Task();
		$task->setName($name);
		$task->setDueDate($dueDate ?? $project->getDueDate());
		$project->addTask($task);
		$em->persist($task);
		$em->persist($project);
		$em->flush();
		return new JsonResponse([
			"name" => $task->getName(),
			"uuid" => $task->getUuid(),
			"viewUuid" => $task->getViewUuid(),
			"editUuid" => $task->getEditUuid(),
			"encodedUuid" => $encoder->encode($task->getUuid()),
			"encodedViewUuid" => $encoder->encode($task->getViewUuid()),
			"encodedEditUuid" => $encoder->encode($task->getEditUuid())
		], 200);
=======
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
>>>>>>> master
	}

	/**
	 * @Route("/api/v1/task/view")
	 */
	public function viewTask(Request $request, TaskRepository $taskRepository, UuidEncoder $encoder){
		$data = json_decode($request->getContent());
		if(!$encodedUuid = $data->encodedUuid) return new JsonResponse(['error'=>'encodedUuid required'], 400);

		/**
		 * @var Task|null
		 */
		$task = $taskRepository->findOneByEncodedEditUuid($encodedUuid) ?? $taskRepository->findOneByEncodedViewUuid($encodedUuid);
		if($task){
			$project = $task->getProject();
			$permission = $task->getEditUuid() == $encoder->decode($encodedUuid) ? 'edit' : 'view';
			return new JsonResponse([
				'name'=>$task->getName(),
				'encodedUuid'=>$encoder->encode($task->getUuid()),
				'encodedEditUuid'=>('edit'===$permission) ? $encoder->encode($task->getEditUuid()) : null,
				'encodedViewUuid'=>$encoder->encode($task->getViewUuid()),
				'project'=>($project) ? [
					'name'=>$project->getName(),
					'dueDate'=>$project->getDueDate(),
					'encodedUuid'=>$encoder->encode($project->getUuid()),
					'encodedViewUuid'=>$encoder->encode($project->getViewUuid())
				] : null
			]);
		}
		return new JsonResponse(['error'=>'task not found for that uuid']);
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
	public function deleteTask(string $encoded, TaskRepository $taskRepository){
		/**
		 * @var Task
		 */
		if(($task = $taskRepository->findOneByEncodedEditUuid($encoded)) && (!$task->getDeleted())){
			$em = $this->getDoctrine()->getManager();
			$task->setDeleted(true);
			$em->persist($task);
			$em->flush();
			return new JsonResponse([true], 200);
		}else{
			return new JsonResponse(["error"=>"Task not found"], 404);
		}
	}
}