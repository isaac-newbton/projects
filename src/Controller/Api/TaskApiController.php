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
use App\Repository\UserRepository;
use Symfony\Component\Finder\Finder;

class TaskApiController extends AbstractController
{
	/**
	 * @Route("/api/v1/task/create", methods={"POST"})
	 */
	public function createTask(Request $request, ProjectRepository $projectRepository)
	{
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
	public function viewTask(Request $request, TaskRepository $taskRepository, UuidEncoder $encoder)
	{
		$data = json_decode($request->getContent());
		if (!$encodedUuid = $data->encodedUuid) return new JsonResponse(['error' => 'encodedUuid required'], 400);

		/**
		 * @var Task|null
		 */
		$task = $taskRepository->findOneByEncodedUuid($encodedUuid);


		if ($task) {
			$project = $task->getProject();
			return new JsonResponse([
				'name' => $task->getName(),
				'encodedUuid' => $encoder->encode($task->getUuid()),
				'assignedUser' => $task->getAssignedUser() ? [
					'encodedUuid' => UuidEncoder::encode($task->getAssignedUser()->getUuid()),
					'displayName' => $task->getAssignedUser()->getDisplayName(),
					'email' => $task->getAssignedUser()->getEmail(),
					'mobilePhone' => $task->getAssignedUser()->getMobileNumber(),
				] : null,
				'project' => ($project) ? [
					'name' => $project->getName(),
					'dueDate' => $project->getDueDate(),
					'encodedUuid' => $encoder->encode($project->getUuid())
				] : null,
				'comments' => array_map(function ($comment) {
					return [
						'content' => $comment->getContent(),
						'timestamp' => $comment->getTimeStamp()->format('g:ia F dS Y'),
						'user' => array_filter([
							'mobileNumber' => $comment->getUser()->getMobileNumber() ?? null,
							'email' => $comment->getUser()->getEmail() ?? null,
						], function ($item) {
							return isset($item) && !empty($item) ?? $item;
						}),
					];
				}, $task->getComments()->getValues()),
				"files" => array_map(function ($file) {
					return [
						'name' => $file->getName(),
						'encodedUuid' => UuidEncoder::encode($file->getUuid()),
					];
				}, $task->getMediaFiles()->getValues())

			]);
		}
		return new JsonResponse(['error' => 'task not found for that uuid']);
	}

	/**
	 * @Route("/api/v1/task/update", methods={"POST"})
	 */
	public function updateTask()
	{
		return new JsonResponse('TODO: update task');
	}

	/**
	 * @Route("/api/v1/task/assign/user", methods={"POST"})
	 */
	public function assignUser(Request $request, TaskRepository $taskRepository, UserRepository $userRepository)
	{
		$data = json_decode($request->getContent());
		if (!$encodedUserUuid = $data->encodedUserUuid) return new JsonResponse(['error' => 'encoded user uuid is required']);
		if (!$encodedTaskUuid = $data->encodedTaskUuid) return new JsonResponse(['error' => 'encoded task uuid is required']);
		if (!$task = $taskRepository->findOneByEncodedUuid($encodedTaskUuid)) return new JsonResponse(['error' => 'task not found']);
		if (!$user = $userRepository->findOneByEncodedUuid($encodedUserUuid)) return new JsonResponse(['error' => 'user not found']);

		$task->setAssignedUser($user);

		$em = $this->getDoctrine()->getManager();
		$em->persist($task);
		$em->merge($user);
		$em->flush();

		return new JsonResponse(['success'], 200);
	}
	/**
	 * @Route("/api/v1/task/remove/user", methods={"POST"})
	 */
	public function removeUser(Request $request, TaskRepository $taskRepository)
	{
		$data = json_decode($request->getContent());
		if (!$encodedTaskUuid = $data->encodedTaskUuid) return new JsonResponse(['error' => 'encoded task uuid is required']);
		if (!$task = $taskRepository->findOneByEncodedUuid($encodedTaskUuid)) return new JsonResponse(['error' => 'task not found']);

		$task->setAssignedUser(null);

		$em = $this->getDoctrine()->getManager();
		$em->persist($task);
		$em->flush();

		return new JsonResponse(['success'], 200);
	}

	/**
	 * @Route("/api/v1/task/delete/{encoded}", methods={"DELETE"})
	 */
	public function deleteTask(string $encoded, TaskRepository $taskRepository)
	{
		/**
		 * @var Task
		 */
		if (($task = $taskRepository->findOneByEncodedUuid($encoded)) && (!$task->getDeleted())) {
			$em = $this->getDoctrine()->getManager();
			$task->setDeleted(true);
			$em->persist($task);
			$em->flush();
			return new JsonResponse([true], 200);
		} else {
			return new JsonResponse(["error" => "Task not found"], 404);
		}
	}

	/**
	 * @Route("/api/v1/user/tasks", methods={"POST"})
	 */
	public function projectList(Request $request, UserRepository $userRepository)
	{
		$data = json_decode($request->getContent());
		if (!$data->encodedUserUuid) return new JsonResponse(['error' => 'encoded user uuid is required']);
		if (!$user = $userRepository->findOneByEncodedUuid($data->encodedUserUuid)) return new JsonResponse(['error' => 'user was not found']);

		$tasks = $user->getTasks();

		return new JsonResponse(array_map(function ($task) {
			return [
				"encodedUuid" => UuidEncoder::encode($task->getUuid()),
				"name" => $task->getName(),
				"dueDate" => ($task->getDueDate() ? $task->getDueDate()->format('Y-m-d') : null),
			];
		}, $tasks->getValues()));
	}
}
