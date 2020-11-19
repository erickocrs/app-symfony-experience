<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\DTO\VideoDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/video")
 */
class VideoController extends AbstractApiController
{
    /**
     * @Route("/new", name="video_new", methods={"POST"})
     */
    public function new(Request $request) {

        $video = new Video();

        $form = $this->buildForm(VideoType::class, $video);
        
        $form->handleRequest($request);//$form->submit($request->request->all());
        
        if ($form->isSubmitted() && $form->isValid()) {
            
            $videoRepository = $this->getDoctrine()
            ->getRepository(Video::class);

            $errormessage = $videoRepository->validateWithDB($video);
            if(!$errormessage) {                
                
                $videoRepository->encodePassword($video);
                $videoRepository->save($video);
                
                return $this->respond(
                    new VideoDTO($video),
                    Response::HTTP_OK
                );
            }
            else {

                return $this->respond(
                    $form->getErrors(),
                    Response::HTTP_UNAUTHORIZED,
                    $errormessage
                );
            }
        }
        else {

            return $this->respond(
                $form->getErrors(),
                Response::HTTP_UNAUTHORIZED,
                "Invalid Fields");                
        }
    }
    
}
