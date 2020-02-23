<?php
namespace App\Controller\Api;

use App\Entity\MediaFile;
use App\Repository\TaskRepository;
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
	 * @Route("/api/v1/file/upload/{encodedEntityUuid}", methods={"POST"})
	 */
	public function uploadFile(Request $request, string $encodedEntityUuid, TaskRepository $taskRepository){
		// FIXME: this should be agnostic to the entity
		if (!$task = $taskRepository->findOneByEncodedUuid($encodedEntityUuid)) return new JsonResponse(['error' => 'task not found']);
		if (!$files = ($request->files->all())) return new JsonResponse(['error' => 'files required']);
		// if (!$encodedUserUuid = $this->getUser()->getEncodedUuid()) return new JsonResponse(['error' => 'user is not authenticated']);
		$encodedUserUuid = 'asdf'; // TODO: remove me before merge - depends on user login
		
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
			$mediaFile->addTask($task);
			$mediaFile->setPath($this->getParameter('mediaFiles_directory').$fileName);
			$mediaFile->setTimestamp(new DateTime('now', new DateTimeZone('AMERICA/NEW_YORK')));
			$mediaFile->setMimeType($file->getMimeType());
			
			$em->persist($mediaFile);
		}
		// FIXME: this should be agnostic to the entity
		$em->persist($task);
		
		try {
			$em->flush(); // next line does happen if this fails, but this should be more graceful
			$file->move("{$this->getParameter('mediaFiles_directory')}$encodedUserUuid", $fileName);
			return new JsonResponse(['success'], 200);
		} catch (Exception $e){
			return new JsonResponse(['error' => 'unable to create the file', 'message' => $e->getMessage()], 200);
		}

	}
}