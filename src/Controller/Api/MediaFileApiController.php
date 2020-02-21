<?php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MediaFileApiController extends AbstractController {

	/**
	 * @Route("/api/v1/file/upload", methods={"POST"})
	 */
	public function uploadFile(Request $request){
		$params = $request->files->all();
		return new JsonResponse($params);
	}
}