<?php
namespace App\Controller\Api;

use App\Doctrine\UuidEncoder;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProjectApiController extends AbstractController {
	/**
	 * @Route("/api/v1/project/create")
	 */
	public function createProject(Request $request, UuidEncoder $encoder){
		$params = $request->request->all();
		if(isset($params['name']) && !empty($params['name'])){
			$entityManager = $this->getDoctrine()->getManager();
			$project = new Project();
			$project->setName($params['name']);
			if(isset($params['dueDate']) && !empty($params['dueDate'])){
				$project->setDueDate(new \DateTime($params['dueDate']));
			}
			$entityManager->persist($project);
			$entityManager->flush();
			return new JsonResponse($encoder->encode($project->getEditUuid()));
		}else{

		}
		return new JsonResponse(false);
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