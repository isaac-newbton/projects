<?php
namespace App\Controller\Api;

use App\Entity\MediaFile;
use DateTime;
use DateTimeZone;
use Exception;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MediaFileApiController extends AbstractController {

	/**
	 * @Route("/api/v1/file/upload", methods={"POST"})
	 */
	public function uploadFile(Request $request){
		if (!$files = ($request->files->all())) return new JsonResponse(['error' => 'files required']);
		if (!$encodedUserUuid = $this->getUser()->getEncodedUuid()) return new JsonResponse(['error' => 'user is not authenticated']);
		$encodedUserUuid = 'asdf';

		$em = $this->getDoctrine()->getManager();
		/**
		 * @var UploadedFile
		 */
		foreach ($files as $file){
			$mediaFile = new MediaFile();
			
			$originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
			$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
			$fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();
			
			$mediaFile->setName($fileName);
			$mediaFile->setPath($this->getParameter('mediaFiles_directory').$fileName);
			$mediaFile->setTimestamp(new DateTime('now', new DateTimeZone('AMERICA/NEW_YORK')));
			$mediaFile->setMimeType($file->getMimeType());
			//FIXME: banking on this always being able to flush is a bad idea
			$em->merge($mediaFile);

			$file->move("{$this->getParameter('mediaFiles_directory')}$encodedUserUuid", $fileName);
		}
		
		$em->flush();


		return new JsonResponse(['success'], 200);
	}
}