<?php

namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use DateTimeZone;
use DateTime;
use Ramsey\Uuid\UuidInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectApiController extends AbstractController
{
	/**
	 * @Route("/api/v1/project/create")
	 */
	public function createProject(Request $request, UuidEncoder $encoder)
	{
		$name = $request->get("name");
		$dueDate = $request->get("dueDate");

		if (!$name) return new JsonResponse(["error" => "project name is required"], 418); // LOL

		$em = $this->getDoctrine()->getManager();

		$project = new Project();
		$project->setName($name);
		if ($dueDate) $project->setDueDate(new \DateTime($dueDate));


		$em->persist($project);
		$em->flush();
		$em->refresh($project);

		return new JsonResponse([
			"name" => $project->getName(),
			"uuid" => $project->getUuid(),
			"encodedUuid" => $encoder->encode($project->getUuid())
		], 200);
	}

	/**
	 * @Route("/api/v1/project/owner/add", methods={"POST"})
	 */
	public function addOwner(Request $request, UserRepository $userRepository, ProjectRepository $projectRepository)
	{
		$data = json_decode($request->getContent());
		if (!$data->user->encodedUuid) return new JsonResponse(['error' => 'encoded user uuid is required']);
		if (!$data->project->encodedUuid) return new JsonResponse(['error' => 'encoded project uuid is required']);
		if (!$user = $userRepository->findOneByEncodedUuid($data->user->encodedUuid)) return new JsonResponse(['error' => 'user was not found']);
		if (!$project = $projectRepository->findOneByEncodedUuid($data->project->encodedUuid)) return new JsonResponse(['error' => 'project was not found']);
		if ($project->getOwner() !== null) return new JsonResponse(['error' => 'project already has ownership']);

		$em = $this->getDoctrine()->getManager();
		$project->setOwner($user);
		$em->persist($project);
		$em->flush();

		return new JsonResponse(['success'], 200);
	}

	/**
	 * @Route("/api/v1/project/list", methods={"POST"})
	 */
	public function projectList(Request $request, UserRepository $userRepository)
	{
		$data = json_decode($request->getContent());
		if (!$data->encodedUserUuid) return new JsonResponse(['error' => 'encoded user uuid is required']);
		if (!$user = $userRepository->findOneByEncodedUuid($data->encodedUserUuid)) return new JsonResponse(['error' => 'user was not found']);

		$projects = $user->getProjects();

		return new JsonResponse(array_map(function ($project) {
			return [
				"encodedUuid" => UuidEncoder::encode($project->getUuid()),
				"name" => $project->getName(),
				"dueDate" => ($project->getDueDate() ? $project->getDueDate()->format('Y-m-d') : null),
			];
		}, $projects->getValues()));
	}
	/**
	 * @Route("/api/v1/project/view", methods={"POST"})
	 */
	public function viewProject(Request $request, ProjectRepository $projectRepository, UuidEncoder $encoder)
	{
		$data = json_decode($request->getContent());
		if (!$encodedUuid = $data->encodedUuid) return new JsonResponse("encodedUuid is required");

		$project = $projectRepository->findOneByEncodedUuid($encodedUuid);

		if ($project) {
			return new JsonResponse([
				"name" => $project->getName(),
				"dueDate" => ($project->getDueDate() ? $project->getDueDate()->format('Y-m-d') : null),
				"encodedUuid" => $encoder->encode($project->getUuid()),
				"owner" =>
				$project->getOwner() ? [
					"encodedUuid" => UuidEncoder::encode($project->getOwner()->getUuid()),
					"displayName" => $project->getOwner()->getDisplayName(),
					'email' => $project->getOwner()->getEmail(),
					'mobilePhone' => $project->getOwner()->getMobileNumber(),
				] : null,
				"tasks" => array_map(function ($task) use ($encoder) {
					return [
						"name" => $task->getName(),
						"dueDate" => $task->getDueDate() ? $task->getDueDate()->format('y-m-d') : null,
						"encodedUuid" => $encoder->encode($task->getUuid()),
						"active" => !$task->getDeleted() ? true : false,
						'assignedUser' => $task->getAssignedUser() ? [
							'encodedUuid' => UuidEncoder::encode($task->getAssignedUser()->getUuid()),
							'displayName' => $task->getAssignedUser()->getDisplayName(),
							'email' => $task->getAssignedUser()->getEmail(),
							'mobilePhone' => $task->getAssignedUser()->getMobileNumber(),
						] : null,
					];
				}, $project->getTasks()->getValues())
			]);
		}
		return new JsonResponse(["error" => "project matching encoded uuid not found"]);
	}
	/**
	 * @Route("/api/v1/project/update", methods={"POST"})
	 */
	public function updateProject(Request $request, ProjectRepository $projectRepository, UuidEncoder $encoder)
	{
		$data = json_decode($request->getContent());
		if (!$data->project) return new JsonResponse("project is required");
		$project = $projectRepository->findOneByEncodedUuid($data->project->encodedUuid);

		if ($project) {
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
				"edit" => true, // this should always be edit if we have access to this endpoint
				"tasks" => array_map(function ($task) use ($encoder) {
					return [
						"name" => $task->getName(),
						"dueDate" => $task->getDueDate() ? $task->getDueDate()->format('y-m-d') : null,
						"encodedUuid" => $encoder->encode($task->getUuid()),
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
	public function deleteProject(string $encoded, ProjectRepository $projectRepository)
	{
		/**
		 * @var Project
		 */
		if (($project = $projectRepository->findOneByEncodedUuid($encoded)) && (!$project->getDeleted())) {
			$em = $this->getDoctrine()->getManager();
			$project->setDeleted(true);
			if ($tasks = $project->getTasks()) {
				foreach ($tasks as $task) {
					$task->setDeleted(true);
					$em->persist($task);
				}
			}
			$em->persist($project);
			$em->flush();
			return new JsonResponse([true], 200);
		} else {
			return new JsonResponse(["error" => "Project not found"], 404);
		}
	}
}
