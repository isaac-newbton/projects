<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * / TODO: isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController {

	/**
	 * @Route("/admin", name="admin_home")
	 */
	public function adminIndex(){
		return $this->render("admin/index.html.twig", [
			"projects" => $this->getDoctrine()->getRepository(Project::class)->findAll()
		]);
	}
}