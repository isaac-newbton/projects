<?php
namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractFormLoginAuthenticator{
	use TargetPathTrait;

	/**
	 * @var EntityManagerInterface
	 */
	private $entityManager;

	/**
	 * @var RouterInterface
	 */
	private $router;

	/**
	 * @var CsrfTokenManagerInterface
	 */
	private $csrfTokenManager;

	/**
	 * @var UserPasswordEncoderInterface
	 */
	private $passwordEncoder;

	public function __construct(EntityManagerInterface $entityManager, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder){
		$this->entityManager = $entityManager;
		$this->router = $router;
		$this->csrfTokenManager = $csrfTokenManager;
		$this->passwordEncoder = $passwordEncoder;
	}

	public function supports(Request $request){
		return 'app_login'===$request->attributes->get('_route') && $request->isMethod('POST');
	}

	public function getCredentials(Request $request){
		$credentials = [
			'password'=>$request->request->get('password'),
			'csrf_token'=>$request->request->get('_csrf_token')
		];

		if($email = $request->request->get('email')){
			$credentials['email'] = $email;
		}else if($mobileNumber = $request->request->get('mobileNumber')){
			$credentials['mobileNumber'] = $mobileNumber;
		}

		$request->getSession()->set(
			Security::LAST_USERNAME,
			$credentials['email'] ?? $credentials['mobileNumber'] ?? ''
		);
		return $credentials;
	}

	public function getUser($credentials, UserProviderInterface $userProvider){
		$token = new CsrfToken('authenticate', $credentials['csrf_token']);
		if(!$this->csrfTokenManager->isTokenValid($token)){
			throw new InvalidCsrfTokenException();
		}

		if(isset($credentials['email']) && !empty($credentials['email'])){
			$user = $this->entityManager->getRepository(User::class)->findOneBy(['email'=>$credentials['email']]);
		}else if(isset($credentials['mobileNumber']) && !empty($credentials['mobileNumber'])){
			$user = $this->entityManager->getRepository(User::class)->findOneBy(['mobileNumber'=>$credentials['mobileNumber']]);
		}

		if(!isset($user) || !$user){
			throw new CustomUserMessageAuthenticationException('user was not found');
		}

		return $user;
	}

	public function checkCredentials($credentials, UserInterface $user){
		return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
	}

	public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey){
		if($targetPath = $this->getTargetPath($request->getSession(), $providerKey)){
			return new RedirectResponse($targetPath);
		}

		return new RedirectResponse($this->router->generate('admin_home'));
	}

	protected function getLoginUrl(){
		return $this->router->generate('app_login');
	}
}