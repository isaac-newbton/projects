<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * / TODO: isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController {

	/**
	 * @Route("/admin", name="admin_home")
	 */
	public function adminIndex(){
		/**
		 * @var ProjectRepository
		 */
		$projectRepository = $this->getDoctrine()->getRepository(Project::class);
		return $this->render("admin/index.html.twig", [
			"projects" => $projectRepository->findAll(),
			"expiredProjects" => $projectRepository->findExpiredAndUnowned()
		]);
	}
}