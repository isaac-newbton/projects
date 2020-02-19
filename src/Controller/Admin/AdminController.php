<?php
namespace App\Controller\Admin;

use App\Entity\Project;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProjectRepository;
use App\Service\UserEmailService;
use App\Service\UserPasswordService;
use Mailgun\Mailgun;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;

/**
 * / TODO: isGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController {
	/**
	 * @var string
	 */
	private $appName;

	public function __construct($appName){
		$this->appName = $appName;
	}

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

	/**
	 * @Route("/admin/testemail/{to}", name="admin_test_email")
	 */
	public function testEmail(string $to, UserEmailService $userEmailService){
		/**
		 * @var Mailgun
		 */
		$sent = $userEmailService->send([
			'from'=>"$this->appName <admintest@mail.lifeprojex.com>",
			'to'=>$to,
			'subject'=>"Test email from $this->appName",
			'html'=>$this->renderView('email/dev/test.html.twig', [
				'subject'=>'Test email',
				'var1'=>'this is the value for var1',
				'var2'=>10000,
				'var3'=>'Variable #3'
			])
		]);
		return new Response("<html><body>Sent to: $to<br>Success: " . ($sent ? 'Y' : 'N') . "<pre>" . var_export($sent, true) . "</pre></body></html>", 200);
	}
}