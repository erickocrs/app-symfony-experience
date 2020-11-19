<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class AbstractApiController extends AbstractFOSRestController
{
    protected function buildForm(string $type, $data = null, array $options = []): FormInterface
    {
        $options = array_merge($options, [
           'csrf_protection' => false,
        ]);

        return $this->container->get('form.factory')->createNamed('', $type, $data, $options);
    }

    protected function respond($data, int $statusCode = Response::HTTP_OK, string $exceptionMessage = ""): Response
    {
        if($statusCode == Response::HTTP_OK) {
            return $this->json($data);
        }
        else {
            
            throw new HttpException(
                $statusCode,
                json_encode(
                    array([
                        "message" => $exceptionMessage, 
                        "data" => $data
                    ])
                )
            );
        }        
    }
}