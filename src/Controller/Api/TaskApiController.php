<?php
namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use Symfony\Component\Finder\Finder;

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

		return new JsonResponse(['success', 200]);
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
		$task = $taskRepository->findOneByEncodedUuid($encodedUuid);


		if($task){
			$project = $task->getProject();
			return new JsonResponse([
				'name'=>$task->getName(),
				'encodedUuid'=>$encoder->encode($task->getUuid()),
				'project'=>($project) ? [
					'name'=>$project->getName(),
					'dueDate'=>$project->getDueDate(),
					'encodedUuid'=>$encoder->encode($project->getUuid())
				] : null,
				'comments' => array_map(function($comment){
					return [
						'content' => $comment->getContent(),
						'timestamp' => $comment->getTimeStamp()->format('g:ia F dS Y'),
						'user' => array_filter([
							'mobileNumber' => $comment->getUser()->getMobileNumber() ?? null,
							'email' => $comment->getUser()->getEmail() ?? null,
						], function($item){
							return isset($item) && !empty($item) ?? $item;
						}),
					];
				}, $task->getComments()->getValues()),
				"files" => array_map(function($file){
						return [
							'name' => $file->getName(),
							'encodedUuid' => UuidEncoder::encode($file->getUuid()),
						];
				}, $task->getMediaFiles()->getValues())

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
		if(($task = $taskRepository->findOneByEncodedUuid($encoded)) && (!$task->getDeleted())){
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