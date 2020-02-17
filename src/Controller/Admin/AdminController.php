<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use App\Service\UserPasswordService;
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

	/**
	 * @Route("/admin/users", name="admin_users")
	 */
	public function users(){
		$userRepository = $this->getDoctrine()->getRepository(User::class);
		return $this->render("admin/users.html.twig", [
			"users"=>$userRepository->findAll()
		]);
	}

	/**
	 * @Route("/admin/randompassword", name="admin_users")
	 */
	public function randomPassword(UserPasswordService $passwords){
		$password = $passwords->makeRandomPassword();
		return $this->render("admin/random_password.html.twig", [
			"password"=>$password
		]);
	}
}